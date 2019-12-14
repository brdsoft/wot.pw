<?php

class SitesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $defaultAction = 'update';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array('0', '1'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel($this->site->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sites']))
		{
			$model->css=isset($_POST['Sites']['css']) ? $_POST['Sites']['css'] : '';
			$model->css_disabled=isset($_POST['Sites']['css_disabled']) ? $_POST['Sites']['css_disabled'] : 0;
			$model->header=isset($_POST['Sites']['header']) && $this->site->premium_html ? $_POST['Sites']['header'] : '';
			$model->footer=isset($_POST['Sites']['footer']) && $this->site->premium_html ? $_POST['Sites']['footer'] : '';
			$model->sidebar=isset($_POST['Sites']['sidebar']) ? $_POST['Sites']['sidebar'] : '';
			$model->index=isset($_POST['Sites']['index']) ? $_POST['Sites']['index'] : '';
			$model->news=isset($_POST['Sites']['news']) ? $_POST['Sites']['news'] : '';
			$model->favicon=isset($_POST['Sites']['favicon']) ? $_POST['Sites']['favicon'] : '';
			$model->time_modified=time();
			
			if (isset($_POST['Sites']['style_id']) && $model->style_id != $_POST['Sites']['style_id'])
			{
				$model->css='';
				$model->css_disabled=0;
				$model->header='';
				$model->footer='';
			}
			$model->style_id=isset($_POST['Sites']['style_id']) ? $_POST['Sites']['style_id'] : 1;
			
			if($model->save())
				$this->refresh();
		}
		
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sites the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Sites::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sites $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sites-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
