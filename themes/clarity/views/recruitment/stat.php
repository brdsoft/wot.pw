<?php
/* @var $this SiteController */

$this->pageTitle=Yii::t('wot', 'Any player stats');

?>

<div class="modul recruitment recr-stat">

	<div class="title">
		<h3><?php echo Yii::t('wot', 'Any player stats')?></h3>
	</div>
	<div class="cell">

	<ul class="recruitment-nav">
		<li><a class="recr-index" href="/recruitment"><?php echo Yii::t('wot', 'Recruting Station')?></a></li>
		<li><?php echo CHtml::link(Yii::t('wot', 'Apply'), array('recruitment/add'), array('class'=>'recr-add')) ?></li>
		<li><?php echo CHtml::link(Yii::t('wot', 'Terms of Recruitment'), array('page/index', 'id'=>'requirements'), array('class'=>'recr-cand')) ?></li>
	<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('4') || Yii::app()->user->checkAccess('8')){ ?>
	<li class="active"><?php echo CHtml::link(Yii::t('wot', 'Any player stats'), array('recruitment/stat'), array('class'=>'recr-stat')) ?></li>
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

		<p><?php echo Yii::t('wot', 'Here you can find stats on any World of Tank player')?></p>

		<div class="form">

			<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'stat-form',
					'enableAjaxValidation'=>false,
			)); ?>

			<?php echo $form->errorSummary($model); ?>

				<div class="row">
					<?php echo $form->textField($model,'nickname'); ?>
					<?php echo $form->labelEx($model,'nickname'); ?>
				</div>

				<div class="row">
					<?php echo CHtml::submitButton(Yii::t('wot', 'Search ')); ?>
				</div>

			<?php $this->endWidget(); ?>

		</div>

		<br>

		<? if (!empty($model->account) && $account = $model->account){ ?>
			<div class="table-data">
			<? $this->widget('zii.widgets.CDetailView', array(
					'cssFile'=>false,
					'data'=>$account,
					'attributes'=>array(
							array(
								'label'=>Yii::t('wot', 'Nickname'),
								'type'=>'raw',
								'value'=>$account->nickname,
							),
							array(
								'label'=>Yii::t('wot', 'Clan'),
								'type'=>'raw',
								'value'=>$account->clan_abbreviation ? '<a target="_blank" href="http://worldoftanks.ru/community/clans/'.$account->clan_id.'-'.$account->clan_abbreviation.'/">['.$account->clan_abbreviation.'] '.$account->clan_name.'</a>' : 'Нет',
							),
							array(
								'label'=>Yii::t('wot', 'Position'),
								'type'=>'raw',
								'value'=>WGApi::getClanRole($account->role),
							),
							array(
								'label'=>Yii::t('wot', 'WoT Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/">http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/</a>',
							),
							array(
								'label'=>Yii::t('wot', 'BroneSite Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'">http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль КТТС',
								'type'=>'raw',
                                'value'=>'<a target="_blank" href="https://kttc.ru/wot/ru/user/'.$account->nickname.'/">https://kttc.ru/wot/ru/user/'.$account->nickname.'/</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Ivanerr Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://ivanerr.ru/lt/history/player/'.$account->nickname.'">http://ivanerr.ru/lt/history/player/'.$account->nickname.'</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Battles '),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBattleClass($account->stat['b_al'] / 1000).'">'.$account->stat['b_al'].'</span>',
							),
							array(
								'label'=>Yii::t('wot', 'Winrate'),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWinClass($account->stat['wins']).'">'.$account->stat['wins'].'%</span>',
							),
							array(
								'label'=>Yii::t('wot', '"BroneSite" rating'),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBSClass($account->stat['bs']).'">'.$account->stat['bs'].'</span>',
							),
							array(
								'label'=>Yii::t('wot', 'Efficiency rating'),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getREClass($account->stat['re']).'">'.$account->stat['re'].'</span>',
							),
							array(
								'label'=>'WN8',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWN8Class($account->stat['wn8']).'">'.$account->stat['wn8'].'</span>',
							),
							array(
								'label'=>'Кол-во техники 10 ур.',
								'type'=>'raw',
								'value'=>$account->stat['t10'],
							),
							array(
								'label'=>'Кол-во техники 8 ур.',
								'type'=>'raw',
								'value'=>$account->stat['t8'],
							),
							array(
								'label'=>Yii::t('wot', 'Top-level tanks list'),
								'type'=>'raw',
								'value'=>WGApi::getTanksColored($account->stat['tl10']),
							),
							array(
								'label'=>'Создан',
								'type'=>'raw',
								'value'=>$account->created_at ? date('d.m.Y H:i', $account->created_at + Yii::app()->params['moscow']) : '---',
							),
							array(
								'label'=>Yii::t('wot', 'Last seen in game '),
								'type'=>'raw',
								'value'=>$account->stat['b_at'] ? date('d.m.Y H:i', $account->stat['b_at'] + Yii::app()->params['moscow']) : '---',
							),
					),
			));			?>
			</div>
		<? } ?>

	</div>

</div>