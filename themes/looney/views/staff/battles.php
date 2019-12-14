<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_battles_title')->value;

?>

<div class="modul staff">

	<div class="custom-title">
		<h4><?php echo Config::all('staff_battles_title')->value ?></h4>
	</div>
	<div class="pastic">
	
		<? if (!$clans){ ?>
				<p>Для отображения боев <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке</p>
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
				$items[] = array('label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/battles', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'), 'itemOptions'=>array('id'=>'clan_link'.$clan->id, 'class'=>'clan-link '.($clan_id == $clan->id ? 'active' : '')));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>

		<div class="table-data">
			<? if ($battles === false){ ?>
				<p>Ошибка загрузки боев</p>
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
								'value'=>'date("d.m - H:i", $data["battle_planned_date"]+Yii::app()->params["moscow"])',
							),
					),
					'summaryText'=>'Время Московское',
			));
			?>
			<? } ?>
		</div>
	</div>
</div>