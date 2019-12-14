<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'clans-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p><?php echo Yii::t('wot', 'To add a clan you need to know its ID. It can found in the address bar on the page of the clan in the official website of Wargaming.')?><br><?php echo Yii::t('wot', 'Example: Clan')?> [WOTPW] http://ru.wargaming.net/clans/<strong>200400</strong>/, <strong>200400</strong>&nbsp;&mdash; ID&nbsp;клана.</p>

	<p><?php echo Yii::t('wot', 'After adding a clan, you have three days to add a link on the page of the clan on the official Wargaming Clan List')?> <a href="http://ru.wargaming.net/clans/">http://ru.wargaming.net/clans/</a>. <?php echo Yii::t('wot', 'Clans without such link will be deleted automatically.')?></p>

	<!--<p class="note"><?php echo Yii::t('wot', 'Do never add to your site clans from other alliances without their consent. Such actions will lead to site removal.') ?></p>
	<p class="note"><?php echo Yii::t('wot', 'To add a clan you need to know its ID. It can found in the address bar on the page of the clan in the official website of WG. Example: Clan "[BS] Black Label" ID = 165 ') ?>(http://ru.wargaming.net/clans/<span style="color: red;">165</span>/)</p>
	<p class="note"><?php echo Yii::t('wot', 'After adding a clan, you have three days to add a link on the official page of the clan on the WG-site to any page of this site.'),Yii::t('wot', 'You can do this editing clans description. Clans without such links will be deleted automatically.') ?></p>-->

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->