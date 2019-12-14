<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'news-categories-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'order'); ?>
		<?php echo $form->textField($model,'order',array('size'=>60,'maxlength'=>11)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
