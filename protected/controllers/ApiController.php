<?php

class ApiController extends Controller
{
	public function actionLogin()
	{
		$openid=new EOpenID;
		
		$tokenOk = false;
		if (Yii::app()->user->getState('token'))
		{
			Tokens::model()->deleteAll("`time` < '".(time()-600)."'");
			$token = Tokens::model()->findByPk(Yii::app()->user->getState('token'));
			if ($token)
			{
				$tokenOk = true;
				$id = $token->account_id;
				$token->delete();
			}
			Yii::app()->user->setState('token', NULL);
		}
		
		if (!$tokenOk)
		{
			if(!isset($_GET['openid_mode']))
			{
				$openid->authenticate($this->cluster['openid_url']);
			}
			elseif(isset($_GET['openid_mode']))
			{
				if (!$openid->validate())
				{
					$this->redirect(Yii::app()->homeUrl);
					return;
				}


				$id = preg_replace('=.*/(\d+).*=', '$1', $openid->identity);
			}
		}
		
		if (!isset($id))
		{
			$this->redirect(Yii::app()->homeUrl);
			return;
		}
		
		$accountInfo = WGApi::getAccountInfo($id);
		if (empty($accountInfo))
		{
			exit('Account info error');
			return;
		}
		$accountInfo = array_shift($accountInfo);
		if (empty($accountInfo))
		{
			$this->redirect(Yii::app()->homeUrl);
			return;
		}
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$gb = new IPGeoBase(__DIR__ . '/../ipgeobase/cidr_optim.txt', __DIR__ . '/../ipgeobase/cities.txt');
		$ipInfo = $gb->getRecord($ip);

		$account = Accounts::model()->findByPk($accountInfo['account_id']);
		if (!$account)
		{
			$account = new Accounts;
			$account->id = $accountInfo['account_id'];
			$account->created_at = $accountInfo['created_at'];
		}
		
		$clanInfo = WGApi::getClanMembersInfo($accountInfo['account_id']);
		if ($clanInfo !== false)
		{
			if (!empty($clanInfo['clan']['clan_id']))
			{
				$account->clan_id = $clanInfo['clan']['clan_id'];
				$account->clan_name = $clanInfo['clan']['name'];
				$account->clan_abbreviation = $clanInfo['clan']['tag'];
				$account->role = $clanInfo['role'];
				$account->role_i18n = $clanInfo['role_i18n'];
				$account->since = $clanInfo['joined_at'];
			}
			else
			{
				$account->reset();
			}
		}
		
		$account->nickname = $accountInfo['nickname'];
		$account->updated_at = $accountInfo['updated_at'];
		$account->visited_at = time();
		$account->ip = $ip;
		$account->region = $ipInfo ? (isset($ipInfo['city']) && $ipInfo['city'] ? iconv('windows-1251', 'utf-8', $ipInfo['city']) : (isset($ipInfo['cc']) && $ipInfo['cc'] ? $ipInfo['cc'] : 'Не определен')) : 'Не определен';
		$account->save();
		
		$openid->id = $id;
		Yii::app()->user->login($openid, 3600*24*30);
		Yii::app()->user->name = $id;
		$this->redirect(Yii::app()->user->returnUrl);
	}

