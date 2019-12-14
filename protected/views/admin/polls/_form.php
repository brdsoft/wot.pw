<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'question'); ?>
		<?php echo $form->textField($model,'question',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->checkBox($model,'enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'access'); ?>
		<?php echo $form->dropDownList($model,'access', $model->accessAllowed); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'answer1'); ?>
		<?php echo $form->textField($model,'answer1',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer2'); ?>
		<?php echo $form->textField($model,'answer2',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer3'); ?>
		<?php echo $form->textField($model,'answer3',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer4'); ?>
		<?php echo $form->textField($model,'answer4',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer5'); ?>
		<?php echo $form->textField($model,'answer5',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer6'); ?>
		<?php echo $form->textField($model,'answer6',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer7'); ?>
		<?php echo $form->textField($model,'answer7',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer8'); ?>
		<?php echo $form->textField($model,'answer8',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer9'); ?>
		<?php echo $form->textField($model,'answer9',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'answer10'); ?>
		<?php echo $form->textField($model,'answer10',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->