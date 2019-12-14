<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'View Menu Item');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Sidebar Menu'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View Menu Item')?> #<?php echo $model->id; ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete this menu item?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
				'cssFile'=>false,
				'data'=>$model,
				'attributes'=>array(
					'id',
					'label',
					array(
						'name'=>'url',
						'value'=>mb_strlen($model->url) > 60 ? mb_substr($model->url, 0, 60)."..." : $model->url,
					),
					'order',
					'highlight',
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>