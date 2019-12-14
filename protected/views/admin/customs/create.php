<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel - Add a Page');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'HTML Pages'), array('admin')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'Add a page')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('admin')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Add a page'), array('create'), array('class'=>'current')) ?>
		</div>

		<div class="filling">

			<?php $this->renderPartial('_form', array('model'=>$model)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>