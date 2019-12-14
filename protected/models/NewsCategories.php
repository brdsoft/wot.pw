<?php

class NewsCategories extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news_categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('site_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>255),
			array('order', 'numerical', 'integerOnly'=>true),
			array('enabled, edit, on_main, on_title', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, name, enabled, edit, on_main, on_title', 'safe', 'on'=>'search'),
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
			'enabled' => Yii::t('wot', 'Enabled'),
			'order' => Yii::t('wot', 'Sort Order'),
			'edit' => Yii::t('wot', 'Editable'),
			'on_main' => Yii::t('wot', 'Show in common News Feed'),
			'on_title' => Yii::t('wot', 'Show Category name in News header'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('enabled',$this->enabled,true);
		$criteria->compare('edit',$this->edit,true);
		$criteria->compare('on_main',$this->on_main,true);
		$criteria->compare('on_title',$this->on_title,true);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'sort'=>array(
				'defaultOrder'=>"`order`",
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NewsCategories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeCount()
	{
		$this->dbCriteria->order = '`order`';
		$this->dbCriteria->compare('enabled', '1');
		$this->dbCriteria->compare($this->tableAlias.'.site_id', array(Yii::app()->controller->site->id, 168));
	}
	
	protected function beforeFind()
	{
		$this->dbCriteria->order = '`order`';
		$this->dbCriteria->compare('enabled', '1');
		$this->dbCriteria->compare($this->tableAlias.'.site_id', array(Yii::app()->controller->site->id, 168));
	}
}
