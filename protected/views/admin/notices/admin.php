<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Notifications');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Notifications')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add a notification'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
                'cssFile'=>false,
                'pager'=>array(
                    'cssFile'=>false,
                ),
                'id'=>'notices-grid',
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
                        'filter'=>false,
                        'value'=>'date("d.m.y H:i", $data->time+Yii::app()->params["moscow"])',
                    ),
                    array(
                        'name'=>'expire',
                        'filter'=>false,
                        'value'=>'date("d.m.y H:i", $data->expire+Yii::app()->params["moscow"])',
                    ),
                    array(
                        'name'=>'notice',
                        'type'=>'html',
                        'value'=>'$data->notice',
                        'filter'=>false,
                        'sortable'=>false,
                    ),
                    /*
                    'recipients',
                    */
                    array(
                        'template'=>'{view} {delete}',
                        'htmlOptions'=>array('class'=>'adm-sed-2'),
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