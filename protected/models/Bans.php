<?php

class Bans extends ActiveRecord
{
	public $expiresAllowed = [];
	public $expiresTime = array(
		'1'=>86400,
		'2'=>172800,
		'3'=>259200,
		'4'=>604800,
		'5'=>1209600,
		'6'=>2592000,
		'7'=>31536000,
	);

	public function __construct($scenario='insert')
	{
		$this->expiresAllowed = [
			'1'=>Yii::t('wot', 'One day'),
			'2'=>Yii::t('wot', 'Two days'),
			'3'=>Yii::t('wot', 'Three days'),
			'4'=>Yii::t('wot', 'One week'),
			'5'=>Yii::t('wot', 'Two weeks'),
			'6'=>Yii::t('wot', 'One month'),
			'7'=>Yii::t('wot', 'One year'),
		];
		return parent::__construct($scenario);
	}

	public function tableName()
	{
		return 'bans';
	}

	public function rules()
	{
		return array(
			array('account_id, time, expire, author_id', 'required'),
			array('site_id', 'length', 'max'=>11),
			array('account_id, author_id', 'length', 'max'=>20),
			array('time, expire', 'length', 'max'=>10),
			array('time, expire', 'numerical', 'integerOnly'=>true),
			array('expire', 'in', 'range'=>array_keys($this->expiresAllowed), 'message'=>Yii::t('wot', 'Ban expiration is incorrect.')),
			array('account_id', 'accountExists'),
			array('id, site_id, account_id, time, expire, author_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'author' => array(self::BELONGS_TO, 'Accounts', 'author_id'),
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
			'account_id' => Yii::t('wot', 'Account'),
			'time' => Yii::t('wot', 'Ban time'),
			'expire' => Yii::t('wot', 'Ban expiration'),
			'author_id' => Yii::t('wot', 'Ban creator'),
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

		$criteria->with = array('account', 'author');
		$criteria->compare('id',$this->id);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('expire',$this->expire,true);
		$criteria->compare('author_id',$this->author_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function accountExists()
	{
		if (empty($this->account_id))
			return;
		$account = Accounts::model()->findByPk($this->account_id);
		if (!$account)
		{
			$this->addError('account_id',Yii::t('wot', 'Account with specified ID is not found.'));
			return;
		}
		
		foreach ($account->rolesDb as $value)
		{
			if ($value['id'] == 0 || $value['id'] == 1 || $value['id'] == 8)
			{
				$this->addError('account_id',Yii::t('wot', 'This account can not be banned.'));
				break;
			}
		}
	}
	
	protected function beforeSave()
	{
		$this->expire = time()+$this->expiresTime[$this->expire];
		return parent::beforeSave();
	}
	
}
