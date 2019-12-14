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

	<p><?php echo Yii::t('wot', 'Allowed symbols: a..z 0..9 _ - . For example, if you name your page "mycustompage" - the created page will be available at')?> <?php echo $this->site->url ?>/custom/mycustompage.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text1'); ?>
		<?php echo $form->textField($model,'text1',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text2', array()); ?>
		<?php echo $form->textArea($model,'text2', array('rows'=>20)); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->