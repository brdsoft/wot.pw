<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/files.js?v=2'); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/files.css">
		<script type="text/javascript">
			var target = <?php echo json_encode($model->target) ?>;
			var id = <?php echo $id ? "'".CHtml::encode($id)."'" : 'false' ?>;
		</script>
		<title><?php echo Yii::t('wot', 'File Upload')?></title>
	</head>
	<body>
		<div class="layout">
			<div id="btn_select" class="btn"><?php echo Yii::t('wot', 'Upload')?></div>
			<input id="file" type="file">
			<div id="comment" class="comment">
				<?php echo $model->target['message'] ?>
			</div>
			<div id="progress" class="progress">
				<div></div>
			</div>
			<div id="error" class="error"></div>
			<div id="csrf"><?php echo Yii::app()->request->csrfToken ?></div>
			<div class="url">
				<p><?php echo Yii::t('wot', 'Copy the link to your file:')?></p>
				<input id="url" type="text">
			</div>
			<div class="insert"></div>
		</div>		
	</body>
</html>