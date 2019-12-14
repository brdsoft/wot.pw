<?php
$this->pageTitle='Новости';
?>

<div class="block">
	<div class="head head2">
		<?php echo Yii::app()->format->text($new->text1) ?>
	</div>
	<div class="body body2">
		<div class="news_one standalone" style="padding: 0; background: none;">
			<div class="time">
				<?php echo date('d.m.Y H:i', $new->time + 3600*4) ?>
			</div>
			<? if ($new->image){ ?>
				<img class="image" src="<?php echo Files::model()->link($new->image) ?>" alt="<?php echo $new->text1 ?>">
			<? } ?>
			<? if ($new->text2){ ?>
				<div class="text" style="font-style: italic;">
					<?php echo Yii::app()->format->mhtml($new->text2) ?>
				</div>
			<? } ?>
			<? if ($new->text3){ ?>
			<div class="text">
				<?php echo Yii::app()->format->mhtml($new->text3) ?>
			</div>
			<? } ?>
			<div class="links">
				<?php echo CHtml::link('&larr; Вернуться к списку новостей', array('/news')); ?>
			</div>
		</div>
	</div>
</div>
<? if (!$this->site->premium_reklama){ ?>
			<div class="block">
				<div class="body" style="text-align: center;">
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
			</div>
<? } ?>
<?php $this->widget('application.components.WMessages', array('name'=>'news', 'object_id'=>$new->id, 'site_id'=>$new->site_id)); ?>

