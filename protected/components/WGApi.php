<?php

class WGApi extends CComponent
{
	public static $colors = array(
		"very_bad"=>"#FE0E00",
		"bad"=>"#FE7903",
		"normal"=>"#F8F400",
		"good"=>"#60FF00",
		"very_good"=>"#02C9B3",
		"unique"=>"#D042F3"
  );
	public static $classes = array(
		"very_bad"=>"very-bad",
		"bad"=>"bad",
		"normal"=>"normal",
		"good"=>"good",
		"very_good"=>"very-good",
		"unique"=>"unique"
  );
	public static $cbattles = array(
    2=>'very_bad',
    5 =>'bad',
    9=>'normal',
    14=>'good',
    20=>'very_good',
    999=>'unique'
	);
	public static $civanerr = array(
    51=>'unique',
    101=>'very_good',
    501=>'good',
    1001=>'normal',
    10001 =>'bad'
	);
	public static $cwins = array(
    46=>'very_bad',
    48=>'bad',
    51=>'normal',
    57=>'good',
    64=>'very_good',
    101=>'unique'
	);
	public static $cre = array(
    614=>'very_bad',
    869=>'bad',
    1174=>'normal',
    1524=>'good',
    1849=>'very_good',
    9999=>'unique'
	);
	public static $cwn8 = array(
    369=>'very_bad',
    844=>'bad',
    1394=>'normal',
    2069=>'good',
    2714=>'very_good',
    9999=>'unique'
	);
	public static $cbs = array(
    4000=>'very_bad',
    4500=>'bad',
    5000=>'normal',
    6000=>'good',
    8000=>'very_good',
    99999=>'unique'
	);
	function getClanRole($role)
	{
		if ($role == 'commander')
			return '<span class="leader">'.Yii::t("wot", "Commander").'</span>';
		if ($role == 'executive_officer')
			return '<span class="vice-leader">'.Yii::t("wot", "Executive Officer").'</span>';
		if ($role == 'personnel_officer')
			return '<span class="personnel-officer">'.Yii::t("wot", "Personnel officer").'</span>';
		if ($role == 'combat_officer')
			return '<span class="commander">'.Yii::t("wot", "Combat officer").'</span>';
		if ($role == 'intelligence_officer')
			return '<span class="diplomat">'.Yii::t("wot", "Intelligence officer").'</span>';
		if ($role == 'quartermaster')
			return '<span class="treasurer">'.Yii::t("wot", "Quartermaster").'</span>';
		if ($role == 'recruitment_officer')
			return '<span class="recruiter">'.Yii::t("wot", "Recruitment officer").'</span>';
		if ($role == 'junior_officer')
			return '<span class="junior-officer">'.Yii::t("wot", "Junior officer").'</span>';
		if ($role == 'private')
			return '<span class="private">'.Yii::t("wot", "Private").'</span>';
		if ($role == 'recruit')
			return '<span class="recruit">'.Yii::t("wot", "Recruit").'</span>';
		if ($role == 'reservist')
			return '<span class="reservist">'.Yii::t("wot", "Reservist").'</span>';
		return $role;
	}
	public static function send($url, $params, $log = false)
	{
		$result = @file_get_contents($url.'?'.http_build_query($params));
		if (!$result)
		{
			usleep(300000);
			$result = @file_get_contents($url.'?'.http_build_query($params));
		}
		if (!$result)
		{
			usleep(300000);
			$result = @file_get_contents($url.'?'.http_build_query($params));
		}
		$data = json_decode($result, true);
		if ($log)
		{
			echo '<pre>';
			echo 'Result:<br>';
			echo $result;
			echo '<br>JSON decoded:<br>';
			print_r($data);
			echo '</pre>';
		}
		Yii::app()->params['wgr'] = Yii::app()->params['wgr'] + 1;
		return $data;
	}

	public static function send_async($urls)
	{
		$requests = new Requests();
		$requests->process($urls);
		Yii::app()->params['wgr'] = Yii::app()->params['wgr'] + count($urls);
		foreach ($requests->result as $key=>$value)
		{
			$requests->result[$key] = @json_decode($value, true);
		}
		return $requests->result;
	}

