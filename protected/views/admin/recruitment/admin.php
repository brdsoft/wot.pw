<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Военкомат');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Военкомат')?></h2>

<div class="content-inner-page">

    <div class="content-column">

        <div class="subnav">
            <?php echo CHtml::link(Yii::t('wot', 'Удалить все заявки'), array('create'), ['csrf'=>true, 'submit'=>array('deleteAll'), 'confirm'=>Yii::t('wot', 'Внимание! Удалить все заявки из военкомата?'),]) ?>
        </div>

        <div class="filling">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'cssFile'=>false,
                'pager'=>array(
                    'cssFile'=>false,
                ),
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
                        'name'=>'resolution_account',
                        'type'=>'html',
                        'value'=>'$data->resolutionAccount ? ($data->resolution_account." - ".CHtml::link($data->resolutionAccount->nickname, array("/profile/account", "id"=>$data->resolution_account))) : "---"',
                        'filter'=>CHtml::activeTextField($model, 'resolution_account', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
                    ),
                    array(
                        'name'=>'resolution_time',
                        'value'=> '$data->resolution_time ? Yii::app()->dateFormatter->formatDateTime($data->resolution_time+Yii::app()->params["moscow"], "long", "short") : "---"',
                        'filter'=>false,
                    ),
                    array(
                        'htmlOptions'=>array('class'=>'adm-sed'),
                        'class'=>'CButtonColumn',
                        'template'=>'{delete}',
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