<?php
/* @var $this SiteController */

$this->pageTitle='История игроков клана';Config::all('staff_members_title')->value;

function parseValue($value)
{
	$value = @unserialize($value);
	if (!$value)
		return '';
	if ($value['event'] == 'join')
		return '<span style="color: #090">'.CHtml::link($value['nickname'], array("/profile/account", "id"=>$value['id'])).' вступил в клан</span>';
	return '<span style="color: #f00">'.CHtml::link($value['nickname'], array("/profile/account", "id"=>$value['id'])).' покинул клан</span>';
}

function parseDate($date)
{
	static $odate;
	if (date("d.m.y", $odate+3600*4) != date("d.m.y", $date+3600*4))
	{
		$odate = $date;
	}
	else
		return '';
	return date("d.m.y", $date+3600*4);
}

?>


	<div class="block">
		<div class="head head2">
			История игроков клана<?php //echo Config::all('staff_members_title')->value ?>
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history'), array('class'=>'active')) ?>
			<?php //echo CHtml::link('3 Кампания', array('/staff/famepoints')) ?>
			<div class="both"></div>
		</div>
		<? if (!$clans){ ?>
			<div class="body body2">
				Для отображения истории <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке
			</div>
			</div>
		<? return;} ?>
		<div class="head head3">
			<? foreach ($clans as $key=>$clan){
					if ($clan_id == $clan->id)
						$active = $key;
			?>
				<?php echo CHtml::link($clan->abbreviation, array('/staff/history', 'id'=>$clan->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan_id == $clan->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<div class="clan-grid">
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
						'summaryText'=> false,//$leader ? 'Командир: '.CHtml::link($leader->nickname, array("/profile/account", "id"=>$leader->id)).'. Личный состав (<a target="_blank" href="http://worldoftanks.ru/community/clans/'.$clans[$active]->id.'-'.$clans[$active]->abbreviation.'/">{count}</a>)' : false,
				));
				?>
			</div>
		</div>
	</div>
