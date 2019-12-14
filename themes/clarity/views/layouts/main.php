<?php $this->beginContent('//layouts/core'); ?>

<main class="column">
	<div class="main-inner">

		<?php echo $content; ?>

	</div>
</main>

<aside class="column">

	<?=Yii::app()->format->whtml($this->site->getHtml('sidebar'), $this->site->premium_html)?>

	<? if (!$this->site->premium_reklama){ ?>
		<div class="modul publicity">
			<div class="title">
				<h3><?php echo Yii::t('wot', 'Ads')?></h3>
			</div>
			<div class="cell">
				<div class="publicity-inner">
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
		</div>
	<? } ?>

</aside>

<?php $this->endContent(); ?>