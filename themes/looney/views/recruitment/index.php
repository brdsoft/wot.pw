<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('recruitment_title')->value;

?>

<div class="modul recruitment">

	<div class="custom-title">
		<h4><?php echo Config::all('recruitment_title')->value ?></h4>
	</div>
	<div class="pastic">

		<ul class="recruitment-subnav">
			<li><a href="/recruitment">Военкомат</a></li>
			<li class="proposal"><?php echo CHtml::link('Подать заявку', array('recruitment/add')) ?></li>
			<li><?php echo CHtml::link('Требования к кандидатам', array('page/index', 'id'=>'requirements')) ?></li>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('4') || Yii::app()->user->checkAccess('8')){ ?>
			<li><?php echo CHtml::link('Статистика любого игрока', array('recruitment/stat')) ?></li>
		<? } ?>
		</ul>

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

		<a name="scrollTo"></a>
		<div class="table-data">
			<?php if($this->beginCache('recruitment-'.$this->site->id.'-'.(empty($_GET['Recruitment_page']) ? '1' : md5($_GET['Recruitment_page'])), array('duration'=>1200, 'dependency'=>array(
				'class'=>'system.caching.dependencies.CExpressionDependency',
				'expression'=>'Yii::app()->cache->get("recruitment:'.$this->site->id.'")',
			)))) { ?>
			<?
			$dataProvider=new CActiveDataProvider('Recruitment', array(
				'criteria'=>array(
					'with'=>array('account', 'clan'),
				),
				'sort'=>array(
					'defaultOrder'=>"`status` ASC, t.id DESC",
				),
				'pagination'=>array(
					'pageSize'=>50,
				),
			));
			$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'id'=>'recruitment',
					'cssFile'=>false,
					'pager'=>array(
						'cssFile'=>false,
						'header'=>false,
					),
					'columns'=>array(
							array(
								'sortable'=>false,
								'type'=>'html',
								'name'=>'account_id',
								'value'=>'CHtml::link($data->account->nickname, array("recruitment/one", "id"=>$data->id))',
								'header'=>'Кандидат',
							),
							[
								'type'=>'raw',
								'name'=>'battles',
								'value'=>'"<span class=\"".WGApi::getBattleClass(round($data->battles / 1000))."\">".round($data->battles / 1000)."k</span>"',
								'sortable'=>false,
								'header'=>'Бои',
							],
							[
								'type'=>'raw',
								'name'=>'wins',
								'header'=>'%',
								'value'=>'"<span class=\"".WGApi::getWinClass($data->wins)."\">".$data->wins."</span>"',
								'sortable'=>false,
							],
							[
								'type'=>'raw',
								'name'=>'re',
								'value'=>'"<span class=\"".WGApi::getREClass($data->re)."\">".$data->re."</span>"',
								'sortable'=>false,
								'header'=>'РЭ',
							],
							[
								'type'=>'raw',
								'name'=>'wn8',
								'value'=>'"<span class=\"".WGApi::getWN8Class($data->wn8)."\">".$data->wn8."</span>"',
								'sortable'=>false,
								'header'=>'WN8',
							],
							[
								'type'=>'raw',
								'name'=>'bs',
								'value'=>'"<span class=\"".WGApi::getBSClass($data->bs)."\">".$data->bs."</span>"',
								'sortable'=>false,
								'header'=>'БС',
							],
							array(
								'sortable'=>false,
								'type'=>'html',
								'name'=>'t10',
								'value'=>'$data->t10',
								'header'=>'Т',
							),
							array(
								'sortable'=>false,
								'name'=>'clan_id',
								'value'=>'isset($data->clan) ? "[".$data->clan->abbreviation."] ".$data->clan->name : ""',
								'header'=>'Вступает в клан',
							),
							array(
								'sortable'=>false,
								'name'=>'time',
								'value'=>'date("d.m.Y", $data->time+3600*4)',
								'header'=>'Дата',
							),
							array(
								'sortable'=>false,
								'type'=>'raw',
								'name'=>'coments',
								'value'=>'Messages::model()->count("`name`=\'recruitment\' AND `object_id`=\'".$data->id."\'")',
								'header'=>'Комментарии',
							),
							array(
								'sortable'=>false,
								'type'=>'raw',
								'name'=>'resolution',
								'value'=>'$data->resolutionColored',
								'header'=>'Статус',
							),
					),
					'summaryText'=>false,
			));
			?>
			<?php $this->endCache(); } ?>
		</div>

	</div>

</div>