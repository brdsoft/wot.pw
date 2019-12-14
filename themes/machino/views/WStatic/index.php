<? if ($static) { ?>
	<div class="block">
		<div class="head head2">
			<?=CHtml::encode($this->title)?>
		</div>
		<div class="body body2">
			<div>
				<?=Yii::app()->format->mhtml($static->text2)?>
				<div class="both"></div>
			</div>
		</div>
	</div>
<? } ?>
