<?php
/* @var $this SiteController */

$this->pageTitle='Роты';


?>


	<div class="block">
		<div class="head head2">
			Роты
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies'), array('class'=>'active')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history')) ?>
			<?php //echo CHtml::link('3 Кампания', array('/staff/famepoints')) ?>
			<div class="both"></div>
		</div>
		<? if (!$clans){ ?>
			<div class="body body2">
				Для отображения рот <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке
			</div>
			</div>
		<? return;} ?>
		<div class="head head3">
			<? foreach ($clans as $key=>$clan){
					if ($clan_id == $clan->id)
						$active = $key;
			?>
				<?php echo CHtml::link($clan->abbreviation, array('/staff/companies', 'id'=>$clan->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan_id == $clan->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<? if (!$companies){ ?>
				<div class="body">
					В данном клане рот нет
				</div>
			<? } else { ?>
				<? foreach ($companies as $value){ ?>
					<div class="company-title">
						<?php echo $value->name ?>
					</div>
					<div class="company-grid">
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
		</div>
	</div>
