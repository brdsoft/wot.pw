<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Company Viewer');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Companies'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View company')?> &laquo;<?php echo $model->name; ?>&raquo;</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete this company?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
				'cssFile'=>false,
				'data'=>$model,
				'attributes'=>array(
					array(
						'name'=>'clan_id',
						'value'=>'['.$model->clan->abbreviation.'] '.$model->clan->name,
					),
					'name',
					'order',
					array(
						'name'=>'accounts',
						'type'=>'html',
						'value'=>implode('<br>', array_intersect_key(CHtml::listData(Accounts::model()->findAllByAttributes(array('clan_id'=>$model->clan_id)), 'id', 'nickname'), array_flip(explode(',', $model->accounts)))),
					),
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>