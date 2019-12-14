<?php

class CustomController extends Controller
{
	public function actionIndex($id)
	{
		$this->layout = '//layouts/custom';
		$page=Customs::model()->findByAttributes(array('name'=>$id));
		if($page===null)
			throw new CHttpException(404,'Страница не найдена.');
		$formatter = new Formatter;
		$page->text1 = $formatter->text($page->text1);
		$page->text2 = $formatter->whtml($page->text2);
		$this->render('index', array('page'=>$page));
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