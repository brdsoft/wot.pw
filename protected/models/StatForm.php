<?php

class StatForm extends CFormModel
{
	public $nickname;
	public $account;

	public function rules()
	{
		return array(
			array('nickname', 'required'),
			array('nickname', 'match', 'pattern'=>'=^[\w]+$=', 'message'=>Yii::t('wot', 'Nickname is incorrect.')),
			array('nickname', 'stat', 'skipOnError'=>true),
		);
	}

	public function attributeLabels()
	{
		return array(
			'nickname'=>Yii::t('wot', 'Nickname'),
		);
	}
	
	public function stat()
	{
		$account = Accounts::model()->findByAttributes(['nickname'=>$this->nickname]);
		if ($account)
		{
			$stat = $account->stat; //Для предзагрузки статы
			$this->account = $account;
			return;
		}
		
		$accountList = WGApi::getAccountList($this->nickname);
		if (empty($accountList[0]))
		{
			$this->addError('nickname',Yii::t('wot', 'Account is not found.'));
			return;
		}
		
		$account = new Accounts;
		$account->id = $accountList[0]['account_id'];
		$account->nickname = $accountList[0]['nickname'];
		$account->created_at = $accountList[0]['created_at'];
		$account->save();
		
		$stat = $account->stat; //Для предзагрузки статы
		$this->account = $account;
		return;
	}

}
