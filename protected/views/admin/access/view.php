<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'View Restriction');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Access Restriction'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View Restriction')?> #<?php echo $model->id; ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
				'cssFile'=>false,
				'data'=>$model,
				'attributes'=>array(
					'id',
					'url',
					array(
						'name'=>'clans',
						'type'=>'html',
						'value'=>implode('<br>', array_intersect_key(CHtml::listData(Clans::model()->findAll(), 'id', 'abbreviation'), array_flip(explode(',', $model->clans)))),
					),
					array(
						'name'=>'roles',
						'type'=>'html',
						'value'=>implode('<br>', array_intersect_key($model->rolesAllowed, array_flip(explode(',', $model->roles)))),
					),
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>