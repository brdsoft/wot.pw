<?php

class WVK extends Widget {
	public $width = 240;
	public $height = 400;
	public $color1 = 'FFFFFF';
	public $color2 = '2B587A';
	public $color3 = '5B7FA6';
	public $id = '87826724';
	public $title = 'ВКонтакте';

	private static $divid = 0;

	public function init() {
		$this->read(['width', 'height', 'color1', 'color2', 'color3', 'id']);
		self::$divid++;
	}

	public function run() {
		$this->render('index');
	}

	public function divid() {
		return self::$divid;
	}
}
