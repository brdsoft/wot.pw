<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Clans');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Clans')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add a Clan'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'clans-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'id',
						'filter'=>CHtml::activeTextField($model, 'id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'abbreviation',
						'filter'=>CHtml::activeTextField($model, 'abbreviation', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'name',
						'filter'=>false,
					),
					array(
						'name'=>'order',
						'filter'=>false,
					),
					array(
						'htmlOptions'=>array('class'=>'adm-sed'),
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