<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bans-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('wot', 'Banned player will not be possible to enroll in the your recruiting center, leave comments, create topics, write messages at the forum and change profile data.')?></p>
	<p class="note"><?php echo Yii::t('wot', 'In the "Account" field - enter the ID of the player.')?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'account_id'); ?>
		<?php echo $form->textField($model,'account_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'expire'); ?>
		<?php echo $form->dropDownList($model,'expire', $model->expiresAllowed); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
