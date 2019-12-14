<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<?php if ($_SERVER['SERVER_NAME'] == 'able-hands.wot.pw') { ?>
		<meta name='yandex-verification' content='4caeff483bf47b87'>
	<?php } ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
	<script type="text/javascript">
		<? if (!Yii::app()->user->isGuest){ ?>
		var notices = <?php echo json_encode($this->notices) ?>;
		<? } ?>
		var nodeData = <?php echo json_encode($this->nodeData) ?>;
		var nodeURL = '<?= ($nodeURL = '//wot.pw:'.(in_array($this->site->url, ['nommyde.wot.pw', 'devsite.wot.pw']) ? 55709 : 55710)) ?>';
		var CSRF_TOKEN = '<?= Yii::app()->request->csrfToken ?>';
	</script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/reset.css'); ?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/css/core.css?v=8'); ?>
	<? if (!$this->site->css_disabled){ ?><?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/css/style.css?v=17'); ?><? } ?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/css/style'.$this->site->time_modified.'.css'); ?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/replay.css?v=2'); ?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.fancybox.css'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/socket.io.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/modernizr.custom.min.js'); ?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.fancybox.pack.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/front.js?v=7'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/node.js?v=3'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/js/custom.js?v=2'); ?>
	<link href="<?php echo Files::model()->link($this->site->favicon) ?>" rel="icon" type="image/png">
</head>
<body class="<?php foreach(explode('/', $this->route) as $value) {echo 'body-'.$value.' ';} ?>">

<? if (!$this->site->premium_reklama && $noticeAll = NoticesAll::model()->find()){ ?>
	<? if (!isset($_COOKIE['notice_'.$noticeAll->id])){ ?>
		<?php setcookie('notice_'.$noticeAll->id, 1, time()+3600*24*7, '/');  ?>
		<?php echo $noticeAll->notice; ?>
	<? } ?>
<? } ?>

<? if (Yii::app()->user->checkAccess('0')
|| Yii::app()->user->checkAccess('1')
|| Yii::app()->user->checkAccess('2')
|| Yii::app()->user->checkAccess('3')
|| Yii::app()->user->checkAccess('5')
|| Yii::app()->user->checkAccess('8')){ ?>

	<div class="admin-bar">
		<ul>
			<li><?php echo CHtml::link(Yii::t('wot', 'AP'), array('/admin')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'News'), array('/admin/news/create')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Banlist'), array('/admin/bans')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Settings'), array('/admin/config/admin')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Store'), array('/admin/shop')) ?></li>
		</ul>
	</div>

<? } ?>



	<div class="wrapper">

		<?=Yii::app()->format->whtml($this->site->getHtml('header'))?>

		<div class="content-wrapper">

			<div class="content side-left">

				<div class="content-inner">

					<?php echo $content; ?>

				</div><!-- class="content-inner" -->

			</div>

		</div><!-- class="content-wrapper" -->

	</div><!-- class="wrapper" -->



	<footer class="footer">

		<div class="footer-inner">

			<?=Yii::app()->format->whtml($this->site->getHtml('footer'))?>

		</div>

	</footer>


	<?php if (Yii::app()->user->account && in_array(15, $this->site->premium_widgets)) $this->widget('WPmsg') ?>


	<!-- Yandex.Metrika counter -->
	<script type="text/javascript">
		(function (d, w, c) {
			(w[c] = w[c] || []).push(function() {
				try {
					w.yaCounter23901934 = new Ya.Metrika({id:23901934,
						webvisor:false,
						clickmap:false,
						trackLinks:true,
						accurateTrackBounce:true});
				} catch(e) { }
			});

			var n = d.getElementsByTagName("script")[0],
				s = d.createElement("script"),
				f = function () { n.parentNode.insertBefore(s, n); };
			s.type = "text/javascript";
			s.async = true;
			s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

			if (w.opera == "[object Opera]") {
				d.addEventListener("DOMContentLoaded", f, false);
			} else { f(); }
		})(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="//mc.yandex.ru/watch/23901934" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

	<!--LiveInternet counter-->
	<script type="text/javascript">
		<!--
			document.write("<a class='liveinternet-counter' href='//www.liveinternet.ru/click;wot_pw' "+
			"target=_blank><img src='//counter.yadro.ru/hit;wot_pw?t21.6;r"+
			escape(document.referrer)+((typeof(screen)=="undefined")?"":
			";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
			screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
			";"+Math.random()+
			"' alt='' title='LiveInternet: показано число просмотров за 24"+
			" часа, посетителей за 24 часа и за сегодня' "+
			"border='0' width='0' height='0'><\/a>")
		//-->
	</script>
	<!--/LiveInternet-->

</body>
</html>