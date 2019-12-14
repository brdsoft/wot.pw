<div class="news-segment">


	<div class="news-title">

		<h4><?php echo CHtml::link(Yii::app()->format->text($data->text1), array('/news/one', 'id'=>$data->id)); ?></h4>

	</div>


	<div class="news-content">

		<? if ($data->image){ ?>
<div class="news-image"><?php echo CHtml::link('<img src="'.Files::model()->link($data->image).'" alt="">', array('/news/one', 'id'=>$data->id)); ?></div>
		<? } ?>
<div class="news-data"><?php echo $data->text2 ? Yii::app()->format->mhtml($data->text2) : Yii::app()->format->mhtml($data->text3) ?></div>

	</div>


	<div class="news-link">
		<span class="date"><span class="day"><?php echo date('d', $data->time + Yii::app()->params["moscow"]) ?></span><span class="dot-o">.</span><span class="month"><?php echo date('m', $data->time + Yii::app()->params["moscow"]) ?></span><span class="dot-t">.</span><span class="year"><?php echo date('Y', $data->time + Yii::app()->params["moscow"]) ?></span></span>
		<span class="comment"><?php echo CHtml::link(Yii::t('wot', 'Comments ').' ('.Messages::model()->cache(3600, new CExpressionDependency('Yii::app()->cache->get("messages")'))->count("`site_id`='".Yii::app()->controller->site->id."' AND `name`='news' AND `object_id`='{$data->id}'").')', array('/news/one', 'id'=>$data->id, '#'=>'messages')) ?></span>
		<? if (($data->text2 && $data->text3)){ ?>
<span class="more"><?php echo CHtml::link(Yii::t('wot', 'More'), array('/news/one', 'id'=>$data->id)); ?></span>
		<? } ?>
<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('2') || Yii::app()->user->checkAccess('8')){ ?>
<? if (Yii::app()->controller->site->id == 168 || $data->site_id != 168){ ?><span class="edit"><?php echo CHtml::link(Yii::t('wot', 'Edit'), array('/admin/news/update', 'id'=>$data->id)); ?></span>
		<span class="delet"><?php echo CHtml::link(Yii::t('wot', 'Delete'), '#', array('csrf'=>true, 'submit'=>array('/admin/news/delete','id'=>$data->id),'confirm'=>Yii::t('zii', Yii::t('wot', 'Are you sure?')))); ?></span>
			<? } ?>
		<? } ?>

	</div>


</div>