	public static function getStrongholdAccountstats($account_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/stronghold/accountstats/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'account_id'=>$account_id,
			'fields'=>'total_resources_earned',
		));
		if (empty($result['status']) || $result['status'] != 'ok' || empty($result['data']) || !is_array($result['data']))
			return false;
		return $result['data'];
	}

	public static function getAccountInfo($account_id, $access_token = null)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/account/info/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'account_id'=>$account_id,
			'access_token'=>$access_token,
			'fields'=>'statistics,account_id,last_battle_time,created_at,updated_at,global_rating,clan_id,nickname,logout_at',
			'extra'=>'statistics.globalmap_absolute,statistics.globalmap_champion,statistics.globalmap_middle,statistics.random',
		));
		if (empty($result['status']) || $result['status'] != 'ok' || empty($result['data']) || !is_array($result['data']))
			return false;
		return $result['data'];
	}

	public static function getFamepoints($map_id, $account_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/globalwar/accountpoints/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'map_id'=>$map_id,
			'account_id'=>$account_id,
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
		{
			return false;
		}
		return $result['data'];
	}

	public function getAccountList($search, $type = 'exact')
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wgn/account/list/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'search'=>$search,
			'type'=>$type,
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

	public static function getAccountTanks($account_id)
	{
		$urls = [];
		if (!is_array($account_id))
		{
			$account_id = [$account_id];
		}
		foreach ($account_id as $value)
		{
			$urls[$value] = Yii::app()->controller->cluster['api_url'].'/wot/tanks/stats/?application_id='.Yii::app()->controller->cluster['application_id'].'&fields=all,mark_of_mastery,tank_id&account_id='.$value;
		}
		$result = self::send_async($urls);
		return $result;
	}

	public static function getClanMembersInfo($account_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wgn/clans/membersinfo/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'account_id'=>$account_id,
		));
		if (!isset($result['status']) || $result['status'] != 'ok' || empty($result['data']) || !is_array($result['data']))
			return false;
		return array_shift($result['data']);
	}

	public static function getClanInfo($clan_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/clan/info/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'clan_id'=>$clan_id,
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

	public static function getClansInfo($clan_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wgn/clans/info/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'clan_id'=>$clan_id,
			'members_key'=>'id',
			'fields'=>'name,tag,is_clan_disbanded,members',
		));
		if (empty($result['status']) || $result['status'] != 'ok' || empty($result['data']) || !is_array($result['data']))
			return false;
		return $result['data'];
	}

	public static function getTanks()
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/encyclopedia/vehicles/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
            'fields' => 'tank_id,name,images.contour_icon,tag,short_name,nation,tier,is_premium,type',
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

	public static function getAchievements()
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/encyclopedia/achievements/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

