<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Access Restriction');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Access Restriction')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add Restriction'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'access-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'url',
						'filter'=>CHtml::activeTextField($model, 'url', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'clans',
						'type'=>'html',
						'filter'=>false,
						'value'=>'implode(", ", array_intersect_key(CHtml::listData(Clans::model()->findAll(), "id", "abbreviation"), array_flip(explode(",", $data->clans))))',
					),
					array(
						'name'=>'roles',
						'filter'=>false,
						'value'=>'implode(", ", array_intersect_key($data->rolesAllowed, array_flip(explode(",", $data->roles))))',
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