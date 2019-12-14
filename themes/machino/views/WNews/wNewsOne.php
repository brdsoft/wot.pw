<div class="news_one">
	<div class="time">
		<?php echo date('d.m.Y', $data->time + Yii::app()->params["moscow"]) ?>
	</div>
	<div class="title">
		<?php echo CHtml::link(Yii::app()->format->text($data->text1), array('/news/one', 'id'=>$data->id)); ?>
	</div>
	<div class="text">
		<? if ($data->image){ ?>
			<img class="image" src="<?php echo Files::model()->link($data->image) ?>" alt="<?php echo $data->text1 ?>">
		<? } ?>
		<?php echo $data->text2 ? Yii::app()->format->mhtml($data->text2) : Yii::app()->format->mhtml($data->text3) ?>
	</div>
	<div class="links">
		<?php echo CHtml::link('Комментарии ('.Messages::model()->cache(3600, new CExpressionDependency('Yii::app()->cache->get("messages")'))->count("`site_id`='".Yii::app()->controller->site->id."' AND `name`='news' AND `object_id`='{$data->id}'").')', array('/news/one', 'id'=>$data->id, '#'=>'messages')) ?>
		<? if (($data->text2 && $data->text3)){ ?>
			|
			<?php echo CHtml::link('Подробнее', array('/news/one', 'id'=>$data->id)); ?>
		<? } ?>
	</div>
	<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('2')){ ?>
		<? if (Yii::app()->controller->site->id == 168 || $data->site_id != 168){ ?>
			<div class="links">
				<?php echo CHtml::link('Редактировать', array('/admin/news/update', 'id'=>$data->id)); ?> | 
				<?php echo CHtml::link('Создать', array('/admin/news/create')); ?>
			</div>
		<? } ?>
	<? } ?>
	<div class="both"></div>
</div>
