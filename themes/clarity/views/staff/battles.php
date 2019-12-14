<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_battles_title')->value;

function getProvinces($provinces)
{
	$provinces = explode(',', $provinces);
	$result = array();
	foreach ($provinces as $value)
	{
		$province = Provinces::model()->findByPk($value);
		if ($province)
			$name = $province->name;
		else
			$name = $value;
		$result[] = '<a target="_blank" href="http://worldoftanks.ru/clanwars/maps/eventmap/?province='.$value.'">'.$name.'</a>';
	}
	return implode(', ', $result);
}

?>

<div class="modul staff battles">

	<div class="title">
		<h3><?php echo Config::all('staff_battles_title')->value ?></h3>
	</div>
	<div class="cell">
	
		<? if (!$clans){ ?>
				<p><?php echo Yii::t('wot', 'To start listing your battles')?> <?php echo CHtml::link(Yii::t('wot', 'add to you site'), array('/admin/clans/create')); ?> <?php echo Yii::t('wot', 'at least one clan in the Admin Panel')?></p>
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
				$items[] = array('label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/battles', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'), 'itemOptions'=>array('id'=>'clan_link'.$clan->id, 'class'=>'clan-link '.($clan_id == $clan->id ? 'active' : '')));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>

		<div class="table-data">
			<? if ($battles === false){ ?>
				<p><?php echo Yii::t('wot', 'Ошибка загрузки боев')?></p>
			<? } else { ?>
			<?
			$dataProvider=new CArrayDataProvider($battles, array(
				'keyField' => 'id',
				'sort'=>array(
					'defaultOrder'=>'battle_planned_date',
					'attributes'=>array(
						'battle_type',
						'battle_planned_date',
						'attack_direction',
						'defense_direction',
					),
				),
				'pagination'=>array(
					'pageSize'=>100,
				),
			));
			$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'cssFile'=>false,
					'emptyText'=>'Боев нет',
					'columns'=>array(
							array(
								'header'=>'Тип боя',
								'name'=>'battle_type',
								'value'=>'Yii::app()->controller->getBattleType($data["battle_type"])',
							),
							array(
								'header'=>'Противник',
								'name'=>'enemy',
								'value'=>'$data["battle_type"] == "attack" ? $data["defender_clan_tag"] : $data["attacker_clan_tag"]',
							),
							array(
								'header'=>'Атака',
								'name'=>'attack_direction',
								'value'=>'$data["attack_direction"]',
							),
							array(
								'header'=>'Оборона',
								'name'=>'defense_direction',
								'value'=>'$data["defense_direction"]',
							),
							array(
								'header'=>'Дата боя',
								'name'=>'battle_planned_date',
								'value'=>'date("d.m - H:i", $data["battle_planned_date"]+Yii::app()->params["moscow"])." МСК"',
							),
					),
					'summaryText'=>false,
			));
			?>
			<? } ?>
		</div>
	</div>
</div>