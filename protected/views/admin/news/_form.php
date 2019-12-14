<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>

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
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id',CHtml::listData(NewsCategories::model()->findAll(), 'id', 'name')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text1'); ?>
		<?php echo $form->textField($model,'text1',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<a class="file-upload-news fancybox.iframe" href="/files?target=news&id=News_image&v=2"><?php echo Yii::t('wot', 'Add a picture')?></a>
		<?php echo $form->hiddenField($model,'image'); ?>
		<div class="upload-image-preview" id="News_image_preview" <?php echo $model->image ? '' : 'style="display: none;"' ?>>
			<img src="<?php echo Files::model()->link($model->image) ?>" alt="">
			<a class="upload-image-delet" href="#" onClick="$('#News_image').val(''); $('#News_image_preview').hide(); return false;"></a>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text2'); ?>
		<?php echo $form->textArea($model,'text2',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text3'); ?>
		<?php echo $form->textArea($model,'text3',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_admin', 'skin'=>'light')); ?>

</div><!-- form -->