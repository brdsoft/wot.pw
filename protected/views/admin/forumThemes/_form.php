<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'forum-themes-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id',CHtml::listData(ForumCategories::model()->findAll(), 'id', 'name')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'account_id'); ?>
		<?php echo $form->textField($model,'account_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fixed'); ?>
		<?php echo $form->dropDownList($model,'fixed',array('0'=>Yii::t('wot', 'Off'), '1'=>Yii::t('wot', 'On'))); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'closed'); ?>
		<?php echo $form->dropDownList($model,'closed',array('0'=>Yii::t('wot', 'Off'), '1'=>Yii::t('wot', 'On'))); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->