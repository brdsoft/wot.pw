<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'Poll Viewer');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'Polls'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'View Poll')?> "<?php echo CHtml::encode($model->question); ?>"</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'View'), array('view', 'id'=>$model->id), array('class'=>'current')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('update', 'id'=>$model->id)) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('class'=>'delet', 'csrf'=>true, 'submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('wot', 'Are you sure you want to delete this poll?'))) ?>
		</div>

		<div class="filling">
		
			<?php
				$answers = [];
				$stat = $model->calculateAnswers();
				for ($i = 1; $i <= 10; $i++){
					$answers[] = ['type'=>'html', 'name'=>'answer'.$i, 'value'=>CHtml::encode($model['answer'.$i]).' - '.(isset($stat[$i]) ? $stat[$i]->percent.'% ('.$stat[$i]->count.') - '.$model->getAnswerAccounts($i) : '0% (0)'), 'visible'=>$model['answer'.$i] != '',];
				}
			?>

			<?php $this->widget('zii.widgets.CDetailView', array(
				'cssFile'=>false,
				'data'=>$model,
				'attributes'=>array_merge([
					'question',
					[
						'name'=>'time',
						'value'=>Yii::app()->dateFormatter->formatDateTime($model->time+Yii::app()->params["moscow"], "long", false),
					],
					[
						'name'=>'enabled',
						'value'=>$model->enabled ? Yii::t('wot', 'On') : Yii::t('wot', 'Off'),
					],
					[
						'name'=>'access',
						'value'=>$model->accessAllowed[$model->access],
					],
					[
						'name'=>'account_id',
						'type'=>'html',
						'value'=>$model->account_id." - ".CHtml::link($model->account->nickname, array('/profile/account', 'id'=>$model->account_id)),
					],
					[
						'label'=>'Код для вставки',
						'value'=>'{POLL id='.$model->id.' title="Опрос"}',
					],
				], $answers)
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>