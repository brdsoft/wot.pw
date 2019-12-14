<?php
class WNews extends Widget
{
	public $title = 'Новости';
	public $cnt = 10;
	public $ids = [];
	public $spec_ids = [2,3,4];
	public $type = 'default';

	public function init() {
		$this->read(['cnt', 'ids', 'type']);

		$this->cnt = intval($this->cnt);

		if ($this->cnt < 1) $this->cnt = 1;
		if ($this->cnt > 50) $this->cnt = 50;

		if (!is_array($this->ids)) {
			$this->ids = explode(',', $this->ids);
			foreach ($this->ids as $idkey => $idval) {
				$this->ids[$idkey] = intval(trim($idval));
				if ($this->ids[$idkey] < 1) $this->ids[$idkey] = 1;
			}
		}
	}

	public function run()
	{
		Yii::app()->params['skipSiteCheck'] = true;

		$criteria=new CDbCriteria;
		$criteria->with = array('category');
		$criteria->order = "`time` DESC";
		$criteria->compare("`t`.`site_id`",Yii::app()->controller->site->id);
		if ($this->type == 'mini')
			$criteria->limit = $this->cnt;

		$ids_show = array_diff($this->ids, $this->spec_ids);
		$spec_ids_show = array_intersect($this->spec_ids, $this->ids);

		if (!empty($this->ids))
		{
			if (!empty($ids_show))
				$criteria->addCondition("`t`.`category_id` IN (".implode(',', $ids_show).")");
			else
				$criteria->addCondition("`t`.`category_id` = '0'");
			if (!empty($spec_ids_show))
				$criteria->addCondition("`t`.`site_id` = '168' AND `t`.`category_id` IN (".implode(',', $spec_ids_show).")", 'OR');
		}
		else
			$criteria->addCondition("`t`.`site_id` = '168' AND `t`.`category_id` IN (".implode(',', $this->spec_ids).")", 'OR');

		$news=new CActiveDataProvider('News', array(
			'pagination'=>$this->type == 'mini' ? false : array('pageSize'=> $this->cnt),
			'criteria'=>$criteria,
		));
		if ($this->type == 'mini')
			$this->render('wNewsMini', array('news'=>$news));
		else
			$this->render('wNews', array('news'=>$news));
		Yii::app()->params['skipSiteCheck'] = false;
	}
}
?>