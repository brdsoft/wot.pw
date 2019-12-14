<?php

class Notices extends ActiveRecord
{
	public $expiresAllowed = [];
	public $expiresTime = array(
		'1'=>86400,
		'2'=>172800,
		'3'=>259200,
		'4'=>604800,
		'5'=>1209600,
		'6'=>2592000,
	);
	public $recipientsAllowed = [];
	public $answersAllowed = [];
	
	public function __construct($scenario='insert')
	{
		$this->recipientsAllowed = Accounts::model()->WGRoles;
		$this->expiresAllowed = [
			'1'=>Yii::t('wot', 'One day'),
			'2'=>Yii::t('wot', 'Two days'),
			'3'=>Yii::t('wot', 'Three days'),
			'4'=>Yii::t('wot', 'One week'),
			'5'=>Yii::t('wot', 'Two weeks'),
			'6'=>Yii::t('wot', 'One month'),
		];
		 $this->answersAllowed = [
			'1'=>Yii::t('wot', 'Acknowledged'),
			'2'=>Yii::t('wot', 'Agree'),
			'3'=>Yii::t('wot', 'Disagree'),
		];
		return parent::__construct($scenario);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, time, expire, notice, clans, recipients, answers', 'required'),
			array('time, expire', 'numerical', 'integerOnly'=>true),
			array('site_id', 'length', 'max'=>11),
			array('account_id', 'length', 'max'=>20),
			array('notice', 'length', 'max'=>800),
			array('clans', 'checkClans'),
			array('recipients', 'checkRecipients'),
			array('answers', 'checkAnswers'),
			array('expire', 'in', 'range'=>array_keys($this->expiresAllowed), 'message'=>Yii::t('wot', 'Expiration time is incorrect.')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, account_id, time, expire, notice, recipients, answers, clans', 'safe', 'on'=>'search'),
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
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'noticesRecipients' => array(self::HAS_MANY, 'NoticesRecipients', 'notices_id'),
			'recipientsCount' => array(self::STAT, 'NoticesRecipients', 'notices_id'),
			'recipientsCount1' => array(self::STAT, 'NoticesRecipients', 'notices_id', 'condition'=>"`answer` = '1'"),
			'recipientsCount2' => array(self::STAT, 'NoticesRecipients', 'notices_id', 'condition'=>"`answer` = '2'"),
			'recipientsCount3' => array(self::STAT, 'NoticesRecipients', 'notices_id', 'condition'=>"`answer` = '3'"),
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
			'account_id' => Yii::t('wot', 'Author'),
			'time' => Yii::t('wot', 'Created'),
			'expire' => Yii::t('wot', 'Expiration time'),
			'notice' => Yii::t('wot', 'Message'),
			'recipients' => Yii::t('wot', 'Recipients'),
			'answers' => Yii::t('wot', 'Possible answers'),
			'clans' => Yii::t('wot', 'Clans'),
			'recipientsCount' => Yii::t('wot', 'Recipients count'),
			'recipientsCount1' => Yii::t('wot', 'Acknowledged'),
			'recipientsCount2' => Yii::t('wot', 'Agree'),
			'recipientsCount3' => Yii::t('wot', 'Disagree'),
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
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('expire',$this->expire);
		$criteria->compare('notice',$this->notice,true);
		$criteria->compare('clans',$this->clans,true);
		$criteria->compare('recipients',$this->recipients,true);
		$criteria->compare('answers',$this->answers,true);

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
	 * @return Notices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkRecipients()
	{
		if (empty($this->recipients))
			return;
		if (!is_array($this->recipients))
		{
			$this->recipients = '';
			$this->addError('recipients',Yii::t('wot', 'Recipients is incorrect.'));
			return;
		}
		foreach ($this->recipients as $value)
		{
			if (!isset($this->recipientsAllowed[$value]))
			{
				$this->recipients = '';
				$this->addError('recipients',Yii::t('wot', 'Recipients is incorrect.'));
				return;
			}
		}
		$this->recipients = implode(',', $this->recipients);
	}
	
	public function checkAnswers()
	{
		if (empty($this->answers))
			return;
		if (!is_array($this->answers))
		{
			$this->answers = '';
			$this->addError('answers',Yii::t('wot', 'Possible answers is incorrect.'));
			return;
		}
		foreach ($this->answers as $value)
		{
			if (!isset($this->answersAllowed[$value]))
			{
				$this->answers = '';
				$this->addError('answers',Yii::t('wot', 'Possible answers is incorrect.'));
				return;
			}
		}
		$this->answers = implode(',', $this->answers);
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
	
	protected function beforeSave()
	{
		$this->expire = time()+$this->expiresTime[$this->expire];
		return parent::beforeSave();
	}
	
	public function addRecipients($notices_id)
	{
		$roles = array();
		$clans = explode(',',$this->clans);
		$recipients = explode(',',$this->recipients);
		foreach ($recipients as $key=>$value)
		{
			$recipients[$key] = "'".$value."'";
		}
		foreach ($clans as $value)
		{
			$accounts = Accounts::model()->findAll("`clan_id` = '{$value}' AND `role` IN (".implode(',', $recipients).")");
			$rows = array();
			foreach ($accounts as $account)
			{
				$rows[] = array('site_id'=>Yii::app()->controller->site->id, 'notices_id'=>$notices_id, 'account_id'=>$account->id);
			}
			if (!$rows)
				continue;
			$builder=Yii::app()->db->schema->commandBuilder;
			$command=$builder->createMultipleInsertCommand('notices_recipients', $rows);
			$command->execute();
		}
	}
	
	public function addRecipients2($notices_id)
	{
		$accountsRoles = AccountsRoles::model()->findAll("`role_id` = '1'");
		
		$rows = array();
		foreach ($accountsRoles as $account)
		{
			$rows[$account->account_id] = array('site_id'=>168, 'notices_id'=>$notices_id, 'account_id'=>$account->account_id);
		}
		
		unset($accountsRoles);
		
		$sites = Sites::model()->findAll();
		foreach ($sites as $site)
		{
			$rows[$site->account_id] = array('site_id'=>168, 'notices_id'=>$notices_id, 'account_id'=>$site->account_id);
		}
		
		unset($sites);
		
		$builder=Yii::app()->db->schema->commandBuilder;
		$command=$builder->createMultipleInsertCommand('notices_recipients', $rows);
		$command->execute();
	}
}