	public function actionDeleteNotices()
	{
		header('Content-type: text/plain; charset="utf-8"');
		Notices::model()->deleteAll("`expire` < ".(time() - 3600*24*7));
		echo "готово\n";
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionGetTS()
	{
		$time = microtime(1);
		header('Content-type: text/plain; charset="utf-8"');
		Yii::app()->params['skipSiteCheck'] = true;
		$clans = Clans::model()->findAll(array('order'=>'`parser_teamspeak_time`', 'limit'=>3));
		if (!$clans)
			return;
		foreach ($clans as $clan)
		{
			echo "Обработка клана ".$clan->id." - ".$clan->abbreviation."\n";
			$clan->parser_teamspeak_time = time();
			$clan->save();
			
			$ar_config = Config::model()->findAllByAttributes(array('site_id'=>$clan->site_id));
			$config = array();
			foreach ($ar_config as $value)
			{
				$value->value = Yii::app()->format->text($value->value);
				$config[$value->name] = $value;
			}
			
			if (empty($config['teamspeak_address']->value) || empty($config['teamspeak_queryport']->value) || empty($config['teamspeak_querylogin']->value) || empty($config['teamspeak_querypassword']->value))
			{
				echo "Пустой конфиг\n";
				continue;
			}
			$teamspeak_address = explode(':', $config['teamspeak_address']->value);
			if (empty($teamspeak_address[0]) || !preg_match('=^[\w\d\.\-]+$=', $teamspeak_address[0]))
			{
				echo "Неверный адрес сервера\n";
				continue;
			}
			if (empty($teamspeak_address[1]) || !preg_match('=^[\d]+$=', $teamspeak_address[1]))
				$teamspeak_address[1] = 9987;
			if (!preg_match('=^[\d]+$=', $config['teamspeak_queryport']->value))
				$config['teamspeak_queryport']->value = 10011;
			
			$tsAdmin = new ts3admin($teamspeak_address[0], $config['teamspeak_queryport']->value);
			if (!$tsAdmin->getElement('success', $tsAdmin->connect()))
			{
				echo "Невозможно подключиться к серверу TeamSpeak\n";
				continue;
			}
			if (!$tsAdmin->login($config['teamspeak_querylogin']->value, $config['teamspeak_querypassword']->value))
			{
				echo "Логин и пароль не подходят\n";
				continue;
			}
			if (!$tsAdmin->getElement('success', $tsAdmin->selectServer($teamspeak_address[1])))
			{
				echo "Неверный порт виртуального сервера\n";
				continue;
			}
			foreach ($clan->accounts as $account)
			{
				echo "Обработка игрока ".$account->id." - ".$account->nickname.": ";
				$client = $tsAdmin->clientDbFind($account->nickname.'%');
				
				if (empty($client['data'][0]))
				{
					echo "не найден".(empty($client['errors'][0]) ? '' : " - ".$client['errors'][0])."\n";
					if (strpos($client['errors'][0], 'flood ban') !== false)
						break;
					continue;
				}
				
				echo 'найдено учеток: '.count($client['data']).' - ';
				
				$maxtime = 0;
				$tmpclient = array();
				$maxclient = array();
				foreach ($client['data'] as $maxkey=>$maxvalue)
				{
					$tmpclient = $tsAdmin->clientDbInfo($maxvalue['cldbid']);
					if (empty($tmpclient['data']))
						continue;
					if ($tmpclient['data']['client_lastconnected'] > $maxtime)
					{
						$maxtime = $tmpclient['data']['client_lastconnected'];
						$maxclient = $tmpclient;
					}
				}
				if (!$maxclient)
				{
					echo "данные не загружены\n";
					continue;
				}
				
				if ($account->teamspeak_at == $maxclient['data']['client_lastconnected'])
				{
					echo "пропуск\n";
					continue;
				}
				
				$account->teamspeak_at = $maxclient['data']['client_lastconnected'];
				$account->update(array('teamspeak_at'));
				
				echo "готово\n";
			}
		}
		echo "Время выполнения: ".(microtime(1) - $time)."\n";
	}

	public function actionGetIvanerr()
	{
		header('Content-type: text/plain; charset="utf-8"');
		Yii::app()->params['skipSiteCheck'] = true;
		$clans = Clans::model()->findAll(array('order'=>'`parser_ivanerr_time`', 'limit'=>10));
		if (!$clans)
			return;
			
		$artanks = Tanks::model()->findAll();
		$tanks = array();
		
		$om_sum = 0;
		foreach ($artanks as $value)
		{
			$tanks[$value['id']] = $value;
			$om_sum += $value->weight;
		}

		foreach ($clans as $clan)
		{
			$clan->parser_ivanerr_time = time();
			$clan->update(array('parser_ivanerr_time'));
			
			echo "Обработка клана {$clan->id} - {$clan->abbreviation}: ";
			
			$ivanerr = @file_get_contents('http://ivanerr.ru/lt/export.php?clanid='.$clan->id.'&byclanid');
			$ivanerr = @json_decode($ivanerr, true);
			
			if (empty($ivanerr[$clan->id]['totalrate']) || empty($ivanerr[$clan->id]['position']))
			{
				echo "не участвует в рейтинге\n";
				continue;
			}
			
			//exit(print_r($ivanerr, 1));
			
			$clan->ivanerr_rating = $ivanerr[$clan->id]['position'];
			$clan->ivanerr_power = $ivanerr[$clan->id]['totalrate'];
			$clan->update(array('ivanerr_rating', 'ivanerr_power'));
			
			/* $om = 0;
			$accuracy = 0;
			$acount = 0;
			$wins = 0;
			$wcount = 0;
			$frags = 0;
			$fcount = 0;
			$accp = array();
			foreach ($clan->accounts as $account)
			{
				$accp[$account->id] = array('accuracy'=>0,'acount'=>0,'wins'=>0,'wcount'=>0,'frags'=>0,'fcount'=>0,'power'=>0);
				$acc_tanks = @unserialize($account->tanks_list);
				$days = round((time() - $account['battle_at']) / 3600 / 24);
				if (is_array($acc_tanks))
				{
					foreach ($acc_tanks as $key=>$acc_tank)
					{
						//Победы
						if ($acc_tank['l'] == 10 && $acc_tank['b'] > 10 && !empty($acc_tank['w']) && !empty($acc_tank['b']))
						{
							$wcount++;
							$wins += $acc_tank['w']/$acc_tank['b'];
							$om += $tanks[$key]['weight'] * ($days <= 7 ? 1 : 3.194858 / (3.100884 + exp($days * 0.281403 - 4.334563)));
						}
						//Фраги
						if (!empty($acc_tank['f']) && !empty($acc_tank['b']))
						{
							$fcount++;
							$frags += $acc_tank['f']/$acc_tank['b'];
						}
						//Попадания
						if ($account->battles > 1000 && !empty($acc_tank['h']) && !empty($acc_tank['s']))
						{
							$acount++;
							$accuracy += $acc_tank['h']/$acc_tank['s'];
						}
					}
				}
			}
			if ($wcount == 0)
			{
				$wins = 1;
				$wcount = 1;
			}
			if ($fcount == 0)
			{
				$frags = 1;
				$fcount = 1;
			}
			if ($acount == 0)
			{
				$accuracy = 1;
				$acount = 1;
			}
			$om = $om * pow($wins/$wcount, 3) * pow($frags/$fcount, 2) * $accuracy/$acount * 2.15;
			if ($om == 0)
				$k = 0;
			else
				$k = $clan->ivanerr_power / $om; */
			
			echo "\nIvanerr power: ".$clan->ivanerr_power."\n";
			/* echo 'Calculate power: '.$om."\n"; */
			
		}
		echo 'complete';
	}
	
	public function actionGetTanks()
	{
		sleep(1);
		$tanks = WGApi::getTanks();
		if (!$tanks)
		{
			echo 'api error';
			return;
		}
		foreach ($tanks as $tank)
		{
			$ar_tank = Tanks::model()->findByPk($tank['tank_id']);
			if (!$ar_tank)
			{
				$ar_tank = new Tanks;
			}
            $tank['id'] = (int)$tank['tank_id'];
            $tank['level'] = $tank['tier'];
            $tank['nation_i18n'] = $tank['nation'];
            $tank['is_premium'] = (int)$tank['is_premium'];
            $tank['name_i18n'] = $tank['name'];
            $tank['name'] = $tank['tag'];
			$tank['image'] = $tank['images']['contour_icon'];
			$tank['short_name_i18n'] = $tank['short_name'];
			$ar_tank->attributes = $tank;
			$ar_tank->save();
		}
		echo 'complete';
	}
	
	public function actionGetAchievements()
	{
		header('Content-type: text/plain; charset="utf-8"');
		$achievements = WGApi::getAchievements();
		echo count($achievements)."\n";
		if (!$achievements)
		{
			echo 'api error';
			return;
		}
		foreach ($achievements as $value)
		{
			$ar_achievement = Achievements::model()->findByPk($value['name']);
			if (!$ar_achievement)
			{
				$ar_achievement = new Achievements;
			}
			if (isset($value['options']))
				$value['options'] = json_encode($value['options']);
			$ar_achievement->attributes = $value;
			$ar_achievement->save();
		}
		echo 'complete';
	}
	
/* 	public function actionGetClansBattles()
	{
		
		//sleep(15);
		Yii::app()->params['skipSiteCheck'] = true;
		$clans = Clans::model()->findAll(array('order'=>'`parser_battles_time`', 'limit'=>10));
		if (!$clans)
			return;

		$clans_list = array();
		foreach ($clans as $clan)
		{
			$clan->parser_battles_time = time();
			$clan->save();
			$clans_list[] = $clan->id;
		}
		$clans_battles = WGApi::getClanBattles('eventmap', implode(',', $clans_list));
		if (!$clans_battles)
		{
			echo 'api error';
			return;
		}
		$types = array('for_province'=>'Бой за провинцию', 'meeting_engagement'=>'Встречный бой', 'landing'=>'Бой за высадку', );
		foreach ($clans as $clan)
		{
			
			Battles::model()->deleteAll("`clan_id`='{$clan->id}'");
			if (!isset($clans_battles[$clan->id]))
				continue;
			foreach ($clans_battles[$clan->id] as $new_battle)
			{
				$battle = new Battles;
				$battle->clan_id = $clan->id;
				$battle->provinces = implode(',', $new_battle['provinces']);
				$battle->started = $new_battle['started'] ? 1 : 0;
				$battle->time = $new_battle['time'];
				$arenas = array();
				foreach ($new_battle['arenas'] as $value)
				{
					$arenas[] = $value['name_i18n'];
				}
				$battle->arenas = implode(',', $arenas);
				$battle->type = $types[$new_battle['type']];
				$battle->save();
			}
		}
		echo 'complete';
	}
 */	
	public function actionGetMaps()
	{
		$api_fronts = WGApi::getFronts();
		if (empty($api_fronts))
		{
			echo 'api error';
			return;
		}
		foreach ($api_fronts as $api_front)
		{
			if (empty($api_front))
			{
				continue;
			}
			$front = Fronts::model()->findByPk($api_front['front_id']);
			if (!$front)
				$front = new Fronts;
			
			$front->id = $api_front['front_id'];
			$front->name = $api_front['front_name'];
			$front->is_active = (int)$api_front['is_active'];
			$front->is_event = (int)$api_front['is_event'];
			$front->save();
		}
		echo 'complete';
	}
	
	public function actionGetProvinces()
	{
		header('Content-type: text/plain; charset="utf-8"');
		$maps = Maps::model()->findAll();
		if (!$maps)
		{
			echo 'Карты не найдены';
			return;
		}
		$provinces = Provinces2::model()->findAll(array('index'=>'id'));
		
		$counter = 0;
		foreach ($maps as $map)
		{
			$api_provinces = WGApi::getProvinces($map->id);
			if (!$api_provinces)
			{
				echo 'Провинции для карты '.$map->id." не найдены. Пропуск.\n";
				continue;
			}
			foreach ($api_provinces as $api_province)
			{
				if (isset($provinces[$map->id.$api_province['province_id']]))
					$province = $provinces[$map->id.$api_province['province_id']];
				else
					$province = new Provinces2;
				
				if ($province->isNewRecord || $provinces[$map->id.$api_province['province_id']]->updated_at != $api_province['updated_at'])
				{
					$province->id = $map->id.$api_province['province_id'];
					$province->arena_i18n = $api_province['arena_i18n'];
					$province->arena_id = $api_province['arena_id'];
					$province->clan_id = $api_province['clan_id'] ? $api_province['clan_id'] : 0;
					$province->map_id = $map->id;
					$province->neighbors = json_encode($api_province['neighbors']);
					$province->primary_region = $api_province['primary_region'];
					$province->prime_time = $api_province['prime_time'];
					$province->province_i18n = $api_province['province_i18n'];
					$province->revenue = $api_province['revenue'];
					$province->status = $api_province['status'];
					$province->updated_at = $api_province['updated_at'];
					$province->vehicle_max_level = $api_province['vehicle_max_level'];
					$province->regions = json_encode($api_province['regions']);
					$province->save();
					if ($province->hasErrors())
					{
						echo 'Ошибка: '.print_r($province->errors, true)."\n";
					}
					$counter++;
				}
			}
		}
		echo 'Готово. Обработано провинций: '.$counter;
	}
	
/* 	public function actionGetClansProvinces()
	{
		sleep(20);
		Yii::app()->params['skipSiteCheck'] = true;
		$clans = Clans::model()->findAll(array('order'=>'`parser_provinces_time`', 'limit'=>10));
		if (!$clans)
		{
			echo "Ошибка Clans::model()->findAll\n";
			return;
		}
		
		$clans_provinces = array();
		foreach ($clans as $clan)
		{
			$clans_provinces[$clan->id] = WGApi::getClanProvinces($clan->id);
		}
		if (!$clans_provinces)
		{
			echo 'api error';
			return;
		}
		foreach ($clans as $clan)
		{
			$clan->parser_provinces_time = time();
			$clan->save();

			$tmp = Provinces::model()->findAll("`clan_id`='{$clan->id}'");
			$old = array();
			foreach ($tmp as $value)
			{
				$old[$value->id] = $value;
			}
			Provinces::model()->updateAll(array('clan_id'=>'0'), "`clan_id`='{$clan->id}'");
			if (!isset($clans_provinces[$clan->id]) && !is_array($clans_provinces[$clan->id]))
				continue;
			foreach ($clans_provinces[$clan->id] as $id=>$new_province)
			{	
				$province = Provinces::model()->findByPk($id);
				if (!$province)
					$province = new Provinces;
				
				$province->id = $id;
				$province->name = $new_province['name'];
				$province->arena_name = $new_province['arena_name'];
				$province->prime_time = $new_province['prime_time'];
				$province->attacked = $new_province['attacked'] ? 1 : 0;
				$province->revenue = $new_province['revenue'];
				$province->occupancy_time	 = $new_province['occupancy_time'];
				$province->combats_running = $new_province['combats_running'] ? 1 : 0;
				$province->clan_id = $clan->id;
				$province->save();
				
				if (isset($old[$id]))
					unset($old[$id]);
				else
				{
					//$event = new Events;
					//$event->event = '<span style="color: #449944;">Клан ['.$clan->abbreviation.'] захватил провинцию '.$new_province['name'].'</span>';
					//$event->time = time();
					//$event->save();
				}
			}
			if (!empty($old))
			{
				foreach ($old as $value)
				{
					//$event = new Events;
					//$event->event = '<span style="color: #FF4444;">Клан ['.$clan->abbreviation.'] потерял провинцию '.$value->name.'</span>';
					//$event->time = time();
					//$event->save();
				}
			}
		}
		echo 'complete';
	}
 */	
	public function actionGetStat($cluster = 'ru')
	{
		if (!isset(Yii::app()->params['clusters'][$cluster]))
		{
			echo "Кластер не найден\n";
			return;
		}
		$this->setCluster($cluster);

		$time = microtime(1);
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		
		echo 'Подключен к Redis: '.$this->redis->dbSize()." ключей в базе. Память: ".round($this->redis->info('memory')['used_memory']/1024/1024, 2)." МБ\n\n";
		
		Bans::model()->deleteAll("`expire` < '".time()."'");
		
		$tanks = Tanks::model()->cache(1800)->findAll(['index'=>'id']);
		if (!$tanks)
		{
			echo "Ошибка Tanks::model()->findAll\n";
			return;
		}
		
		$clans = Clans::model()->findAll(['condition'=>"`cluster` = '{$cluster}'", 'index'=>'id', 'order'=>'`parser_info_time`', 'limit'=>40]);
		if (!$clans)
		{
			echo "Ошибка Clans::model()->findAll\n";
			return;
		}
		
		$clans_list = [];
		foreach ($clans as $key=>$value)
			$clans_list[] = $key;
		
		$api_clans = WGApi::getClansInfo(implode(',', $clans_list));
		if (!$api_clans)
		{
			echo "Ошибка WGApi::getClansInfo\n";
			return;
		}
		
		while (microtime(1) - $time < 50 && $clan = array_shift($clans))
		{
			//Клан
			
			echo "Обработка клана ".$clan->id." - ".$clan->abbreviation."\n";
			
			if (empty($api_clans[$clan->id]))
			{
				echo "Нет данных клана от АПИ\n";
				continue;
			}
			
			$api_clan = $api_clans[$clan->id];
			
			if (($api_clan['is_clan_disbanded']))
			{
				$clan->delete();
				echo "Клан удален\n";
				continue;
			}
			
			$clan->name = $api_clan['name'];
			$clan->abbreviation = $api_clan['tag'];
			$clan->parser_info_time = time();
			if (!$clan->update(['name', 'abbreviation', 'parser_info_time']))
				continue;
			
			$accounts = Accounts::model()->findAll(['index'=>'id', 'condition'=>"`clan_id` = '{$clan->id}'"]);
			
			$members_list = array_keys($api_clan['members']);
			
			$api_accounts = WGApi::getAccountInfo(implode(',', $members_list));
			$api_stronghold = [];// deprecated :( WGApi::getStrongholdAccountstats(implode(',', $members_list));
			if (empty($api_accounts) || !is_array($api_accounts) || !is_array($api_stronghold))
			{
				echo "Ошибка WGApi::getAccountInfo || WGApi::getStrongholdAccountstats\n";
				continue;
			}
			
			//Добавление/удаление игроков клана
			$join = array_diff_key($api_accounts, $accounts);
			$left = array_diff_key($accounts, $api_accounts);
			
			foreach ($join as $key=>$value)
			{
				$accounts[$key] = Accounts::model()->findByPk($key);
				if (!$accounts[$key])
				{
					$accounts[$key] = new Accounts;
					$accounts[$key]->id = $key;
				}
				
				$accounts[$key]->trigger_joined_clan = true;
				
				//Дефолтные поля кланового игрока
				$accounts[$key]->nickname = $value['nickname'];
				$accounts[$key]->created_at = $value['created_at'];
				$accounts[$key]->updated_at = $value['updated_at'];
				$accounts[$key]->clan_id = $clan->id;
				$accounts[$key]->clan_abbreviation = $clan->abbreviation;
				$accounts[$key]->clan_name = $clan->name;
				$accounts[$key]->since = $api_clan['members'][$key]['joined_at'];
				$accounts[$key]->role = $api_clan['members'][$key]['role'];
				$accounts[$key]->role_i18n = $api_clan['members'][$key]['role_i18n'];
				$accounts[$key]->save();
				
				//Создать событие "Вступление в клан"
				echo $accounts[$key]->nickname." вступил в клан.\n";
				$event = new Events;
				$event->clan_id = $clan->id;
				$event->type = 1;
				$event->event = serialize(array('event'=>'join','id'=>$key,'nickname'=>$accounts[$key]->nickname));
				$event->time = time();
				$event->save();
			}
			
			foreach ($left as $key=>$value)
			{
				//Удалить все роли игрока
				AccountsRoles::model()->deleteAllByAttributes(['site_id'=>$clan->site_id, 'account_id'=>$key]);
			
				//Создать событие "Выход из клана"
				echo $accounts[$key]->nickname." покинул клан\n";
				$event = new Events;
				$event->clan_id = $clan->id;
				$event->type = 1;
				$event->event = serialize(array('event'=>'left','id'=>$key,'nickname'=>$accounts[$key]->nickname));
				$event->time = time();
				$event->save();
				
				$accounts[$key]->reset();
				$accounts[$key]->save();
				unset ($accounts[$key]);
			}
			
			echo "Личный состав: ".count($accounts)."\n";
			
			//Найти игроков у которых есть изменения (активных)
			$active_accounts = [];
			$active_accounts_keys = [];
			foreach ($accounts as $key => $value)
			{
				if (empty($api_accounts[$key]))
					continue;
				
				//Проверить смену никнейма
				if ($accounts[$key]->nickname != $api_accounts[$key]['nickname'])
				{
					//Создать событие "Смена никнейма"
					echo $accounts[$key]->nickname." сменил никнейм на ".$api_accounts[$key]['nickname']."\n";
					$event = new Events;
					$event->clan_id = $clan->id;
					$event->type = 2;
					$event->event = serialize(array('event'=>'nickname','id'=>$key,'nickname_old'=>$accounts[$key]->nickname,'nickname_new'=>$api_accounts[$key]['nickname']));
					$event->time = time();
					$event->save();
					
					$accounts[$key]->nickname = $api_accounts[$key]['nickname'];
					$accounts[$key]->update(['nickname']);
				}
				
				//Проверить смену должности
				if ($accounts[$key]->role != $api_clan['members'][$key]['role'])
				{
					//Создать событие "Смена должности"
					echo $accounts[$key]->nickname." сменил должность с ".$accounts[$key]->role_i18n." на ".$api_clan['members'][$key]['role_i18n']."\n";
					$event = new Events;
					$event->clan_id = $clan->id;
					$event->type = 2;
					$event->event = serialize(array('event'=>'role','id'=>$key,'nickname'=>$accounts[$key]->nickname,'role_old'=>$accounts[$key]->role_i18n,'role_new'=>$api_clan['members'][$key]['role_i18n']));
					$event->time = time();
					$event->save();
					
					$accounts[$key]->role = $api_clan['members'][$key]['role'];
					$accounts[$key]->role_i18n = $api_clan['members'][$key]['role_i18n'];
					$accounts[$key]->update(['role', 'role_i18n']);
				}
				
				$stat = $this->redis->hGetAll('a:'.$key);
				
				if (
					!$accounts[$key]->trigger_joined_clan &&
					$stat &&
					$stat['b_al'] == $api_accounts[$key]['statistics']['all']['battles'] &&
					$stat['b_cl'] == $api_accounts[$key]['statistics']['clan']['battles'] &&
					$stat['b_co'] == $api_accounts[$key]['statistics']['company']['battles'] &&
					$stat['b_te'] == $api_accounts[$key]['statistics']['team']['battles'] &&
					$stat['b_sd'] == $api_accounts[$key]['statistics']['stronghold_defense']['battles'] &&
					$stat['b_ss'] == $api_accounts[$key]['statistics']['stronghold_skirmish']['battles'] &&
					$stat['b_ga'] == $api_accounts[$key]['statistics']['globalmap_absolute']['battles'] &&
					$stat['b_gc'] == $api_accounts[$key]['statistics']['globalmap_champion']['battles'] &&
					$stat['b_gm'] == $api_accounts[$key]['statistics']['globalmap_middle']['battles'] &&
					$stat['b_ra'] == $api_accounts[$key]['statistics']['random']['battles']
				) continue;

				$active_accounts[$key] = $value;
				$active_accounts_keys[] = $key;
			}
			$active_accounts_keys = array_chunk($active_accounts_keys, 40);
			
			echo "Активных: ".count($active_accounts)."\n";
			
			//Загрузить танки с API
			$api_tanks = [];
			foreach ($active_accounts_keys as $value)
			{
				$api_tanks = $api_tanks + WGApi::getAccountTanks($value); //Загрузка танков в 40 потоков с интервалом 1 сек.
				if (count($active_accounts_keys) > 1)
					sleep(1);
			}
			
			//Обработать активных игроков
			foreach ($active_accounts as $key=>$value)
			{
				echo "Обработка игрока ".$key." - ".$accounts[$key]->nickname."\n";
				
				//Статистика Redis
				$stat = [];
				if (isset($api_tanks[$key]['status']) && $api_tanks[$key]['status'] == 'ok')
					$stat = $accounts[$key]->updateStat($api_accounts[$key], $tanks, $api_tanks[$key]['data'][$key]);
				
				//Онлайн Redis
				if (!empty($stat))
					$accounts[$key]->updateOnline($stat, empty($api_stronghold[$key]) ? 0 : $api_stronghold[$key]['total_resources_earned']);
			}
			
			echo "\n";
		}
		echo "Запросов к API: ".Yii::app()->params['wgr']."\n";
		echo "Время выполнения: ".(microtime(1) - $time)."\n";
	}
	
	public function actionGetFamepoints($cluster = 'ru')
	{
		if (!isset(Yii::app()->params['clusters'][$cluster]))
		{
			echo "Кластер не найден\n";
			return;
		}
		$this->setCluster($cluster);
		
		//Подготовка данных
		
		$time = microtime(1);
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		
		$clans = Clans::model()->findAll(['condition'=>"`cluster` = '{$cluster}'", 'index'=>'id', 'order'=>'`parser_famepoints_time`', 'limit'=>40]);
		if (!$clans)
		{
			echo "Ошибка Clans::model()->findAll\n";
			return;
		}
		
		while (microtime(1) - $time < 50 && $clan = array_shift($clans))
		{
			$clan->parser_famepoints_time = time();
			if (!$clan->update(['parser_famepoints_time']))
				continue;
			
			$accounts = Accounts::model()->findAll(['index'=>'id', 'condition'=>"`clan_id` = '{$clan->id}'"]);
			
			echo "Обработка клана ".$clan->id." - ".$clan->abbreviation."\n";
			
			//Клан
			
			$acc_list = array(); 
			foreach ($accounts as $key => $value)
			{
				$acc_list[] = $key;
			}
			$acc_list = implode(',', $acc_list);
			
			//Данные игроков
			
			$api_famepoints = WGApi::getFamepoints('eventmap', $acc_list);
			
			if (!$api_famepoints)
			{
				echo "Ошибка API getFamepoints\n";
				return;
			}
			
			foreach ($accounts as $key => $value)
			{
				if (empty($api_famepoints[$key]))
					continue;
				
				echo "Обработка игрока ".$key." - ".$accounts[$key]->nickname.": ";
				
				if ($accounts[$key]->fame_points == $api_famepoints[$key]['points'] && $accounts[$key]->position == $api_famepoints[$key]['position'])
				{
					echo "пропуск\n";
					continue;
				}

				$accounts[$key]->fame_points = $api_famepoints[$key]['points'];
				$accounts[$key]->position = $api_famepoints[$key]['position'];
				
				$accounts[$key]->update(array('fame_points', 'position'));
				
				echo "готово\n";
			}
		}
		echo "Запросов к API: ".Yii::app()->params['wgr']."\n";
		echo "Время выполнения: ".(microtime(1) - $time)."\n";
		echo "готово\n";
	}
	
	public function actionCheckSites($time = 0)
	{
		if (!$time)
		{
			$time = microtime(1);
			header('Content-type: text/plain; charset="utf-8"');
		}
		
		Yii::app()->params['skipSiteCheck'] = true;
		$site = Sites::model()->find(array('order'=>'`check_time`'));
		if (!$site)
		{
			echo "Ошибка Sites::model()->find\n";
			return;
		}
		$site->check_time = time();
		$site->update(array('check_time'));
		
		$check_url = $site->url;
		if (!in_array(preg_replace('=^.*?([^\.]+\.[\w]+)$=', '$1', $site->url), Yii::app()->params['domains']))
			$check_url = preg_replace('=^.*?([^\.]+\.[\w]+)$=', '$1', $site->url);
		echo "Обработка сайта {$site->url} - проверка URL ".$check_url."\n";
		
		
		$check = @json_decode($site->check, true);
		if (empty($check))
			$check = array();
		
		foreach ($site->clans as $clan)
		{
			sleep(3);
			
			if ($site->url == '7kazak7.wot.pw')
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'wotbs.ru')
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'stat.wot.pw') //До 1 июня
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'forze.wot.pw') //До 1 июня
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'lngst.wot.pw') //До 1 июня
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'xn--80aeirbbdtomneq5kf1nk.xn--j1amh') //Вечно
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'xn--80aaad6bgpyret8b.xn--p1ai') //Вечно
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'xn--29-ilctt.xn--p1ai') //Вечно
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
			if ($site->url == 'devsite.wot.pw') //Вечно
			{
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
					continue;
				}
			}
				
			if (empty($clan->abbreviation))
			{
				echo "Удаление клана ".$clan->id."\n";
				$clan->delete();
				continue;
			}
			echo "Обработка клана ".$clan->abbreviation.": ";
			
			$data = WGApi::checkClan($clan, $check_url);
			
			if ($data == 1)
			{
				echo "страница http://ru.wargaming.net/clans/".$clan->id."/ недоступна\n";
				continue;
			}
			
			if ($data == 2)
			{
				echo "клан не найден на странице\n";
				continue;
			}
			
			if ($data == 3)
			{
				echo "готово\n";
				if (!empty($check[$clan->abbreviation]))
				{
					unset($check[$clan->abbreviation]);
				}
			}
			else
			{
				echo "ссылка не найдена: ";
				if (empty($check[$clan->abbreviation]))
				{
					$check[$clan->abbreviation] = time();
				}
				if ($check[$clan->abbreviation] < time() - 3600*24*3)
				{
					echo "удаление клана\n";
					$clan->delete();
				}
				else
					echo "с ".date('d.m.Y H:i', $check[$clan->abbreviation]).", удаление ".(date('d.m.Y H:i', $check[$clan->abbreviation] + 3600*24*3))." UTC\n";
			}
		}
		
		$site->check = json_encode($check);
		$site->update(array('check'));
		
		echo "Время выполнения: ".(microtime(1) - $time)."\n";
		if (microtime(1) - $time < 30)
		{
			unset($site);
			return $this->actionCheckSites($time);
		}
		echo "готово\n";
		
	}
	
	public function actionTestIvanerr($clan_id)
	{
		Yii::app()->params['skipSiteCheck'] = true;
		
		$ivanerr_top = Yii::app()->cache->get('ivanerr_top');
		if (!$ivanerr_top)
		{
			$ivanerr_top = @file_get_contents('http://ivanerr.ru/lt/export.php?byclanid&lastpos=1');
			if (!$ivanerr_top)
				return false;
			$ivanerr_top = @json_decode($ivanerr_top, true);
			if (!$ivanerr_top)
				return false;
			$ivanerr_top = array_shift($ivanerr_top);
			if (!isset($ivanerr_top['totalrate']) || !isset($ivanerr_top['clanid']))
				return false;
			Yii::app()->cache->set('ivanerr_top', $ivanerr_top, 3600);
		}
		
		$tanks = Tanks::model()->findAll(array('index'=>'id', 'condition'=>"`level` = '10' AND `weight` > '0'"));
		
		$clan_top = Clans::model()->findByPk($ivanerr_top['clanid']);
		if (!$clan_top)
			return false;
		
		$accounts_top = Accounts::model()->findAll(array('index'=>'id', 'condition'=>"`clan_id` = '{$ivanerr_top['clanid']}'"));
		
		foreach ($accounts_top as $key=>$value) {
			if (!empty($value->tanks_list))
				$accounts_top[$key]->tanks_list = unserialize($value->tanks_list);
		}
		$power_top = WGApi::ivanerrRating($accounts_top, $tanks);
		$k = $ivanerr_top['totalrate']/$power_top;
		$accounts = Accounts::model()->findAll(array('index'=>'id', 'condition'=>"`clan_id` = '{$clan_id}'"));
		echo 'Игроков: '.count($accounts).'<br>';
		
		foreach ($accounts as $key=>$value) {
			if (!empty($value->tanks_list))
				$accounts[$key]->tanks_list = unserialize($value->tanks_list);
		}
		
		$tp = 0;
		foreach ($accounts as $key=>$value) {
			$tp += WGApi::ivanerrRating([$key=>$value], $tanks);
		}
		echo 'Расчетная сила: '.($tp*$k).'<br>';
		/* $tp = 0;
		foreach ($accounts as $key=>$value) {
			$a = $value;
			unset($accounts[$key]);
			$power = WGApi::ivanerrRating($accounts, $tanks);
			$tp += ($power_sum - $power)*$k;
			echo $value->nickname.': '.round(($power_sum - $power)*$k,2).'<br>';
			$accounts[$key] = $a;
		}
		echo $tp.'<br>'; */
	}
	
	public function actionProcessTsServer()
	{
		exit('disabled');
		$time = microtime(1);
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		$servers = TsServers::model()->findAll("`time_down` = '0'");
		if (!$servers)
		{
			echo "Нет ни одного запущенного сервера\n";
			exit();
		}
		
		foreach ($servers as $value)
		{
			if (!$value->connect())
			{
				echo "Невозможно подключиться к серверу ".$value->server['ip']."\n";
				continue;
			}
			echo "Обработка сервера ".$value->server['ip'].':'.$value->port.": ";
			if (!$value->select())
			{
				echo "сервер остановлен\n";
				continue;
			}
			$server_info = $value->server['query']->serverInfo();
			if (!$server_info['success'])
			{
				echo "ошибка serverInfo\n";
				continue;
			}
			$server_info = $server_info['data'];
			$slots = $server_info['virtualserver_clientsonline'] - 1 + $value->freeSlots;
			if ($slots != $server_info['virtualserver_maxclients'])
			{
				$value->server['query']->serverEdit(['virtualserver_maxclients'=>$slots]);
				echo "обновление слотов - ".$slots.", ";
			}
			echo "готово\n";
		}
		
		echo "Время выполнения: ".(microtime(1) - $time)."\n";			
	}
	
	public function actionDeleteTsServer()
	{
		$time = microtime(1);
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		$servers = TsServers::model()->findAll("`time_down` BETWEEN 1 AND ".(time() - 3600*24*14));
		if (!$servers)
		{
			echo "Нет серверов в очереди\n";
			exit();
		}
		echo count($servers)." серверов в очереди\n";

		foreach ($servers as $value)
		{
			if (!$value->connect())
			{
				echo "Невозможно подключиться к серверу ".$value->server['ip']."\n";
				continue;
			}
			echo "Обработка сервера ".$value->server['ip'].':'.$value->port.": ";
			echo "остановлен с ".date('d.m.Y H:i', $value->time_down).": ";

			if ($value->delete())
			{
				echo "удален: ";
			}
			else
			{
				echo "ошибка удаления\n";
				continue;
			}

			echo "готово\n";
		}

		echo "Время выполнения: ".(microtime(1) - $time)."\n";
	}

	public function actionProcessTsStat()
	{
		$time = microtime(1);
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		
		$date = mktime(0,0,0,date('m'),date('d'),date('Y'));
		
		$count = TsServers::model()->count();
		echo "Всего серверов: ".$count."\n";
		
		$servers = TsServers::model()->findAll(['condition'=>"`time_down` = '0'"]);
		if (!$servers)
		{
			echo "Нет ни одного запущенного сервера\n";
			exit();
		}
		
		$stat = TsStat::model()->findAll(['condition'=>"`time` = '{$date}'", 'index'=>'server_id']);
		
		foreach ($servers as $value)
		{
			if (!$value->connect())
			{
				echo "Невозможно подключиться к серверу ".$value->server['ip']."\n";
				continue;
			}
			echo "Обработка сервера ".$value->server['ip'].':'.$value->port.": ";
			if (!$value->select())
			{
				echo "сервер остановлен\n";
				continue;
			}
			$server_info = $value->server['query']->serverInfo();
			if (!$server_info['success'])
			{
				echo "ошибка serverInfo\n";
				continue;
			}
			$server_info = $server_info['data'];
			
			$online = $server_info['virtualserver_clientsonline']-$server_info['virtualserver_queryclientsonline'];
			echo 'онлайн: '.$online.', ';
			if (!isset($stat[$value->id]))
			{
				$stat_new = new TsStat;
				$stat_new->site_id = $value->site_id;
				$stat_new->server_id = $value->id;
				$stat_new->time = $date;
				$stat_new->rate = $value->rate;
				$stat_new->online = $online;
				if ($stat_new->save())
					echo 'добавление статистики, ';
				else
					echo 'ОШИБКА добавления статистики, ';
			}
			else
			{
				if ($stat[$value->id]->online < $online)
				{
					$stat[$value->id]->online = $online;
					if ($stat[$value->id]->update(['online']))
						echo 'обновление статистики, ';
					else
						echo 'ОШИБКА ошибка обновления статистики, ';
				}
			}
			
			echo "готово\n";
		}
		
		echo "Использовано памяти: ".round(memory_get_usage() / 1000 / 1000, 2)." Мб\n";			
		echo "Время выполнения: ".(microtime(1) - $time)."\n";			
	}
	
	public function actionProcessTsAmount()
	{
		$time = microtime(1);
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		
		$date = mktime(0,0,0,date('m', time()-3600*24),date('d', time()-3600*24),date('Y', time()-3600*24));
		
		TsStat::model()->deleteAll(['condition'=>"`time` < '".(time()-3600*24*30)."'"]);
		
		$stat = TsStat::model()->with(['server', 'site'=>['select'=>"`balance`"]])->findAll(['condition'=>"`time` = '{$date}' AND `processed` = '0'", 'limit'=>'30']);
		if (!$stat)
		{
			echo "Нет серверов в очереди\n";
			exit();
		}
		
		foreach ($stat as $value)
		{
			echo "Обработка сервера ".$value->server_id.": ";
			$online = $value->online > 10 ? $value->online : 10;
			$value->amount = $online*$value->rate;
			$value->processed = 1;
			if (!$value->update(['amount', 'processed']))
			{
				echo "ошибка TsStat::update\n";
				continue;
			}
			if ($value->amount > 0)
			{
				$value->site->balance = $value->site->balance - $value->amount;
				$value->site->update(['balance']);
				//$this->addProfit('Looney', $value->amount*0.3);
				//$this->addProfit('Zaur', $value->amount*0.2);
				echo "списание с баланса ". $value->amount." рублей, ";
			}
			if ($value->server && $value->site->balance <= 0)
			{
				if (!$value->server->connect())
				{
					echo "невозможно подключиться к серверу ".$value->server->server['ip']."\n";
					continue;
				}
				if ($value->server->select())
				{
					$value->server->server['query']->serverEdit(['virtualserver_autostart'=>0]);
				}
				$value->server->server['query']->serverStop($value->server->sid);
				if ($value->server->time_down == 0)
				{
					$value->server->time_down = time();
					$value->server->update(['time_down']);
				}
				echo "отключение по балансу, ";
			}
			echo "готово\n";
		}
		
		echo "Время выполнения: ".(microtime(1) - $time)."\n";			
	}
	
	public function actionProcessTsLost()
	{
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset="utf-8"');
		
		$date = mktime(0,0,0,date('m', time()),date('d', time()),date('Y', time()));
		
		$stat = TsStat::model()->with(['server'])->findAll(['condition'=>"`time` < '{$date}' AND `processed` = '0'"]);
		if (!$stat)
		{
			echo "Нет серверов в очереди\n";
			exit();
		}
		
		$sum = 0;
		foreach ($stat as $value)
		{
			echo "Обработка сервера ".$value->server_id.": ";
			$value->amount = $value->online*$value->rate;
			$value->processed = 1;
			$sum+=$value->amount;
			
			if (!$value->update(['amount', 'processed']))
			{
				echo "ошибка TsStat::update\n";
				continue;
			}
			
			if ($value->amount > 0)
			{
				$site = Sites::model()->findByPk($value->site_id);
				echo 'баланс - '.$site->balance.'. ';
				$site->balance = $site->balance - $value->amount;
				$site->update(['balance']);
				echo "списание с баланса ". $value->amount." рублей, ";
			}
			echo "готово\n";
		}
		echo $sum;
	}
	
	private function addProfit($name, $amount)
	{
		$amount = number_format($amount, 2, '.', '');
		$profit = Profits::model()->findByPk($name);
		$profit->debt = $profit->debt + $amount;
		$profit->sum = $profit->sum + $amount;
		$profit->update(array('debt', 'sum'));
	}
	
	public function actionGetTsIp($url)
	{
		Yii::app()->params['skipSiteCheck'] = true;
		$model = TsServers::model()->cache(600)->with(['site'=>['select'=>'url']])->find(['condition'=>"`site`.`url` = '{$url}'"]);
		if ($model)
			header('tsip: '.$model::$servers[$model->server_id]['ip'].':'.$model->port);
		else
			header('tsip: ');
	}
}