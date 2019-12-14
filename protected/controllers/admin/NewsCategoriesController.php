<?php

class NewsCategoriesController extends Controller
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
		$model=new NewsCategories;

		if(isset($_POST['NewsCategories']))
		{
			$model->attributes=$_POST['NewsCategories'];
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
		if ($model->site_id != $this->site->id)
			throw new CHttpException(403,'Доступ запрещен.');

		if(isset($_POST['NewsCategories']))
		{
			$model->attributes=$_POST['NewsCategories'];
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
		$model=new NewsCategories('search');
		$model->unsetAttributes();
		if(isset($_GET['NewsCategories']))
			$model->attributes=$_GET['NewsCategories'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=NewsCategories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='news-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
