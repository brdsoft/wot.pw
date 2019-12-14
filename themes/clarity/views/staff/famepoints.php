<?php
/* @var $this SiteController */

$this->pageTitle= 'Армагеддон. Очки славы';

function getClanRole($role)
{
	if ($role == 'private')
		return '<span class="private">Рядовой</span>';
	if ($role == 'commander')
		return '<span class="commander">Боевой офицер</span>';
	if ($role == 'junior_officer')
		return '<span class="junior-officer">Мл. офицер</span>';
	if ($role == 'personnel_officer')
		return '<span class="personnel-officer">Шт. офицер</span>';
	if ($role == 'leader')
		return '<span class="leader">Командир</span>';
	if ($role == 'recruiter')
		return '<span class="recruiter">Вербовщик</span>';
	if ($role == 'recruit')
		return '<span class="recruit">Новобранец</span>';
	if ($role == 'diplomat')
		return '<span class="diplomat">Дипломат</span>';
	if ($role == 'vice_leader')
		return '<span class="vice-leader">Зам. командира</span>';
	if ($role == 'treasurer')
		return '<span class="treasurer">Казначей</span>';
	if ($role == 'reservist')
		return '<span class="reservist">Резервист</span>';
	return $role;
}

function getPositionColor($position)
{
	if ($position > 30000)
		return WGApi::$classes['very_bad'];
	if ($position > 10000)
		return WGApi::$classes['good'];
	if ($position > 1000)
		return WGApi::$classes['very_good'];
	if ($position > 0)
		return WGApi::$classes['unique'];
	return WGApi::$classes['very_bad'];
}

?>

<div class="modul staff famepoints">

	<div class="title">
		<h3>Армагеддон. Очки славы</h3>
	</div>
	<div class="cell">

		<? if (!$clans){ ?>
				<p>Для отображения игроков <?php echo CHtml::link('добавьте', array('/admin/clans/create')); ?> хотя бы один клан в админке</p>
			</div>
			</div>
		<? return;} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-nav'),
			'items'=>array(
				array('label'=>'Состав', 'url'=>array('/staff/members'), 'linkOptions'=>array('class'=>'members')),
				array('label'=>'Онлайн', 'url'=>array('/staff/attendance'), 'linkOptions'=>array('class'=>'online')),
				array('label'=>'Роты', 'url'=>array('/staff/companies'), 'linkOptions'=>array('class'=>'companies'), 'visible'=>$this->site->premium_companies),
				array('label'=>'Бои', 'url'=>array('/staff/battles'), 'linkOptions'=>array('class'=>'battles')),
				array('label'=>'Провинции', 'url'=>array('/staff/provinces'), 'linkOptions'=>array('class'=>'provinces')),
				array('label'=>'Планшет', 'url'=>'#', 'linkOptions'=>array('class'=>'tactic', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;'), 'visible'=>$this->site->premium_tactic),
				array('label'=>'История игроков', 'url'=>array('/staff/history'), 'linkOptions'=>array('class'=>'history')),
//				array('label'=>Yii::t('wot', 'Армагеддон'), 'url'=>array('/staff/famepoints'), 'linkOptions'=>array('class'=>'famepoints')),
			),
		)); ?>
		
		<?
			$items = array();
			foreach ($clans as $key=>$clan){
				if ($clan_id == $clan->id)
					$active = $key;
				$items[] = array('active'=> $clan_id == $clan->id ? true : false, 'label'=>'['.$clan->abbreviation.']', 'url'=>array('/staff/famepoints', 'id'=>$clan->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($clan->id, -3).'/'.$clan->id.'/emblem_24x24.png);'));
		} ?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-clannav'),
			'items'=>$items,
		)); ?>
	
<? if (!$this->site->premium_reklama){ ?>
		<div class="h-banner">
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
<? } ?>

		<div class="table-data">
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
				'value'=>'WGApi::getClanRole($data->role)',
			);
			$columns[] = array(
				'type'=>'raw',
				'name'=>'fame_points',
				'header'=>'Очки',
				'value'=>'"<span class=\"".getPositionColor($data->position)."\">".$data->fame_points."</span>"',
			);
			$columns[] = array(
				'type'=>'raw',
				'name'=>'position',
				'header'=>'Место',
				'value'=>'"<span class=\"".getPositionColor($data->position)."\">".($data->position ? $data->position : "---")."</span>"',
			);
			$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'cssFile'=>false,
					'columns'=>$columns,
					'summaryText'=> false,
			));
			?>
		</div>
		<p>
			* Актуальность данных: <?php echo date('d.m.Y H:i', $clans[$active]->parser_famepoints_time+Yii::app()->params['moscow']) ?> (МСК). Московское время: <?php echo date('d.m.Y H:i', time()+Yii::app()->params['moscow']) ?>
		</p>
	</div>
</div>
