<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notices-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required"><?php echo Yii::t('wot', 'Warning!')?></span> <?php echo Yii::t('wot', 'Please, fill in the fields carefully. Once you have created a notification it can not be edited, only deleted.')?></p>
	<p class="note"><?php echo Yii::t('wot', 'The notification will disappear from users view as soon as it become unactual and it will be deleted automatically in a week.')?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'expire'); ?>
		<?php echo $form->dropDownList($model,'expire', $model->expiresAllowed); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notice'); ?>
		<?php echo $form->textArea($model,'notice',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'clans'); ?>
		<div class="checkbox"><input type="checkbox" id="select_all_clans" onClick="$('input.checkbox-clan').prop('checked', $('#select_all_clans').prop('checked'))"> <span><?php echo Yii::t('wot', 'Select all.')?></span></div>
		<?php echo CHtml::checkBoxList('Notices[clans]',explode(',',$model->clans),CHtml::listData(Clans::model()->findAll(array('order'=>'`order`')), 'id', 'abbreviation'),array('class'=>'checkbox-clan', 'container'=>false, 'separator'=>false, 'template'=>'<div class="checkbox">{input} <span>{labelTitle}</span></div>')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recipients'); ?>
		<div class="checkbox"><input type="checkbox" id="select_all_recipients" onClick="$('input.checkbox-recipient').prop('checked', $('#select_all_recipients').prop('checked'))"> <span><?php echo Yii::t('wot', 'Select all.')?></span></div>
		<?php echo CHtml::checkBoxList('Notices[recipients]',explode(',',$model->recipients),$model->recipientsAllowed,array('class'=>'checkbox-recipient', 'container'=>false, 'separator'=>false, 'template'=>'<div class="checkbox">{input} <span>{labelTitle}</span></div>')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'answers'); ?>
		<?php echo CHtml::checkBoxList('Notices[answers]',explode(',',$model->answers),$model->answersAllowed,array('container'=>false, 'separator'=>false, 'template'=>'<div class="checkbox">{input} <span>{labelTitle}</span></div>')); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_admin', 'skin'=>'light')); ?>

</div><!-- form -->