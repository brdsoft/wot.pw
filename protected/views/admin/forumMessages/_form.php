<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'forum-messages-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'theme_id'); ?>
		<?php echo $form->textField($model,'theme_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'account_id'); ?>
		<?php echo $form->textField($model,'account_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textArea($model,'message'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_admin', 'skin'=>'light')); ?>

</div><!-- form -->