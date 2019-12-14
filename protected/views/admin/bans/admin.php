<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Banlist');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Banlist')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add Ban'), array('create')) ?>
		</div>

		<div class="filling">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'bans-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
							'name'=>'account_id',
							'type'=>'html',
							'value'=>'$data->account_id." - ".CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account_id))',
							'filter'=>CHtml::activeTextField($model, 'account_id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
							'name'=>'time',
							'value'=> 'Yii::app()->dateFormatter->formatDateTime($data->time+Yii::app()->params["moscow"], "long", "short")',
							'filter'=>false,
					),
					array(
							'name'=>'expire',
							'value'=> 'Yii::app()->dateFormatter->formatDateTime($data->expire+Yii::app()->params["moscow"], "long", "short")',
							'filter'=>false,
					),
					array(
							'name'=>'author_id',
							'type'=>'html',
							'value'=>'$data->author_id." - ".CHtml::link($data->author->nickname, array("/profile/account", "id"=>$data->author_id))',
							'filter'=>CHtml::activeTextField($model, 'author_id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
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