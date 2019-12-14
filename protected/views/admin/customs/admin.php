<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'HTML Pages');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'HTML Pages')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add a page'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
                'cssFile'=>false,
                'pager'=>array(
                    'cssFile'=>false,
                ),
                'id'=>'pages-grid',
                'summaryText'=>false,
                'dataProvider'=>$model->search(),
                'filter'=>$model,
                'columns'=>array(
                    array(
                        'name'=>'name',
                        'filter'=>CHtml::activeTextField($model, 'name', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
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
                        'header'=>Yii::t('wot', 'Link'),
                        'type'=>'html',
                        'value'=>'CHtml::link("/custom/".$data->name, array("/custom/index", "id"=>$data->name))',
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