<div class="modul news-mini">
	<div class="title">
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>
	<div class="cell">
		<?php $this->widget('zii.widgets.CListView', array(
			'cssFile'=>false,
			'dataProvider'=>$news,
			'itemView'=>'wNewsMiniOne',
			'summaryText'=>false,
			'emptyText'=>Yii::t('wot', 'There is no news yet'),
			'pager'=>array(
				'cssFile'=>false,
				'header'=>false,
			),
		)); ?>
	</div>
</div>
