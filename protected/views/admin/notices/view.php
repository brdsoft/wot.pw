<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Notification Viewer');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Notifications'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View Notification')?> #<?php echo $model->id; ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete this notification?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
                'cssFile'=>false,
                'data'=>$model,
                'attributes'=>array(
                    'id',
                    array(
                        'name'=>'account_id',
                        'type'=>'html',
                        'value'=>$model->account_id." - ".CHtml::link($model->account->nickname, array('/profile/account', 'id'=>$model->account_id)),
                    ),
                    array(
                        'name'=>'time',
                        'value'=>date('d.m.Y H:i', $model->time+Yii::app()->params["moscow"]),
                    ),
                    array(
                        'name'=>'expire',
                        'value'=>date('d.m.Y H:i', $model->expire+Yii::app()->params["moscow"]),
                    ),
                    array(
                        'name'=>'notice',
                        'type'=>'html',
                    ),
                    array(
                        'name'=>'clans',
                        'type'=>'html',
                        'value'=>implode('<br>', array_intersect_key(CHtml::listData(Clans::model()->findAll(), 'id', 'abbreviation'), array_flip(explode(',', $model->clans)))),
                    ),
                    array(
                        'name'=>'recipients',
                        'type'=>'html',
                        'value'=>implode('<br>', array_intersect_key($model->recipientsAllowed, array_flip(explode(',', $model->recipients)))),
                    ),
                    array(
                        'name'=>'answers',
                        'type'=>'html',
                        'value'=>implode('<br>', array_intersect_key($model->answersAllowed, array_flip(explode(',', $model->answers)))),
                    ),
                    array(
                        'name'=>'recipientsCount',
                        'type'=>'html',
                        'value'=>CHtml::link($model->recipientsCount, array('/admin/noticesRecipients?NoticesRecipients[notices_id]='.$model->id)),
                    ),
                    array(
                        'name'=>'recipientsCount1',
                        'type'=>'html',
                        'value'=>CHtml::link($model->recipientsCount1, array('/admin/noticesRecipients?NoticesRecipients[notices_id]='.$model->id.'&NoticesRecipients[answer]=1')),
                    ),
                    array(
                        'name'=>'recipientsCount2',
                        'type'=>'html',
                        'value'=>CHtml::link($model->recipientsCount2, array('/admin/noticesRecipients?NoticesRecipients[notices_id]='.$model->id.'&NoticesRecipients[answer]=2')),
                    ),
                    array(
                        'name'=>'recipientsCount3',
                        'type'=>'html',
                        'value'=>CHtml::link($model->recipientsCount3, array('/admin/noticesRecipients?NoticesRecipients[notices_id]='.$model->id.'&NoticesRecipients[answer]=3')),
                    ),
                ),
            ));
            Yii::app()->params['skipSiteCheck'] = false;
            ?>
 
 		</div>
 
	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>