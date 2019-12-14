<?php

class CompaniesController extends Controller
{
	public $defaultAction = 'admin';

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array('0','1','8'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionCreate()
	{
		$model=new Companies;

		if(isset($_POST['Companies']))
		{
			$model->attributes=$_POST['Companies'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Companies']))
		{
			$model->attributes=$_POST['Companies'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionAdmin()
	{
		if(!$this->site->premium_companies)
			throw new CHttpException(403,'Доступ к ротам запрещен.');
		$model=new Companies('search');
		$model->unsetAttributes();
		if(isset($_GET['Companies']))
			$model->attributes=$_GET['Companies'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Companies::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='companies-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionGetAccounts($clan_id, $company_id)
	{
		$result = array('status'=>0, 'data'=>array());
		
		$clan = Clans::model()->findByPk($clan_id);
		if (!$clan)
		{
			exit(json_encode($result));
		}
		
		$accounts = Accounts::model()->findAllByAttributes(array('clan_id'=>$clan_id), array('order'=>"`nickname`"));
		if (!$accounts)
		{
			exit(json_encode($result));
		}
		if ($company_id)
		{
			$company = Companies::model()->findByAttributes(array('id'=>$company_id));
			if ($company)
			{
				$company->accounts = explode(',', $company->accounts);
			}
		}
		
		$others = Companies::model()->findAllByAttributes(array('clan_id'=>$clan_id), array('condition'=>isset($company) ? "`id` != '{$company_id}'" : ""));
		$exclude = array();
		foreach ($others as $value)
		{
			$exclude[] = explode(',', $value->accounts);
		}
		
		foreach($accounts as $value)
		{
			foreach($exclude as $ex)
			{
				if (in_array($value->id, $ex))
					continue(2);
			}
			if (isset($company) && in_array($value->id, $company->accounts))
				$result['data'][] = array('id'=>$value->id, 'nickname'=>$value->nickname, 'checked'=>true);
			else
				$result['data'][] = array('id'=>$value->id, 'nickname'=>$value->nickname, 'checked'=>false);
		}
		$result['status'] = 1;
		exit(json_encode($result));
	}
}
