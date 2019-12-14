<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - News');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'News Feed')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add news'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
						'cssFile'=>false,
				),
				'id'=>'news-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
							'name'=>'time',
							'value'=> 'date("d M Y", $data->time+Yii::app()->params["moscow"])',
							'filter'=>false,
					),
					array(
							'name'=>'category_id',
							'value'=> '$data->category->name',
							'filter'=>false,
					),
					array(
							'name'=>'text1',
							'filter'=>CHtml::activeTextField($model, 'text1', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
							'name'=>'account_id',
							'type'=>'html',
							'value'=>'$data->account_id." - ".CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account_id))',
							'filter'=>CHtml::activeTextField($model, 'account_id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
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