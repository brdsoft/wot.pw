<?php

class CssController extends Controller
{
	public function actionIndex($style)
	{
		$style = "/*------------------------------------------
	Пользовательские стили
------------------------------------------*/

".$this->site->css;
		
		header('Content-Type: text/css; charset=utf-8');
		header("Expires: ".gmdate("D, d M Y H:i:s", time()+86400*365)." GMT");
		header("Cache-Control: max-age=".(86400*365).', must-revalidate');
		header("Pragma: cache");
		
		echo $style;
		Yii::app()->end();
	}
}
