<?php

/**
 * This is the model class for table "ts_stat".
 *
 * The followings are the available columns in table 'ts_stat':
 * @property string $id
 * @property string $server_id
 * @property string $time
 * @property double $rate
 * @property double $amount
 * @property string $online
 * @property string $processed
 *
 * The followings are the available model relations:
 * @property TsServers $server
 */
class TsStat extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ts_stat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('server_id, time', 'required'),
			array('amount, rate, site_id', 'numerical'),
			array('server_id, time, online', 'length', 'max'=>10),
			array('processed', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, server_id, time, amount, online, processed', 'safe', 'on'=>'search'),
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
			'server' => array(self::BELONGS_TO, 'TsServers', 'server_id'),
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
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
			'server_id' => 'Сервер',
			'time' => 'Дата',
			'amount' => 'Сумма',
			'online' => 'Онлайн',
			'processed' => 'Обработано',
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
		$criteria->compare('server_id',$this->server_id,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('online',$this->online,true);
		$criteria->compare('processed',$this->processed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TsStat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
