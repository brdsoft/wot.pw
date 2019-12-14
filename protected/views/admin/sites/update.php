<?php
$this->pageTitle= Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'site design');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel') ?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Site design') ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="filling">

			<?php $this->renderPartial('_form', array('model'=>$model)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>