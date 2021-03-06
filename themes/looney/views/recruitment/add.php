<?php
/* @var $this SiteController */

$this->pageTitle='Заявка на вступление в клан';

?>

<div class="modul recruitment">

	<div class="custom-title">
		<h4>Подать заявку</h4>
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

			<? if ($action == 'ok'){ ?>
				<p>Спасибо! Ваша заявка принята.</p>
				<p>Заявка будет обработана в ближайшее время. Список всех заявок доступен в <?php echo CHtml::link('военкомате', array('/recruitment/index')) ?>.</p>
			<? } ?>
			<? if ($action == 'guest'){ ?>
				<p>Чтобы подать заявку на вступление в клан вы должны войти на сайт через Wargaming.net ID.</p>
				<p>Для входа на сайт перейдите по <?php echo CHtml::link('этой ссылке', array('/api/login')) ?>. Авторизация через Wargaming.net ID абсолютно безопасна.</p>
			<? } ?>
			<? if ($action == 'clan'){ ?>
				<p>Вы уже состоите в клане [<?php echo Yii::app()->user->account->clan_abbreviation ?>] <?php echo Yii::app()->user->account->clan_name ?></p>
				<p>Если вы вышли из клана, подождите, пока обновятся данные на нашем сайте (не более 10 минут).</p>
			<? } ?>
			<? if ($action == 'alredy'){ ?>
				<p>Вы уже отправили заявку.</p>
				<p>Пожалуйста, дождитесь ее рассмотрения.</p>
			<? } ?>


			<? if ($action == 'form'){ ?>

				<p>Перед отправкой заявки ознакомьтесь с <?php echo CHtml::link('требованиями к кандидатам', array('page/index', 'id'=>'requirements')) ?>.</p>
				<p>Поля, отмеченные символом <span class="required">*</span> обязательны для заполнения.</p>

				<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'recruitment-form',
						'enableAjaxValidation'=>false,
				)); ?>
				<?php echo $form->errorSummary($model); ?>
						<div class="row">
								<?php echo $form->labelEx($model,'clan_id'); ?>
								<?php echo CHtml::dropDownList('Recruitment[clan_id]', $model->clan_id, CHtml::listData(Clans::model()->findAll(array('order'=>"`order`")), 'id', function($clan){return '['.$clan->abbreviation.'] '.$clan->name;})); ?>
								<?php echo $form->error($model,'clan_id'); ?>
						</div>
						<div class="row">
								<?php echo $form->labelEx($model,'name'); ?>
								<?php echo $form->textField($model,'name'); ?>
								<?php echo $form->error($model,'name'); ?>
						</div>

						<div class="row">
								<?php echo $form->labelEx($model,'age'); ?>
								<?php echo $form->textField($model,'age'); ?>
								<?php echo $form->error($model,'age'); ?>
						</div>

						<div class="row">
								<?php echo $form->labelEx($model,'experience'); ?>
								<?php echo $form->dropDownList($model,'experience', array('Нет'=>'Нет', 'Командные бои'=>'Командные бои', 'Чемпионские роты'=>'Чемпионские роты', 'Абсолютные роты'=>'Абсолютные роты', 'Мировая война'=>'Мировая война')); ?>
								<?php echo $form->error($model,'experience'); ?>
						</div>

						<div class="row">
								<?php echo $form->labelEx($model,'about'); ?>
								<?php echo $form->textArea($model,'about'); ?>
								<?php echo $form->error($model,'about'); ?>
						</div>
						
						<div class="row buttons">
								<?php echo CHtml::submitButton('Отправить'); ?>
						</div>

				<?php $this->endWidget(); ?>
				</div>
			<? } ?>

	</div>

</div>