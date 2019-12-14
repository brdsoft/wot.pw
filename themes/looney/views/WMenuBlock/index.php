<div class="modul subnav">

	<div class="custom-title">
		<h4><?=CHtml::encode($this->title)?></h4>
	</div>
	<div class="pastic">
		<?php $this->widget('Menu',array(
			'activateParents'=>true,
			'activateItems'=>true,
			'items'=>array_merge($this->controller->menuLeft, array()),
		)); ?>
	</div>

</div>