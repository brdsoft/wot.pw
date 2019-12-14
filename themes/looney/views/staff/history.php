<?php
/* @var $this SiteController */

$this->pageTitle='История игроков клана';

function parseValue($value)
{
	$value = @unserialize($value);
	if (!$value)
		return '';
	if ($value['event'] == 'join')
		return '<span class="joined-clan">'.CHtml::link($value['nickname'], array("/profile/account", "id"=>$value['id'])).' вступил в клан</span>';
	return '<span class="left-clan">'.CHtml::link($value['nickname'], array("/profile/account", "id"=>$value['id'])).' покинул клан</span>';
}

function parseDate($date)
{
	static $odate;
	if (date("d.m.y", $odate+Yii::app()->params['moscow']) != date("d.m.y", $date+Yii::app()->params['moscow']))
	{
		$odate = $date;
	}
	else
		return '';
	return date("d.m.y", $date+Yii::app()->params['moscow']);
}

?>

<div class="modul staff">

	<div class="custom-title">
		<h4>История игроков клана</h4>
	</div>
	<div class="pastic">

		<? if (!$clans){ ?>
				<p>Для отображения истории <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке</p>
			</div>
			</div>
		<? return;} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-subnav'),
			'items'=>array(
				array('label'=>'Состав', 'url'=>array('/staff/members'), 'linkOptions'=>array('class'=>'reserve')),
				array('label'=>'Онлайн', 'url'=>array('/staff/attendance'), 'linkOptions'=>array('class'=>'online')),
				array('label'=>'Роты', 'url'=>array('/staff/companies'), 'linkOptions'=>array('class'=>'companies'), 'visible'=>$this->site->premium_companies),
				array('label'=>'Бои', 'url'=>array('/staff/battles'), 'linkOptions'=>array('class'=>'battles')),
				array('label'=>'Провинции', 'url'=>array('/staff/provinces'), 'linkOptions'=>array('class'=>'provinces')),
				array('label'=>'Планшет', 'url'=>'#', 'linkOptions'=>array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;'), 'visible'=>$this->site->premium_tactic),
				array('label'=>'История игроков', 'url'=>array('/staff/history'), 'linkOptions'=>array('class'=>'editing')),
//				array('label'=>'Армагеддон', 'url'=>array('/staff/famepoints'), 'linkOptions'=>array('class'=>'victory-points')),
			),
		)); ?>
		
		<?
			$items = array();
			foreach ($clans as $key=>$clan){
				if ($clan_id == $clan->id)
					$active = $key;
				$items[] = array('active'=> $clan_id == $clan->id ? true : false, 'label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/history', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>
		<div class="table-data">
			<?
			$dataProvider=new CActiveDataProvider('Events', array(
				'criteria'=>array(
					'condition'=>"`clan_id` = '{$clan_id}' AND `type` = '1'",
				),
				'sort'=>array(
					'defaultOrder'=>"`id` DESC",
				),
				'pagination'=>array(
					'pageSize'=>100,
				),
			));
			$this->widget('zii.widgets.grid.CGridView', array(
				'dataProvider'=>$dataProvider,
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'columns'=>array(
						array(
								'name'=>'time',
								'value'=>'parseDate($data->time)',
								'header'=>'Дата',
						),
						array(
								'type'=>'raw',
								'name'=>'event',
								'value'=>'parseValue($data->event)',
						),
				),
				'summaryText'=> false,
			));
			?>
		</div>
	</div>
</div>
