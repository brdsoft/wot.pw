<?php

class SiteController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@'),
				'actions'=>array('authByToken'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
				'actions'=>array('authByToken'),
			),
		);
	}
	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
				'layout'=>false,
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$textonmain = Pages::model()->cache(3600, new CExpressionDependency('Yii::app()->cache->get("textOnMain")'))->findByAttributes(array('name'=>'textonmain'));
		if ($textonmain)
		{
			$formatter = new Formatter;
			$textonmain->text1 = $formatter->text($textonmain->text1);
			$textonmain->text2 = $formatter->mhtml($textonmain->text2);
		}
		$this->render('index', array('textonmain'=>$textonmain));
	}

	public function actionStat()
	{
		$this->layout = '//layouts/stat';
		$this->render('stat');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
	public function actionAuthByToken($url)
	{
		Yii::app()->params['skipSiteCheck'] = true;
		
		$account = Yii::app()->user->account;
		if (empty($account->clan_id))
			exit('Доступ имеют только пользователи, имеющий сайт в системе WoT.pw');
		
		$clan = Clans::model()->findByPk($account->clan_id);
		if (empty($clan))
			exit('Доступ  имеют только пользователи, имеющий сайт в системе WoT.pw');
		
		$site = Sites::model()->findByPk($clan->site_id);
		if (empty($site))
			exit('Доступ имеют только пользователи, имеющий сайт в системе WoT.pw');
		
		$token = new Tokens;
		$token->id = md5(microtime(1).'_'.mt_rand());
		$token->time = time();
		$token->account_id = $account->id;
		$token->save();
		
		$this->redirect('http://'.$site->url.$url.'?token='.$token->id);
	}

}