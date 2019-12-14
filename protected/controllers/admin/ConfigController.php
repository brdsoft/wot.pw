<?php

class ConfigController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $defaultAction = 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + deleteSite', // we only allow deletion via POST request
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Config']['value']))
		{
			$model->value=$_POST['Config']['value'];
			if ($model->type == 'checkbox')
			{
				if (is_array($model->value))
					$model->value = implode(',', $model->value);
				else
					$model->value = '';
			}
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$site=$this->site;
		if(isset($_POST['Sites']))
		{
			if ((isset($_POST['Sites']['language']) && $site->language != $_POST['Sites']['language']) || !empty($_POST['Sites']['reset']))
			{
				$site->css='';
				$site->css_disabled=0;
				$site->header='';
				$site->footer='';
				$site->sidebar='';
				$site->index='';
				$site->news='';
				$site->reset=1;
			}
			$site->language=isset($_POST['Sites']['language']) && isset(Yii::app()->params['languages'][$_POST['Sites']['language']]) ? $_POST['Sites']['language'] : 'ru';

			if($site->save())
				$this->refresh();
		}
		
		$model=new Config('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Config']))
			$model->attributes=$_GET['Config'];

		$this->render('admin',array(
			'site'=>$site,
			'model'=>$model,
		));
	}

	public function actionDeleteSite()
	{
		if (!Yii::app()->user->checkAccess('0'))
			throw new CHttpException(403,'Удалить сайт может только владелец');
		Sites::model()->deleteByPk($this->site->id);
		Yii::app()->cache->flush();
		Yii::app()->end('Сайт удален. Создать новый сайт можно <a href="http://wot.pw">тут</a>');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Config the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Config::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Config $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='config-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
