<?php

/**
 * Class Accounts
 *
 * @property int $id
 * @property string $nickname
 * @property string $avatar
 */
class Accounts extends ActiveRecord
{
	const DEFAULT_AVATAR = 'no-avatar.png';

	public $WGRoles = [];
	public $roles = array();
	public $trigger_joined_clan = false; //Триггер вступил в клан на момент прохода парсера
	public $wn8_prec;
	public $wins_prec;
	public $online = []; //temp

	public function __construct($scenario='insert')
	{
		$this->WGRoles = [
			'commander'=>Yii::t('wot', 'Commander'),
			'executive_officer'=>Yii::t('wot', 'Executive Officer'),
			'personnel_officer'=>Yii::t('wot', 'Personnel officer'),
			'combat_officer'=>Yii::t('wot', 'Combat officer'),
			'intelligence_officer'=>Yii::t('wot', 'Intelligence officer'),
			'quartermaster'=>Yii::t('wot', 'Quartermaster'),
			'recruitment_officer'=>Yii::t('wot', 'Recruitment officer'),
			'junior_officer'=>Yii::t('wot', 'Junior officer'),
			'private'=>Yii::t('wot', 'Private'),
			'recruit'=>Yii::t('wot', 'Recruit'),
			'reservist'=>Yii::t('wot', 'Reservist'),
		];
		return parent::__construct($scenario);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounts';
	}
	
