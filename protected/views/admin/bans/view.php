<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'View ban');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Banlist'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View ban')?> "<?php echo $model->account->nickname; ?>"</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
                'cssFile'=>false,
                'data'=>$model,
                'attributes'=>array(
                    array(
                        'name'=>'account_id',
                        'type'=>'html',
                        'value'=>$model->account_id." - ".CHtml::link($model->account->nickname, array('/profile/account', 'id'=>$model->account_id)),
                    ),
                    array(
                        'name'=>'time',
                        'value'=>Yii::app()->dateFormatter->formatDateTime($model->time+Yii::app()->params["moscow"], 'long', 'short'),
                    ),
                    array(
                        'name'=>'expire',
                        'value'=>Yii::app()->dateFormatter->formatDateTime($model->expire+Yii::app()->params["moscow"], 'long', 'short'),
                    ),
                    array(
                        'name'=>'author_id',
                        'type'=>'html',
                        'value'=>$model->author_id." - ".CHtml::link($model->author->nickname, array('/profile/account', 'id'=>$model->author_id)),
                    ),
                ),
            )); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>