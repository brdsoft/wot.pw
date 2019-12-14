<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_provinces_title')->value;

?>


	<div class="block">
		<div class="head head2">
			<?php echo Config::all('staff_provinces_title')->value ?>
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces'), array('class'=>'active')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history')) ?>
			<?php //echo CHtml::link('3 Кампания', array('/staff/famepoints')) ?>
			<div class="both"></div>
		</div>
		<? if (!$clans){ ?>
			<div class="body body2">
				Для отображения провинций <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке
			</div>
			</div>
		<? return;} ?>
		<div class="head head3">
			<? foreach ($clans as $key=>$clan){	?>
				<?php echo CHtml::link($clan->abbreviation, array('/staff/provinces', 'id'=>$clan->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan_id == $clan->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<div class="clan-grid">
				<? if ($provinces === false){ ?>
					<p>Ошибка загрузки провинций</p>
				<? } else { ?>
				<?
				$dataProvider=new CArrayDataProvider($provinces, array(
					'keyField' => 'province_id',
					'sort'=>array(
						'attributes'=>array(
							'province_name',
							'front_name',
							'arena_name',
							'daily_revenue',
							'prime_time',
						),
					),
					'pagination'=>array(
						'pageSize'=>100,
					),
				));
				$this->widget('zii.widgets.grid.CGridView', array(
						'dataProvider'=>$dataProvider,
						'cssFile'=>false,
						'emptyText'=>'Провинций нет',
					'columns'=>array(
							array(
									'header'=>'Название',
									'type'=>'raw',
									'name'=>'province_name',
									'value'=>'"<a target=\"_blank\" href=\"https://".Yii::app()->controller->site->cluster.".wargaming.net/globalmap/#province/".$data["province_id"]."\">".$data["province_name"]."</a></span>"',
							),
							array(
									'header'=>'Фронт',
									'type'=>'raw',
									'name'=>'front_name',
									'value'=>'$data["front_name"]',
							),
							array(
									'header'=>'Игровая карта',
									'type'=>'raw',
									'name'=>'arena_name',
									'value'=>'$data["arena_name"]',
							),
							array(
									'header'=>'Атакована',
									'type'=>'raw',
									'name'=>'attackers',
									'value'=>'$data["info"]["attackers"] ? "Да" : "Нет"',
							),
							array(
									'header'=>'Прайм',
									'name'=>'prime_time',
									'value'=>'$data["prime_time"]." UTC"',
							),
							array(
									'header'=>'Доход',
									'type'=>'raw',
									'name'=>'daily_revenue',
									'value'=>'"<span style=\"color: #FFC364; padding: 0 15px 0 0; background: url(\'/images/currency-gold.png\') no-repeat scroll 100% center rgba(0, 0, 0, 0);\">".$data["daily_revenue"]."</span>"',
							),
					),
					'summaryText'=>false,
				));
				?>
				<? } ?>
			</div>
		</div>
	</div>
