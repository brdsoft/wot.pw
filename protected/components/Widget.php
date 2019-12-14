<?php

class Widget extends CWidget {
	public $title = 'Виджет';
	public $params = [];

	/* public function render($view, $data = null, $return = false) {
		if ($return) return Yii::app()->format->whtml(parent::render($view, $data, $return));

		ob_start();
		ob_implicit_flush(false);
		$result = parent::render($view, $data, $return);
		echo Yii::app()->format->whtml(ob_get_clean());
		return $result;
	} */

	public function init() {
		$this->read();
	}

	protected function read($params = []) {
		if (!is_array($params)) $params = [$params];
		$params[] = 'title';

		foreach ($params as $param)
			if (isset($this->params[$param])) $this->{$param} = $this->params[$param];
	}
}
