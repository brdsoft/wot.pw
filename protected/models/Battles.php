<?php

/**
 * This is the model class for table "battles".
 *
 * The followings are the available columns in table 'battles':
 * @property integer $clan_id
 * @property string $provinces
 * @property integer $started
 * @property integer $time
 * @property string $arenas
 * @property string $type
 */
class Battles extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'battles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clan_id, provinces, started, time, arenas, type', 'required'),
			array('clan_id, started, time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clan_id, provinces, started, time, arenas, type', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'clan_id' => Yii::t('wot', 'Clan'),
			'provinces' => Yii::t('wot', 'Provinces'),
			'started' => Yii::t('wot', 'Battle began'),
			'time' => Yii::t('wot', 'Time'),
			'arenas' => Yii::t('wot', 'Arenas'),
			'type' => Yii::t('wot', 'Type'),
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

		$criteria->compare('clan_id',$this->clan_id);
		$criteria->compare('provinces',$this->provinces,true);
		$criteria->compare('started',$this->started);
		$criteria->compare('time',$this->time);
		$criteria->compare('arenas',$this->arenas,true);
		$criteria->compare('type',$this->type,true);

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
	 * @return Battles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
