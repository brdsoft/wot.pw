<?php
$this->menu=array(
	array('label'=>'Настройки', 'url'=>array('/admin/config')),
);
?>

<h2>Удаление конфига</h2>

		<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-add-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

		</div><!-- form -->

	</div>

</div>