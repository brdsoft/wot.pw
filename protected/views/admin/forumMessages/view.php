<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'View Post');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Forums'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View Post')?>  #<?php echo $model->id; ?></h2>

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
			array(
				'name'=>'category_id',
				'type'=>'html',
				'value'=>$model->category_id." - ".CHtml::link($model->category->name, array('/admin/forumCategories/view', 'id'=>$model->category_id)),
			),
			array(
				'name'=>'theme_id',
				'type'=>'html',
				'value'=>$model->theme_id." - ".CHtml::link($model->theme->name, array('/admin/forumThemes/view', 'id'=>$model->theme_id)),
			),
			array(
				'name'=>'account_id',
				'type'=>'html',
				'value'=>$model->account_id." - ".CHtml::link($model->account->nickname, array('/profile/account', 'id'=>$model->account_id)),
			),
			array(
				'name'=>'message',
				'type'=>'mhtml',
				'value'=>$model->message,
			),
			array(
				'name'=>'time',
				'value'=>date("d.m.y H:i", $model->time+Yii::app()->params["moscow"]),
			),
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>