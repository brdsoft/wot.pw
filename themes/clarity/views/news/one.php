<?php
$this->pageTitle=Yii::t('wot', 'News');
?>

<div class="modul detail-news">
	<div class="title">
		<h3><?php echo Yii::app()->format->text($new->text1) ?></h3>
	</div>
	<div class="cell">

		<div class="date"><?php echo date('d.m.Y', $new->time + Yii::app()->params["moscow"]) ?> - <?php echo date('H:i', $new->time + Yii::app()->params["moscow"]) ?></div>
	
		<? if ($new->text3){ ?>
		<div class="detail-news-content">
			<?php echo Yii::app()->format->mhtml($new->text3) ?>
		</div>
		<? } ?>

		<div class="detail-news-links"><?php echo CHtml::link(Yii::t('wot', 'All News'), array('/news')); ?></div>

	</div>
</div>

<? if (!$this->site->premium_reklama){ ?>
		<div class="h-banner">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Темный сайт - центральный баннер -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2157584601406270"
     data-ad-slot="8247754143"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
		</div>
<? } ?>

<?php $this->widget('application.components.WMessages', array('name'=>'news', 'object_id'=>$new->id, 'site_id'=>$new->site_id)); ?>

