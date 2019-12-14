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

<div class="modul staff">

	<div class="custom-title">
		<h4><?php echo Config::all('staff_members_title')->value ?></h4>
	</div>
	<div class="pastic">

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
		<div style="height:90px; margin-bottom:18px;">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Светлый сайт - центральный баннер -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2157584601406270"
     data-ad-slot="2622089344"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
		</div>
<? } ?>

		<? $leader = Accounts::model()->find("`clan_id`='{$clan->id}' AND `role`='commander'"); ?>
		<ul class="staff-legend">
			<li><span class="clan-name">Клан: [<?php echo $clan->abbreviation ?>] <?php echo $clan->name ?></span></li>
			<? if ($leader){ ?><li>Командир: <?php echo CHtml::link($leader->nickname, array("/profile/account", "id"=>$leader->id)) ?></li><? } ?>
			<li>Количество игроков: <a target="_blank" href="http://<?php echo $this->site->cluster ?>.wargaming.net/clans/<?php echo $clan->id ?>/"><?php echo $stat['count'] ?></a></li>
			<li>Средний по клану % побед: <span class="<?php echo WGApi::getWinClass(round($stat['wins'])) ?>"><?php echo number_format($stat['wins'], 1, '.', '') ?></span></li>
			<li>Средний по клану БС: <span class="<?php echo WGApi::getBSClass(round($stat['bs'])) ?>"><?php echo round($stat['bs']) ?></span></li>
			<li>Средний по клану РЭ: <span class="<?php echo WGApi::getREClass(round($stat['re'])) ?>"><?php echo round($stat['re']) ?></span></li>
			<li>Средний по клану WN8: <span class="<?php echo WGApi::getWN8Class(round($stat['wn8'])) ?>"><?php echo round($stat['wn8']) ?></span></li>
		</ul>

		<? if (isset($check[$clan->abbreviation])){ ?>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')){ ?>
			<p class="errorSummary">
				Внимание! Не найдена ссылка на сайт <?php echo $this->site->url ?> со <a target="_blank" href="http://<?php echo $this->site->cluster ?>.wargaming.net/clans/<?php echo $clan->id ?>/">страницы клана</a>. Клан будет удален <?php echo date('d.m.Y', $check[$clan->abbreviation] + 3600*24*3 + Yii::app()->params['moscow']) ?>. Удаленный клан можно будет снова добавить на этот сайт только после указания ссылки.
				<br/><br/>Для чего нужна ссылка:
				<br/>1. Для подтверждения согласия командира клана [<?php echo $clan->abbreviation ?>] на размещение его клана на этом сайте;
				<br/>2. Для исключения использования сайта только в личных интересах командованием клана.
				<br/><br/>Дата последней проверки ссылки: <?php echo date('d.m.Y H:i', $this->site->check_time + Yii::app()->params['moscow']) ?> МСК. Ознакомиться с Пользовательским соглашением можно <a target="_blank" href="http://wot.pw/ru/site/page?view=agreement">тут</a>
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
								'headerHtmlOptions'=>['title'=>'Никнейм'],
								'htmlOptions' => array('class' => 'nickname'),
							],
							[
								'type'=>'raw',
								'name'=>'role',
								'header'=>'Должность',
								'value'=>'WGApi::getClanRole($data["role"])',
								'visible'=>in_array('role', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Должность в клане'],
								'htmlOptions' => array('class' => 'role'),
							],
							[
								'type'=>'html',
								'name'=>'region',
								'header'=>'Регион',
								'value'=>'getRegion($data["city"] ? CHtml::encode($data["city"]) : CHtml::encode($data["region"]))',
								'visible'=>in_array('region', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Регион'],
								'htmlOptions' => array('class' => 'region'),
							],
							[
								'name'=>'since',
								'value'=>'date("d.m.y", $data["since"]+Yii::app()->params["moscow"])',
								'header'=>'Вступил',
								'visible'=>in_array('since', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Дата вступления в клан'],
							],
							[
								'type'=>'raw',
								'name'=>'b_al',
								'header'=>'Бои',
								'value'=>'"<span class=\"".WGApi::getBattleClass(round($data["b_al"] / 1000))."\">".round($data["b_al"] / 1000)."k</span>"',
								'visible'=>in_array('battles', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Количество боев'],
							],
							[
								'type'=>'raw',
								'name'=>'wins',
								'header'=>'%',
								'value'=>'"<span class=\"".WGApi::getWinClass($data["wins"])."\">".round($data["wins"])."</span>"',
								'visible'=>in_array('wins', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Процент побед'],
							],
							[
								'type'=>'raw',
								'name'=>'re',
								'header'=>'РЭ',
								'value'=>'"<span class=\"".WGApi::getREClass($data["re"])."\">".round($data["re"])."</span>"',
								'visible'=>$show_stat && in_array('re', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Рейтинг эффективности (РЭ)'],
							],
							[
								'type'=>'raw',
								'name'=>'wn8',
								'header'=>'WN8',
								'value'=>'"<span class=\"".WGApi::getWN8Class($data["wn8"])."\">".round($data["wn8"])."</span>"',
								'visible'=>$show_stat && in_array('wn8', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Рейтинг WN8'],
							],
							[
								'type'=>'raw',
								'name'=>'bs',
								'header'=>'БС',
								'value'=>'"<span class=\"".WGApi::getBSClass($data["bs"])."\">".round($data["bs"])."</span>"',
								'visible'=>$show_stat && in_array('bs', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Рейтинг бронесайта (БС)'],
							],
							/* [
								'header'=>'И',
								'type'=>'raw',
								'name'=>'ivanerr_power',
								'value'=>'number_format($data->ivanerr_power*2.15, 2, ".", "")',
								'visible'=>$show_ivanerr && in_array('ivanerr_power', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Сила по иванерру'],
							], */
							[
								'name'=>'t10',
								'value'=>'$data["t10"]',
								'header'=>'Т',
								'visible'=>in_array('tanks10', Config::explode(Config::all('staff_members_columns')->value)),
								'headerHtmlOptions'=>['title'=>'Количество танков 10 уровня'],
							],
						],
						'summaryText'=> false,
				));
				?>
		</div>
		<p>* Актуальность данных: <?php echo date('d.m.Y H:i', $clan->parser_info_time+Yii::app()->params['moscow']) ?> (МСК). Московское время: <?php echo date('d.m.Y H:i', time()+Yii::app()->params['moscow']) ?></p>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1')){ ?>
			<p>* Загрузка игроков после добавления нового клана происходит в течение 5 минут</p>
			<p>* Сила игроков по Иванерру может отличаться от действительной, потому что данные на сайте ivanerr.ru обновляются очень редко</p>
		<? } ?>	
	
	</div>

</div>
