<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="modul">
	<div class="custom-title">
		<h4><?php echo CHtml::link('Форум', array('/forum')) ?> &mdash; Новая тема</h4>
	</div>
	<div class="pastic">

		<div class="form">

			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'forumThemes-form',
				'enableAjaxValidation'=>false,
			)); ?>

			<?php echo $form->errorSummary(array($theme, $message)); ?>

			<div class="row">
				<label>Название темы</label>
				<?php echo $form->textField($theme,'name',array('maxlength'=>255)); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($message,'message'); ?>
				<?php echo $form->textArea($message,'message'); ?>
			</div>

			<div class="row">
				<?php echo CHtml::submitButton('Создать'); ?>
			</div>
			<?php $this->endWidget(); ?>

		</div>

	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>'light')); ?>