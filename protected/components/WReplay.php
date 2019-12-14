<?php

class WReplay extends Widget {
	public $file = 'empty';

	public function init() {
		$this->read('file');
	}

	public function run()
	{
		if (!preg_match('=^\w{32}\.wotreplay$=', $this->file))
		{
			echo '{Неверное имя файла реплея}';
			return;
		}
		$file = substr(Files::model()->link($this->file), 1);
		
		if (!file_exists($file))
		{
			echo '{Файл реплея не найден}';
			return;
		}
		
		$parser = new replayParser($file);
		$data = $parser->parse();
		if (!$data)
		{
			echo '{Бой не доигран или реплей имеет неизвестный формат}';
			return;
		}
		$tpl = $this->render('WReplay', ['data'=>$data, 'file'=>$this->file, 'parser'=>$parser], true);
		if ($parser->error)
			echo '{Бой не доигран или реплей имеет неизвестный формат}';
		else
			echo $tpl;
	}
}
