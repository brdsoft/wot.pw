<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.05.2016
 * Time: 18:12
 */
class RecruitmentController extends Controller
{
    public $defaultAction = 'admin';
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete, deleteAll', // we only allow deletion via POST request
        );
    }
    public function accessRules()
    {
        return array(
            array('allow',
                'roles'=>array('0','1','8','4'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    public function actionDeleteAll()
    {
        Recruitment::model()->deleteAllByAttributes(['site_id'=>Yii::app()->controller->site->id]);
        $this->redirect(['admin']);
    }
    public function actionAdmin()
    {
        $model=new Recruitment('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Recruitment']))
            $model->attributes=$_GET['Recruitment'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }
    public function loadModel($id)
    {
        $model=Recruitment::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}