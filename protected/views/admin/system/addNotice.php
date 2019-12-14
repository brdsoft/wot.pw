<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>

<h2>Создание уведомления</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="filling">

		<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notices-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные символом <span class="required">*</span> обязательны.</p>
	<p class="note"><span class="required">Важно!</span> Заполняйте поля внимательно. Созданное уведомление уже нельзя редактировать, можно только удалить.</p>
	<p class="note">Уведомление перестанет отображаться у пользователей по истечении актуальности и будет удалено автоматически через неделю.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'expire'); ?>
		<?php echo $form->dropDownList($model,'expire', $model->expiresAllowed); ?>
		<?php echo $form->error($model,'expire'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notice'); ?>
		<?php echo $form->textArea($model,'notice',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notice'); ?>
	</div>
	<div class="both"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_admin', 'skin'=>'light')); ?>

		</div><!-- form -->

	</div>

</div>
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>
</div>