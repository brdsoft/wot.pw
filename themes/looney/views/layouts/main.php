<?php $this->beginContent('//layouts/core'); ?>

<div class="column main">

	<?php echo $content; ?>

</div><!-- class="column main -->

<div class="column aside">

	<?=Yii::app()->format->whtml($this->site->getHtml('sidebar'), $this->site->premium_html)?>

	<? if (!$this->site->premium_reklama){ ?>
		<div class="modul publicity">
			<div class="custom-title">
				<h4>Реклама</h4>
			</div>
			<div class="pastic">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Темный сайт - боковой баннер -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:240px;height:400px"
                     data-ad-client="ca-pub-2157584601406270"
                     data-ad-slot="3119550549"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
		</div>
	<? } ?>

</div><!-- class="column aside -->
	
<?php $this->endContent(); ?>