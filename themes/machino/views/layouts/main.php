<?php $this->beginContent('//layouts/core'); ?>


<div class="sidebar">

	<?=Yii::app()->format->whtml($this->site->getHtml('sidebar'), $this->site->premium_html)?>

	<? if (!$this->site->premium_reklama){ ?>
		<div class="block">
			<div id="advertur_64733"></div><script type="text/javascript">
					(function(w, d, n) {
							w[n] = w[n] || [];
							w[n].push({
									section_id: 64733,
									place: "advertur_64733",
									width: 240,
									height: 400
							});
					})(window, document, "advertur_sections");
			</script>
			<script type="text/javascript" src="//ddnk.advertur.ru/v1/s/loader.js" async></script>
		</div>
	<? } ?>
</div>

<div class="main">
	<?php echo $content; ?>
</div>

<?php $this->endContent(); ?>