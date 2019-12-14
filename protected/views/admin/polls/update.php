<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'Edit Poll');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Polls'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'Edit Poll')?> "<?php echo CHtml::encode($model->question); ?>"</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete this poll?'))) ?>
		</div>

		<div class="filling">

			<?php $this->renderPartial('_form', array('model'=>$model)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>