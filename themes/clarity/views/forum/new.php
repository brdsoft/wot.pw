<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="modul forum addtheme">
	<div class="title">
		<h3><?php echo Yii::t('wot', 'Forum')?> &mdash; <?php echo Yii::t('wot', 'New Topic')?></h3>
	</div>
	<div class="cell">

		<h3 class="category-title"><?php echo Yii::t('wot', 'Add new topic')?></h3>

		<div class="form">

			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'forumThemes-form',
				'enableAjaxValidation'=>false,
			)); ?>

			<?php echo $form->errorSummary(array($theme, $message)); ?>

			<div class="row input">
				<?php echo $form->textField($theme,'name',array('maxlength'=>255)); ?>
				<label><?php echo Yii::t('wot', 'Topic name')?></label>
			</div>

			<div class="row">
				<?php echo $form->textArea($message,'message'); ?>
			</div>

			<div class="row buttons">
				<?php echo CHtml::submitButton(Yii::t('wot', 'Create')); ?>
			</div>
			<?php $this->endWidget(); ?>

		</div>

	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>'dark')); ?>