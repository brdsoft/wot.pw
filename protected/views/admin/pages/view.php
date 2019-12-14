<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Page Viewer');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Static pages'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View Page')?> <?php echo $model->name; ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete this page?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
				'cssFile'=>false,
				'data'=>$model,
				'attributes'=>array(
					'name'=>'name',
					'text1',
					array(
							'name'=>'text2',
							'type'=>'mhtml',
							'value'=>$model->text2,
					),
					array(
					'name'=>'account_id',
					'type'=>'html',
					'value'=>$model->account_id." - ".CHtml::link($model->account->nickname, array('/profile/account', 'id'=>$model->account_id)),
					),
					array(
					'label'=>Yii::t('wot', 'Link to the website page'),
					'type'=>'html',
					'value'=>CHtml::link('http://'.$this->site->url.'/page/'.$model->name, array('/page/index', 'id'=>$model->name)),
					),
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>