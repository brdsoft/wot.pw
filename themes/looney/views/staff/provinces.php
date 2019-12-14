<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_provinces_title')->value;

?>


<div class="modul staff">

	<div class="custom-title">
		<h4><?php echo Config::all('staff_provinces_title')->value ?></h4>
	</div>
	<div class="pastic">
	
		<? if (!$clans){ ?>
				<p>Для отображения провинций <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке</p>
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
				$items[] = array('label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/provinces', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'), 'itemOptions'=>array('id'=>'clan_link'.$clan->id, 'class'=>'clan-link '.($clan_id == $clan->id ? 'active' : '')));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>

		<div class="table-data">
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
