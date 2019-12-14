<?php

class WPoll extends Widget {
	public $id;
	public $title = 'Опрос';
	
	public function init() {
		$this->read('id');
	}
	
	public function run() {
		if (!preg_match('=^\d+$=', $this->id))
		{
			echo '{Не указан идентификатор опроса}';
			return;
		}
		if (Yii::app()->request->getParam('poll_action') == 'poll' && Yii::app()->request->getParam('poll_id') == $this->id)
		{
			$this->answer();
		}
		
		$poll = Polls::model()->findByPk($this->id);
		if (!$poll)
		{
			echo '{Опрос с указанным идентификатором не найден в виджете POLL}';
			return;
		}
		
		$answer = [];
		if (Yii::app()->user->account)
			$answer = PollsAnswers::model()->findByAttributes(['poll_id'=>$poll->id, 'account_id'=>Yii::app()->user->account->id]);
		
		if ($this->title === false)
			$this->title = $poll->question;
		
		$this->render('index', ['poll' => $poll, 'answer'=>$answer]);
	}
	
	private function answer()
	{
		if (!Yii::app()->user->account)
			return;
		
		$poll = Polls::model()->findByPk(Yii::app()->request->getParam('poll_id'));
		if (!$poll || !$poll->enabled)
			return;
		if ($poll->access && !isset(Yii::app()->controller->clans[Yii::app()->user->account->clan_id]))
			return;
		
		$answer = PollsAnswers::model()->findByAttributes(['poll_id'=>$poll->id, 'account_id'=>Yii::app()->user->account->id]);
		if ($answer)
			return;
		
		if (!preg_match('=^\d+$=', Yii::app()->request->getParam('poll_answer')) || !isset($poll['answer'.Yii::app()->request->getParam('poll_answer')]) || $poll['answer'.Yii::app()->request->getParam('poll_answer')] == '')
			return;
		
		$answer = new PollsAnswers;
		$answer->poll_id = $poll->id;
		$answer->account_id = Yii::app()->user->account->id;
		$answer->answer = Yii::app()->request->getParam('poll_answer');
		$answer->save();
	}
	
}