	public function init()
	{
		$this->signature = '';
		$this->about = '';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, nickname', 'required'),
			array('created_at, updated_at, visited_at, teamspeak_at, clan_id, since, banned', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>20),
			array('nickname, clan_name, role, role_i18n, region, email', 'length', 'max'=>255),
			array('email', 'email'),
			array('skype, name, city, cluster', 'length', 'max'=>64),
			array('avatar', 'file', 'types'=>'jpg, jpeg, gif, png', 'maxSize' => 2000000, 'allowEmpty'=>true, 'safe'=>false),
			array('clan_abbreviation', 'length', 'max'=>5),
			array('ip', 'length', 'max'=>15),
			array('tel', 'length', 'max'=>12),
			array('about, signature', 'length', 'max'=>2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nickname, created_at, updated_at, visited_at, teamspeak_at, clan_id, clan_abbreviation, clan_name, role, role_i18n, since, ip, region', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'news' => array(self::HAS_MANY, 'News', 'account_id'),
			'recruitments' => array(self::HAS_MANY, 'Recruitment', 'account_id'),
			'sort' => array(self::BELONGS_TO, 'AccountsSort', 'role'),
			'forumMessagesCount' => array(self::STAT, 'ForumMessages', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'nickname' => Yii::t('wot', 'Nickname'),
			'created_at' => Yii::t('wot', 'Account created'),
			'updated_at' => Yii::t('wot', 'Information updated'),
			'visited_at' => Yii::t('wot', 'Site visit'),
			'battle_at' => Yii::t('wot', 'Last battle'),
			'teamspeak_at' => Yii::t('wot', 'TeamSpeak visit'),
			'clan_id' => Yii::t('wot', 'Clan'),
			'clan_abbreviation' => Yii::t('wot', 'Clantag'),
			'clan_name' => Yii::t('wot', 'Clan name'),
			'role' => Yii::t('wot', 'Role'),
			'role_i18n' => Yii::t('wot', 'Localized role'),
			'since' => Yii::t('wot', 'Clan since'),
			'ip' => Yii::t('wot', 'IP'),
			'region' => Yii::t('wot', 'Region'),
			'battles' => Yii::t('wot', 'Battles'),
			'wins' => Yii::t('wot', 'Wins'),
			'bs' => Yii::t('wot', 'BS'),
			'tanks10' => Yii::t('wot', '10 lvl tanks'),
			're' => Yii::t('wot', 'RE'),
			'wn8' => Yii::t('wot', 'WN8'),
			'tanks_list' => Yii::t('wot', 'Tanks list'),
			'parser_tanks_time' => Yii::t('wot', 'Tanks updated'),
			'avatar' => Yii::t('wot', 'Avatar (less then 2 MB)'),
			'email' => Yii::t('wot', 'E-mail'),
			'skype' => Yii::t('wot', 'Skype'),
			'tel' => Yii::t('wot', 'Phone'),
			'name' => Yii::t('wot', 'Name'),
			'city' => Yii::t('wot', 'City'),
			'signature' => Yii::t('wot', 'Forum signature'),
			'about' => Yii::t('wot', 'About'),
			'banned' => Yii::t('wot', 'Banned'),
			'fame_points' => Yii::t('wot', 'Famepoints'),
			'position' => Yii::t('wot', 'Hall of Fame position'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nickname',$this->nickname);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Accounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getRolesDb()
	{
		$roles_rel = AccountsRoles::model()->findAllByAttributes(array('account_id'=>$this->id), array('with'=>'role'));
		$roles = array();
		foreach ($roles_rel as $value)
		{
			$roles[] = $value->role;
		}
		if ($this->id == Yii::app()->controller->site->account_id)
			$roles[] = array('id'=>0, 'name'=>Yii::t('wot', 'Site owner'), 'description'=>Yii::t('wot', 'Has unlimited privileges'), 'order'=>'-999');
		if ($this->id != Yii::app()->controller->site->account_id && in_array($this->id, Yii::app()->params['admins']))
			$roles[] = array('id'=>0, 'name'=>Yii::t('wot', 'Site owner'), 'description'=>Yii::t('wot', 'Has unlimited privileges'), 'order'=>'-999');
		return $roles;
	}

	public function complete($term, $limit = 7)
	{
		$term = preg_replace('~[^A-Za-z0-9_]~', '', $term);

		if (empty($term)) {
			return [];
		}

		$term = str_replace('_', '\_', $term).'%';

		$criteria = new CDbCriteria;

		$criteria->addSearchCondition('nickname', $term, false);
		$criteria->select = 'nickname';
		$criteria->limit = $limit;

		return array_map(function ($account) {
			return $account->nickname;
		}, $this->findAll($criteria));
	}

	public function reset()
	{
		$this->clan_id = 0;
		$this->clan_abbreviation = '';
		$this->clan_name = '';
		$this->since = 0;
		$this->role = '';
		$this->role_i18n = '';
		//$this->visited_at = 0;
		//$this->battle_at = 0;
		//$this->teamspeak_at = 0;
	}

	public function getAvatar()
	{
		return empty($this->avatar) ? Accounts::DEFAULT_AVATAR : $this->avatar;
	}

	public function updateStat($api_account, $tanks, $api_tanks)
	{
		//Танки
		$tanks8 = 0;
		$tanks10 = 0;
		$tanks_list = array();
		$tanks_list_gw = array();

		if (!empty($api_tanks))
		{
			foreach ($api_tanks as $tank)
			{
				if (empty($tank['tank_id']) || empty($tanks[$tank['tank_id']]))
					continue;

				if ($tanks[$tank['tank_id']]['level'] == 10 || $tanks[$tank['tank_id']]['level'] == 8)
				{
					$l = 'tanks'.$tanks[$tank['tank_id']]['level'];
					$$l++;
					$tanks_list_gw[$tank['tank_id']] = [
						'b'=>$tank['all']['battles'],
						'w'=>$tank['all']['wins'],
						'd'=>$tank['all']['damage_dealt'],
						'l'=>$tanks[$tank['tank_id']]['level'],
					];
				}

				$tanks_list[$tank['tank_id']] = [
					'm'=>$tank['mark_of_mastery'],
					'b'=>$tank['all']['battles'],
					'w'=>$tank['all']['wins'],
					'f'=>$tank['all']['frags'],
					's'=>$tank['all']['shots'],
					'h'=>$tank['all']['hits'],
					'd'=>$tank['all']['damage_dealt'],
					'l'=>$tanks[$tank['tank_id']]['level'],
				];
			}
		}

		//ГК техника игрока
		$tanks_list_gw = json_encode($tanks_list_gw);

		//Статистика Redis
		$stat_all =        $api_account['statistics']['all'];
		$stat_clan =       $api_account['statistics']['clan'];
		$stat_company =    $api_account['statistics']['company'];
		$stat_team =       $api_account['statistics']['team'];
		$stat_stronghold_defense =       $api_account['statistics']['stronghold_defense'];
		$stat_stronghold_skirmish =       $api_account['statistics']['stronghold_skirmish'];
		$stat_globalmap_absolute = $api_account['statistics']['globalmap_absolute'];
		$stat_globalmap_champion = $api_account['statistics']['globalmap_champion'];
		$stat_globalmap_middle = $api_account['statistics']['globalmap_middle'];
		$stat_random = $api_account['statistics']['random'];

		$stat = [
			'b_at'=>$api_account['last_battle_time'], //Был в бою
			'wins'=>$stat_all['battles'] > 0 ? round(($stat_all['wins'] / $stat_all['battles']) * 100, 2) : 0,
			'bs'=>round(WGApi::bsRating($stat_all['battles']-$stat_clan['battles']-$stat_company['battles'], $stat_all['xp']-$stat_clan['xp']-$stat_company['xp'], $stat_all['damage_dealt']-$stat_clan['damage_dealt']-$stat_company['damage_dealt'], $stat_all['wins']-$stat_clan['wins']-$stat_company['wins'], $stat_all['frags']-$stat_clan['frags']-$stat_company['frags'], $stat_all['spotted']-$stat_clan['spotted']-$stat_company['spotted'], $stat_all['capture_points']-$stat_clan['capture_points']-$stat_company['capture_points'], $stat_all['dropped_capture_points']-$stat_clan['dropped_capture_points']-$stat_company['dropped_capture_points']), 2),
			'wn8'=>round(WGApi::wn8Rating($stat_all, $tanks_list), 2),
			're'=>round(WGApi::reRating($stat_all['battles'], $tanks_list, $stat_all['damage_dealt'], $stat_all['frags'], $stat_all['spotted'], $stat_all['capture_points'], $stat_all['dropped_capture_points']), 2),
			't_cu'=>$api_account['statistics']['trees_cut'], //Повалено деревьев
			'b_al'=>$stat_all['battles'], //Всего боев
			'b_cl'=>$stat_clan['battles'],
			'b_co'=>$stat_company['battles'],
			'b_te'=>$stat_team['battles'],
			'b_sd'=>$stat_stronghold_defense['battles'],
			'b_ss'=>$stat_stronghold_skirmish['battles'],
			'b_ga'=>$stat_globalmap_absolute['battles'],
			'b_gc'=>$stat_globalmap_champion['battles'],
			'b_gm'=>$stat_globalmap_middle['battles'],
			'b_ra'=>$stat_random['battles'],
			't8'=>$tanks8,
			't10'=>$tanks10,
			'tl10'=>$tanks_list_gw,
		];

		Yii::app()->controller->redis->hMset('a:'.$this->id, $stat);
		Yii::app()->controller->redis->expire('a:'.$this->id, 259200); //3 суток

		return $stat;
	}

	public function updateOnline($stat, $resources)
	{
		$time = mktime(0,0,0);

		Yii::app()->controller->redis->set('o:'.$this->clan_id.':'.$this->id.':'.$time,
			$stat['wins'].':'. //wins
			$stat['bs'].':'. //bs
			$stat['wn8'].':'. //wn8
			$stat['re'].':'. //re
			$stat['b_al'].':'. //battles all
			$stat['b_cl'].':'. //battles clan
			$stat['b_co'].':'. //battles company
			$stat['b_te'].':'. //battles team
			$stat['b_sd'].':'. //battles sd
			$stat['b_ss'].':'. //battles ss
			$stat['b_ga'].':'. //battles ga
			$stat['b_gc'].':'. //battles gc
			$stat['b_gm'].':'. //battles gm
			$stat['b_ra'].':'. //battles random
			$resources //resources
		);
		Yii::app()->controller->redis->expire('o:'.$this->clan_id.':'.$this->id.':'.$time, 8640000); //100 суток
	}

	public function getStat()
	{
		$stat = Yii::app()->controller->redis->hGetAll('a:'.$this->id);
		if (empty($stat))
		{
			$accountInfo = WGApi::getAccountInfo($this->id);
			if (empty($accountInfo))
				throw new CHttpException(503,'Статистика временно недоступна. Обновите страницу или зайдите позже.');
			/** @noinspection PhpParamsInspection */
			$accountInfo = array_shift($accountInfo);
			if (empty($accountInfo))
				throw new CHttpException(503,'Статистика временно недоступна. Обновите страницу или зайдите позже.');
			$accounts_tanks = WGApi::getAccountTanks($this->id);
			if (isset($accounts_tanks[$this->id]['status']) && $accounts_tanks[$this->id]['status'] == 'ok')
			{
				$tanks = Tanks::model()->findAll(array('index'=>'id'));
				$stat = $this->updateStat($accountInfo, $tanks, $accounts_tanks[$this->id]['data'][$this->id]);
			}
			else
				throw new CHttpException(503,'Статистика временно недоступна. Обновите страницу или зайдите позже.');
		}
		return $stat;
	}
}
