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


	<div class="block">
		<div class="head head2">
			<?php echo Config::all('staff_members_title')->value ?>
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members'), array('class'=>'active')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history')) ?>
			<?php //echo CHtml::link('3 Кампания', array('/staff/famepoints')) ?>
			<div class="both"></div>
		</div>
		<div class="head head3">
			<? foreach ($this->clans as $key=>$value){
					if ($clan->id == $value->id)
						$active = $key;
			?>
				<?php echo CHtml::link($value->abbreviation, array('/staff/members', 'id'=>$value->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($value->id, -3).'/'.$value->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan->id == $value->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<? $leader = Accounts::model()->find("`clan_id`='{$clan->id}' AND `role`='commander'"); ?>
			<? if ($leader){ ?>
			<div class="body">
				<table style="width: 100%;">
					<tr>
						<td>
							<p>
								<span class="clan-name"><?php echo $clan->name ?></span>
							</p>
							<p>
								Командир: <?php echo CHtml::link($leader->nickname, array("/profile/account", "id"=>$leader->id)) ?>
								<br/>
								Количество игроков: <a target="_blank" href="http://worldoftanks.ru/community/clans/<?php echo $clan->id ?>-<?php echo $clan->abbreviation ?>/"><?php echo $stat['count'] ?></a>
								<br/>
								Средний процент побед по клану: <span class="<?php echo WGApi::getWinClass(round($stat['wins'])) ?>"><?php echo number_format($stat['wins'], 1, '.', '') ?></span>
								<br/>
								Средний бронесайт по клану: <span class="<?php echo WGApi::getBSClass(round($stat['bs'])) ?>"><?php echo round($stat['bs']) ?></span>
								<br/>
								Средний рейтинг эффективности по клану: <span class="<?php echo WGApi::getREClass(round($stat['re'])) ?>"><?php echo round($stat['re']) ?></span>
								<br/>
								Средний WN8 по клану: <span class="<?php echo WGApi::getREClass(round($stat['wn8'])) ?>"><?php echo round($stat['wn8']) ?></span>
								<br/>
								<br/>
								Рейтинг по Иванерру: <span class="<?php echo WGApi::getIvanerrClass($clan->ivanerr_rating) ?>"><?php echo $clan->ivanerr_rating ?></span>
								<br/>
								Сила по Иванерру: <a target="_blank" href="http://ivanerr.ru/lt/clan/<?php echo $clan->id ?>"><?php echo $clan->ivanerr_power ?></a>
							</p>
						</td>
						<td style="text-align: right;">
							РЭ - рейтинг эффективности (КПД)
							<br/>БС - рейтинг Бронесайта
							<br/>И - Сила по Иванерру (приблизительно)
							<br/>Т - танки 10 уровня
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
			<? if (isset($check[$clan->abbreviation])){ ?>
			<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')){ ?>
				<div class="block">
					<div class="body">
						<strong>
							Внимание! Не найдена ссылка на сайт <?php echo $this->site->url ?> со <a target="_blank" href="http://ru.wargaming.net/clans/<?php echo $clan->id ?>/">страницы клана</a>. Клан будет удален <?php echo date('d.m.Y', $check[$clan->abbreviation] + 3600*24*3 + Yii::app()->params['moscow']) ?>. Удаленный клан можно будет снова добавить на этот сайт только после указания ссылки.
							<br/><br/>Для чего нужна ссылка:
							<br/>1. Для подтверждения согласия командира клана [<?php echo $clan->abbreviation ?>] на размещение его клана на этом сайте;
							<br/>2. Для исключения использования сайта только в личных интересах командованием клана.
							<br/><br/>Дата последней проверки ссылки: <?php echo date('d.m.Y H:i', $this->site->check_time + Yii::app()->params['moscow']) ?> МСК. Ознакомиться с Пользовательским соглашением можно <a target="_blank" href="http://wot.pw/ru/site/page?view=agreement">тут</a>
						</strong>
					</div>
				</div>
			<? } ?>
			<? } ?>
			<div class="clan-grid">
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
			<div class="block">
				* Актуальность данных: <?php echo date('d.m.Y H:i', $clan->parser_info_time+Yii::app()->params['moscow']) ?> (МСК). Московское время: <?php echo date('d.m.Y H:i', time()+Yii::app()->params['moscow']) ?>
			<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1')){ ?>
				<br/>* Загрузка игроков после добавления нового клана происходит в течение 5 минут
				<br/>* Сила игроков по Иванерру может отличаться от действительной, потому что данные на сайте ivanerr.ru обновляются очень редко
			<? } ?>
			</div>
		</div>
	</div>
