<?php

class FilesController extends Controller
{
	public $layout = false;
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + upload',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$model = new Files;
		$model->target = Yii::app()->request->getParam('target', false);
		$this->render('index', array(
			'model'=>$model,
			'id'=>Yii::app()->request->getParam('id', false),
		));
	}

	public function actionUpload()
	{
		$model = new Files;
		$model->target = Yii::app()->request->getParam('target', false);
		$result = array('status'=>0, 'data'=>$model->errors);
		if(isset($_POST['YII_CSRF_TOKEN']))
		{
			$model->account_id = Yii::app()->user->id;
			$model->time = time();
			$model->file = CUploadedFile::getInstance($model,'file');
			if ($model->file)
			{
				$model->size = $model->file->size;
				$model->name = $model->randomName.'.'.$model->file->extensionName;
			}
			if($model->validate())
			{
				$model->createDir();
				if ($model->file->saveAs($model->path))
				{
					if ($model->process())
					{
						$result['status'] = 1;
						$result['data']['url'] = 'http://'.$_SERVER['SERVER_NAME'].$model->url;
						$result['data']['extension'] = $model->file->extensionName;
						$model->md5 = md5(file_get_contents($model->path));
						$model->save();
					}
					else
						$result['data'] = array(array('Загруженный файл не является изображением.'));
				}
				else
					$result['data'] = array(array('Не удалось сохранить файл.'));
			}
			else
			{
				$result['data'] = $model->errors;
			}
		}
		exit(json_encode($result));
	}

	public function actionList()
	{
		$this->render('list');
	}
}