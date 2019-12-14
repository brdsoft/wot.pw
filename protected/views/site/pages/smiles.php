<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
<link rel="stylesheet" type="text/css" href="/css/reset.css">
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<title></title>
<style type="text/css">
.wg-smile {overflow:hidden; margin:7px 10px;}
.wg-smile div {float:left; margin:0 0 0 6px; text-align:center;}
.wg-smile div {width:76px;}
.wg-smile div:nth-of-type(6n+1) {margin-left:0; clear:left;}

.wg-smile div a {display:block; width:76px; height:40px; margin:3px 0; border:1px solid #dbdbdb; background-color:#eee;}
.wg-smile div a:hover {display:block; border:1px solid #ccc; background-color:#fff;}


.wg-smile div a img {padding:3px;}


</style>
</head>
<body>

<div class="wg-smile">

<div><a href="#"><img src="/images/wg_smile/6_sense.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/announce.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/arta.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/asap.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/cheewing.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/dracula.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/facepalmic.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/girl_love.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/girl_support.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/halloween.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/loose.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/papka.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/repair.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/school.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/shy.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_amazed.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_blinky.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_bush.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_cat_japan.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_child.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_coin.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_confused.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_crab.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_deer.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_Default.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/smile_flower.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_girl.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_glasses.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_great.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_harp.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_honoring.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_izmena_jap.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_justwait.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_kamikadze_japan.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_medal.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_mellow.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_ohmy.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_popcorn1.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_sad.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_sceptic.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_smile.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_teethhappy.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_unsure.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile_veryhappy.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile-angry.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile-bajan2.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile-hiding.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile-izmena.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile-playing.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/Smile-tongue.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/teacher.gif" alt=""></a></div>
<div><a href="#"><img src="/images/wg_smile/zombi.gif" alt=""></a></div>

</div>
<script type="text/javascript">
	$(function(){
		$('.wg-smile a').click(function(){
			window.parent.tinymce.activeEditor.insertContent($(this).html());
			window.parent.$.fancybox.close();
		});
	});
</script>
</body>
</html>