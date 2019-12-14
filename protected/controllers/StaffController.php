<?php

class StaffController extends Controller
{
	public $defaultAction = 'members';

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array_merge(array(
			array('allow',
				'users'=>array('@'),
				'actions'=>array('tacticClient'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
				'actions'=>array('tacticClient'),
			),
		),parent::accessRules());
	}

	public function actionBattles()
	{
		$clans = Clans::model()->findAll(array('index'=>'id', 'order'=>'`order`'));
		
		if (isset($_GET['id']) && isset($clans[$_GET['id']]))
			$clan_id = $_GET['id'];
		else
			$clan_id = key($clans);
		
		$battles = $this->getBattles($clan_id);
		
		$this->render('battles', array(
			'clans'=>$clans,
			'clan_id'=>$clan_id,
			'battles'=>$battles,
		));
	}

	public function actionTactic()
	{
		if(!$this->site->premium_tactic)
			throw new CHttpException(403,'Доступ к планшету запрещен.');
		$this->layout = '//layouts/tactic';
		$this->render('tactic', []);
	}

	public function actionTacticClient()
	{
		if(!$this->site->premium_tactic)
			throw new CHttpException(403,'Доступ к планшету запрещен.');
		$this->layout = '//layouts/tacticClient';
		$this->render('tactic', []);
	}

	public function actionMembers()
	{
		if (empty($this->clans))
			throw new CHttpException(403,'Для отображения игроков '.CHtml::link('добавьте', array('/admin/clans/create')).' хотя бы один клан в админке.');
			
		if (isset($_GET['id']) && isset($this->clans[$_GET['id']]))
			$clan = $this->clans[$_GET['id']];
		else
			$clan = current($this->clans);
		
		$clanStat = $clan->getAccountsStat();
		if (!$clanStat)
			throw new CHttpException(403,'Дождитесь загрузки статистики всех игроков клана.');

		$show_stat = 0;
		$staff_stat_show = Config::explode(Config::all('staff_stat_show')->value);
		if (is_array($staff_stat_show))
		{
			foreach ($staff_stat_show as $value)
			{
				if (Yii::app()->user->checkAccess($value))
				{
					$show_stat = 1;
					break;
				}
				if (!empty(Yii::app()->user->account) && Yii::app()->user->account->clan_id != '' && Yii::app()->user->account->role == $value)
				{
					if (!empty($this->clans[Yii::app()->user->account->clan_id]) && Config::all('staff_stat_clans')->value == 'alliance')
					{
						$show_stat = 1;
						break;
					}
					if (Yii::app()->user->account->clan_id == $clan->id && Config::all('staff_stat_clans')->value == 'clan')
					{
						$show_stat = 1;
						break;
					}
				}
			}
		}
		
		$check = @json_decode($this->site->check, true);
		
		$this->render('members', array(
			'accounts'=>$clanStat['accounts'],
			'clan'=>$clan,
			'stat'=>$clanStat['stat'],
			'show_stat'=>$show_stat,
			'check'=>$check,
 			/*'clan_id'=>$clan_id,
*/
		));
	}
	
	public function actionOnline($id = 0)
	{
		if (empty($this->clans))
			throw new CHttpException(403,'Для отображения игроков '.CHtml::link('добавьте', array('/admin/clans/create')).' хотя бы один клан в админке.');
		
		if (empty($id))
		{
			$clan = current($this->clans);
			$this->redirect(['/staff/online', 'id'=>$clan->id]);
		}
		
		if (!isset($this->clans[$id]))
			throw new CHttpException(404, 'Клан с указанным ID не найден');
		
		$clan = $this->clans[$id];
		
	}
	
	public function actionAttendance($clan_id = 0, $param = 0)
	{
		if (empty($this->clans))
			throw new CHttpException(403,'Для отображения игроков '.CHtml::link('добавьте', array('/admin/clans/create')).' хотя бы один клан в админке.');
			
		if (empty($clan_id) || empty($param))
		{
			$clan = current($this->clans);
			$this->redirect(['/staff/attendance', 'clan_id'=>$clan->id, 'param'=>1]);
		}
			
		if (!isset($this->clans[$clan_id]))
			throw new CHttpException(404, 'Клан с указанным ID не найден');
		
		$clan = $this->clans[$clan_id];
		$param--;
		
		if ($param > 14)
			throw new CHttpException(404, 'Статистика по указанному параметру не найдена');
		
		$stat = [];
		$keys = $this->redis->keys('o:'.$clan->id.':*');
		if (!empty($keys))
		{
			$stat = $this->redis->mGet($keys);
		}
		$keys = array_flip($keys);
		
		$limits = [30,60,90];
		$limit = Yii::app()->request->getParam('p');
		if (!in_array($limit, $limits))
			$limit = 30;
		
		$dates = [];
		for ($i=-$limit;$i<=0;$i++)
		{
			$dates[] = mktime(0,0,0,date('n'),date('j')+$i);
		}
		
/* 		echo '<pre>';
		exit(print_r($dates, 1));
 */	
		$is_div = in_array($param, [4,5,6,7,8,9,10,11,12,13,14]);
		
		$data = [];
		foreach ($clan->accountsOrdered as $id=>$account)
		{
			$data[$id] = [];
			$prev = 0;
			$current = 0;
			foreach ($dates as $date)
			{
				$prev = $current;
				if (isset($keys['o:'.$clan->id.':'.$id.':'.$date]))
				{
					$current = explode(':', $stat[$keys['o:'.$clan->id.':'.$id.':'.$date]])[$param];
					$data[$id][] = $is_div ? ($prev ? $current-$prev : 0) : $current;
				}
				else
				{
					$data[$id][] = $is_div ? ($prev ? $current-$prev : 0) : $current;
				}
			}
		}
		
		//echo '<pre>';
		//exit(print_r($data, 1));
		
		$this->render('attendance', array(
			'clan'=>$clan,
			'dates'=>$dates,
			'data'=>$data,
			'param'=>$param,
			'is_div'=>$is_div,
			'limit'=>$limit,
		));
	}
	
