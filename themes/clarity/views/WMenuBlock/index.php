<nav class="modul subnav">

	<div class="title">
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>

	<?php $this->widget('Menu',array(
		'activateParents'=>true,
		'activateItems'=>true,
		'items'=>array_merge($this->controller->menuLeft, array()),
	)); ?>

</nav>