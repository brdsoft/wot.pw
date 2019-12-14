<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'access-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">В URL указывается путь от корня сайта до необходимого раздела или страницы. Примеры<br>
		/forum - ограничит доступ к форумам, всем темам и сообщениям<br>
		/forum/[CATEGORY_ID] - ограничит доступ к конкретному форуму (замените [CATEGORY_ID] на свой)<br>
		/forum/[CATEGORY_ID]/[THEME_ID] - ограничит доступ к конкретной теме (замените [CATEGORY_ID] и [THEME_ID] на свои)<br>
		/staff - ограничит доступ ко всему штабу<br>
		/staff/history - ограничит доступ к истории игроков<br>
		/page/[NAME] - ограничит доступ к конкретной странице (замените [NAME] на свой)
	</p>
	<p>Внимание! Ограничение доступа автоматически закрывает страницу от неавторизованных пользователей и авторизованных пользователей сторонних кланов или без клана.</p>
	<p>Внимание! Не закрывайте военкомат. Там скоро будет своя система доступа.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'clans'); ?>
		<div class="checkbox"><input type="checkbox" id="select_all_clans" onClick="$('input.checkbox-clan').prop('checked', $('#select_all_clans').prop('checked'))"> <span>Выбрать все</span></div>
		<?php echo CHtml::checkBoxList('Access[clans]',explode(',',$model->clans),CHtml::listData(Clans::model()->findAll(array('order'=>'`order`')), 'id', 'abbreviation'),array('class'=>'checkbox-clan', 'container'=>false, 'separator'=>false, 'template'=>'<div class="checkbox">{input} <span>{labelTitle}</span></div>')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'roles'); ?>
		<div class="checkbox"><input type="checkbox" id="select_all_roles" onClick="$('input.checkbox-roles').prop('checked', $('#select_all_roles').prop('checked'))"> <span>Выбрать все</span></div>
		<?php echo CHtml::checkBoxList('Access[roles]',explode(',',$model->roles),$model->rolesAllowed,array('class'=>'checkbox-roles', 'container'=>false, 'separator'=>false, 'template'=>'<div class="checkbox">{input} <span>{labelTitle}</span></div>')); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
