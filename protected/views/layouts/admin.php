<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="/css/reset.css">
		<link rel="stylesheet" type="text/css" href="/css/admin.css?v=3">
		<link rel="stylesheet" type="text/css" href="/css/replay.css">
		<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css">
		<title><?php echo CHtml::encode($this->pageTitle) ?></title>
		<link href="" rel="icon" type="image/png">
		<script src="/js/modernizr.custom.min.js"></script>
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>



		<div class="wrapper">



			<header class="header">

					<div class="back-home"><?php echo CHtml::link($this->site->url, 'http://'.$this->site->url); ?></div>
					<div class="rapid-access">
						<ul>
							<li><a href="#"><?php echo Yii::t('wot', 'Quick access')?></a>
								<ul>
									<li><?php echo CHtml::link(Yii::t('wot', 'Add News'), array('/admin/news/create')) ?></li>
									<li><?php echo CHtml::link(Yii::t('wot', 'Add Notifications'), array('/admin/notices/create')) ?></li>
									<li><?php echo CHtml::link(Yii::t('wot', 'Add Pages'), array('/admin/pages/create')) ?></li>
									<li><?php echo CHtml::link(Yii::t('wot', 'Main Settings'), array('/admin/config/admin')) ?></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="logout"><?php echo CHtml::link(Yii::t('wot', 'Logout').' ('.Yii::app()->user->account->nickname.')', array('/api/logout')) ?></a></div>

			</header>



			<div class="middle">



				<div class="container">

					<main class="content">

						<?php echo $content; ?>

					</main>

				</div><!-- class="container" -->



				<aside class="left-sidebar">
					<?php $this->widget('Menu',[
						'activateParents'=>true,
						'activateItems'=>true,
						'items'=>[
							['label'=>Yii::t('wot', 'Admin Panel'), 'url'=>['/admin/index']],
							['label'=>Yii::t('wot', 'Personnel'), 'url'=>'#', 'items'=>[
								['label'=>Yii::t('wot', 'Clans'), 'url'=>['/admin/clans']],
								['label'=>Yii::t('wot', 'Companies'), 'url'=>['/admin/companies'], 'visible'=>$this->site->premium_companies],
								['label'=>Yii::t('wot', 'Banlist'), 'url'=>['/admin/bans']],
								['label'=>Yii::t('wot', 'Военкомат'), 'url'=>['/admin/recruitment']],
								/* ['label'=>Yii::t('wot', 'HQ'), 'url'=>['/admin/companies']],
								['label'=>Yii::t('wot', 'Recruiting'), 'url'=>['/admin/companies']], */
							]],
							['label'=>Yii::t('wot', 'Site Content'), 'url'=>'#', 'items'=>[
								['label'=>Yii::t('wot', 'News Categories'), 'url'=>['/admin/newsCategories']],
								['label'=>Yii::t('wot', 'News Feed'), 'url'=>['/admin/news']],
								['label'=>Yii::t('wot', 'Notifications'), 'url'=>['/admin/notices']],
								['label'=>Yii::t('wot', 'Static Pages'), 'url'=>['/admin/pages']],
								['label'=>Yii::t('wot', 'HTML Pages'), 'url'=>['/admin/customs'], 'visible'=>$this->site->premium_html],
								['label'=>Yii::t('wot', 'Polls'), 'url'=>['/admin/polls'], 'visible'=>in_array(12, $this->site->premium_widgets)],
							]],
							['label'=>Yii::t('wot', 'Site Layout'), 'url'=>'#', 'items'=>[
								['label'=>Yii::t('wot', 'Site Design'), 'url'=>['/admin/sites']],
								['label'=>Yii::t('wot', 'Top Menu'), 'url'=>['/admin/menuTop']],
								['label'=>Yii::t('wot', 'Sidebar Menu'), 'url'=>['/admin/menuLeft']],
								['label'=>Yii::t('wot', 'Widgets'), 'url'=>'http://wot.pw/ru/site/page?view=functional#widgets'],
							]],
							['label'=>Yii::t('wot', 'Forum'), 'url'=>'#', 'items'=>[
								['label'=>Yii::t('wot', 'Forum Categories'), 'url'=>['/admin/forumGroups']],
								['label'=>Yii::t('wot', 'Forums'), 'url'=>['/admin/forumCategories']],
								['label'=>Yii::t('wot', 'Topics'), 'url'=>['/admin/forumThemes']],
								['label'=>Yii::t('wot', 'Posts'), 'url'=>['/admin/forumMessages']],
							]],
							['label'=>Yii::t('wot', 'File Upload'), 'url'=>'/files?target=standalone&v=2', 'linkOptions'=>['class'=>'file-upload-console fancybox.iframe']],
							['label'=>Yii::t('wot', 'Settings'), 'url'=>'#', 'items'=>[
								['label'=>Yii::t('wot', 'Main Settings'), 'url'=>['/admin/config']],
								['label'=>Yii::t('wot', 'Roles'), 'url'=>['/admin/roles']],
								['label'=>Yii::t('wot', 'Roles assignment'), 'url'=>['/admin/accountsRoles']],
								['label'=>Yii::t('wot', 'Access Restriction'), 'url'=>['/admin/access']],
								['label'=>Yii::t('wot', 'Delete the Website'), 'url'=>'#', 'linkOptions'=>[
									'csrf'=>true,
									'submit'=>array('/admin/config/deleteSite'),
									'confirm'=>Yii::t('wot', 'Are you sure you want to delete the website? Warning! Once removed, it will be impossible to restore the website! Make sure that you really want to perform this operation!'),
									'class'=>'adm-i-delete tooltip',
								]],
							]],
							['label'=>Yii::t('wot', 'TeamSpeak'), 'url'=>['/admin/teamspeak']],
							['label'=>Yii::t('wot', 'Store'), 'url'=>['/admin/shop']],
						],
					]); ?>

				</aside>



			</div><!-- class="middle"-->



		</div><!-- class="wrapper" -->



		<footer class="footer">

			<div class="footer-logo-set">
				<a class="wotpw" href="http://wot.pw/"></a>
				<a class="wotgame" href="http://worldoftanks.ru/"></a>
				<a class="wotcpp" href="http://ru.wargaming.net/"></a>
			</div>

			<div class="bug-tracking">
				# <a target="_blank" href="http://support.wot.pw/forum/category/4566"><?php echo Yii::t('wot', 'Report a bug')?></a>
			</div>

		</footer>



		<script src="/js/masonry.pkgd.min.js"></script>
		<script src="/js/jquery.fancybox.pack.js"></script>
		<script src="/js/admin/custom.js"></script>
</body>
</html>