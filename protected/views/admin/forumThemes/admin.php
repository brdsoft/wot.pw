<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Forum Topics');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Forum Topics')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add Topic'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'forum-themes-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'id',
						'filter'=>CHtml::activeTextField($model, 'id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'category_id',
						'filter'=>CHtml::listData(ForumCategories::model()->findAll(), 'id', 'name'),
						'type'=>'html',
						'value'=>'$data->category_id." - ".CHtml::link($data->category->name, array("/admin/forumCategories/view", "id"=>$data->category_id))',
					),
					array(
						'name'=>'account_id',
						'type'=>'html',
						'value'=>'$data->account_id." - ".CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account_id))',
						'filter'=>CHtml::activeTextField($model, 'account_id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'name',
						'filter'=>CHtml::activeTextField($model, 'name', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'fixed',
						'filter'=>array('0'=>Yii::t('wot', 'Off'), '1'=>Yii::t('wot', 'On')),
						'value'=>'$data->fixed ? Yii::t("wot", "On") : Yii::t("wot", "Off")',
					),
					array(
						'name'=>'closed',
						'filter'=>array('0'=>Yii::t('wot', 'Off'), '1'=>Yii::t('wot', 'On')),
						'value'=>'$data->closed ? Yii::t("wot", "On") : Yii::t("wot", "Off")',
					),
					array(
						'name'=>'time',
						'filter'=>false,
						'value'=>'date("d.m.y", $data->time+Yii::app()->params["moscow"])',
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