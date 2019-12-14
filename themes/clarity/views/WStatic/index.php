<? if ($static) { ?>
	<div class="modul static">

		<div class="title">
			<h3><?=CHtml::encode($this->title)?></h3>
		</div>
		<div class="cell">
			<?=Yii::app()->format->mhtml($static->text2)?>
			<!--<div class="statik-edit">
				<?
					//  0 создатель
					//  1 админ
					//  3 редактор страниц
					//  8 модератор
					// if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('3') || Yii::app()->user->checkAccess('8')){
					if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('3')){
				?>
					<?php echo CHtml::link(Yii::t('wot', 'Edit'), array('/admin/pages/update', 'id'=>$static->id)) ?>
				<? } ?>
			</div>-->
		</div>

	</div>
<? } ?>
