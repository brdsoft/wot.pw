<?php
/* @var $this SiteController */

$this->pageTitle=$page->text1;

?>

<div class="modul static">

	<div class="title">
		<h3><?php echo $page->text1 ?></h3>
	</div>
	<div class="cell">

		<div class="statik-content">
			<?php echo $page->text2 ?>
		</div>

		<div class="statik-edit">
			<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('3') || Yii::app()->user->checkAccess('8')){ ?>
			<?php echo CHtml::link(Yii::t('wot', 'Edit page'), array('/admin/pages/update', 'id'=>$page->id)) ?>.
			<? } ?>
			<span><?php echo Yii::t('wot', 'Last modified:')?> <?php echo $page->account->nickname ?> <?php echo date('d.m.Y', $page->time+Yii::app()->params['moscow']) ?>.</span>
		</div>

	</div>

</div>