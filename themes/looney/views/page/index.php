<?php
/* @var $this SiteController */

$this->pageTitle=$page->text1;

?>

<div class="modul anypage">

	<div class="custom-title">
		<h4><?php echo $page->text1 ?></h4>
	</div>
	<div class="pastic">

		<!--<div class="backlink">
			<?php echo CHtml::link('&larr; Назад', 'javascript: history.back();'); ?>
		</div>-->

		<div class="anypage-content">
			<?php echo $page->text2 ?>
		</div>

		<div class="anypage-edit">
			<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('3') || Yii::app()->user->checkAccess('8')){ ?>
			<?php echo CHtml::link('Редактировать страницу', array('/admin/pages/update', 'id'=>$page->id)) ?>
			<? } ?>
			<span>Последнее изменение: <?php echo $page->account->nickname ?> <?php echo date('d.m.Y', $page->time+Yii::app()->params['moscow']) ?></span>
		</div>

	</div>

</div>