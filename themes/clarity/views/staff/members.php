<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_members_title')->value;

function getRegion($region)
{
	$a1 = array(
		'MD',
		'LV',
		'BY',
		'UA',
		'KZ',
		'CY',
		'IL',
		'IS',
		'FI',
		'BG',
	);
	$a2 = array(
		'Молдавия',
		'Латвия',
		'Беларусь',
		'Украина',
		'Казахстан',
		'Кипр',
		'Израиль',
		'Исландия',
		'Финляндия',
		'Болгария',
	);
	$region = str_replace($a1, $a2, $region);
	return $region;
}

?>

<div class="modul staff members">

	<div class="title">
		<h3><?php echo Config::all('staff_members_title')->value ?></h3>
	</div>
	<div class="cell">

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
			foreach ($this->clans as $key=>$value){
				if ($clan->id == $value->id)
					$active = $key;
				$items[] = array('active'=> $clan->id == $value->id ? true : false, 'label'=>'['.$value->abbreviation.']', 'url'=>array('/staff/members', 'id'=>$value->id), 'linkOptions'=>array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($value->id, -3).'/'.$value->id.'/emblem_24x24.png);'));
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

		<? $leader = Accounts::model()->find("`clan_id`='{$clan->id}' AND `role`='commander'"); ?>
		<ul class="staff-legend">
			<li><span class="clan-name"><?php echo Yii::t('wot', 'Clan')?>: [<?php echo $clan->abbreviation ?>] <?php echo $clan->name ?></span>;</li>
			<? if ($leader){ ?><li><?php echo Yii::t('wot', 'Commander')?>: <?php echo CHtml::link($leader->nickname, array("/profile/account", "id"=>$leader->id)) ?>;</li><? } ?>
			<li><?php echo Yii::t('wot', 'Personnel:')?> <a target="_blank" href="http://ru.wargaming.net/clans/<?php echo $clan->id ?>/"><?php echo $stat['count'] ?></a>;</li>
			<li><?php echo Yii::t('wot', 'Average:')?> <span class="<?php echo WGApi::getWinClass(round($stat['wins'])) ?>"><?php echo number_format($stat['wins'], 1, '.', '') ?>% <?php echo Yii::t('wot', 'winrate')?></span>, <?php echo Yii::t('wot', 'BS')?> <span class="<?php echo WGApi::getBSClass(round($stat['bs'])) ?>"><?php echo round($stat['bs']) ?></span>, <?php echo Yii::t('wot', 'RE')?> <span class="<?php echo WGApi::getREClass(round($stat['re'])) ?>"><?php echo round($stat['re']) ?></span>, <?php echo Yii::t('wot', 'WN8')?> <span class="<?php echo WGApi::getWN8Class(round($stat['wn8'])) ?>"><?php echo round($stat['wn8']) ?></span>.</li>
		</ul>

		<? if (isset($check[$clan->abbreviation])){ ?>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')){ ?>
			<p class="errorSummary">
				<?php echo Yii::t('wot', 'Warning! No link to the website found')?> <?php echo $this->site->url ?> <?php echo Yii::t('wot', 'in')?> <a target="_blank" href="http://<?php echo $this->site->cluster ?>.wargaming.net/clans/<?php echo $clan->id ?>/"><?php echo Yii::t('wot', 'the official list of clans in World of Tanks')?></a>. <?php echo Yii::t('wot', 'Clan will be deleted')?> <?php echo date('d.m.Y', $check[$clan->abbreviation] + 3600*24*3 + Yii::app()->params['moscow']) ?>. <?php echo Yii::t('wot', 'The deleted clan can be added again only after adding the proper link')?>.
				<br><br><?php echo Yii::t('wot', 'The link is needed:')?>
				<br><?php echo Yii::t('wot', '1. To prove that the Commander of')?> [<?php echo $clan->abbreviation ?>] <?php echo Yii::t('wot', 'agrees with creation of the website')?>;
				<br><?php echo Yii::t('wot', '2. To prevent commanders from using the website in their private interests')?>.
				<br><br><?php echo Yii::t('wot', 'Last link check')?> <?php echo date('d.m.Y H:i', $this->site->check_time + Yii::app()->params['moscow']) ?> <?php echo Yii::t('wot', '. You can check the User Agreement <a target="_blank" href="http://wot.pw/site/page?view=agreement">')?> </a>.
			</p>
		<? } ?>
		<? } ?>

		<div class="table-data table-members">
				<?
				$dataProvider=new CArrayDataProvider($accounts, array(
					'id'=>'id',
					'sort'=>array(
						'defaultOrder'=>"role_order, nickname",
						'attributes'=>[
							'nickname',
							'region',
							'since',
							'b_al',
							'wins',
							're',
							'wn8',
							'bs',
							't10',
							'role'=>[
								'asc'=>'role_order, nickname',
								'desc'=>'role_order DESC, nickname DESC',
							 ],
						],
					),
					'pagination'=>array(
						'pageSize'=>100,
					),
				));
				$this->widget('zii.widgets.grid.CGridView', array(
						'dataProvider'=>$dataProvider,
						'cssFile'=>false,
						'columns'=>[
							[
								'header'=>'#',
								'type'=>'raw',
								'name'=>'n',
								'value'=>'$row + 1',
							],
							[
								'type'=>'raw',
								'name'=>'nickname',
								'header'=>'Никнейм',
								'value'=>'CHtml::link($data["nickname"], array("/profile/account", "id"=>$data["id"]))',
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Nickname')],
								'htmlOptions' => array('class' => 'nickname'),
							],
							[
								'type'=>'raw',
								'name'=>'role',
								'header'=>'Должность',
								'value'=>'WGApi::getClanRole($data["role"])',
								'visible'=>in_array('role', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Position in the clan')],
								'htmlOptions' => array('class' => 'role'),
							],
							[
								'type'=>'html',
								'name'=>'region',
								'header'=>'Регион',
								'value'=>'getRegion($data["city"] ? CHtml::encode($data["city"]) : CHtml::encode($data["region"]))',
								'visible'=>in_array('region', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Region')],
								'htmlOptions' => array('class' => 'region'),
							],
							[
								'name'=>'since',
								'value'=>'date("d.m.y", $data["since"]+Yii::app()->params["moscow"])',
								'header'=>'Вступил',
								'visible'=>in_array('since', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Enlisting date')],
							],
							[
								'type'=>'raw',
								'name'=>'b_al',
								'header'=>'Бои',
								'value'=>'"<span class=\"".WGApi::getBattleClass(round($data["b_al"] / 1000))."\">".round($data["b_al"] / 1000)."k</span>"',
								'visible'=>in_array('battles', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Battles played')],
							],
							[
								'type'=>'raw',
								'name'=>'wins',
								'header'=>'%',
								'value'=>'"<span class=\"".WGApi::getWinClass($data["wins"])."\">".round($data["wins"])."</span>"',
								'visible'=>in_array('wins', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Winrate')],
							],
							[
								'type'=>'raw',
								'name'=>'re',
								'header'=>'РЭ',
								'value'=>'"<span class=\"".WGApi::getREClass($data["re"])."\">".round($data["re"])."</span>"',
								'visible'=>$show_stat && in_array('re', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Efficiency rating')],
							],
							[
								'type'=>'raw',
								'name'=>'wn8',
								'header'=>'WN8',
								'value'=>'"<span class=\"".WGApi::getWN8Class($data["wn8"])."\">".round($data["wn8"])."</span>"',
								'visible'=>$show_stat && in_array('wn8', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'WN8 rating')],
							],
							[
								'type'=>'raw',
								'name'=>'bs',
								'header'=>'БС',
								'value'=>'"<span class=\"".WGApi::getBSClass($data["bs"])."\">".round($data["bs"])."</span>"',
								'visible'=>$show_stat && in_array('bs', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', '"BroneSite" rating')],
							],
							[
								'name'=>'t10',
								'value'=>'$data["t10"]',
								'header'=>'Т',
								'visible'=>in_array('tanks10', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>Yii::t('wot', 'Top-level (10) tanks')],
							],
						],
						'summaryText'=> false,
				));
				?>
		</div>
		<p><?php echo Yii::t('wot', '* Data updated at:')?> <?php echo date('d.m.Y H:i', $clan->parser_info_time+Yii::app()->params['moscow']) ?> <?php echo Yii::t('wot', '(UTC+3, MSK). MSK-time:')?> <?php echo date('d.m.Y H:i', time()+Yii::app()->params['moscow']) ?></p>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1')){ ?>
			<p><?php echo Yii::t('wot', '* Data upload after adding a new clan is performed in 5 minutes.')?></p>
			<p><?php echo Yii::t('wot', '* Ivanerr Power of the players can differ from the current one because ivanerr.ru updates its data rarely.')?></p>
		<? } ?>	
	
	</div>

</div>
