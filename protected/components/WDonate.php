<?php

class WDonate extends Widget {
	public $balance = '1';
	public $title = 'Помощь сайту';

	public function init() {
		$this->read('balance');
	}

	public function run() {
		if ($this->controller->nodeData[0]['clan_on_site'])
			$this->render('index');
	}
}
