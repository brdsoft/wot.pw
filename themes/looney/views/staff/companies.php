<?php
/* @var $this SiteController */

$this->pageTitle='Роты';


?>

<div class="modul staff">
	<div class="custom-title">
		<h4>Роты</h4>
	</div>

	<div class="pastic">

		<? if (!$clans){ ?>
				<p>Для отображения рот <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке</p>
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
				$items[] = array('label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/companies', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'), 'itemOptions'=>array('id'=>'clan_link'.$clan->id, 'class'=>'clan-link '.($clan_id == $clan->id ? 'active' : '')));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>

		<? if (!$companies){ ?>
			<p>В данном клане рот нет</p>
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
						'header'=>'Должность',
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