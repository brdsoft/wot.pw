<? $admin = false; foreach (Config::explode(Yii::app()->params['access_admin']) as $role){
	if (Yii::app()->user->checkAccess($role))
		$admin = true;
}
?>
<? if ($admin){ ?>
	<div class="modul adminnav">

		<div class="custom-title">
			<h4><?=CHtml::encode($this->title)?></h4>
		</div>

		<div class="pastic">
			<?php
			$this->widget('Menu', array(
				'encodeLabel'=>false,
				'items'=>array(
					array('label'=>'Консоль администратора', 'url'=>array('/admin/index')),
					array('label'=> 'Магазин дополнений', 'url'=>'http://wot.pw/rshop/?site='.$this->controller->site->id),
				),
				'htmlOptions'=>array('class'=>'operations'),
			));
			?>
			<? if (!empty($this->menu)){ ?>
				<?php
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$this->menu,
					'htmlOptions'=>array('class'=>'operations'),
				));
				?>
			<? } ?>
		</div>

	</div>
<? } ?>