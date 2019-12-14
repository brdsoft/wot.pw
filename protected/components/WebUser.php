<?php
class WebUser extends CWebUser
{
	public $loginUrl = array('/api/login');

	/**
	 * @return bool|Accounts
     */
	public function getAccount()
	{
		if (Yii::app()->user->isGuest)
		{
			return false;
		}

		$account = $this->getState('account_data', false);

		if (!$account || $account->visited_at < time() - 600)
		{
			$account = Accounts::model()->findByPk(Yii::app()->user->id);
			if (!$account)
				return false;
			$account->visited_at = time();
			$account->roles = $account->rolesDb;
			$account->save();

			//if ($account->id == 11944945)
			//	$account->clan_id = 4545;
			//if ($account->id == 7208418)
			//	$account->clan_id = 4545;

			$this->setState('account_data', $account);
		}

		return $account;
	}
}