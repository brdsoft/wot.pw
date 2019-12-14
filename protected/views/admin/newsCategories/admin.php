<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - News Categories');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'News Categories')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add News Category'), array('create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
                'cssFile'=>false,
                'pager'=>array(
                    'cssFile'=>false,
                ),
                'id'=>'news-categories-grid',
                'summaryText'=>false,
                'dataProvider'=>$model->search(),
                'filter'=>$model,
                'columns'=>array(
                    'id',
                    'name',
                    'order',
                    array(
                        'htmlOptions'=>array('class'=>'adm-sed'),
                        'class'=>'CButtonColumn',
                        'updateButtonImageUrl'=>false,
                        'viewButtonImageUrl'=>false,
                        'deleteButtonImageUrl'=>false,
                        'buttons'=>array(
                            'update'=>array(
                                'visible'=>'$data->edit',
                            ),
                            'delete'=>array(
                                'visible'=>'$data->edit',
                            ),
                        ),
                    ),
                ),
            )); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>