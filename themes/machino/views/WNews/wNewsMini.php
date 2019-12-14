<div class="block news-mini">
	<div class="head head1">
		<?=CHtml::encode($this->title)?>
	</div>
	<div class="body">
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
