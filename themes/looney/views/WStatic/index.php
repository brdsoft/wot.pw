<? if ($static) { ?>
	<div class="modul static">

		<div class="custom-title">
			<h4><?=CHtml::encode($this->title)?></h4>
		</div>
		<div class="pastic">
			<?=Yii::app()->format->mhtml($static->text2)?>
		</div>

	</div>
<? } ?>
