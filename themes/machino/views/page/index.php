<?php
/* @var $this SiteController */

$this->pageTitle=$page->text1;

?>


<div class="block">
	<div class="head head2">
		<?php echo $page->text1 ?>
			<div class="pull-right">
				<?php echo CHtml::link('&larr; Назад', 'javascript: history.back();'); ?>
			</div>
	</div>
	<div class="body body2 page">
		<?php echo $page->text2 ?>
		<hr/>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('3') || Yii::app()->user->checkAccess('8')){ ?>
			<div style="font-size: 12px; float: left;"><?php echo CHtml::link('Редактировать страницу', array('/admin/pages/update', 'id'=>$page->id)) ?></div>
		<? } ?>
		<div style="font-size: 12px; float: right;">Последнее изменение: <?php echo $page->account->nickname ?> <?php echo date('d.m.Y', $page->time+Yii::app()->params['moscow']) ?></div>
		<div class="both"></div>
	</div>
</div>
 