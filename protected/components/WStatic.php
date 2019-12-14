<?php

class WStatic extends Widget {
	public $name = 'textonmain';
	public $title = false;

	public function init() {
		$this->read('name');
	}

	public function run() {
		$static = Pages::model()->cache(3600, new CExpressionDependency('Yii::app()->cache->get("textOnMain")'))->findByAttributes(array('name'=>$this->name));
		if (!$static)
		{
			echo '{Неправильно указан параметр "name" в виджете STATIC}';
			return;
		}
		if ($this->title === false)
			$this->title = $static->text1;

		$this->render('index', ['static' => $static]);
	}
}
