<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('recruitment_title')->value;

?>

<div class="modul recruitment recr-index">

	<div class="title">
		<h3><?php echo Config::all('recruitment_title')->value ?></h3>
	</div>
	<div class="cell">

	<ul class="recruitment-nav">
		<li class="active"><a class="recr-index" href="/recruitment"><?php echo Yii::t('wot', 'Recruting Station')?></a></li>
		<li><?php echo CHtml::link(Yii::t('wot', 'Apply'), array('recruitment/add'), array('class'=>'recr-add')) ?></li>
		<li><?php echo CHtml::link(Yii::t('wot', 'Terms of Recruitment'), array('page/index', 'id'=>'requirements'), array('class'=>'recr-cand')) ?></li>
	<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('4') || Yii::app()->user->checkAccess('8')){ ?>
	<li><?php echo CHtml::link(Yii::t('wot', 'Any player stats'), array('recruitment/stat'), array('class'=>'recr-stat')) ?></li>
	<? } ?>
	</ul>

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

<a name="scrollTo"></a>
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
								'header'=>Yii::t('wot', 'Applicant'),
							),
							[
								'type'=>'raw',
								'name'=>'battles',
								'value'=>'"<span class=\"".WGApi::getBattleClass(round($data->battles / 1000))."\">".round($data->battles / 1000)."k</span>"',
								'sortable'=>false,
								'header'=>Yii::t('wot', 'Battles'),
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
								'header'=>Yii::t('wot', 'ER'),
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
								'header'=>Yii::t('wot', 'BS'),
							],
							array(
								'sortable'=>false,
								'type'=>'html',
								'name'=>'t10',
								'value'=>'$data->t10',
								'header'=>Yii::t('wot', 'T'),
							),
							array(
								'sortable'=>false,
								'name'=>'clan_id',
								'value'=>'isset($data->clan) ? "[".$data->clan->abbreviation."] " : ""',
								'header'=>Yii::t('wot', 'Applies to'),
							),
							array(
								'sortable'=>false,
								'name'=>'time',
								'value'=>'date("d.m.Y", $data->time+3600*4)',
								'header'=>Yii::t('wot', 'Date'),
							),
							array(
								'sortable'=>false,
								'type'=>'raw',
								'name'=>'coments',
								'value'=>'Messages::model()->count("`name`=\'recruitment\' AND `object_id`=\'".$data->id."\'")',
								'header'=>Yii::t('wot', 'Comments'),
							),
							array(
								'sortable'=>false,
								'type'=>'raw',
								'name'=>'resolution',
								'value'=>'$data->resolutionColored',
								'header'=>Yii::t('wot', 'Application Status'),
							),
					),
					'summaryText'=>false,
			));
			?>
			<?php $this->endCache(); } ?>


	</div>

</div>