<?php

/**
 * This is the model class for table "ts_servers".
 *
 * The followings are the available columns in table 'ts_servers':
 * @property int $id
 * @property int $site_id
 * @property int $server_id
 * @property int $sid
 * @property string $port
 * @property string $alias
 * @property int $time_created
 * @property int $time_down
 * @property int $time_stat
 *
 * The followings are the available model relations:
 * @property Sites $site
 */
class TsServers extends ActiveRecord
{
	public $snapshot = 'empty';
	public $minBalance = 10;
	public $balance = 0;
	public $minPort = 20000;
	public $maxPort = 30000;
	public $freeSlots = 200;
	public static $servers = [
		[
			'ip'=>'87.98.129.223',
			'qip'=>'185.60.134.146',
			'port'=>'10010',
			'username'=>'serveradmin',
			'password'=>'************',
			'query'=>false,
			'connectStatus'=>false,
		],
	];
	public $createServerId = 0;
	public $createStatus = true;
	public $statusAvailable = [];
	public $tokenTypes = [];
	public $snapshots = [];
	public $defaultRate = 0.2;
	public $snapshot_alliance = 'protected/data/teamspeak_alliance.txt';
	public $snapshot_clan = 'protected/data/teamspeak_clan.txt';
	
	public function __construct($scenario='insert')
	{
		$this->statusAvailable = [
			'offline'=>Yii::t('wot', 'Остановлен'),
			'online'=>Yii::t('wot', 'Работает'),
		];
		$this->tokenTypes = [
			Yii::t('wot', 'Группа сервера'),
			Yii::t('wot', 'Группа канала'),
		];
		$this->snapshots = [
			'empty'=>Yii::t('wot', 'Пустой шаблон'),
			'clan'=>Yii::t('wot', 'Шаблон для одного клана'),
			'alliance'=>Yii::t('wot', 'Шаблон для альянса'),
		];
		return parent::__construct($scenario);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ts_servers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id', 'unique', 'message'=>'Создать можно только один TeamSpeak сервер.'),
			array('port', 'numerical', 'integerOnly'=>true, 'message'=>'К сожалению, свободных серверов нет.'),
			array('server_id, sid, time_created, time_down, time_stat', 'numerical', 'integerOnly'=>true),
			array('rate', 'numerical'),
			array('alias', 'length', 'max'=>128),
			array('snapshot', 'in', 'range'=>array_keys($this->snapshots)),
			array('createStatus', 'compare', 'compareValue'=>true, 'message'=>'В данный момент сервер недоступен.'),
			array('balance', 'compare', 'operator'=>'>=', 'compareAttribute'=>'minBalance', 'message'=>Yii::t('wot', 'Баланс сайта должен быть не менее :minBalance рублей.', [':minBalance'=>$this->minBalance])),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, server_id, port, alias', 'safe', 'on'=>'search'),
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
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
			'tsStat' => array(self::HAS_MANY, 'TsStat', 'server_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'site_id' => 'Сайт',
			'server_id' => 'ID реального сервера ',
			'sid' => 'ID виртуального сервера ',
			'port' => 'Порт',
			'alias' => 'Алиас',
			'snapshot' => 'Шаблон',
			'balance' => 'Баланс сайта',
			'rate' => 'Цена слота',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('server_id',$this->server_id,true);
		$criteria->compare('port',$this->port,true);
		$criteria->compare('alias',$this->alias,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TsServers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getFreePort()
	{
		Yii::app()->params['skipSiteCheck'] = true;
		$existsPorts = $this->findAll(['select'=>'`port`', 'index'=>'port', 'condition'=>"`server_id` = '{$this->createServerId}'"]);
		$allPorts = range($this->minPort, $this->maxPort);
		shuffle($allPorts);
		foreach ($allPorts as $value)
		{
			if (empty($existsPorts[$value]))
				return $value;
		}
		return false;
	}
	
	public function connect()
	{
		if ($this->server['connectStatus'])
			return true;
		self::$servers[$this->server_id]['query'] = new ts3admin($this->server['qip'], $this->server['port']);
		if (!$this->server['query']->connect()['success'])
			return false;
		if (!$this->server['query']->login($this->server['username'], $this->server['password'])['success'])
			return false;
		self::$servers[$this->server_id]['connectStatus'] = true;
		return true;
	}
	
	public function select()
	{
		if (!$this->server['query']->selectServer($this->port)['success'])
			return false;
		return true;
	}
	
	public function getServer()
	{
		return self::$servers[$this->server_id];
	}
	
	public function delete()
	{
		return $this->server['query']->serverDelete($this->sid)['success'] && parent::delete();
	}

}
