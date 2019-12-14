<div class="modul news-mini">
	<div class="custom-title">
		<h4><?=CHtml::encode($this->title)?></h4>
	</div>
	<div class="pastic">
		<?php $this->widget('zii.widgets.CListView', array(
			'cssFile'=>false,
			'dataProvider'=>$news,
			'itemView'=>'wNewsMiniOne',
			'summaryText'=>false,
			'emptyText'=>'Новостей пока нет',
			'pager'=>array(
				'cssFile'=>false,
				'header'=>false,
			),
		)); ?>
	</div>
</div>
