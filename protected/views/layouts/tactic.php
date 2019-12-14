<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		<script type="text/javascript">
			var nodeData = <?php echo json_encode($this->nodeData) ?>;
			var nodeURL = '<?= ($nodeURL = '//wot.pw:'.(in_array($this->site->url, ['nommyde.wot.pw', 'devsite.wot.pw']) ? 55709 : 55710)) ?>';
		</script>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/front.js'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/socket.io.js'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/modernizr.custom.min.js'); ?>
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/node.js?v=2'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/tactic.js'); ?>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/reset.css'); ?>
		<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/tactic.css?v=2'); ?>
		<link href="<?php echo Files::model()->link($this->site->favicon) ?>" rel="icon" type="image/png">

		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/tactic.custom.js'); ?>

	</head>
	<body class="<?php foreach(explode('/', $this->route) as $value) {echo 'wrapper-'.$value.' ';} ?>">
		<div id="wrapper">
			<?php echo $content; ?>
		</div>
	</body>
</html>
