<div class="form site-design">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sites-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'style_id'); ?>
		<p><?php echo Yii::t('wot', 'When changing to "Own CSS" - HTML codes of the footer and the header of the site will be automatically reset')?></p>
		<? if (Yii::app()->controller->site->style_id == 1 || Yii::app()->controller->site->style_id == 2){ ?>
			<?php echo $form->dropDownList($model,'style_id',CHtml::listData(Styles::model()->findAll("`enabled` = '1'"), 'id', 'name')); ?>
		<? } else { ?>
			<?php echo $form->dropDownList($model,'style_id',CHtml::listData(Styles::model()->findAll("`enabled` = '1' AND `id` != 1 AND `id` != 2"), 'id', 'name')); ?>
		<? } ?>
	</div>

	<div class="row upload-favicon">
		<?php echo $form->labelEx($model,'favicon'); ?>
		<p><?php echo Yii::t('wot', 'Favicon must be a *.PNG picture with the size of 16x16 px')?></p>
		<input type="button" value="<?php echo Yii::t('wot', 'Upload Favicon')?>" class="upload" onClick="window.open('/files?target=favicon&id=Sites_favicon', 'upload', 'width=420,height=400,resizable=no,scrollbars=no,status=no');">
		<?php echo $form->hiddenField($model,'favicon'); ?>
		<div class="upload-favicon-wrap" id="Sites_favicon_preview" <?php echo $model->favicon ? '' : 'style="display: none;"' ?>>
			<img src="<?php echo Files::model()->link($model->favicon) ?>" alt=""> <a class="upload-favicon-delet" href="#" onClick="$('#Sites_favicon').val(''); $('#Sites_favicon_preview').hide(); return false;"><?php echo Yii::t('wot', 'Delete')?></a>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'css_disabled'); ?>
		<p><?php echo Yii::t('wot', 'You can turn off ')?><a href="<?php echo Yii::app()->theme->baseUrl ?>/<?php echo $this->site->style->skin ?>/css/style.css"><?php echo Yii::t('wot', 'the CSS code of the selected template')?></a>.<br><?php echo Yii::t('wot', 'In this case you should write all the CSS codes by your own')?></p>
		<?php echo $form->dropDownList($model,'css_disabled',[Yii::t('wot', 'Enable CSS'), Yii::t('wot', 'Disable CSS')]); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'css'); ?>
		<p><?php echo Yii::t('wot', 'Your own CSS code will be added after the CSS code of the template, so it will overwrite the basic CSS code of the template')?><br><?php echo Yii::t('wot', 'View ')?><a href="<?php echo Yii::app()->theme->baseUrl ?>/<?php echo $this->site->style->skin ?>/css/style.css"><?php echo Yii::t('wot', 'the CSS code of the selected template')?></a>. <?php echo Yii::t('wot', 'CSS Manuals')?>: <a href="http://htmlbook.ru/css/">htmlbook.ru</a>, <a href="http://css.manual.ru/">css.manual.ru</a>.</p>
		<?php echo $form->textArea($model,'css',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<?php if ($model->premium_html) { ?>

	<div class="row">
		<?php echo $form->labelEx($model,'header'); ?>
		<?php echo $form->textArea($model,'header',array('rows'=>6, 'cols'=>50, 'value'=> $model->header == '' ? $this->site->getHtml('header') : null)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'footer'); ?>
		<?php echo $form->textArea($model,'footer',array('rows'=>6, 'cols'=>50, 'value'=> $model->footer == '' ? $this->site->getHtml('footer') : null)); ?>
	</div>

	<?php } ?>

	<div class="row">
		<?php echo $form->labelEx($model,'sidebar'); ?>
		<?php echo $form->textArea($model,'sidebar',array('rows'=>6, 'cols'=>50, 'value'=> $model->sidebar == '' ? $this->site->getHtml('sidebar') : null)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'index'); ?>
		<?php echo $form->textArea($model,'index',array('rows'=>6, 'cols'=>50, 'value'=> $model->index == '' ? $this->site->getHtml('index') : null)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'news'); ?>
		<?php echo $form->textArea($model,'news',array('rows'=>6, 'cols'=>50, 'value'=> $model->news == '' ? $this->site->getHtml('news') : null)); ?>
	</div>


	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->