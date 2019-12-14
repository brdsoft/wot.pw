<?php

class replayParser {
	
	public $error = false;
	public $file;
	public static $tanks = [];
	public $achievements;
	public $battleTypes = array(
		'domination'=>'Встречный бой',
		'ctf'=>'Стандартный бой',
		'assault'=>'Штурм',
	);
	
	public function errorCatcher($errno, $errstr)
	{
		//$this->error = false;
	}
	
	public function __construct($file)
	{
		set_error_handler(array($this, 'errorCatcher'));
		$this->file = $file;
		$this->achievements = $this->fillAchievements();
		if (!self::$tanks)
		{
			$artanks = Tanks::model()->findAll();
			foreach ($artanks as $key=>$value)
			{
				if ($value['name'] == '#france_vehicles:Bat_Chatillon155')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#france_vehicles:Bat_Chatillon155_58';
				}
				if ($value['name'] == '#usa_vehicles:T37')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#usa_vehicles:A94_T37';
				}
				if ($value['name'] == '#usa_vehicles:_M44')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#usa_vehicles:M44';
				}
				if ($value['name'] == '#usa_vehicles:T110')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#usa_vehicles:A69_T110E5';
				}
				if ($value['name'] == '#usa_vehicles:T110E4')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#usa_vehicles:A83_T110E4';
				}
				if ($value['name'] == '#france_vehicles:AMX_50_68t')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#france_vehicles:F10_AMX_50B';
				}
				if ($value['name'] == '#ussr_vehicles:T-34')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#ussr_vehicles:R04_T-34';
				}
				if ($value['name'] == '#ussr_vehicles:R53_Object_704')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#ussr_vehicles:Object_704';
				}
				if ($value['name'] == '#ussr_vehicles:IS-6')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#ussr_vehicles:Object252';
				}
				if ($value['name'] == '#ussr_vehicles:SU-85')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#ussr_vehicles:R02_SU-85';
				}
				if ($value['name'] == '#ussr_vehicles:IS-3')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#ussr_vehicles:R19_IS-3';
				}
				if ($value['name'] == '#ussr_vehicles:T62A')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#ussr_vehicles:R87_T62A';
				}
				if ($value['name'] == '#germany_vehicles:Pro_Ag_A')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#germany_vehicles:G91_Pro_Ag_A';
				}
				if ($value['name'] == '#germany_vehicles:Leopard1')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#germany_vehicles:G89_Leopard1';
				}
				if ($value['name'] == '#germany_vehicles:JagdTiger')
				{
					$name = explode(':', $value['name']);
					self::$tanks[$value['nation'].':'.$name[1]] = $value;
					$value['name'] = '#germany_vehicles:G44_JagdTiger';
				}
				$name = explode(':', $value['name']);
				self::$tanks[$value['nation'].':'.$name[1]] = $value;
			}
		}
	}

	public function parse()
	{
		$handle = @fopen($this->file, 'rb');
		if (!$handle)
			return false;
		
		@fseek($handle, 4);
		$contents = @unpack("I", @fread($handle, 4));
		if (empty($contents[1]) || $contents[1] < 2 || $contents[1] > 4)
			return false;
		
		$data = [];
		$position = 8;
		for ($i=0;$i<$contents[1];$i++)
		{
			@fseek($handle, $position);
			$size = @unpack("I", @fread($handle, 4));
			if (empty($size[1]))
				return false;
			$position += 4;
			@fseek($handle, $position);
			$data[] = @fread($handle, $size[1]);
			$position += $size[1];
		}
	
		if (empty($data[0]) || empty($data[1]))
			return false;

		$data[0] = @json_decode($data[0], true);
		if (!$data[0])
			return false;
		$data[0] = $this->htmlspecialcharsarray($data[0]);
		
		$data[1] = @json_decode($data[1], true);
		if (!$data[1])
			return false;
		$data[1] = $this->htmlspecialcharsarray($data[1]);
		
		if ($this->error)
			return false;
		
		if (version_compare(implode('.', explode(', ', $data[0]['clientVersionFromExe'])), '0.9.8.0', '>='))
		{
			$data[1][0]['personal'] = array_shift($data[1][0]['personal']);
			$d = [];
			foreach ($data[1][0]['personal']['details'] as $key=>$value)
			{
				$k = preg_replace('=^\((\d+).+=', '$1', $key);
				$d[$k] = $value;
			}
			$data[1][0]['personal']['details'] = $d;
		}
		// echo '<pre>';
		// print_r($data[0]['clientVersionFromExe']);
		// exit();
		$data['common'] = array();
		$data['common']['isPremium'] = $data[1][0]['personal']['isPremium'];
		$data['common']['battleType'] = $this->battleTypes[$data[0]['gameplayID']];
		$data['common']['leftTeam'] = $data[1][0]['players'][$data[0]['playerID']]['team'];
		$data['common']['rightTeam'] = $data['common']['leftTeam'] == 1 ? 2 : 1;
		$data['common']['battleResult'] = $data[1][0]['common']['winnerTeam'] == 0 ? -1 : ($data[1][0]['common']['winnerTeam'] == $data['common']['leftTeam'] ? 1 : 0);
		
		$data['common']['leftAchievements'] = array();
		$data['common']['rightAchievements'] = array();
		if (isset($data[1][0]['personal']['dossierPopUps']) && is_array($data[1][0]['personal']['dossierPopUps']) && is_array($data[1][0]['personal']['achievements']))
		{
			$achievements = array();
			$arAchievements = Achievements::model()->findAll(array('order'=>"`order`"));
			foreach($arAchievements as $key=>$value)
			{
				$achievements[$value->name] = $value;
			}
			
			//Добавить мастера если нету
			if ($data[1][0]['personal']['markOfMastery'] > 0)
			{
				$find = false;
				foreach($data[1][0]['personal']['dossierPopUps'] as $value)
				{
					if ($value[0] == 79)
						$find = true;
				}
				if (!$find)
					$data[1][0]['personal']['dossierPopUps'][] = array(79, $data[1][0]['personal']['markOfMastery']);
			}
			
			//Собственные медали
			foreach ($achievements as $achievement)
			{
				$achievements_exists = array_search($achievement->name, $this->achievements);
				if ($achievements_exists === false)
					continue;
				foreach($data[1][0]['personal']['dossierPopUps'] as $value)
				{
					if ($value[0] == $achievements_exists)
					{
						if (!empty($achievement->options))
						{
							$achievement->options = json_decode($achievement->options, 1);
							if (!empty($achievement->options[$value[1]-1]['name_i18n'])) $achievement->name_i18n = $achievement->options[$value[1]-1]['name_i18n'];
							if (!empty($achievement->options[$value[1]-1]['image'])) $achievement->image = $achievement->options[$value[1]-1]['image'];
							if (!empty($achievement->options[$value[1]-1]['image_big'])) $achievement->image_big = $achievement->options[$value[1]-1]['image_big'];
							if (!empty($achievement->options[$value[1]-1]['description'])) $achievement->description = $achievement->options[$value[1]-1]['description'];
						}
						if (in_array($achievements_exists, $data[1][0]['personal']['achievements']))
							$data['common']['rightAchievements'][] = $achievement;
						else
							$data['common']['leftAchievements'][] = $achievement;
					}
				}
			}
		}
		
		if ($this->error)
			return false;
		
		//Определить порядковый номер игрока
		//Определить медали каждого игрока
		$data['common']['cId'] = false;
		foreach ($data[1][0]['vehicles'] as $key=>$value)//Проверить
		{
			if (version_compare(implode('.', explode(', ', $data[0]['clientVersionFromExe'])), '0.9.8.0', '>='))
			{
				$value = array_shift($value);
				$data[1][0]['vehicles'][$key] = array_shift($data[1][0]['vehicles'][$key]);
			}
			if ($value['accountDBID'] == $data[0]['playerID'])
				$data['common']['cId'] = $key;
			$data[1][0]['vehicles'][$key]['achievementsText'] = array();
			foreach ($value['achievements'] as $a)
			{
				if (isset($this->achievements[$a]) && isset($achievements[$this->achievements[$a]]))
					$data[1][0]['vehicles'][$key]['achievementsText'][] = $achievements[$this->achievements[$a]]->name_i18n;
			}
		}
		if (!$data['common']['cId'])
			return false;
		
		if ($this->error)
			return false;
		
		//Определить взводы
		
		uasort($data[1][0]['players'], function($a, $b){
			return strcmp($a["prebattleID"], $b["prebattleID"]);
		});
		$vzvod1 = 0;
		$vzvod2 = 0;
		$counter1 = 0;
		$counter2 = 0;
		foreach ($data[1][0]['players'] as $key=>$value)//Проверить
		{
			$data[1][0]['players'][$key]['vzvod'] = 0;
			
			if ($value['team'] == 1 && $value['prebattleID'] > $counter1)
			{
				$counter1 = $value['prebattleID'];
				$vzvod1++;
			}
			if ($value['team'] == 1 && $value['prebattleID'] > 0)
				$data[1][0]['players'][$key]['vzvod'] = $vzvod1;
			if ($value['team'] == 2 && $value['prebattleID'] > $counter2)
			{
				$counter2 = $value['prebattleID'];
				$vzvod2++;
			}
			if ($value['team'] == 2 && $value['prebattleID'] > 0)
				$data[1][0]['players'][$key]['vzvod'] = $vzvod2;
		}
		
		if ($this->error)
			return false;
		
		return $data;
	}

	private function htmlspecialcharsarray($array)
	{
		if (is_array($array))
		{
			$result = array();
			foreach ($array as $key => $value)
			{
				$result[$key] = $this->htmlspecialcharsarray($value);
			}
		}
		elseif (is_object($array))
		{
			$result = new stdClass();
			foreach ($array as $key => $value)
			{
				$result -> $key = $this->htmlspecialcharsarray($value);
			}
		}
		else
			$result = htmlspecialchars($array);
		return $result;
	}
	
	private function fillAchievements()
	{
		$achievements = array();
		$achievements[1] = 'xp';
		$achievements[2] = 'maxXP';
		$achievements[3] = 'battlesCount';
		$achievements[4] = 'wins';
		$achievements[5] = 'losses';
		$achievements[6] = 'survivedBattles';
		$achievements[7] = 'lastBattleTime';
		$achievements[8] = 'battleLifeTime';
		$achievements[9] = 'winAndSurvived';
		$achievements[10] = 'battleHeroes';
		$achievements[11] = 'frags';
		$achievements[12] = 'maxFrags';
		$achievements[13] = 'frags8p';
		$achievements[14] = 'fragsBeast';
		$achievements[15] = 'shots';
		$achievements[16] = 'directHits';
		$achievements[17] = 'spotted';
		$achievements[18] = 'damageDealt';
		$achievements[19] = 'damageReceived';
		$achievements[20] = 'treesCut';
		$achievements[21] = 'capturePoints';
		$achievements[22] = 'droppedCapturePoints';
		$achievements[23] = 'sniperSeries';
		$achievements[24] = 'maxSniperSeries';
		$achievements[25] = 'invincibleSeries';
		$achievements[26] = 'maxInvincibleSeries';
		$achievements[27] = 'diehardSeries';
		$achievements[28] = 'maxDiehardSeries';
		$achievements[29] = 'killingSeries';
		$achievements[30] = 'maxKillingSeries';
		$achievements[31] = 'piercingSeries';
		$achievements[32] = 'maxPiercingSeries';
		$achievements[34] = 'warrior';
		$achievements[35] = 'invader';
		$achievements[36] = 'sniper';
		$achievements[37] = 'defender';
		$achievements[38] = 'steelwall';
		$achievements[39] = 'supporter';
		$achievements[40] = 'scout';
		$achievements[41] = 'medalKay';
		$achievements[42] = 'medalCarius';
		$achievements[43] = 'medalKnispel';
		$achievements[44] = 'medalPoppel';
		$achievements[45] = 'medalAbrams';
		$achievements[46] = 'medalLeClerc';
		$achievements[47] = 'medalLavrinenko';
		$achievements[48] = 'medalEkins';
		$achievements[49] = 'medalWittmann';
		$achievements[50] = 'medalOrlik';
		$achievements[51] = 'medalOskin';
		$achievements[52] = 'medalHalonen';
		$achievements[53] = 'medalBurda';
		$achievements[54] = 'medalBillotte';
		$achievements[55] = 'medalKolobanov';
		$achievements[56] = 'medalFadin';
		$achievements[57] = 'tankExpert';
		$achievements[58] = 'titleSniper';
		$achievements[59] = 'invincible';
		$achievements[60] = 'diehard';
		$achievements[61] = 'raider';
		$achievements[62] = 'handOfDeath';
		$achievements[63] = 'armorPiercer';
		$achievements[64] = 'kamikaze';
		$achievements[65] = 'lumberjack';
		$achievements[66] = 'beasthunter';
		$achievements[67] = 'mousebane';
		$achievements[68] = 'creationTime';
		$achievements[69] = 'maxXPVehicle';
		$achievements[70] = 'maxFragsVehicle';
		$achievements[72] = 'evileye';
		$achievements[73] = 'medalRadleyWalters';
		$achievements[74] = 'medalLafayettePool';
		$achievements[75] = 'medalBrunoPietro';
		$achievements[76] = 'medalTarczay';
		$achievements[77] = 'medalPascucci';
		$achievements[78] = 'medalDumitru';
		$achievements[79] = 'markOfMastery';
		$achievements[80] = 'xp';
		$achievements[81] = 'battlesCount';
		$achievements[82] = 'wins';
		$achievements[83] = 'losses';
		$achievements[84] = 'survivedBattles';
		$achievements[85] = 'frags';
		$achievements[86] = 'shots';
		$achievements[87] = 'directHits';
		$achievements[88] = 'spotted';
		$achievements[89] = 'damageDealt';
		$achievements[90] = 'damageReceived';
		$achievements[91] = 'capturePoints';
		$achievements[92] = 'droppedCapturePoints';
		$achievements[93] = 'xp';
		$achievements[94] = 'battlesCount';
		$achievements[95] = 'wins';
		$achievements[96] = 'losses';
		$achievements[97] = 'survivedBattles';
		$achievements[98] = 'frags';
		$achievements[99] = 'shots';
		$achievements[100] = 'directHits';
		$achievements[101] = 'spotted';
		$achievements[102] = 'damageDealt';
		$achievements[103] = 'damageReceived';
		$achievements[104] = 'capturePoints';
		$achievements[105] = 'droppedCapturePoints';
		$achievements[106] = 'medalLehvaslaiho';
		$achievements[107] = 'medalNikolas';
		$achievements[108] = 'fragsSinai';
		$achievements[109] = 'sinai';
		$achievements[110] = 'heroesOfRassenay';
		$achievements[111] = 'mechanicEngineer';
		$achievements[112] = 'tankExpert0';
		$achievements[113] = 'tankExpert1';
		$achievements[114] = 'tankExpert2';
		$achievements[115] = 'tankExpert3';
		$achievements[116] = 'tankExpert4';
		$achievements[117] = 'tankExpert5';
		$achievements[118] = 'tankExpert6';
		$achievements[119] = 'tankExpert7';
		$achievements[120] = 'tankExpert8';
		$achievements[121] = 'tankExpert9';
		$achievements[122] = 'tankExpert10';
		$achievements[123] = 'tankExpert11';
		$achievements[124] = 'tankExpert12';
		$achievements[125] = 'tankExpert13';
		$achievements[126] = 'tankExpert14';
		$achievements[127] = 'mechanicEngineer0';
		$achievements[128] = 'mechanicEngineer1';
		$achievements[129] = 'mechanicEngineer2';
		$achievements[130] = 'mechanicEngineer3';
		$achievements[131] = 'mechanicEngineer4';
		$achievements[132] = 'mechanicEngineer5';
		$achievements[133] = 'mechanicEngineer6';
		$achievements[134] = 'mechanicEngineer7';
		$achievements[135] = 'mechanicEngineer8';
		$achievements[136] = 'mechanicEngineer9';
		$achievements[137] = 'mechanicEngineer10';
		$achievements[138] = 'mechanicEngineer11';
		$achievements[139] = 'mechanicEngineer12';
		$achievements[140] = 'mechanicEngineer13';
		$achievements[141] = 'mechanicEngineer14';
		$achievements[142] = 'gold';
		$achievements[143] = 'medalBrothersInArms';
		$achievements[144] = 'medalCrucialContribution';
		$achievements[145] = 'medalDeLanglade';
		$achievements[146] = 'medalTamadaYoshio';
		$achievements[147] = 'bombardier';
		$achievements[148] = 'huntsman';
		$achievements[149] = 'alaric';
		$achievements[150] = 'sturdy';
		$achievements[151] = 'ironMan';
		$achievements[152] = 'luckyDevil';
		$achievements[153] = 'fragsPatton';
		$achievements[154] = 'pattonValley';
		$achievements[155] = 'xpBefore8_8';
		$achievements[156] = 'battlesCountBefore8_8';
		$achievements[157] = 'originalXP';
		$achievements[158] = 'damageAssistedTrack';
		$achievements[159] = 'damageAssistedRadio';
		$achievements[160] = 'mileage';
		$achievements[161] = 'directHitsReceived';
		$achievements[162] = 'noDamageDirectHitsReceived';
		$achievements[163] = 'piercingsReceived';
		$achievements[164] = 'explosionHits';
		$achievements[165] = 'piercings';
		$achievements[166] = 'explosionHitsReceived';
		$achievements[167] = 'mechanicEngineerStrg';
		$achievements[168] = 'tankExpertStrg';
		$achievements[169] = 'originalXP';
		$achievements[170] = 'damageAssistedTrack';
		$achievements[171] = 'damageAssistedRadio';
		$achievements[173] = 'directHitsReceived';
		$achievements[174] = 'noDamageDirectHitsReceived';
		$achievements[175] = 'piercingsReceived';
		$achievements[176] = 'explosionHitsReceived';
		$achievements[177] = 'explosionHits';
		$achievements[178] = 'piercings';
		$achievements[179] = 'originalXP';
		$achievements[180] = 'damageAssistedTrack';
		$achievements[181] = 'damageAssistedRadio';
		$achievements[183] = 'directHitsReceived';
		$achievements[184] = 'noDamageDirectHitsReceived';
		$achievements[185] = 'piercingsReceived';
		$achievements[186] = 'explosionHitsReceived';
		$achievements[187] = 'explosionHits';
		$achievements[188] = 'piercings';
		$achievements[189] = 'xp';
		$achievements[190] = 'battlesCount';
		$achievements[191] = 'wins';
		$achievements[192] = 'losses';
		$achievements[193] = 'survivedBattles';
		$achievements[194] = 'frags';
		$achievements[195] = 'shots';
		$achievements[196] = 'directHits';
		$achievements[197] = 'spotted';
		$achievements[198] = 'damageDealt';
		$achievements[199] = 'damageReceived';
		$achievements[200] = 'capturePoints';
		$achievements[201] = 'droppedCapturePoints';
		$achievements[202] = 'originalXP';
		$achievements[203] = 'damageAssistedTrack';
		$achievements[204] = 'damageAssistedRadio';
		$achievements[206] = 'directHitsReceived';
		$achievements[207] = 'noDamageDirectHitsReceived';
		$achievements[208] = 'piercingsReceived';
		$achievements[209] = 'explosionHitsReceived';
		$achievements[210] = 'explosionHits';
		$achievements[211] = 'piercings';
		$achievements[212] = 'xpBefore8_9';
		$achievements[213] = 'battlesCountBefore8_9';
		$achievements[214] = 'xpBefore8_9';
		$achievements[215] = 'battlesCountBefore8_9';
		$achievements[216] = 'winAndSurvived';
		$achievements[217] = 'frags8p';
		$achievements[218] = 'maxDamage';
		$achievements[219] = 'maxDamageVehicle';
		$achievements[220] = 'maxXP';
		$achievements[221] = 'maxXPVehicle';
		$achievements[222] = 'maxFrags';
		$achievements[223] = 'maxFragsVehicle';
		$achievements[224] = 'maxDamage';
		$achievements[225] = 'maxDamageVehicle';
		$achievements[226] = 'battlesCount';
		$achievements[227] = 'sniper2';
		$achievements[228] = 'mainGun';
		$achievements[229] = 'wolfAmongSheep';
		$achievements[230] = 'wolfAmongSheepMedal';
		$achievements[231] = 'geniusForWar';
		$achievements[232] = 'geniusForWarMedal';
		$achievements[233] = 'kingOfTheHill';
		$achievements[234] = 'tacticalBreakthroughSeries';
		$achievements[235] = 'maxTacticalBreakthroughSeries';
		$achievements[236] = 'armoredFist';
		$achievements[237] = 'tacticalBreakthrough';
		$achievements[238] = 'potentialDamageReceived';
		$achievements[239] = 'damageBlockedByArmor';
		$achievements[240] = 'potentialDamageReceived';
		$achievements[241] = 'damageBlockedByArmor';
		$achievements[242] = 'potentialDamageReceived';
		$achievements[243] = 'damageBlockedByArmor';
		$achievements[244] = 'potentialDamageReceived';
		$achievements[245] = 'damageBlockedByArmor';
		$achievements[246] = 'battlesCountBefore9_0';
		$achievements[247] = 'battlesCountBefore9_0';
		$achievements[248] = 'battlesCountBefore9_0';
		$achievements[249] = 'battlesCountBefore9_0';
		$achievements[250] = 'xp';
		$achievements[251] = 'battlesCount';
		$achievements[252] = 'wins';
		$achievements[253] = 'winAndSurvived';
		$achievements[254] = 'losses';
		$achievements[255] = 'survivedBattles';
		$achievements[256] = 'frags';
		$achievements[257] = 'frags8p';
		$achievements[258] = 'shots';
		$achievements[259] = 'directHits';
		$achievements[260] = 'spotted';
		$achievements[261] = 'damageDealt';
		$achievements[262] = 'damageReceived';
		$achievements[263] = 'capturePoints';
		$achievements[264] = 'droppedCapturePoints';
		$achievements[265] = 'originalXP';
		$achievements[266] = 'damageAssistedTrack';
		$achievements[267] = 'damageAssistedRadio';
		$achievements[268] = 'directHitsReceived';
		$achievements[269] = 'noDamageDirectHitsReceived';
		$achievements[270] = 'piercingsReceived';
		$achievements[271] = 'explosionHitsReceived';
		$achievements[272] = 'explosionHits';
		$achievements[273] = 'piercings';
		$achievements[274] = 'potentialDamageReceived';
		$achievements[275] = 'damageBlockedByArmor';
		$achievements[276] = 'maxXP';
		$achievements[277] = 'maxXPVehicle';
		$achievements[278] = 'maxFrags';
		$achievements[279] = 'maxFragsVehicle';
		$achievements[280] = 'maxDamage';
		$achievements[281] = 'maxDamageVehicle';
		$achievements[282] = 'guardsman';
		$achievements[283] = 'makerOfHistory';
		$achievements[284] = 'bothSidesWins';
		$achievements[285] = 'weakVehiclesWins';
		$achievements[286] = 'godOfWar';
		$achievements[287] = 'fightingReconnaissance';
		$achievements[288] = 'fightingReconnaissanceMedal';
		$achievements[289] = 'willToWinSpirit';
		$achievements[290] = 'crucialShot';
		$achievements[291] = 'crucialShotMedal';
		$achievements[292] = 'forTacticalOperations';
		$achievements[293] = 'battleCitizen';
		$achievements[294] = 'movingAvgDamage';
		$achievements[295] = 'marksOnGun';
		$achievements[296] = 'medalMonolith';
		$achievements[297] = 'medalAntiSpgFire';
		$achievements[298] = 'medalGore';
		$achievements[299] = 'medalCoolBlood';
		$achievements[300] = 'medalStark';
		$achievements[301] = 'histBattle1_battlefield';
		$achievements[302] = 'histBattle1_historyLessons';
		$achievements[303] = 'histBattle2_battlefield';
		$achievements[304] = 'histBattle2_historyLessons';
		$achievements[305] = 'histBattle3_battlefield';
		$achievements[306] = 'histBattle3_historyLessons';
		$achievements[307] = 'histBattle4_battlefield';
		$achievements[308] = 'histBattle4_historyLessons';
		$achievements[309] = 'xp';
		$achievements[310] = 'battlesCount';
		$achievements[311] = 'wins';
		$achievements[312] = 'winAndSurvived';
		$achievements[313] = 'losses';
		$achievements[314] = 'survivedBattles';
		$achievements[315] = 'frags';
		$achievements[316] = 'frags8p';
		$achievements[317] = 'shots';
		$achievements[318] = 'directHits';
		$achievements[319] = 'spotted';
		$achievements[320] = 'damageDealt';
		$achievements[321] = 'damageReceived';
		$achievements[322] = 'capturePoints';
		$achievements[323] = 'droppedCapturePoints';
		$achievements[324] = 'originalXP';
		$achievements[325] = 'damageAssistedTrack';
		$achievements[326] = 'damageAssistedRadio';
		$achievements[327] = 'directHitsReceived';
		$achievements[328] = 'noDamageDirectHitsReceived';
		$achievements[329] = 'piercingsReceived';
		$achievements[330] = 'explosionHitsReceived';
		$achievements[331] = 'explosionHits';
		$achievements[332] = 'piercings';
		$achievements[333] = 'potentialDamageReceived';
		$achievements[334] = 'damageBlockedByArmor';
		$achievements[335] = 'maxXP';
		$achievements[336] = 'maxXPVehicle';
		$achievements[337] = 'maxFrags';
		$achievements[338] = 'maxFragsVehicle';
		$achievements[339] = 'maxDamage';
		$achievements[340] = 'maxDamageVehicle';
		$achievements[341] = 'xp';
		$achievements[342] = 'battlesCount';
		$achievements[343] = 'wins';
		$achievements[344] = 'winAndSurvived';
		$achievements[345] = 'losses';
		$achievements[346] = 'survivedBattles';
		$achievements[347] = 'frags';
		$achievements[348] = 'frags8p';
		$achievements[349] = 'shots';
		$achievements[350] = 'directHits';
		$achievements[351] = 'spotted';
		$achievements[352] = 'damageDealt';
		$achievements[353] = 'damageReceived';
		$achievements[354] = 'capturePoints';
		$achievements[355] = 'droppedCapturePoints';
		$achievements[356] = 'originalXP';
		$achievements[357] = 'damageAssistedTrack';
		$achievements[358] = 'damageAssistedRadio';
		$achievements[359] = 'directHitsReceived';
		$achievements[360] = 'noDamageDirectHitsReceived';
		$achievements[361] = 'piercingsReceived';
		$achievements[362] = 'explosionHitsReceived';
		$achievements[363] = 'explosionHits';
		$achievements[364] = 'piercings';
		$achievements[365] = 'potentialDamageReceived';
		$achievements[366] = 'damageBlockedByArmor';
		$achievements[367] = 'maxXP';
		$achievements[368] = 'maxXPVehicle';
		$achievements[369] = 'maxFrags';
		$achievements[370] = 'maxFragsVehicle';
		$achievements[371] = 'maxDamage';
		$achievements[372] = 'maxDamageVehicle';
		$achievements[373] = 'xp';
		$achievements[374] = 'battlesCount';
		$achievements[375] = 'wins';
		$achievements[376] = 'winAndSurvived';
		$achievements[377] = 'losses';
		$achievements[378] = 'survivedBattles';
		$achievements[379] = 'frags';
		$achievements[380] = 'frags8p';
		$achievements[381] = 'shots';
		$achievements[382] = 'directHits';
		$achievements[383] = 'spotted';
		$achievements[384] = 'damageDealt';
		$achievements[385] = 'damageReceived';
		$achievements[386] = 'capturePoints';
		$achievements[387] = 'droppedCapturePoints';
		$achievements[388] = 'originalXP';
		$achievements[389] = 'damageAssistedTrack';
		$achievements[390] = 'damageAssistedRadio';
		$achievements[391] = 'directHitsReceived';
		$achievements[392] = 'noDamageDirectHitsReceived';
		$achievements[393] = 'piercingsReceived';
		$achievements[394] = 'explosionHitsReceived';
		$achievements[395] = 'explosionHits';
		$achievements[396] = 'piercings';
		$achievements[397] = 'potentialDamageReceived';
		$achievements[398] = 'damageBlockedByArmor';
		$achievements[399] = 'xp';
		$achievements[400] = 'battlesCount';
		$achievements[401] = 'wins';
		$achievements[402] = 'winAndSurvived';
		$achievements[403] = 'losses';
		$achievements[404] = 'survivedBattles';
		$achievements[405] = 'frags';
		$achievements[406] = 'frags8p';
		$achievements[407] = 'shots';
		$achievements[408] = 'directHits';
		$achievements[409] = 'spotted';
		$achievements[410] = 'damageDealt';
		$achievements[411] = 'damageReceived';
		$achievements[412] = 'capturePoints';
		$achievements[413] = 'droppedCapturePoints';
		$achievements[414] = 'originalXP';
		$achievements[415] = 'damageAssistedTrack';
		$achievements[416] = 'damageAssistedRadio';
		$achievements[417] = 'directHitsReceived';
		$achievements[418] = 'noDamageDirectHitsReceived';
		$achievements[419] = 'piercingsReceived';
		$achievements[420] = 'explosionHitsReceived';
		$achievements[421] = 'explosionHits';
		$achievements[422] = 'piercings';
		$achievements[423] = 'potentialDamageReceived';
		$achievements[424] = 'damageBlockedByArmor';
		$achievements[425] = 'fortResourceInSorties';
		$achievements[426] = 'maxFortResourceInSorties';
		$achievements[427] = 'fortResourceInBattles';
		$achievements[428] = 'maxFortResourceInBattles';
		$achievements[429] = 'defenceHours';
		$achievements[430] = 'successfulDefenceHours';
		$achievements[431] = 'attackNumber';
		$achievements[432] = 'enemyBasePlunderNumber';
		$achievements[433] = 'enemyBasePlunderNumberInAttack';
		$achievements[434] = 'fortResourceInSorties';
		$achievements[435] = 'maxFortResourceInSorties';
		$achievements[436] = 'fortResourceInBattles';
		$achievements[437] = 'maxFortResourceInBattles';
		$achievements[438] = 'defenceHours';
		$achievements[439] = 'successfulDefenceHours';
		$achievements[440] = 'attackNumber';
		$achievements[441] = 'enemyBasePlunderNumber';
		$achievements[442] = 'enemyBasePlunderNumberInAttack';
		$achievements[443] = 'production';
		$achievements[444] = 'middleBattlesCount';
		$achievements[445] = 'championBattlesCount';
		$achievements[446] = 'absoluteBattlesCount';
		$achievements[447] = 'fortResourceInMiddle';
		$achievements[448] = 'fortResourceInChampion';
		$achievements[449] = 'fortResourceInAbsolute';
		$achievements[450] = 'battlesHours';
		$achievements[451] = 'attackCount';
		$achievements[452] = 'defenceCount';
		$achievements[453] = 'enemyBaseCaptureCount';
		$achievements[454] = 'ownBaseLossCount';
		$achievements[455] = 'ownBaseLossCountInDefence';
		$achievements[456] = 'enemyBaseCaptureCountInAttack';
		$achievements[457] = 'maxXP';
		$achievements[458] = 'maxXPVehicle';
		$achievements[459] = 'maxFrags';
		$achievements[460] = 'maxFragsVehicle';
		$achievements[461] = 'maxDamage';
		$achievements[462] = 'maxDamageVehicle';
		$achievements[463] = 'maxXP';
		$achievements[464] = 'maxXPVehicle';
		$achievements[465] = 'maxFrags';
		$achievements[466] = 'maxFragsVehicle';
		$achievements[467] = 'maxDamage';
		$achievements[468] = 'maxDamageVehicle';
		$achievements[469] = 'promisingFighter';
		$achievements[470] = 'promisingFighterMedal';
		$achievements[471] = 'heavyFire';
		$achievements[472] = 'heavyFireMedal';
		$achievements[473] = 'ranger';
		$achievements[474] = 'rangerMedal';
		$achievements[475] = 'fireAndSteel';
		$achievements[476] = 'fireAndSteelMedal';
		$achievements[477] = 'pyromaniac';
		$achievements[478] = 'pyromaniacMedal';
		$achievements[479] = 'noMansLand';
		$achievements[480] = 'damageRating';
		$achievements[481] = 'citadel';
		$achievements[482] = 'conqueror';
		$achievements[483] = 'fireAndSword';
		$achievements[484] = 'crusher';
		$achievements[485] = 'counterblow';
		$achievements[486] = 'kampfer';
		$achievements[487] = 'soldierOfFortune';
		$achievements[488] = 'WFC2014WinSeries';
		$achievements[489] = 'maxWFC2014WinSeries';
		$achievements[490] = 'WFC2014';
		$achievements[491] = 'histBattle5_battlefield';
		$achievements[492] = 'histBattle5_historyLessons';
		$achievements[493] = 'histBattle6_battlefield';
		$achievements[494] = 'histBattle6_historyLessons';
		$achievements[495] = 'guerrilla';
		$achievements[496] = 'guerrillaMedal';
		$achievements[497] = 'infiltrator';
		$achievements[498] = 'infiltratorMedal';
		$achievements[499] = 'sentinel';
		$achievements[500] = 'sentinelMedal';
		$achievements[501] = 'prematureDetonation';
		$achievements[502] = 'prematureDetonationMedal';
		$achievements[503] = 'bruteForce';
		$achievements[504] = 'bruteForceMedal';
		$achievements[505] = 'awardCount';
		$achievements[506] = 'battleTested';
		$achievements[507] = 'medalRotmistrov';
		$achievements[508] = 'combatCount';
		$achievements[509] = 'combatWins';
		$achievements[510] = 'successDefenceCount';
		$achievements[511] = 'successAttackCount';
		$achievements[512] = 'captureEnemyBuildingTotalCount';
		$achievements[513] = 'lossOwnBuildingTotalCount';
		$achievements[514] = 'resourceCaptureCount';
		$achievements[515] = 'resourceLossCount';
		$achievements[516] = 'reservedInt32';
		$achievements[517] = 'impenetrable';
		$achievements[518] = 'reliableComradeSeries';
		$achievements[519] = 'reliableComrade';
		$achievements[520] = 'maxAimerSeries';
		$achievements[521] = 'shootToKill';
		$achievements[522] = 'fighter';
		$achievements[523] = 'duelist';
		$achievements[524] = 'demolition';
		$achievements[525] = 'arsonist';
		$achievements[526] = 'bonecrusher';
		$achievements[527] = 'charmed';
		$achievements[528] = 'even';
		$achievements[529] = 'reservedInAccountSingleAchievements';
		$achievements[530] = 'wins';
		$achievements[531] = 'capturedBasesInAttack';
		$achievements[532] = 'capturedBasesInDefence';
		$achievements[533] = 'deathTrack';
		$achievements[534] = 'deathTrackWinSeries';
		$achievements[535] = 'maxDeathTrackWinSeries';
		$achievements[536] = 'firstMerit';
		return $achievements;
	}
}