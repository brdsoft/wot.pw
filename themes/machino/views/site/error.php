<?php
$this->pageTitle=Yii::app()->name . ' - Error';
?>

<div class="block">
	<div class="head head2">
		Ошибка <?php echo $code; ?>
	</div>
	<div class="body body2">
		<?php echo $message; ?>
	</div>
</div>
