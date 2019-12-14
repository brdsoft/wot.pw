<?php

/**
 * This is the model class for table "events".
 *
 * The followings are the available columns in table 'events':
 * @property integer $id
 * @property integer $clan_id
 * @property string $type
 * @property string $event
 * @property integer $time
 *
 * The followings are the available model relations:
 * @property Clans $clan
 */
class Events extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clan_id, event, time', 'required'),
			array('clan_id, time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clan_id, type, event, time', 'safe', 'on'=>'search'),
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
			'clan' => array(self::BELONGS_TO, 'Clans', 'clan_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'clan_id' => Yii::t('wot', 'Clan'),
			'type' => Yii::t('wot', 'Type'),
			'event' => Yii::t('wot', 'Event'),
			'time' => Yii::t('wot', 'Time'),
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
		$criteria->compare('clan_id',$this->clan_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('event',$this->event,true);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Events the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
