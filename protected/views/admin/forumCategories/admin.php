<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Forums');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Forums')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add Forum'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'forum-categories-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'group_id',
						'filter'=>CHtml::listData(ForumGroups::model()->findAll(), 'id', 'name'),
						'type'=>'html',
						'value'=>'$data->group_id." - ".CHtml::link($data->group->name, array("/admin/forumGroups/view", "id"=>$data->group_id))',
					),
					array(
						'name'=>'name',
						'filter'=>CHtml::activeTextField($model, 'name', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'description',
						'filter'=>CHtml::activeTextField($model, 'description', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'order',
						'filter'=>CHtml::activeTextField($model, 'order', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
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