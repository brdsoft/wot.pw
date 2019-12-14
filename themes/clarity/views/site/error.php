<?php
$this->pageTitle=Yii::app()->name . ' - Error';
?>

	<div class="modul welcome">

		<div class="title">
			<h3><?php echo Yii::t('wot', 'Error:')?> <?php echo $code; ?></h3>
		</div>

		<div class="cell">

			<?php echo $message; ?>

		</div>

	</div>