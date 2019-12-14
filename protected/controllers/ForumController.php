<?php

class ForumController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array_merge(array(
			array('deny',  // deny all users
				'users'=>array('?'),
				'actions'=>array('new'),
			),
		),parent::accessRules());
	}

	public function actionOldCategory($id) //Редирект со старых урлов
	{
		$category = ForumCategories::model()->findByPk($id);
		if(!$category)
			throw new CHttpException(404,'Форум не найден.');
		$this->redirect(array('/forum/category', 'id'=>$category->id), true, 301);
	}

	public function actionOldTheme($id) //Редирект со старых урлов
	{
		$theme = ForumThemes::model()->findByPk($id);
		if(!$theme)
			throw new CHttpException(404,'Тема не найдена.');
		$this->redirect(array('/forum/theme', 'category_id'=>$theme->category_id, 'id'=>$theme->id), true, 301);
	}

	public function actionCategory($id)
	{
		$category = ForumCategories::model()->findByPk($id);
		if(!$category)
			throw new CHttpException(404,'Форум не найден.');
		$this->render('category', array('category'=>$category));
	}

	public function actionIndex()
	{
		$groups = ForumGroups::model()->findAll(array(
				'with'=>array(
					'categories'=>array(
						'with'=>array(
							'themesCount',
							'messagesCount',
						),
					),
				),
				'order'=>"t.order, categories.order",)
		);
		//if(!$groups)
		//	throw new CHttpException(404,'Нет ни одной группы форумов.');
		$this->render('index', array('groups'=>$groups));
	}

	public function actionNew($id)
	{
		$category = ForumCategories::model()->findByPk($id);
		if(!$category)
			throw new CHttpException(404,'Форум не найден.');
			
		$theme = new ForumThemes;
		$message = new ForumMessages;
		
		if (isset($_POST['ForumThemes']) && isset($_POST['ForumMessages']))
		{
			$ban = Bans::model()->findByAttributes(['account_id'=>Yii::app()->user->id]);
			if ($ban)
				throw new CHttpException(403, Yii::t('wot', 'Access denied. You are banned until').' '.Yii::app()->dateFormatter->formatDateTime($ban->expire+Yii::app()->params["moscow"], 'long', 'short'));
			$theme->attributes = $_POST['ForumThemes'];
			$theme->category_id = $category->id;
			$theme->account_id = Yii::app()->user->id;
			$theme->time = time();
			$theme->fixed = 0;
			$theme->closed = 0;
			
			$message->attributes = $_POST['ForumMessages'];
			$message->category_id = $category->id;
			$message->theme_id = 8; //Любая существующая тема
			$message->account_id = Yii::app()->user->id;
			$message->time = time();
			
			Yii::app()->params['skipSiteCheck'] = true;
			$tv = $theme->validate();
			$mv = $message->validate();
			Yii::app()->params['skipSiteCheck'] = false;
			
			if($tv && $mv)
			{
				$theme->save();
				$message->theme_id = $theme->id;
				$message->save();
				Yii::app()->controller->redirect(array('/forum/theme', 'category_id'=>$theme->category_id, 'id'=>$theme->id));
			}
		}
		
		$this->render('new', array(
			'theme'=>$theme,
			'message'=>$message,
		));
	}

	public function actionTheme($category_id, $id)
	{
		$theme = ForumThemes::model()->findByPk($id);
		if(!$theme)
			throw new CHttpException(404,'Тема не найдена.');
		if($theme->category_id != $category_id)
			throw new CHttpException(404,'Тема не найдена.');

		if ($theme->category_id == '5557' && Yii::app()->user->id != $theme->account_id && !Yii::app()->user->checkAccess('1'))
		{
			throw new CHttpException(403,'Эта тема доступна только ее создателю и администраторам сайта.');
		}
		
		if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8'))
		{
			if (Yii::app()->request->getPost('action') == 'close')
			{
				$theme->closed = 1;
				$theme->save();
			}
			if (Yii::app()->request->getPost('action') == 'open')
			{
				$theme->closed = 0;
				$theme->save();
			}
			if (Yii::app()->request->getPost('action') == 'fix')
			{
				$theme->fixed = 1;
				$theme->save();
			}
			if (Yii::app()->request->getPost('action') == 'unfix')
			{
				$theme->fixed = 0;
				$theme->save();
			}
		}
		
		//Удалить сообщение
		if (Yii::app()->user->account && Yii::app()->request->getParam('action') == 'delete' && Yii::app()->request->getParam('message_id', 0) != 0)
		{
			$message = ForumMessages::model()->findByPk(Yii::app()->request->getParam('message_id'));
			if ($message && (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')))
			{
				$message->delete();
			}
		}
		
		//Редактировать сообщение
		$edit = false;
		if (Yii::app()->user->account && !empty($_POST['ForumMessages']['id']))
		{
			$message = ForumMessages::model()->findByPk($_POST['ForumMessages']['id']);
			if ($message && (Yii::app()->user->id == $message->account_id || Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')))
				$edit = true;
		}
		
		if (!$edit)
			$message = new ForumMessages;
		if(!$theme->closed && Yii::app()->user->account && isset($_POST['ForumMessages']))
		{
			$ban = Bans::model()->findByAttributes(['account_id'=>Yii::app()->user->id]);
			if ($ban)
				throw new CHttpException(403, Yii::t('wot', 'Access denied. You are banned until').' '.Yii::app()->dateFormatter->formatDateTime($ban->expire+Yii::app()->params["moscow"], 'long', 'short'));
			if ($edit)
			{
				$message->message = $_POST['ForumMessages']['message'];
				$message->updated_account_id = Yii::app()->user->id;
				$message->updated_time = time();
			}
			else
			{
				$message->attributes = $_POST['ForumMessages'];
				$message->category_id = $theme->category->id;
				$message->theme_id = $id;
				$message->account_id = Yii::app()->user->id;
				$message->time = time();
			}
			if($message->validate())
			{
				$message->save();
				if (!$edit)
				{
					$theme->time=time();
					$theme->save();
				}
				Yii::app()->getController()->refresh(true, '#message'.$message->id);
			}
		}
		
		$this->render('theme', array('theme'=>$theme,'message'=>$message));
	}

	public function actionGetMessage($id)
	{
		$result = array('status'=>false, 'data'=>array());
		if (!Yii::app()->user->account)
			exit(json_encode($result));
		$message = ForumMessages::model()->findByPk($id);
		if (!$message)
			exit(json_encode($result));
		$result['status'] = true;
		$result['data']['message'] = $message->message;
		$result['data']['author'] = $message->account->nickname;
		$result['data']['time'] = date('d.m.Y - H:i', $message->time+Yii::app()->params['moscow']);
		exit(json_encode($result));
	}
}