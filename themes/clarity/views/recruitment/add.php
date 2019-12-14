<?php
/* @var $this SiteController */

$this->pageTitle=Yii::t('wot', 'Enrollment Application');

?>

<div class="modul recruitment recr-add">

	<div class="title">
		<h3><?php echo Yii::t('wot', 'Apply')?></h3>
	</div>
	<div class="cell">

	<ul class="recruitment-nav">
		<li><a class="recr-index" href="/recruitment"><?php echo Yii::t('wot', 'Recruting Station')?></a></li>
		<li class="active"><?php echo CHtml::link(Yii::t('wot', 'Apply'), array('recruitment/add'), array('class'=>'recr-add')) ?></li>
		<li><?php echo CHtml::link(Yii::t('wot', 'Terms of Recruitment'), array('page/index', 'id'=>'requirements'), array('class'=>'recr-cand')) ?></li>
	<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('4') || Yii::app()->user->checkAccess('8')){ ?>
	<li><?php echo CHtml::link(Yii::t('wot', 'Any player stats'), array('recruitment/stat'), array('class'=>'recr-stat')) ?></li>
	<? } ?>
	</ul>

			<? if ($action == 'ok'){ ?>
				<p<?php echo Yii::t('wot', 'Thank you! Your application has been submitted! ')?></p>
				<p><?php echo Yii::t('wot', 'Your application will be reviewed in short time. All applications are available at the ')?><?php echo CHtml::link(Yii::t('wot', 'Recruting Station '), array('/recruitment/index')) ?>.</p>
			<? } ?>
			<? if ($action == 'guest'){ ?>
				<p><?php echo Yii::t('wot', 'To submit your application you should login with your Wargaming.net ID.')?></p>
				<p><?php echo Yii::t('wot', 'To proceed to the website click on ')?> <?php echo CHtml::link(Yii::t('wot', 'this link'), array('/api/login')) ?>. <?php echo Yii::t('wot', 'Authorization with Wargaming.net ID is absolutely secure.')?></p>
			<? } ?>
			<? if ($action == 'clan'){ ?>
				<p><?php echo Yii::t('wot', 'You are already in a clan')?> [<?php echo Yii::app()->user->account->clan_abbreviation ?>] <?php echo Yii::app()->user->account->clan_name ?></p>
				<p><?php echo Yii::t('wot', 'If you have just left your clan - please, wait for the data to be updated on our servers (near 10 minutes).')?></p>
			<? } ?>
			<? if ($action == 'alredy'){ ?>
				<p><?php echo Yii::t('wot', 'You have already applied.')?></p>
				<p><?php echo Yii::t('wot', 'Please, wait for the application being reviewed.')?></p>
			<? } ?>


			<? if ($action == 'form'){ ?>

				<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'recruitment-form',
						'enableAjaxValidation'=>false,
				)); ?>
				<?php echo $form->errorSummary($model); ?>

					<div class="row">
						<?php echo CHtml::dropDownList('Recruitment[clan_id]', $model->clan_id, CHtml::listData(Clans::model()->findAll(array('order'=>"`order`")), 'id', function($clan){return '['.$clan->abbreviation.'] '.$clan->name;})); ?>
						<?php echo $form->labelEx($model,'clan_id'); ?>
					</div>

					<div class="row">
						<?php echo $form->textField($model,'name'); ?>
						<?php echo $form->labelEx($model,'name'); ?>
					</div>

					<div class="row">
						<?php echo $form->textField($model,'age'); ?>
						<?php echo $form->labelEx($model,'age'); ?>
					</div>

					<div class="row">
						<?php echo $form->dropDownList($model,'experience', array(Yii::t('wot', 'No')=>Yii::t('wot', 'No'), Yii::t('wot', 'Team Battles')=>Yii::t('wot', 'Team Battles'), Yii::t('wot', 'Champion Tank Companies')=>Yii::t('wot', 'Champion Tank Companies'), Yii::t('wot', 'Absolute Tank Companies')=>Yii::t('wot', 'Absolute Tank Companies'), Yii::t('wot', 'Clan Wars')=>Yii::t('wot', 'Clan Wars'))); ?>
						<?php echo $form->labelEx($model,'experience'); ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($model,'about'); ?>
						<?php echo $form->textArea($model,'about'); ?>
					</div>

					<div class="row">
						<?php echo CHtml::submitButton(Yii::t('wot', 'Apply')); ?>
					</div>

				<?php $this->endWidget(); ?>
				</div>
			<? } ?>

	</div>

</div>