	public function actionCompanies()
	{
		if(!$this->site->premium_companies)
			throw new CHttpException(403,'Доступ к ротам запрещен.');
		
		$clans = Clans::model()->findAll(array('order'=>'`order`'));
		
		$clan_list = array();
		foreach ($clans as $value)
		{
			$clan_list[$value->id] = $value->id;
		}
		
		if (isset($_GET['id']) && in_array($_GET['id'], $clan_list))
			$clan_id = $_GET['id'];
		else
			$clan_id = current($clan_list);
		
		$companies = Companies::model()->findAllByAttributes(array('clan_id'=>$clan_id), array('order'=>"`order`"));
		//foreach ($companies as $key=>$value)
		//{
		//	$companies[$key]->accounts = explode(',', $companies[$key]->accounts);
		//}
		
		
		$this->render('companies', array(
			'clans'=>$clans,
			'clan_id'=>$clan_id,
			'companies'=>$companies,
		));
	}

	public function actionFamepoints()
	{
		$clans = Clans::model()->findAll(array('order'=>'`order`'));
		
		$clan_list = array();
		foreach ($clans as $value)
		{
			$clan_list[$value->id] = $value->id;
		}
		
		if (isset($_GET['id']) && in_array($_GET['id'], $clan_list))
			$clan_id = $_GET['id'];
		else
			$clan_id = current($clan_list);
		
		$stat = array();
		if ($clan_id)
		{
			$stat = Yii::app()->db->createCommand("SELECT COUNT(*) `count`, SUM(`fame_points`) `fp` FROM `accounts` WHERE `clan_id` = '{$clan_id}'")->queryRow();
		}
				
		$this->render('famepoints', array(
			'clans'=>$clans,
			'clan_id'=>$clan_id,
			'stat'=>$stat,
		));
	}

	public function actionHistory()
	{
		$clans = Clans::model()->findAll(array('order'=>'`order`'));
		
		$clan_list = array();
		foreach ($clans as $value)
		{
			$clan_list[$value->id] = $value->id;
		}
		
		if (isset($_GET['id']) && in_array($_GET['id'], $clan_list))
			$clan_id = $_GET['id'];
		else
			$clan_id = array_shift($clan_list);
		
		$this->render('history', array(
			'clans'=>$clans,
			'clan_id'=>$clan_id,
		));
	}

	public function actionProvinces()
	{
		$clans = Clans::model()->findAll(array('index'=>'id', 'order'=>'`order`'));
		
		if (isset($_GET['id']) && isset($clans[$_GET['id']]))
			$clan_id = $_GET['id'];
		else
			$clan_id = key($clans);
		
		$provinces = $this->getProvinces($clan_id);
		
		$this->render('provinces', array(
			'clans'=>$clans,
			'clan_id'=>$clan_id,
			'provinces'=>$provinces,
		));
	}
	
	public function getProvinces($clan_id)
	{
		$clan = Clans::model()->findByPk($clan_id);
		if (!$clan)
			return false;
		$provinces = Yii::app()->cache->get('provinces_'.$clan->id);
		if ($provinces !== false)
			return $provinces;
		$provinces = WGApi::getClanProvinces($clan->id);
		if ($provinces === false)
			return false;
		Yii::app()->cache->set('provinces_'.$clan->id, $provinces, 600);
		return $provinces;
	}

	public function getBattles($clan_id)
	{
		$clan = Clans::model()->findByPk($clan_id);
		if (!$clan)
			return false;
		$battles = Yii::app()->cache->get('battles_'.$clan->id);
		if ($battles !== false)
			return $battles;
		
		$api_battles = WGApi::getClanBattles($clan->id);
		if (!isset($api_battles[$clan->id]) || !is_array($api_battles[$clan->id]))
			$battles = [];
		else
			$battles = $api_battles[$clan->id];
		
		foreach ($battles as $key=>$value)
		{
			$battles[$key]['id'] = $key;
		}
		
		Yii::app()->cache->set('battles_'.$clan->id, $battles, 600);
		return $battles;
	}
	
	public function getBattleType($type)
	{
		$types = array(
			'for_province'=>'Бой за провинцию',
			'meeting_engagement'=>'Встречный бой',
			'landing'=>'Бой за высадку',
			'attack'=>'Атака',
			'defense'=>'Оборона',
		);
		return $types[$type];
	}
}