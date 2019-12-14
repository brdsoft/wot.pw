<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="verify-admitad" content="1cb18ea187" />
		<script type="text/javascript">
			<? if (!Yii::app()->user->isGuest){ ?>
			var notices = <?php echo json_encode($this->notices) ?>;
			<? } ?>
			var nodeData = <?php echo json_encode($this->nodeData) ?>;
			var nodeURL = '<?= ($nodeURL = '//wot.pw:'.(in_array($this->site->url, ['nommyde.wot.pw', 'devsite.wot.pw']) ? 55709 : 55710)) ?>';
			var CSRF_TOKEN = '<?= Yii::app()->request->csrfToken ?>';
		</script>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/css/core.css?v=8'); ?>
		<? if (!$this->site->css_disabled){ ?><?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/css/style.css?v=17'); ?><? } ?>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/'.$this->site->style->skin.'/css/style'.$this->site->time_modified.'.css'); ?>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/replay.css?v=2'); ?>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.fancybox.css'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/socket.io.js'); ?>
		<!--<link href="/fav.png" rel="shortcut icon">-->
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.fancybox.pack.js'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/front.js?v=7'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/node.js?v=3'); ?>
		<link href="<?php echo Files::model()->link($this->site->favicon) ?>" rel="icon" type="image/png">
	</head>
	<body>

<? if (Yii::app()->user->checkAccess('0')
|| Yii::app()->user->checkAccess('1')
|| Yii::app()->user->checkAccess('2')
|| Yii::app()->user->checkAccess('3')
|| Yii::app()->user->checkAccess('5')
|| Yii::app()->user->checkAccess('8')){ ?>

	<div class="admin-bar">
		<ul>
			<li><?php echo CHtml::link(Yii::t('wot', 'Консоль'), array('/admin')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Новости'), array('/admin/news/create')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Банлист'), array('/admin/bans')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Настройки'), array('/admin/config/admin')) ?></li>
			<li><?php echo CHtml::link(Yii::t('wot', 'Store'), array('/admin/shop')) ?></li>
		</ul>
	</div>

<? } ?>

	<div class="layout" id="page" style="position: relative;">
		<? if ($this->site->id == 1){ ?>
			<img id="ship" src="/images/ship.png" alt="" style="display: block; position: absolute; left: 100px; top: 20px;" />
		<? } ?>
		<?=Yii::app()->format->whtml($this->site->getHtml('header'))?>
		<div class="content">
			<div class="menu">
				<?php $this->widget('Menu',array(
					'activateParents'=>true,
					'activateItems'=>true,
					'submenuHtmlOptions'=>array('class'=>'sub'),
					'items'=>array_merge($this->menuTop, array(
						array('label'=>'Выйти ('.(empty(Yii::app()->user->account) ? Yii::app()->user->id : Yii::app()->user->account->nickname).(empty(Yii::app()->user->account->clan_abbreviation) ? '' : ' ['.Yii::app()->user->account->clan_abbreviation.']').')', 'url'=>array('/api/logout'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'pull-right last')),
						array('label'=>'Мой аккаунт', 'url'=>array('/profile/index'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'pull-right')),
						array('label'=>'Войти на сайт', 'url'=>array('/api/login'), 'visible'=>Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'pull-right last')),
					)),
				)); ?>
			</div>
			
			<?php echo $content; ?>
			
			<div class="both"></div>
			<div class="footer" style="overflow:hidden;">
				<?php //list($queryCount, $queryTime) = Yii::app()->db->getStats(); ?>
				<!--. Queries: <?php //echo $queryCount ?>-->

				<?=Yii::app()->format->whtml($this->site->getHtml('footer'))?>
			</div>
		</div>
	</div>
	<?php if (Yii::app()->user->account && in_array(15, $this->site->premium_widgets)) $this->widget('WPmsg') ?>
	<? if ($this->site->id == 1){ ?>
		<script type="text/javascript">
			var position = 0;
			var degg = 0;
			$(function(){
				setInterval(function(){
					degg = Math.sin(position / 30)*7;
					$('body').css('background-position', position+'px top');
					$('#ship').css('-moz-transform', 'rotate('+degg+'deg)');
					$('#ship').css('-webkit-transform', 'rotate('+degg+'deg)');
					$('#ship').css('-ms-transform', 'rotate('+degg+'deg)');
					$('#ship').css('-o-transform', 'rotate('+degg+'deg)');
					$('#ship').css('-transform', 'rotate('+degg+'deg)');
					position--;
				}, 50);
			});
		</script>
	<? } ?>
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
	<!--LiveInternet counter--><script type="text/javascript"><!--
	document.write("<a href='//www.liveinternet.ru/click;wot_pw' "+
	"target=_blank><img src='//counter.yadro.ru/hit;wot_pw?t21.6;r"+
	escape(document.referrer)+((typeof(screen)=="undefined")?"":
	";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
	screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
	";"+Math.random()+
	"' alt='' title='LiveInternet: показано число просмотров за 24"+
	" часа, посетителей за 24 часа и за сегодня' "+
	"border='0' width='0' height='0'><\/a>")
	//--></script><!--/LiveInternet-->
	</body>
</html>
