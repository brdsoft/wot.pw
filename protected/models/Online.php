<?php

/**
 * This is the model class for table "online".
 *
 * The followings are the available columns in table 'online':
 * @property string $id
 * @property string $site_id
 * @property integer $clan_id
 * @property string $account_id
 * @property integer $time
 * @property integer $battles_all
 * @property integer $battles_clan
 * @property integer $battles_company
 * @property integer $battles_team
 * @property integer $battles_stronghold_defense
 * @property integer $battles_stronghold_skirmish
 * @property integer $battles_globalmap_absolute
 * @property integer $battles_globalmap_champion
 * @property integer $battles_globalmap_middle
 * @property integer $battles_random
 * @property integer $resources
 * @property double $wn8
 * @property double $wins
 * @property integer $globalmap_wins
 * @property double $gold
 *
 * The followings are the available model relations:
 * @property Sites $site
 * @property Clans $clan
 * @property Accounts $account
 */
class Online extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'online';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clan_id, account_id, time, battles_all, battles_clan, battles_company, battles_team, battles_stronghold_defense, battles_stronghold_skirmish, battles_globalmap_absolute, battles_globalmap_champion, battles_globalmap_middle, battles_random, resources, wn8, wins, globalmap_wins, gold', 'required'),
			array('clan_id, time, battles_all, battles_clan, battles_company, battles_team, battles_stronghold_defense, battles_stronghold_skirmish, battles_globalmap_absolute, battles_globalmap_champion, battles_globalmap_middle, battles_random, resources, globalmap_wins', 'numerical', 'integerOnly'=>true),
			array('wn8, wins, gold', 'numerical'),
			array('site_id', 'length', 'max'=>11),
			array('account_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, clan_id, account_id, time, battles_all, battles_clan, battles_company, battles_team, battles_stronghold_defense, battles_stronghold_skirmish, battles_globalmap_absolute, battles_globalmap_champion, battles_globalmap_middle, battles_random, resources, wn8, wins, globalmap_wins, gold', 'safe', 'on'=>'search'),
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
			'clan' => array(self::BELONGS_TO, 'Clans', 'clan_id'),
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
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
			'clan_id' => 'Клан',
			'account_id' => 'Аккаунт',
			'time' => 'Дата',
			'battles_all' => 'Все бои',
			'battles_clan' => 'Бои в составе клана',
			'battles_company' => 'Ротные бои',
			'battles_team' => 'Командные бои',
			'battles_stronghold_defense' => 'Оборона укрепрайона',
			'battles_stronghold_skirmish' => 'Вылазки',
			'battles_globalmap_absolute' => 'ГК Абсолютный дивизион',
			'battles_globalmap_champion' => 'ГК Чемпионский девизион',
			'battles_globalmap_middle' => 'ГК Средний дивизион',
			'battles_random' => 'Случайные бои',
			'resources' => 'Промресурсы',
			'wn8' => 'WN8',
			'wins' => 'Процент побед',
			'globalmap_wins' => 'Победы на ГК',
			'gold' => 'Начислено золота',
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
		$criteria->compare('clan_id',$this->clan_id);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('battles_all',$this->battles_all);
		$criteria->compare('battles_clan',$this->battles_clan);
		$criteria->compare('battles_company',$this->battles_company);
		$criteria->compare('battles_team',$this->battles_team);
		$criteria->compare('battles_stronghold_defense',$this->battles_stronghold_defense);
		$criteria->compare('battles_stronghold_skirmish',$this->battles_stronghold_skirmish);
		$criteria->compare('battles_globalmap_absolute',$this->battles_globalmap_absolute);
		$criteria->compare('battles_globalmap_champion',$this->battles_globalmap_champion);
		$criteria->compare('battles_globalmap_middle',$this->battles_globalmap_middle);
		$criteria->compare('battles_random',$this->battles_random);
		$criteria->compare('resources',$this->resources);
		$criteria->compare('wn8',$this->wn8);
		$criteria->compare('wins',$this->wins);
		$criteria->compare('globalmap_wins',$this->globalmap_wins);
		$criteria->compare('gold',$this->gold);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Online the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
