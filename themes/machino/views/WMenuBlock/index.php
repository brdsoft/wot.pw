<div class="block">
	<div class="head head1">
		<?=CHtml::encode($this->title)?>
	</div>
	<div class="body">
		<div class="block-menu">
			<?php $this->widget('Menu',array(
				'activateParents'=>true,
				'activateItems'=>true,
				'submenuHtmlOptions'=>array('class'=>'sub'),
				'items'=>array_merge($this->controller->menuLeft, array()),
			)); ?>
		</div>
	</div>
</div>
