<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - News Viewer');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'News Feed'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View news')?> #<?php echo $model->id; ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete these news?'))) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.CDetailView', array(
                'cssFile'=>false,
                'data'=>$model,
                'attributes'=>array(
                    'id',
                    array(
                        'name'=>'time',
                        'value'=>date('d.m.Y H:i', $model->time+Yii::app()->params["moscow"]),
                    ),
                    array(
                        'name'=>'category_id',
                        'value'=>$model->category->name,
                    ),
                    'text1',
                    array(
                        'name'=>'image',
                        'type'=>'html',
                        'value'=>'<img src="'.Files::model()->link($model->image).'">',
                    ),
                    array(
                        'name'=>'text2',
                        'type'=>'mhtml',
                        'value'=>$model->text2,
                    ),
                    array(
                        'name'=>'text3',
                        'type'=>'mhtml',
                        'value'=>$model->text3,
                    ),
                    array(
                        'name'=>'account_id',
                        'type'=>'html',
                        'value'=>$model->account_id." - ".CHtml::link($model->account->nickname, array('/profile/account', 'id'=>$model->account_id)),
                    ),
                ),
            )); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>