<? $admin = false; foreach (Config::explode(Yii::app()->params['access_admin']) as $role){
	if (Yii::app()->user->checkAccess($role))
		$admin = true;
}
?>
<? if ($admin){ ?>
	<nav class="modul adminnav">

		<div class="title">
			<h3><?=CHtml::encode($this->title)?></h3>
		</div>

		<?php
		$this->widget('Menu', array(
			'encodeLabel'=>false,
			'items'=>array(
				array('label'=>Yii::t('wot', 'Admin Panel'), 'url'=>array('/admin/index')),
				array('label'=>Yii::t('wot', 'Store'), 'url'=>'http://wot.pw/rshop/?site='.$this->controller->site->id),
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

	</nav>
<? } ?>