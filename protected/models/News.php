<?php

class News extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time, text1, text3, account_id', 'required'),
			array('time, site_id, category_id', 'numerical', 'integerOnly'=>true),
			array('text1, image', 'length', 'max'=>255),
			array('text2, text3', 'length', 'max'=>65535),
			array('account_id', 'length', 'max'=>20),
			array('image', 'match', 'pattern'=>'/^\w{32}\.\w+$/', 'message'=>Yii::t('wot', 'Wrong file name.')),
			array('category_id', 'exist', 'message'=>Yii::t('wot', 'There is no such category.'), 'className'=>'NewsCategories', 'attributeName'=>'id', 'criteria'=>array('condition'=>"`site_id` = '".Yii::app()->controller->site->id."' OR `site_id` = 168")),
			array('category_id', 'checkWG', 'message'=>Yii::t('wot', 'You cannot add news to this category.')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, text1, text2, text3, account_id', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'NewsCategories', 'category_id'),
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
			'time' => Yii::t('wot', 'Time'),
			'text1' => Yii::t('wot', 'Header'),
			'text2' => Yii::t('wot', 'Short text'),
			'text3' => Yii::t('wot', 'Full text'),
			'account_id' => Yii::t('wot', 'Author'),
			'category_id' => Yii::t('wot', 'Category'),
			'image' => Yii::t('wot', 'Picture'),
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

		$criteria->with = array('account','category');
		$criteria->compare('id',$this->id);
		$criteria->compare('time',$this->time);
		$criteria->compare('text1',$this->text1,true);
		$criteria->compare('text2',$this->text2,true);
		$criteria->compare('text3',$this->text3,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('category_id',$this->category_id,true);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'sort'=>array(
				'defaultOrder'=>"`time` DESC",
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkWG()
	{
		if ($this->category_id == 2 && Yii::app()->controller->site->id != 168)
			$this->addError('category_id', Yii::t('wot', 'You cannot add news to this category'));
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('news', time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('news', time());
		return parent::beforeDelete();
	}
}
