<div class="piece-of-news">
	<div class="news-title">
		<h4><?php echo CHtml::link(Yii::app()->format->text($data->text1), array('/news/one', 'id'=>$data->id)); ?></h4>
	</div>
	<div class="news-contents">
		<? if ($data->image){ ?>
			<img class="image" src="<?php echo Files::model()->link($data->image) ?>" alt="<?php echo $data->text1 ?>">
		<? } ?>
		<?php echo $data->text2 ? Yii::app()->format->mhtml($data->text2) : Yii::app()->format->mhtml($data->text3) ?>
	</div>
	<div class="news-links">
		<span class="date"><?php echo date('d.m.Y', $data->time + Yii::app()->params["moscow"]) ?></span>
		<span class="comment"><?php echo CHtml::link('Комментарии ('.Messages::model()->cache(3600, new CExpressionDependency('Yii::app()->cache->get("messages")'))->count("`site_id`='".Yii::app()->controller->site->id."' AND `name`='news' AND `object_id`='{$data->id}'").')', array('/news/one', 'id'=>$data->id, '#'=>'messages')) ?></span>
		<? if (($data->text2 && $data->text3)){ ?>
			<span class="more"><?php echo CHtml::link('Подробнее', array('/news/one', 'id'=>$data->id)); ?></span>
		<? } ?>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('2') || Yii::app()->user->checkAccess('8')){ ?>
			<? if (Yii::app()->controller->site->id == 168 || $data->site_id != 168){ ?>
				<span class="edit"><?php echo CHtml::link('Редактировать', array('/admin/news/update', 'id'=>$data->id)); ?></span>
				<span class="delet"><?php echo CHtml::link('Удалить', '#', array('csrf'=>true, 'submit'=>array('/admin/news/delete','id'=>$data->id),'confirm'=>Yii::t('zii', 'Are you sure you want to delete this item?'))); ?></span>
			<? } ?>
		<? } ?>
	</div>
</div>
