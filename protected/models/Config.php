<?php

/**
 * This is the model class for table "config".
 *
 * The followings are the available columns in table 'config':
 * @property string $id
 * @property string $name
 * @property string $site_id
 * @property string $description
 * @property string $value
 * @property string $category
 */
class Config extends ActiveRecord
{
	public static $_all = array();
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, category', 'required'),
			array('site_id', 'numerical', 'integerOnly'=>true),
			array('value, items', 'length', 'max'=>65535),
			array('name, description, category, type', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, value, category', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('wot', 'ID'),
			'name' => Yii::t('wot', 'Name'),
			'site_id' => Yii::t('wot', 'Site'),
			'description' => Yii::t('wot', 'Description'),
			'value' => Yii::t('wot', 'Value'),
			'category' => Yii::t('wot', 'Category'),
			'items' => Yii::t('wot', 'Available values'),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('category',$this->category,true);

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
	 * @return Config the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function all($name, $format = 'text')
	{
    if(isset(self::$_all[$name]))
		{
			$config = clone self::$_all[$name];
			$config->value = Yii::app()->format->$format($config->value);
			return $config;
		}
		$all=self::model()->cache(3600, new CExpressionDependency('Yii::app()->cache->get("config")'))->findAll();
		foreach($all as $value)
		{
			self::$_all[$value->name] = $value;
		}
		if (isset(self::$_all[$name]))
		{
			$config = clone self::$_all[$name];
			$config->value = Yii::app()->format->$format($config->value);
			return $config;
		}
		return false;
	}
	
	public static function explode($value)
	{
		return preg_split('=[^\w]+=', $value);
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('config', time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('config', time());
		return parent::beforeDelete();
	}
	
	public function input()
	{
		if ($this->type == 'text')
			return CHtml::textField('Config[value]',$this->value,array('maxlength'=>255));
		if ($this->type == 'textarea')
			return CHtml::textArea('Config[value]',$this->value);
		if ($this->type == 'checkbox')
			return CHtml::hiddenField('Config[value]', '').CHtml::checkBoxList('Config[value]',$this->explode($this->value),$this->itemsToArray(),array('style'=>'width: auto; margin: 0; vertical-align: top;', 'container'=>false, 'separator'=>false, 'template'=>'<div style="margin-left: 180px; padding-top: 5px;">{input} {labelTitle}</div>'));
		if ($this->type == 'radio')
			return CHtml::radioButtonList('Config[value]',$this->value,$this->itemsToArray(),array('style'=>'width: auto; margin: 0; vertical-align: top;', 'container'=>false, 'separator'=>false, 'template'=>'<div style="margin-left: 180px; padding-top: 5px;">{input} {labelTitle}</div>'));
	}
	
	public function itemsToArray()
	{
		$items = explode(',', $this->items);
		$result = array();
		foreach ($items as $value)
		{
			$item = explode(':', $value);
			$result[trim($item[0])] = trim($item[1]);
		}
		return $result;
	}
	
	public function displayValue()
	{
		if ($this->type == 'text')
			return $this->value;
		if ($this->type == 'textarea')
			return $this->value;
		if ($this->type == 'checkbox')
		{
			$items = $this->itemsToArray();
			$values = $this->explode($this->value);
			$result = array();
			foreach ($values as $value)
			{
				$result[] = isset($items[$value]) ? $items[$value] : '';
			}
			return implode(', ', $result);
		}
		if ($this->type == 'radio')
		{
			$items = $this->itemsToArray();
			return isset($items[$this->value]) ? $items[$this->value] : '';
		}
	}
}
