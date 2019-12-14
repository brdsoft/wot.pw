<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_provinces_title')->value;

?>


<div class="modul staff provinces">

	<div class="title">
		<h3><?php echo Config::all('staff_provinces_title')->value ?></h3>
	</div>
	<div class="cell">
	
		<? if (!$clans){ ?>
				<p><?php echo Yii::t('wot', 'To start listing your provinces')?> <?php echo CHtml::link(Yii::t('wot', 'add to you site'), array('/admin/clans/create')); ?> <?php echo Yii::t('wot', 'at least one clan in the Admin Panel')?></p>
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
				$items[] = array('label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/provinces', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'), 'itemOptions'=>array('id'=>'clan_link'.$clan->id, 'class'=>'clan-link '.($clan_id == $clan->id ? 'active' : '')));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>

		<div class="table-data">
			<? if ($provinces === false){ ?>
				<p><?php echo Yii::t('wot', 'Error loading Provinces')?></p>
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
					'emptyText'=>Yii::t('wot', 'No provinces occupied'),
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
