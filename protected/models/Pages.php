<?php

class Pages extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, text1, text2, time, account_id', 'required'),
			array('time, site_id', 'numerical', 'integerOnly'=>true),
			array('name, text1', 'length', 'max'=>128),
			array('account_id', 'length', 'max'=>20),
			array('name', 'match', 'pattern'=>'=^[\w\-]+$=', 'message'=>Yii::t('wot', 'Invalid characters found.')),
		// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, text1, text2, time, account_id', 'safe', 'on'=>'search'),
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
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'site_id' => Yii::t('wot', 'Site'),
			'name' => Yii::t('wot', 'Name'),
			'text1' => Yii::t('wot', 'Header'),
			'text2' => Yii::t('wot', 'Text of the page'),
			'time' => Yii::t('wot', 'Date'),
			'account_id' => Yii::t('wot', 'Author'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('text1',$this->text1,true);
		$criteria->compare('text2',$this->text2,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('account_id',$this->account_id,true);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>100,
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('textOnMain', time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('textOnMain', time());
		return parent::beforeDelete();
	}
}
