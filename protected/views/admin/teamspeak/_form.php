<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ts-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required"><?php echo Yii::t('wot', 'Warning!')?></span> <?php echo Yii::t('wot', 'Перед созданием сервера обязательно ознакомьтесь с <a href="/admin/teamspeak/rules">правилами</a> оказания услуг хостинга TeamSpeak.')?></p>
	<p class="note"><span class="required"><?php echo Yii::t('wot', 'Warning!')?></span> <?php echo Yii::t('wot', 'Для создания сервера баланс вашего сайта должен быть не менее :minBalance рублей.', [':minBalance'=>$model->minBalance])?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'snapshot'); ?>
		<?php echo $form->dropDownList($model,'snapshot', $model->snapshots); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->