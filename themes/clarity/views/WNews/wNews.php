<div class="modul news">
	<div class="title">
		<a name="scrollTo"></a>
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>
	<div class="cell">
		<?php $this->widget('zii.widgets.CListView', array(
			'cssFile'=>false,
			'ajaxVar'=>false,
			'ajaxUpdate'=>false,
			'dataProvider'=>$news,
			'itemView'=>'wNewsOne',
			'summaryText'=>false,
			'emptyText'=>Yii::t('wot', 'There is no news yet'),
			'pager'=>array(
				'cssFile'=>false,
				'header'=>false,
			),
		)); ?>
	</div>
</div>
