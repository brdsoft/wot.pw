<?php
class WMessages extends CWidget
{
	public $name;
	public $object_id;
	public $site_id = false;
	public function run()
	{
		//Если не передан ИД сайта комментируемого объекта, сделать таковым текущий сайт
		if (!$this->site_id)
		{
			$this->site_id = Yii::app()->controller->site->id;
		}
		
		if (Yii::app()->user->account && Yii::app()->request->getParam('action') == 'delete' && Yii::app()->request->getParam('message_id', 0) != 0)
		{
			$message = Messages::model()->findByPk(Yii::app()->request->getParam('message_id'));
			if ($message && (Yii::app()->user->id == $message->account_id || Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')))
			{
				$message->delete();
			}
		}
		
		$message = new Messages;
		if(Yii::app()->user->account && isset($_POST['Messages']))
		{
			$ban = Bans::model()->findByAttributes(['account_id'=>Yii::app()->user->id]);
			if ($ban)
				throw new CHttpException(403, Yii::t('wot', 'Access denied. You are banned until').' '.Yii::app()->dateFormatter->formatDateTime($ban->expire+Yii::app()->params["moscow"], 'long', 'short'));
			$message->attributes = $_POST['Messages'];
			$message->account_id = Yii::app()->user->id;
			$message->name = $this->name;
			$message->object_id = $this->object_id;
			$message->time = time();
			if($message->validate())
			{
				$message->save();
				Yii::app()->getController()->refresh(true, '#message'.$message->id);
			}
		}
		
		$this->render('wMessages', array(
			'message'=>$message,
			'name'=>$this->name,
			'object_id'=>$this->object_id,
			'site_id'=>$this->site_id,
		));
	}
}
?>