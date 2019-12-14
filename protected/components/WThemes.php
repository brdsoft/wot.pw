<?php
class WThemes extends Widget
{
	public $title = 'Активные темы';
	public $cnt = 5;
	
	public function init() {
		$this->read(['cnt']);

		$this->cnt = intval($this->cnt);

		if ($this->cnt < 1) $this->cnt = 1;
		if ($this->cnt > 50) $this->cnt = 50;
	}

	public function run()
	{
		$criteria = new CDbCriteria;
		$criteria->order = "`time` DESC";
		$criteria->limit = $this->cnt;
		
		$themes=new CActiveDataProvider('ForumThemes', array(
			'pagination'=>false,
			'criteria'=>$criteria,
		));
		$this->render('wThemes', array('themes'=>$themes));
	}
}
?>