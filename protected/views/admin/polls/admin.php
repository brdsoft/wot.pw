<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'Polls');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Polls')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add a poll'), array('create')) ?>
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
                        'name'=>'question',
                        'filter'=>CHtml::activeTextField($model, 'question', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
                    ),
                    array(
                        'name'=>'time',
                        'value'=> 'Yii::app()->dateFormatter->formatDateTime($data->time+Yii::app()->params["moscow"], "long", false)',
                        'filter'=>false,
                    ),
                    array(
                        'name'=>'enabled',
                        'value'=> '$data->enabled ? Yii::t("wot", "On") : Yii::t("wot", "Off")',
                        'filter'=>CHtml::activeDropDownList($model, 'enabled', [Yii::t("wot", "Off"), Yii::t("wot", "On")], ['prompt'=>'']),
                    ),
                    array(
                        'name'=>'access',
                        'value'=> '$data->accessAllowed[$data->access]',
                        'filter'=>CHtml::activeDropDownList($model, 'access', $model->accessAllowed, ['prompt'=>'']),
                    ),
                    array(
                        'header'=>'Код для вставки',
                        'value'=>'"{POLL id=".$data->id." title=\"Опрос\"}"',
                        'htmlOptions'=>['style'=>'white-space: nowrap;'],
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