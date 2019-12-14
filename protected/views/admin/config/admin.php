<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Main Settings');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Main Settings')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div style="border: 1px solid #e5e5e5; padding: 18px 19px 0 19px; background: #fff; margin-bottom: 20px;">
			<a href="#" onClick="$('#danger_form').slideToggle(300);" style="display: inline-block; margin-bottom: 18px; text-decoration: none; border-bottom: 1px dashed;">
				<?php echo Yii::t('wot', 'Warning! Crucial Site Settings')?>
			</a>
			<div id="danger_form" class="form site-design" style="display: none;">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'sites-form',
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// There is a call to performAjaxValidation() commented in generated controller code.
				// See class documentation of CActiveForm for details on this.
				'enableAjaxValidation'=>false,
			)); ?>
			
				<?php echo $form->errorSummary($site); ?>
				<div class="row">
					<?php echo $form->labelEx($site,'language'); ?>
					<?php echo $form->dropDownList($site,'language',Yii::app()->params['languages']); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($site,'reset'); ?>
					<?php echo $form->checkBox($site,'reset'); ?>
				</div>
				<p><?php echo Yii::t('wot', 'WARNING! Changing the language and site reset will lead to the installation initial state of the top and sidebar menus, main settings, preinstalled static pages, all editable HTML fields. Everything else remains unchanged.') ?></p>
				<div class="row">
					<?php echo CHtml::submitButton(Yii::t('wot', 'Save')); ?>
				</div>
			
			<?php $this->endWidget(); ?>
			</div>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'config-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'category',
						'filter'=>CHtml::listData(Config::model()->findAll(), 'category', 'category'),
					),
					array(
						'name'=>'description',
						'filter'=>CHtml::activeTextField($model, 'description', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'value',
						'value'=>'$data->displayValue()',
						'filter'=>CHtml::activeTextField($model, 'value', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'htmlOptions'=>array('class'=>'adm-sed-2'),
						'template'=>'{view} {update}',
						'class'=>'CButtonColumn',
						'updateButtonImageUrl'=>false,
						'viewButtonImageUrl'=>false,
						'deleteButtonImageUrl'=>false,
					),
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>