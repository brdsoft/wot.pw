<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Companies');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Companies')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add a Company'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'companies-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'clan_id',
						'value'=>'$data->clan->abbreviation',
					),
					'name',
					'order',
					array(
						'filter'=>false,
						'name'=>'accounts',
						'value'=>'$data->accounts ? count(explode(",", $data->accounts)) : 0',
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