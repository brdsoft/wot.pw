<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'Создать сервер');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'TeamSpeak сервер'), array('index')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'Создать сервер')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('index')) ?>
			<?php echo CHtml::link(Yii::t('wot', 'Создать сервер'), array('create'), array('class'=>'current')) ?>
		</div>

		<div class="filling teamspeak">

			<?php $this->renderPartial('_form', array('model'=>$model)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>