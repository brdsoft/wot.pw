<?php
class PhpAuthManager extends CPhpAuthManager{
	public function init(){
		parent::init();

		if(!Yii::app()->user->account)
			return;
		
		foreach (Yii::app()->user->account->roles as $role)
		{
			$this->assign($role['id'], Yii::app()->user->id);
		}
	}
}