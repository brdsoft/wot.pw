<div class="modul news">
	<div class="custom-title">
		<h4><?=CHtml::encode($this->title)?></h4>
	</div>
	<div class="pastic">
		<a name="scrollTo"></a>
		<?php $this->widget('zii.widgets.CListView', array(
			'cssFile'=>false,
			'dataProvider'=>$news,
			'itemView'=>'wNewsOne',
			'summaryText'=>false,
			'emptyText'=>'Новостей пока нет',
			'pager'=>array(
				'cssFile'=>false,
				'header'=>false,
			),
		)); ?>
	</div>
</div>
