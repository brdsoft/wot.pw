<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'accounts-roles-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<label for="AccountsRoles_account_id" class="required"><?php echo Yii::t('wot', 'Account ID')?> <span class="required">*</span></label>
		<?php echo $form->textField($model,'account_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role_id'); ?>
		<?php echo $form->dropDownList($model,'role_id',CHtml::listData(Roles::model()->findAll(), 'id', 'name')); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->