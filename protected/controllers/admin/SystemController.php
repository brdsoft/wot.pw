<?php

class SystemController extends Controller
{
	public $defaultAction = 'admin';

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
				'users'=>array('7208418', '11944945', '536301', '4388762', '59963605'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionAddConfig()
	{
		Yii::app()->params['skipSiteCheck'] = true;
		$model=new Config();
		
    if(isset($_POST['Config']))
    {
			$model->attributes=$_POST['Config'];
			if($model->validate())
			{
				$sites = Sites::model()->findAll();
				foreach ($sites as $value)
				{
					$config = new Config();
					$config->attributes = $model->attributes;
					$config->site_id = $value->id;
					$config->save();
				}
				$this->redirect(array('/admin/config'));
			}
    }
		
		$this->render('addConfig',array(
			'model'=>$model,
		));
	}

	public function actionDeleteConfig()
	{
		Yii::app()->params['skipSiteCheck'] = true;
		$model=new Config();
		
    if(isset($_POST['Config']))
    {
			Config::model()->deleteAllByAttributes(array(
				'name'=>$_POST['Config']['name'],
			));
			$this->redirect(array('/admin/config'));
    }
		
		$this->render('deleteConfig',array(
			'model'=>$model,
		));
	}

	public function actionAddNotice()
	{
		Yii::app()->params['skipSiteCheck'] = true;
		$model=new Notices;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notices']))
		{
			$model->attributes=$_POST['Notices'];
			$model->time = time();
			$model->account_id = Yii::app()->user->id;
			$model->site_id = 168;
			$model->clans = array('1');
			$model->recipients = array('commander');
			$model->answers = array('1');
			if($model->save())
			{
				$model->addRecipients2($model->id);
				$this->redirect(array('/admin'));
			}
		}

		$this->render('addNotice',array(
			'model'=>$model,
		));
	}

	public function actionAddNoticeAll()
	{
		$model=new NoticesAll;

		if(isset($_POST['NoticesAll']))
		{
			$model->attributes=$_POST['NoticesAll'];
			$model->time = time();
			if($model->save())
			{
				$this->redirect(array('/admin'));
			}
		}

		$this->render('addNoticeAll',array(
			'model'=>$model,
		));
	}

	public function actionTsOnline()
	{
		Yii::app()->params['skipSiteCheck'] = true;
		header('Content-type: text/plain; charset=utf-8');
		$stat = TsStat::model()->findAll(['select'=>"`time`, SUM(`online`) `online`, SUM(`amount`) `amount`", 'order'=>"`time` DESC", 'group'=>'`time`']);
		echo "Дата\t\tСлоты\tСумма\n\n";
		foreach ($stat as $value)
		{
			echo date('d.m.Y', $value->time)."\t".$value->online."\t".$value->amount."\n";
		}
	}
}
