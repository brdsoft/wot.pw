<?php

/**
 test
 */
class Access extends ActiveRecord
{
	public $rolesAllowed = [];
	
	public function __construct($scenario='insert')
	{
		$this->rolesAllowed = Accounts::model()->WGRoles;
		return parent::__construct($scenario);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'access';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, clans, roles', 'required'),
			array('site_id', 'length', 'max'=>11),
			array('url', 'match', 'pattern'=>'=^/[\w\-/]{3,254}$=i', 'message'=>Yii::t('wot', 'URL is incorrect')),
			array('clans', 'checkClans'),
			array('roles', 'checkRoles'),
			array('id, site_id, url, roles', 'safe', 'on'=>'search'),
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
			'clans' => Yii::t('wot', 'Clans'),
			'url' => Yii::t('wot', 'URL'),
			'roles' => Yii::t('wot', 'Roles'),
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
		$criteria->compare('url',$this->url,true);
		$criteria->compare('roles',$this->roles,true);

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
	 * @return Access the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkClans()
	{
		if (empty($this->clans))
			return;
		if (!is_array($this->clans))
		{
			$this->clans = '';
			$this->addError('clans',Yii::t('wot', 'Clans is incorrect.'));
			return;
		}
		$clans = CHtml::listData(Clans::model()->findAll(), 'id', 'id');
		foreach ($this->clans as $value)
		{
			if (!isset($clans[$value]))
			{
				$this->clans = '';
				$this->addError('clans',Yii::t('wot', 'Clans is incorrect.'));
				return;
			}
		}
		$this->clans = implode(',', $this->clans);
	}

	public function checkRoles()
	{
		if (empty($this->roles))
			return;
		if (!is_array($this->roles))
		{
			$this->roles = '';
			$this->addError('roles',Yii::t('wot', 'Roles is incorrect.'));
			return;
		}
		foreach ($this->roles as $value)
		{
			if (!isset($this->rolesAllowed[$value]))
			{
				$this->roles = '';
				$this->addError('roles',Yii::t('wot', 'Roles is incorrect.'));
				return;
			}
		}
		$this->roles = implode(',', $this->roles);
	}
}

