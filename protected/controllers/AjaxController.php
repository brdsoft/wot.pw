<?php

class AjaxController extends Controller
{
	public function actionAnswerNotice()
	{
		$result = array('status'=>0, 'data'=>'');
		if (Yii::app()->user->isGuest)
		{
			exit(json_encode($result));
		}
		if (!Yii::app()->request->getParam('id') || !Yii::app()->request->getParam('answer'))
		{
			exit(json_encode($result));
		}
		
		if (!array_key_exists(Yii::app()->request->getParam('answer'), Notices::model()->answersAllowed))
		{
			exit(json_encode($result));
		}
		
		if (!NoticesRecipients::model()->updateByPk(Yii::app()->request->getParam('id'), array('answer'=>Yii::app()->request->getParam('answer')), array('condition'=>"`account_id` = '".Yii::app()->user->id."' AND `answer` = '0'")))
		{
			exit(json_encode($result));
		}
		
		$result['status'] = 1;
		exit(json_encode($result));
		
	}
}