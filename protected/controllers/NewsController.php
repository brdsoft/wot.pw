<?php

class NewsController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionOne($id)
	{
		Yii::app()->params['skipSiteCheck'] = true;
		$criteria=new CDbCriteria;
		$criteria->with = array('category');
		$criteria->compare('`t`.`site_id`',$this->site->id);
		$criteria->addCondition('`t`.`site_id` = 168 AND `t`.`category_id` = 2', 'OR');
		$new=News::model()->findByPk($id, $criteria);
		Yii::app()->params['skipSiteCheck'] = false;
		
		if($new===null)
			throw new CHttpException(404,'Страница не найдена.');
		$formatter = new CFormatter;
		$new->text1 = $formatter->text($new->text1);
		//$page->text2 = $formatter->html($page->text2);
		$this->render('one', array('new'=>$new));
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