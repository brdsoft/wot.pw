<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

?>

<div class="block">
	<div class="head head2">
		<?php echo CHtml::link('Форум', array('/forum')) ?> - Новая тема
	</div>
	<div class="body body2">
		<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'forumThemes-form',
				'enableAjaxValidation'=>false,
			)); ?>
			<p class="note">Поля, отмеченные символом <span class="required">*</span> обязательны.</p>
			<?php echo $form->errorSummary(array($theme, $message)); ?>
			<div class="row">
				<?php echo $form->labelEx($theme,'name'); ?>
				<?php echo $form->textField($theme,'name',array('maxlength'=>255)); ?>
				<?php echo $form->error($theme,'name'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($message,'message'); ?>
				<?php echo $form->textArea($message,'message'); ?>
				<div class="both"></div>
				<?php echo $form->error($message,'message'); ?>
			</div>
			<div class="both"></div>

			<div class="row buttons">
				<?php echo CHtml::submitButton('Отправить'); ?>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>preg_match('=^2$=', $this->site->style_id) ? 'light' : 'dark')); ?>