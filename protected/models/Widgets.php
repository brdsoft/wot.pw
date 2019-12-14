<?php

class Widgets extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'widgets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, params, example, premium', 'required'),
			array('order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('description', 'length', 'max'=>255),
			array('premium', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, description, params, example, premium, order', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('wot', 'Abbreviation'),
			'description' => Yii::t('wot', 'Description'),
			'params' => Yii::t('wot', 'Parameters'),
			'example' => Yii::t('wot', 'Example'),
			'premium' => Yii::t('wot', 'Paid'),
			'order' => Yii::t('wot', 'Sort Order'),
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('example',$this->example,true);
		$criteria->compare('premium',$this->premium,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Widgets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
