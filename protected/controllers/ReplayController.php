<?php

class ReplayController extends Controller
{
	public $layout = false;
	
	public function actionIndex($file)
	{
		if (!preg_match('=^\w{32}\.wotreplay$=', $file))
		{
			exit ('Неверное имя файла реплея');
		}
		$file = substr(Files::model()->link($file), 1);
		
		if (!file_exists($file))
		{
			exit ('Файл реплея не найден');
		}
		
		$parser = new replayParser($file);
		$data = $parser->parse();
		if (!$data)
			exit('Бой не доигран или реплей имеет неизвестный формат');
		
		$tpl = $this->render('index', array('parser'=>$parser, 'data'=>$data), true);
		if ($parser->error)
			echo 'Бой не доигран или реплей имеет неизвестный формат';
		else
			echo $tpl;
	}
}