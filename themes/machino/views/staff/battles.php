<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_battles_title')->value;

?>


	<div class="block">
		<div class="head head2">
			<?php echo Config::all('staff_battles_title')->value ?>
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles'), array('class'=>'active')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history')) ?>
			<?php //echo CHtml::link('3 Кампания', array('/staff/famepoints')) ?>
			<div class="both"></div>
		</div>
		<? if (!$clans){ ?>
			<div class="body body2">
				Для отображения боев <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке
			</div>
			</div>
		<? return;} ?>
		<div class="head head3">
			<? foreach ($clans as $key=>$clan){	?>
				<?php echo CHtml::link($clan->abbreviation, array('/staff/battles', 'id'=>$clan->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan_id == $clan->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<div class="clan-grid">
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
