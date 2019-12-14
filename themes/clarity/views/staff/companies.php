<?php
/* @var $this SiteController */

$this->pageTitle=Yii::t('wot', 'Companies');


?>

<div class="modul staff companies">
	<div class="title">
		<h3><?php echo Yii::t('wot', 'Companies')?></h3>
	</div>

	<div class="cell">

		<? if (!$clans){ ?>
				<p><?php echo Yii::t('wot', 'To start listing your companies')?> <?php echo CHtml::link(Yii::t('wot', 'add to you site'), array('/admin/clans/create')); ?> <?php echo Yii::t('wot', 'at least one clan in the Admin Panel')?></p>
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
				$items[] = array('label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/companies', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'), 'itemOptions'=>array('id'=>'clan_link'.$clan->id, 'class'=>'clan-link '.($clan_id == $clan->id ? 'active' : '')));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>

		<? if (!$companies){ ?>
			<p><?php echo Yii::t('wot', 'There is no companies in this Clan')?></p>
		<? } else { ?>
			<? foreach ($companies as $value){ ?>
				<p class="company-title">
					<?php echo $value->name ?>
				</p>

				<div class="table-data">
					<?
					$dataProvider=new CActiveDataProvider('Accounts', array(
						'criteria'=>array(
							'condition'=>"`clan_id` = '{$clan_id}' AND `id` IN ({$value->accounts})",
							'with'=>array('sort'),
						),
						'sort'=>array(
							'defaultOrder'=>"sort.role_order, `nickname`",
							'attributes'=>array(
									'role'=>array(
										'asc'=>'sort.role_order, `nickname`',
										'desc'=>'sort.role_order DESC, `nickname` DESC',
									),
									'*',
							),
						),
						'pagination'=>array(
							'pageSize'=>100,
						),
					));
					$columns = array();
					$columns[] = array(
						'header'=>'#',
						'type'=>'raw',
						'name'=>'n',
						'value'=>'$row + 1',
						'headerHtmlOptions'=>array('style'=>'width: 30px;'),
					);
					$columns[] = array(
						'type'=>'raw',
						'name'=>'nickname',
						'value'=>'CHtml::link($data->nickname, array("/profile/account", "id"=>$data->id))',
						'headerHtmlOptions'=>array('style'=>'width: 50%;'),
					);
					$columns[] = array(
						'type'=>'raw',
						'name'=>'role',
						'header'=>Yii::t('wot', 'Position'),
						'value'=>'WGApi::getClanRole($data->role)',
					);
					$this->widget('zii.widgets.grid.CGridView', array(
							'dataProvider'=>$dataProvider,
							'cssFile'=>false,
							'columns'=>$columns,
							'summaryText'=> false,
					));
					?>
				</div>
			<? } ?>
		<? } ?>

	</div> <!-- pastic -->
</div> <!-- modul -->