<? $admin = false; foreach (Config::explode(Yii::app()->params['access_admin']) as $role){
	if (Yii::app()->user->checkAccess($role))
		$admin = true;
}
?>
<? if ($admin){ ?>
	<div class="block">
		<div class="body">
			<div class="block-menu">
				<?php
				$this->widget('zii.widgets.CMenu', array(
					'encodeLabel'=>false,
					'items'=>array(
						array('label'=>'Перейти в админку', 'url'=>array('/admin/index')),
						array('label'=> 'Магазин дополнений', 'url'=>'http://wot.pw/rshop/?site='.$this->controller->site->id),
					),
					'htmlOptions'=>array('class'=>'operations'),
				));
				?>
			</div>
		</div>
	</div>
<? } ?>
