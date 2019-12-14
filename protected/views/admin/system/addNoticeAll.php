<h2>Создание уведомления для всех</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="filling">

		<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notices-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Уведомление будет отображаться пока не будет удалено вручную.</p>
	
	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'notice'); ?>
		<?php echo $form->textArea($model,'notice',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notice'); ?>
	</div>
	<div class="both"></div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>
	
<?php $this->endWidget(); ?>

		</div><!-- form -->

	</div>

</div>
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>
</div>