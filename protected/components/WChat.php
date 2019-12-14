<?php

class WChat extends Widget {
	public $title = 'Чат';
	public $height = 400;

	public function init() {
		$this->read('height');

		$this->height = intval($this->height);
	}

	public function run() {
		$this->render('index');
	}
}
