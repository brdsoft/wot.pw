<div class="modul online">
	<div class="title">
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>
	<div class="cell">
		<div class="online-inner">
			<strong><?php echo Yii::t('wot', 'Guests:')?></strong>
			<span class="online-guests"></span>
			<strong><?php echo Yii::t('wot', 'Clanmates:')?></strong>
			<span class="online-allies"></span>
			<div class="online-allies-list"></div>
		</div>
	</div>
</div>