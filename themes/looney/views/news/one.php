<?php
$this->pageTitle='Новости';
?>

<div class="modul singlnews">
	<div class="custom-title">
		<h4><?php echo Yii::app()->format->text($new->text1) ?></h4>
	</div>
	<div class="pastic">
			<div class="date"><?php echo date('d.m.Y H:i', $new->time + Yii::app()->params["moscow"]) ?></div>
			<? if ($new->image){ ?>
				<img class="image" src="<?php echo Files::model()->link($new->image) ?>" alt="<?php echo $new->text1 ?>">
			<? } ?>
			<? if ($new->text3){ ?>
			<div class="singl-news-content">
				<?php echo Yii::app()->format->mhtml($new->text3) ?>
			</div>
			<? } ?>
			<div class="links">&larr; <?php echo CHtml::link('Все новости', array('/news')); ?></div>
	</div>
</div>

<? if (!$this->site->premium_reklama){ ?>
<div class="modul publicity">
	<div class="custom-title">
		<h4>Реклама</h4>
	</div>
	<div class="pastic">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Светлый сайт - центральный баннер -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2157584601406270"
     data-ad-slot="2622089344"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
</div>
<? } ?>

<?php $this->widget('application.components.WMessages', array('name'=>'news', 'object_id'=>$new->id, 'site_id'=>$new->site_id)); ?>