//	public static function getTankInfo($tank_id)
//	{
//		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/encyclopedia/tankinfo/', array(
//			'application_id'=>Yii::app()->controller->cluster['application_id'],
//			'tank_id'=>$tank_id,
//			'fields'=>'contour_image',
//		));
//		if (!isset($result['status']) || $result['status'] != 'ok')
//			return false;
//		return $result['data'];
//	}

	public static function getClanBattles($clan_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/stronghold/plannedbattles/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'clan_id'=>$clan_id,
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

	public static function getFronts()
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/globalmap/fronts/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

	public static function getProvinces($map_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/globalwar/provinces/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'map_id'=>$map_id,
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		return $result['data'];
	}

	public static function getClanProvinces($clan_id)
	{
		$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/globalmap/clanprovinces/', array(
			'application_id'=>Yii::app()->controller->cluster['application_id'],
			'clan_id'=>$clan_id,
		));
		if (!isset($result['status']) || $result['status'] != 'ok')
			return false;
		
		$data = array_shift($result['data']);
		
		if ($data)
		{
			$provinces = [];
			foreach ($data as $value)
			{
				$provinces[$value['front_id']][] = $value['province_id'];
			}
			foreach ($provinces as $key=>$value)
			{
				$value = implode(',', $value);
				$result = self::send(Yii::app()->controller->cluster['api_url'].'/wot/globalmap/provinces/', array(
					'application_id'=>Yii::app()->controller->cluster['application_id'],
					'front_id'=>$key,
					'province_id'=>$value,
				));
				if (!isset($result['status']) || $result['status'] != 'ok')
					return false;
				$provinces[$key] = $result['data'];
			}
			foreach ($provinces as $value)
			{
				foreach ($value as $value2)
				{
					foreach ($data as $key3=>$value3)
					{
						if ($value3['province_id'] == $value2['province_id'])
						{
							$data[$key3]['info'] = $value2;
						}
					}
				}
			}
		}
		else
			$data = [];
		
		
		
		
		return $data;
	}

	public static function bsRating($battles, $xp, $damage_dealt, $wins, $frags, $spotted, $capture_at, $capture_de)
	{
		$rating = $battles > 0 ? log($battles) / 10 * ($xp / $battles + $damage_dealt / $battles * ($wins / $battles * 2 + $frags / $battles * 0.9 + $spotted / $battles * 0.5 + $capture_at / $battles * 0.5 + $capture_de / $battles * 0.5)) : 0;
		return $rating;
	}
	
	public static function reRating($battles, $tanks_list, $damage_dealt, $frags, $spotted, $capture_at, $capture_de)
	{
		if (empty($tanks_list) || empty($battles))
			return 0;
		
		$mid = 0;
		foreach ($tanks_list as $tank)
		{
			$mid += $tank['l']*$tank['b']/$battles;
		}
		$mid = round($mid, 2);
		
		$rating = $damage_dealt / $battles * (10 / ($mid + 2)) * (0.23 + 2*$mid / 100) + $frags / $battles * 250 + $spotted / $battles * 150 + log($capture_at / $battles + 1, 1.732) * 150 + $capture_de / $battles * 150;
		return $rating;
	}

	public static function getWn8Data()
	{
		$data = Yii::app()->cache->get('wn8Data1');
		if ($data)
			return $data;
		
		$data = @file_get_contents('protected/data/expected_tank_values_latest.json');
		if (!$data)
			return false;
		
		$data = @json_decode($data, true);
		if (!$data)
			return false;
		
		$r = [];
		foreach ($data['data'] as $info)
		{
			$r[$info['IDNum']] = $info;
		}
		
		Yii::app()->cache->set('wn8Data1', $r, 3600*6);
		
		return $r;
	}

	public static function wn8Rating($stat_all, $tanks_list)
	{
		if (!$tanks_list || !$stat_all['battles'])
			return 0;
		
		$data = self::getWn8Data();
		
		$expDAMAGE = 0;
		$expSPOT = 0;
		$expFRAG = 0;
		$expDEF = 0;
		$expWIN = 0;
		
		foreach ($tanks_list as $tank_id=>$tank)
		{
			if (!isset($data[$tank_id]))
				continue;
			$expDAMAGE += $data[$tank_id]['expDamage'] * $tank['b'];
			$expSPOT += $data[$tank_id]['expSpot'] * $tank['b'];
			$expFRAG += $data[$tank_id]['expFrag'] * $tank['b'];
			$expDEF += $data[$tank_id]['expDef'] * $tank['b'];
			$expWIN += $data[$tank_id]['expWinRate'] * $tank['b'];
		}
				
		$expWIN = $expWIN / $stat_all['battles'];
		
		$rDAMAGE = $stat_all['damage_dealt'] / $expDAMAGE;
		$rSPOT = $stat_all['spotted'] / $expSPOT;
		$rFRAG = $stat_all['frags'] / $expFRAG;
		$rDEF = $stat_all['dropped_capture_points'] / $expDEF;
		$rWIN = $stat_all['wins'] / $stat_all['battles'] * 100 / $expWIN;
		
		$rWINc = max(0, ($rWIN - 0.71) / (1 - 0.71));
		$rDAMAGEc = max(0, ($rDAMAGE - 0.22) / (1 - 0.22));
		$rFRAGc = max(0, min($rDAMAGEc + 0.2, ($rFRAG - 0.12) / (1 - 0.12)));
		$rSPOTc = max(0, min($rDAMAGEc + 0.1, ($rSPOT - 0.38) / (1 - 0.38)));
		$rDEFc = max(0, min($rDAMAGEc + 0.1, ($rDEF - 0.10) / (1 - 0.10)));
		
		return 980 * $rDAMAGEc + 210 * $rDAMAGEc * $rFRAGc + 155 * $rFRAGc * $rSPOTc + 75 * $rDEFc * $rFRAGc + 145 * min(1.8, $rWINc);
	}

	public static function ivanerrRating($accounts, $tanks)
	{
		$ids = [];
		$plbs = [];
		$plas = [];
		$pla2s = [];

		$o = [];

		$time = time();
		foreach ($tanks as $tank) {
			$ids[] = $tank->id;
			$plas[$tank->id] = [];
			$pla2s[$tank->id] = [];
			foreach ($accounts as $account) {
				if (isset($account->tanks_list[$tank->id])) {
					$plbs[$account->id] = 1;
					if ($account->tanks_list[$tank->id]['b']) {
						if (!isset($o[$account->id])) {
							$days = intval(($time - $account->battle_at) / 86400);
							$o[$account->id] = $days < 8 ? 1 : 3.194858 / (3.100884 + exp($days * 0.281403 - 4.334563));
						}
						$pla2s[$tank->id][] = $account->id;
						if ($account->tanks_list[$tank->id]['b'] > 9) {
							$plas[$tank->id][] = $account->id;
						}
					}
				}
			}
		}
		$plbs = array_keys($plbs);

		$power = 0.0;
		$wintop = 0.0;
		$winbottom = 0.0;

		foreach ($ids as $id) {
			$tmp = 0.0;
			foreach ($pla2s[$id] as $pla2) {
				$tmp += $o[$pla2];
			}
			$power += $tmp * $tanks[$id]->weight;

			$platop = 0.0;
			$plabottom = 0.0;

			foreach ($plas[$id] as $pla) {
				$platop += $accounts[$pla]->
				tanks_list[$id]['b'] > 300 ?
					$accounts[$pla]->tanks_list[$id]['w'] * 300.0 * $o[$pla] / $accounts[$pla]->tanks_list[$id]['b'] :
					$accounts[$pla]->tanks_list[$id]['w'] * $o[$pla];

				$plabottom += $accounts[$pla]->tanks_list[$id]['b'] < 300 ?
					$accounts[$pla]->tanks_list[$id]['b'] * $o[$pla] :
					300.0 * $o[$pla];
				
			}
			$wintop += $plabottom > 0 ? $platop / $plabottom * $tanks[$id]->weight : 0;
			$winbottom += $plabottom > 0 ? $tanks[$id]->weight : 0;
		}

		$winprob = $winbottom > 0 ? $wintop / $winbottom : 0;

		$ftop = 0.0;
		$fbottom = 0.0;
		$htop = 0.0;
		$hbottom = 0;

		foreach ($plbs as $plb) {
			$tf = 0;
			$hits = 0;
			$shots = 0;
			foreach ($accounts[$plb]->tanks_list as $tank) {
				$tf += $tank['f'];
				$hits += $tank['h'];
				$shots += $tank['s'];
			}
			$ftop += $tf;
			$fbottom += $accounts[$plb]->battles;
			if ($accounts[$plb]->battles > 1000) {
				$htop += $hits / $shots;
				$hbottom++;
			}
		}

		$frags = $fbottom > 0 ? $ftop / $fbottom : 0;
		$hitprob = $hbottom > 0 ? $htop / $hbottom : 0;

		return $winprob * $winprob * $winprob * $frags * $frags * $hitprob * $power;
	}

	public static function updateAccount($account_id) //TODO
	{
		$account = Accounts::model()->findByPk($account_id);

	}

	public static function getBattleColor($count)
	{
		foreach (WGApi::$cbattles as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$colors[$rank];
		}
		return WGApi::$colors["unique"];
	}

	public static function getBattleClass($count)
	{
		foreach (WGApi::$cbattles as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$classes[$rank];
		}
		return WGApi::$classes["unique"];
	}

	public static function getIvanerrColor($rating)
	{
		foreach (WGApi::$civanerr as $key=>$rank)
		{
			if ($rating < $key && $rating != 0)
				return WGApi::$colors[$rank];
		}
		return WGApi::$colors["very_bad"];
	}

	public static function getIvanerrClass($rating)
	{
		foreach (WGApi::$civanerr as $key=>$rank)
		{
			if ($rating < $key && $rating != 0)
				return WGApi::$classes[$rank];
		}
		return WGApi::$classes["very_bad"];
	}

	public static function getWinColor($count)
	{
		foreach (WGApi::$cwins as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$colors[$rank];
		}
		return WGApi::$colors["unique"];
	}

	public static function getWinClass($count)
	{
		foreach (WGApi::$cwins as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$classes[$rank];
		}
		return WGApi::$classes["unique"];
	}

	public static function getREColor($count)
	{
		foreach (WGApi::$cre as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$colors[$rank];
		}
		return WGApi::$colors["unique"];
	}

	public static function getREClass($count)
	{
		foreach (WGApi::$cre as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$classes[$rank];
		}
		return WGApi::$classes["unique"];
	}

	public static function getWN8Class($count)
	{
		foreach (WGApi::$cwn8 as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$classes[$rank];
		}
		return WGApi::$classes["unique"];
	}

	public static function getBSColor($count)
	{
		foreach (WGApi::$cbs as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$colors[$rank];
		}
		return WGApi::$colors["unique"];
	}

	public static function getBSClass($count)
	{
		foreach (WGApi::$cbs as $key=>$rank)
		{
			if ($key > $count)
				return WGApi::$classes[$rank];
		}
		return WGApi::$classes["unique"];
	}

	function getDateColor($time)
	{
		if (empty($time))
			return '#f00';
		if ($time < time() - 3600*24*7)
			return '#f00';
		if ($time < time() - 3600*24*4)
			return '#D8D05F';
		return '#0f0';
	}

	function getDateClass($time)
	{
		if (empty($time))
			return WGApi::$classes["very_bad"];
		if ($time < time() - 3600*24*7)
			return WGApi::$classes["very_bad"];
		if ($time < time() - 3600*24*4)
			return WGApi::$classes["normal"];
		return WGApi::$classes["very_good"];
	}

	public static function declOfNum($number, $titles)
	{
			$cases = array (2, 0, 1, 1, 1, 2);
			return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
	}

	public static function getTanksColored($tanks_list)
	{
		$tanks_list = @json_decode($tanks_list, true);
		if (!is_array($tanks_list))
			return 'Неизвестно';

		$tanks = Tanks::model()->findAll();
		if (!$tanks)
			return 'Неизвестно';

		$artanks = array();
		foreach ($tanks as $value)
		{
			$artanks[$value['id']] = $value;
		}

        uasort($tanks_list, function ($a, $b) {
            if ($a['w']/($a['b'] ? $a['b'] : 1) == $b['w']/($b['b'] ? $b['b'] : 1)) {
                return 0;
            }
            return $a['w']/($a['b'] ? $a['b'] : 1) > $b['w']/($b['b'] ? $b['b'] : 1) ? -1 : 1;
        });

		$tanks_list10 = array();
		foreach ($tanks_list as $key=>$value)
		{
		    if (empty($artanks[$key])) {
		        continue;
            }
			if ($value['l'] == 10)
			{
				$img = '<span style="display: inline-block; width: 100px; height: 24px;"><img src="'.$artanks[$key]['image'].'"></span>';
				if ($artanks[$key]['type'] == 'SPG')
					$tanks_list10[] = $img.'<span style="display: inline-block; width: 200px; color: brown; line-height: 24px; vertical-align: top;">'.$artanks[$key]['name_i18n'].'</span><span style="display: inline-block; width: 100px; line-height: 24px; vertical-align: top;">'.self::declOfNum($value['b'], array('бой', 'боя', 'боев')).'</span><span style="display: inline-block; width: 70px; line-height: 24px; vertical-align: top; color: '.self::getWinColor(round($value['w']/($value['b'] ? $value['b'] : 1)*100)).'">'.round($value['w']/($value['b'] ? $value['b'] : 1)*100).'%</span><span style="display: inline-block; width: 90px; line-height: 24px; vertical-align: top;">'.(isset($value['d']) && $value['b'] ? round($value['d'] / $value['b']).' урон' : '---').'</span>';
				elseif ($artanks[$key]['type'] == 'AT-SPG')
					$tanks_list10[] = $img.'<span style="display: inline-block; width: 200px; color: #A35BBF; line-height: 24px; vertical-align: top;">'.$artanks[$key]['name_i18n'].'</span><span style="display: inline-block; width: 100px; line-height: 24px; vertical-align: top;">'.self::declOfNum($value['b'], array('бой', 'боя', 'боев')).'</span><span style="display: inline-block; width: 70px; line-height: 24px; vertical-align: top; color: '.self::getWinColor(round($value['w']/($value['b'] ? $value['b'] : 1)*100)).'">'.round($value['w']/($value['b'] ? $value['b'] : 1)*100).'%</span><span style="display: inline-block; width: 90px; line-height: 24px; vertical-align: top;">'.(isset($value['d']) && $value['b'] ? round($value['d'] / $value['b']).' урон' : '---').'</span>';
				elseif ($artanks[$key]['type'] == 'mediumTank')
					$tanks_list10[] = $img.'<span style="display: inline-block; width: 200px; color: #B7AE33; line-height: 24px; vertical-align: top;">'.$artanks[$key]['name_i18n'].'</span><span style="display: inline-block; width: 100px; line-height: 24px; vertical-align: top;">'.self::declOfNum($value['b'], array('бой', 'боя', 'боев')).'</span><span style="display: inline-block; width: 70px; line-height: 24px; vertical-align: top; color: '.self::getWinColor(round($value['w']/($value['b'] ? $value['b'] : 1)*100)).'">'.round($value['w']/($value['b'] ? $value['b'] : 1)*100).'%</span><span style="display: inline-block; width: 90px; line-height: 24px; vertical-align: top;">'.(isset($value['d']) && $value['b'] ? round($value['d'] / $value['b']).' урон' : '---').'</span>';
				else
					$tanks_list10[] = $img.'<span style="display: inline-block; width: 200px; color: #4A9E59; line-height: 24px; vertical-align: top;">'.$artanks[$key]['name_i18n'].'</span><span style="display: inline-block; width: 100px; line-height: 24px; vertical-align: top;">'.self::declOfNum($value['b'], array('бой', 'боя', 'боев')).'</span><span style="display: inline-block; width: 70px; line-height: 24px; vertical-align: top; color: '.self::getWinColor(round($value['w']/($value['b'] ? $value['b'] : 1)*100)).'">'.round($value['w']/($value['b'] ? $value['b'] : 1)*100).'%</span><span style="display: inline-block; width: 90px; line-height: 24px; vertical-align: top;">'.(isset($value['d']) && $value['b'] ? round($value['d'] / $value['b']).' урон' : '---').'</span>';
			}
		}
		$result = $tanks_list10 ? implode("<br>", $tanks_list10) : '---';
		return $result;
	}

	public function getOnline($online)
	{
		$online = @unserialize($online);
		if (!$online)
			return 'Статистика пока недоступна';
		Yii::app()->clientScript->registerScriptFile('//www.google.com/jsapi');
		$data = array(array('Дата', 'Случайные бои', 'Ротные бои', 'Клановые бои', 'Командные бои', 'Укреп. оборона', 'Укреп. вылазки'));
		for ($i = -25; $i <= 0; $i++)
		{
			$key = date('Y.m.d', time() + 3600*24*$i);
			if (isset($online[$key]))
				$data[] = array(date('d.m.Y', time() + 3600*24*$i), $online[$key]['all']-$online[$key]['company']-$online[$key]['clan'], $online[$key]['company'], $online[$key]['clan'], isset($online[$key]['team']) ? $online[$key]['team'] : 0, isset($online[$key]['stronghold_defense']) ? $online[$key]['stronghold_defense'] : 0, isset($online[$key]['stronghold_skirmish']) ? $online[$key]['stronghold_skirmish'] : 0);
			else
				$data[] = array(date('d.m.Y', time() + 3600*24*$i), 0, 0, 0, 0, 0, 0);
		}
		$result = '
		<div id="online" style="height: 350px;"></div>
		<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable('.json_encode($data).');

        var options = {
          hAxis: {textStyle: {color: "#f0f0f0", fontSize: 11}},
          vAxis: {textStyle: {color: "#f0f0f0", fontSize: 11}, gridlines: {color: "#f0f0f0", count: 10}},
					backgroundColor: "#171718",
					chartArea: {left: "30", top: "10", width: "820"},
					legend: {position: "bottom", textStyle: {color: "#f0f0f0"}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById("online"));
        chart.draw(data, options);
      }
		</script>';
		return $result;
	}

	public function checkClan($clan, $url)
	{
        $result = file_get_contents('https://ru.wargaming.net/clans/wot/'.$clan->id.'/api/claninfo/');

        if (empty($result))
            return 1;
        if (stripos($result, '"'.$clan->abbreviation.'"') === false)
            return 2;
        if (stripos($result, $url) !== false)
            return 3;

        $result = file_get_contents('https://ru.wargaming.net/clans/wot/'.$clan->id.'/');

		if (empty($result))
			return 1;
		if (stripos($result, $url) !== false)
			return 3;

        return 0;
	}
}