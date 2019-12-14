<?php

class IndexController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

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
				'roles'=>array('0','1','2','3','4','5','8'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
}