<?php
/* @var $this SiteController */

$this->pageTitle= '3 кампания';

function getClanRole($role)
{
	if ($role == 'private')
		return 'Рядовой';
	if ($role == 'commander')
		return '<span style="color: #090;">Боевой офицер</span>';
	if ($role == 'junior_officer')
		return '<span style="color: #090;">Мл. офицер</span>';
	if ($role == 'personnel_officer')
		return '<span style="color: #090;">Шт. офицер</span>';
	if ($role == 'leader')
		return '<span style="color: #D042F3; font-weight: bold;">Командир</span>';
	if ($role == 'recruiter')
		return '<span style="color: #E5ED00;">Вербовщик</span>';
	if ($role == 'recruit')
		return '<span style="color: #f00;">Новобранец</span>';
	if ($role == 'diplomat')
		return '<span style="color: #12A0ED;">Дипломат</span>';
	if ($role == 'vice_leader')
		return '<span style="color: #D042F3;">Зам. командира</span>';
	if ($role == 'treasurer')
		return '<span style="color: #3F8E93;">Казначей</span>';
	if ($role == 'reservist')
		return '<span style="color: #D37700;">Резервист</span>';
	return $role;
}

function getPositionColor($position)
{
	if ($position > 30000)
		return WGApi::$colors['very_bad'];
	if ($position > 10000)
		return WGApi::$colors['good'];
	if ($position > 1000)
		return WGApi::$colors['very_good'];
	if ($position > 0)
		return WGApi::$colors['unique'];
	return WGApi::$colors['very_bad'];
}

function getWeightColor($weight)
{
	if ($weight >= 0)
		return '#60FF00';
	return '#f00';
}

?>


	<div class="block">
		<div class="head head2">
			3 Кампания. Очки славы
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history')) ?>
			<?php echo CHtml::link('3 Кампания', array('/staff/famepoints'), array('class'=>'active')) ?>
			<div class="both"></div>
		</div>
		<? if (!$clans){ ?>
			<div class="body body2">
				Для отображения игроков <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке
			</div>
			</div>
		<? return;} ?>
		<div class="head head3">
			<? foreach ($clans as $key=>$clan){
					if ($clan_id == $clan->id)
						$active = $key;
			?>
				<?php echo CHtml::link($clan->abbreviation, array('/staff/famepoints', 'id'=>$clan->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan_id == $clan->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<? $leader = Accounts::model()->find("`clan_id`='{$clan_id}' AND `role`='leader'"); ?>
			<? if ($leader){ ?>
			<div class="body">
				<table style="width: 100%;">
					<tr>
						<td>
							<p>
								<span class="clan-name"><?php echo $clans[$active]->name ?></span>
							</p>
							<p>
								Командир: <?php echo CHtml::link($leader->nickname, array("/profile/account", "id"=>$leader->id)) ?>
								<br/>
								Количество игроков: <a target="_blank" href="http://worldoftanks.ru/community/clans/<?php echo $clans[$active]->id ?>-<?php echo $clans[$active]->abbreviation ?>/"><?php echo $stat['count'] ?></a>
								<!--<br/>
								Очков славы: <a target="_blank" href="http://worldoftanks.ru/community/clans/<?php echo $clans[$active]->id ?>-<?php echo $clans[$active]->abbreviation ?>/"><?php echo $stat['fp'] ?></a>
							--></p>
						</td>
					</tr>
				</table>
			</div>
			<? } ?>
			<? if (!$this->site->premium_reklama){ ?>
			<div class="block">
				<div class="body" style="text-align: center;">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Темный сайт - центральный баннер -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2157584601406270"
     data-ad-slot="8247754143"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
				</div>
			</div>
			<? } ?>
			<div class="clan-grid">
				<?
				$dataProvider=new CActiveDataProvider('Accounts', array(
					'criteria'=>array(
						'condition'=>"`clan_id` = '{$clan_id}'",
						'with'=>array('sort'),
					),
					'sort'=>array(
						'defaultOrder'=>"`fame_points` DESC",
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
				);
				$columns[] = array(
					'type'=>'raw',
					'name'=>'nickname',
					'value'=>'CHtml::link($data->nickname, array("/profile/account", "id"=>$data->id))',
				);
				$columns[] = array(
					'type'=>'raw',
					'name'=>'role',
					'header'=>'Должность',
					'value'=>'getClanRole($data->role)',
				);
				$columns[] = array(
					'type'=>'raw',
					'name'=>'fame_points',
					'header'=>'Очки',
					'value'=>'"<span style=\"color: ".getPositionColor($data->position)."\">".$data->fame_points."</span>"',
				);
				$columns[] = array(
					'type'=>'raw',
					'name'=>'position',
					'header'=>'Место',
					'value'=>'"<span style=\"color: ".getPositionColor($data->position)."\">".($data->position ? $data->position : "---")."</span>"',
				);
				$this->widget('zii.widgets.grid.CGridView', array(
						'dataProvider'=>$dataProvider,
						'cssFile'=>false,
						'columns'=>$columns,
						'summaryText'=> false,
				));
				?>
			</div>
			<div class="block">
				* Актуальность данных: <?php echo date('d.m.Y H:i', $clans[$active]->parser_famepoints_time+Yii::app()->params['moscow']) ?> (МСК). Московское время: <?php echo date('d.m.Y H:i', time()+Yii::app()->params['moscow']) ?>
			</div>
		</div>
	</div>
