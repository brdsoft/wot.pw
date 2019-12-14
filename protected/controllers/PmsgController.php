<?php

class PmsgController extends Controller
{
	public function actionCompleteNick()
	{
		$term = Yii::app()->request->getQuery('term');
		$nicks = Accounts::model()->complete($term);
		$this->jsonResponse($nicks);
	}

	public function actionPrepare()
	{
		/** @var WebUser $user */
		$user = Yii::app()->user;

		if ($user->isGuest) {
			throw new CHttpException(403, 'not authorized');
		}

		$nick = Yii::app()->request->getPost('nick');

		$speaker = $user->getAccount();
		$listener = Accounts::model()->findByAttributes(['nickname' => $nick]);

		if ($listener) {
			$talk_hash = Pmsg::createDialog($speaker, $listener);

			$this->jsonResponse([
				'error' => 'ok',
				'talk_hash' => $talk_hash,
				'last_messages' => Pmsg::model()->lastMessages($talk_hash),
				'avatars' => [
					$listener->nickname => $listener->getAvatar(),
					$speaker->nickname => $speaker->getAvatar()
				]
			]);
		}

		$this->jsonResponse([
			'error' => 'account not found'
		]);
	}

	public function actionThumbs()
	{
		/** @var WebUser $user */
		$user = Yii::app()->user;

		if ($user->isGuest) {
			throw new CHttpException(403, 'not authorized');
		}

		$this->jsonResponse(PmsgThumbs::model()->getUserThumbs($user->getAccount()->id));
	}

	public function actionDelThumb()
	{
		/** @var WebUser $user */
		$user = Yii::app()->user;

		if ($user->isGuest) {
			throw new CHttpException(403, 'not authorized');
		}

		$thumb = PmsgThumbs::model()->findByAttributes([
			'account_id' => $user->getAccount()->id,
			'talk_hash' => Yii::app()->request->getPost('talk_hash')
		]);

		$thumb->delete();

		$this->jsonResponse(['error' => 'ok']);
	}

	public function actionLoad()
	{
		/** @var WebUser $user */
		$user = Yii::app()->user;

		if ($user->isGuest) {
			throw new CHttpException(403, 'not authorized');
		}

		$talk_hash = Yii::app()->request->getQuery('hash');


		$criteria = new CDbCriteria;

		$criteria->addColumnCondition(['talk_hash' => $talk_hash]);
		$criteria->join = 'join `accounts` a on a.id = cc.account_id';
		$criteria->alias = 'cc';
		$criteria->select = ['a.id `id`', 'a.nickname', 'a.avatar'];

		$cc = PmsgCc::model();

		$subscribers = $cc->getCommandBuilder()->createFindCommand($cc->tableName(), $criteria)->queryAll();

		$account_id = $user->getAccount()->id;

		$found = false;

		$avatars = [];

		foreach ($subscribers as $subscriber) {
			if ($subscriber['id'] == $account_id) {
				$found = true;
			}

			$avatars[$subscriber['nickname']] = $subscriber['avatar'] ?: Accounts::DEFAULT_AVATAR;
		}

		if (!$found) {
			throw new CHttpException(403, 'not subscribed');
		}

		$last = PmsgCc::visit($talk_hash, $account_id);

		$this->jsonResponse([
			'error' => 'ok',
			'talk_hash' => $talk_hash,
			'last_messages' => Pmsg::model()->lastMessages($talk_hash),
			'avatars' => $avatars,
			'last_visit' => $last
		]);
	}

	public function actionLoadMore()
	{
		$time = intval(Yii::app()->request->getQuery('time'));
		$talk_hash = Yii::app()->request->getQuery('talk_hash');

		if (PmsgCc::model()->findByAttributes(['talk_hash' => $talk_hash, 'account_id' => Yii::app()->user->getAccount()->id])) {
			$this->jsonResponse([
				'error' => 'ok',
				'messages' => Pmsg::model()->lastMessages($talk_hash, $time)
			]);
		}

		$this->jsonResponse([
			'error' => 'no subscription'
		]);
	}

	public function actionVisit()
	{
		/** @var WebUser $user */
		$user = Yii::app()->user;

		if ($user->isGuest) {
			throw new CHttpException(403, 'not authorized');
		}

		$talk_hash = Yii::app()->request->getPost('hash');

		PmsgCc::visit($talk_hash, $user->getAccount()->id);

		$this->jsonResponse(['error' => 'ok']);
	}
}
