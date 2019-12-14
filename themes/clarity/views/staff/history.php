<?php
/* @var $this SiteController */

$this->pageTitle=Yii::t('wot', 'Clan Log');

function parseValue($value)
{
	$value = @unserialize($value);
	if (!$value)
		return '';
	if ($value['event'] == 'join')
		return '<span class="joined-clan">'.CHtml::link($value['nickname'], array("/profile/account", "id"=>$value['id'])).Yii::t('wot', ' joined the clan </span>');
	return '<span class="left-clan">'.CHtml::link($value['nickname'], array("/profile/account", "id"=>$value['id'])).Yii::t('wot', ' left the clan </span>');
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

<div class="modul staff history">

	<div class="title">
		<h3><?php echo Yii::t('wot', 'Clan Log')?></h3>
	</div>
	<div class="cell">

		<? if (!$clans){ ?>
				<p><?php echo Yii::t('wot', 'To start listing the clan log')?> <?php echo CHtml::link(Yii::t('wot', 'add to you site'), array('/admin/clans/create')); ?> <?php echo Yii::t('wot', 'at least one clan in the Admin Panel')?></p>
			</div>
			</div>
		<? return;} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-nav'),
			'items'=>array(
				array('label'=>Yii::t('wot', 'Personnel'), 'url'=>array('/staff/members'), 'linkOptions'=>array('class'=>'members')),
				array('label'=>Yii::t('wot', 'Online'), 'url'=>array('/staff/attendance'), 'linkOptions'=>array('class'=>'online')),
				array('label'=>Yii::t('wot', 'Companies'), 'url'=>array('/staff/companies'), 'linkOptions'=>array('class'=>'companies'), 'visible'=>$this->site->premium_companies),
				array('label'=>Yii::t('wot', 'Battles'), 'url'=>array('/staff/battles'), 'linkOptions'=>array('class'=>'battles')),
				array('label'=>Yii::t('wot', 'Provinces'), 'url'=>array('/staff/provinces'), 'linkOptions'=>array('class'=>'provinces')),
				array('label'=>Yii::t('wot', 'Tactic Board'), 'url'=>'#', 'linkOptions'=>array('class'=>'tactic', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;'), 'visible'=>$this->site->premium_tactic),
				array('label'=>Yii::t('wot', 'Log'), 'url'=>array('/staff/history'), 'linkOptions'=>array('class'=>'history')),
//				array('label'=>Yii::t('wot', 'Армагеддон'), 'url'=>array('/staff/famepoints'), 'linkOptions'=>array('class'=>'famepoints')),
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
								'header'=>Yii::t('wot', 'Time'),
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
