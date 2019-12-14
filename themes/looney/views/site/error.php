<?php
$this->pageTitle=Yii::app()->name . ' - Error';
?>

	<div class="modul welcome">

		<div class="custom-title">
			<h4>Ошибка <?php echo $code; ?></h4>
		</div>

		<div class="pastic">

<?php echo $message; ?>

		</div>

	</div>