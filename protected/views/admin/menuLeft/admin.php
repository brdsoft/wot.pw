<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Sidebar Menu');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span><?php echo Yii::t('wot', 'Sidebar Menu')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add menu item'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'menu-left-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'columns'=>array(
					'label',
					array(
						'name'=>'url',
						'value'=>'mb_strlen($data->url) > 60 ? mb_substr($data->url, 0, 60)."..." : $data->url',
					),
					'order',
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