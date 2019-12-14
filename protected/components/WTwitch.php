<?php

class WTwitch extends Widget {
	public $title = 'Twitch';
	public $chan = 'xbox';

	public function init() {
		$this->read('chan');
	}

	public function run() {
		$this->render('index');
	}
}
