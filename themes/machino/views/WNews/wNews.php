<div class="block">
	<div class="head head2">
		<?=CHtml::encode($this->title)?>
	</div>
	<div class="body body2">
		<a name="scrollTo"></a>
		<?php $this->widget('zii.widgets.CListView', array(
			'cssFile'=>false,
			'dataProvider'=>$news,
			'itemView'=>'wNewsOne',
			'summaryText'=>false,
			'emptyText'=>'Новостей пока нет',
			'pager'=>array(
				'cssFile'=>false,
			),
		)); ?>
	</div>
</div>
