<?php

/**
 * This is the model class for table "companies".
 *
 * The followings are the available columns in table 'companies':
 * @property integer $id
 * @property string $site_id
 * @property integer $clan_id
 * @property string $name
 * @property integer $order
 * @property string $accounts
 *
 * The followings are the available model relations:
 * @property Sites $site
 * @property Clans $clan
 */
class Companies extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'companies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, order, clan_id, accounts', 'required'),
			array('clan_id, order', 'numerical', 'integerOnly'=>true),
			array('site_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>32),
			array('clan_id', 'exist', 'message'=>Yii::t('wot', 'Selected clan not found'), 'className'=>'Clans', 'attributeName'=>'id', 'criteria'=>array('condition'=>"`site_id` = '".Yii::app()->controller->site->id."'")),
			array('accounts', 'checkAccounts', 'skipOnError'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, clan_id, name, order', 'safe', 'on'=>'search'),
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
			'clan_id' => Yii::t('wot', 'Clan'),
			'name' => Yii::t('wot', 'Name'),
			'order' => Yii::t('wot', 'Sort Order'),
			'accounts' => Yii::t('wot', 'Personnel'),
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
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('clan_id',$this->clan_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'sort'=>array(
				'defaultOrder'=>"`clan_id`",
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Companies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkAccounts()
	{
		if (empty($this->accounts))
			return;
		if (!is_array($this->accounts))
		{
			$this->accounts = '';
			$this->addError('accounts',Yii::t('wot', 'Error with companies personnel'));
			return;
		}
		$accounts = CHtml::listData(Accounts::model()->findAllByAttributes(array('clan_id'=>$this->clan_id)), 'id', 'id');
		foreach ($this->accounts as $value)
		{
			if (!isset($accounts[$value]))
			{
				$this->accounts = '';
				$this->addError('accounts',Yii::t('wot', 'Error with companies personnel'));
				return;
			}
		}
		$this->accounts = implode(',', $this->accounts);
	}

}
