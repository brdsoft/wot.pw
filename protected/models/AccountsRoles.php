<?php

class AccountsRoles extends ActiveRecord
{

	public function tableName()
	{
		return 'accounts_roles';
	}

	public function rules()
	{
		return array(
			array('account_id, role_id', 'required'),
			array('role_id, site_id', 'numerical', 'integerOnly'=>true),
			array('account_id', 'length', 'max'=>20),
			array('account_id', 'accountExists'),
			array('role_id', 'roleExists'),
			array('id, account_id, role_id, site_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'role' => array(self::BELONGS_TO, 'Roles', 'role_id'),
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'site_id' => Yii::t('wot', 'Site'),
			'account_id' => Yii::t('wot', 'Account'),
			'role_id' => Yii::t('wot', 'Role'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('site_id',$this->site_id);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function accountExists()
	{
		if (empty($this->account_id))
			return;
		if (!Accounts::model()->findByPk($this->account_id))
			$this->addError('account_id',Yii::t('wot', 'Account with specified ID is not found.'));
	}
	
	public function roleExists()
	{
		if (empty($this->role_id) || empty($this->account_id))
			return;
		if (self::model()->findByAttributes(array('role_id'=>$this->role_id, 'account_id'=>$this->account_id)))
			$this->addError('role_id','Account already assigned this role.');
	}
}
