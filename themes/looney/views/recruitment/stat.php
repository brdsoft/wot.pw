<?php
/* @var $this SiteController */

$this->pageTitle='Статистика произвольного игрока';

?>

<div class="modul recruitment">

	<div class="custom-title">
		<h4>Статистика произвольного игрока</h4>
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

<? if (!$this->site->isPremium || $this->site->id == 1){ ?>
		<div class="adsbygoogle" style="height:90px; margin-bottom:18px;">
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
		<p>Здесь вы можете узнать статистику любого игрока World of Tanks</p>

		<div class="form">

			<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'stat-form',
					'enableAjaxValidation'=>false,
			)); ?>
			<?php echo $form->errorSummary($model); ?>
			<div class="row">
					<?php echo $form->labelEx($model,'nickname'); ?>
					<?php echo $form->textField($model,'nickname'); ?>
					<?php echo $form->error($model,'nickname'); ?>
			</div>
			<div class="row buttons">
					<?php echo CHtml::submitButton('Отправить'); ?>
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
								'label'=>'Никнейм в игре',
								'type'=>'raw',
								'value'=>$account->nickname,
							),
							array(
								'label'=>'Профиль WoT',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/">http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/</a>',
							),
							array(
								'label'=>'Профиль Бронесайт',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'">http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль КТТС',
								'type'=>'raw',
                                'value'=>'<a target="_blank" href="https://kttc.ru/wot/ru/user/'.$account->nickname.'/">https://kttc.ru/wot/ru/user/'.$account->nickname.'/</a>',
							),
							array(
								'label'=>'Профиль Иванерр',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://ivanerr.ru/lt/history/player/'.$account->nickname.'">http://ivanerr.ru/lt/history/player/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Боев',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBattleClass($account->stat['b_al'] / 1000).'">'.$account->stat['b_al'].'</span>',
							),
							array(
								'label'=>'Побед',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWinClass($account->stat['wins']).'">'.$account->stat['wins'].'%</span>',
							),
							array(
								'label'=>'Бронесайт',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBSClass($account->stat['bs']).'">'.$account->stat['bs'].'</span>',
							),
							array(
								'label'=>'РЭ',
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
								'label'=>'Список ТОП Техники',
								'type'=>'raw',
								'value'=>WGApi::getTanksColored($account->stat['tl10']),
							),
							array(
								'label'=>'Создан',
								'type'=>'raw',
								'value'=>$account->created_at ? date('d.m.Y H:i', $account->created_at + Yii::app()->params['moscow']) : '---',
							),
							array(
								'label'=>'Был в бою',
								'type'=>'raw',
								'value'=>$account->stat['b_at'] ? date('d.m.Y H:i', $account->stat['b_at'] + Yii::app()->params['moscow']) : '---',
							),
					),
			));			?>
			</div>
		<? } ?>

	</div>

</div>