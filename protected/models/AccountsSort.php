<?php

class AccountsSort extends ActiveRecord
{
	public function tableName()
	{
		return 'accounts_sort';
	}

	public function rules()
	{
		return array(
			array('role_name', 'required'),
			array('role_order', 'numerical', 'integerOnly'=>true),
			array('role_name', 'length', 'max'=>255),
			array('role_name, role_order', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'role_name' => 'Role Name',
			'role_order' => 'Role Order',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('role_name',$this->role_name,true);
		$criteria->compare('role_order',$this->role_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
