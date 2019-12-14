<?php

class ProfileController extends Controller
{
	public function actionAccount($id)
	{
		$account=Accounts::model()->findByPk($id);
		if($account===null)
			throw new CHttpException(404,'Аккаунт с указанным ID не найден.');

		$this->render('account', array('account'=>$account, 'stat'=>$account->stat));
	}

	public function actionIndex()
	{
		if(empty(Yii::app()->user->account))
			throw new CHttpException(404,'Страница не найдена.');
		$account = Accounts::model()->findByPk(Yii::app()->user->id);
		
    if(isset($_POST['Accounts']))
    {
			$ban = Bans::model()->findByAttributes(['account_id'=>Yii::app()->user->id]);
			if ($ban)
				throw new CHttpException(403, Yii::t('wot', 'Access denied. You are banned until').' '.Yii::app()->dateFormatter->formatDateTime($ban->expire+Yii::app()->params["moscow"], 'long', 'short'));
			$account->name = $_POST['Accounts']['name'];
			$account->email = $_POST['Accounts']['email'];
			$account->skype = $_POST['Accounts']['skype'];
			$account->tel = $_POST['Accounts']['tel'];
			$account->about = $_POST['Accounts']['about'];
			$account->signature = $_POST['Accounts']['signature'];
			$account->city = $_POST['Accounts']['city'];
			
			if (isset($_POST['delete_avatar']))
			{
				@unlink('upload/avatar/'.$account->avatar);
				$account->avatar = '';
			}
			
			$file = CUploadedFile::getInstance($account,'avatar');
			if ($file)
			{
				$account->avatar = $account->nickname.'_'.time().'.'.$file->extensionName;
			}
			if ($account->save())
			{
				if ($file)
				{
					$file->saveAs('upload/avatar/'.$account->avatar);
				}
			}
    }

		$this->render('index', array('account'=>$account, 'stat'=>$account->stat));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}