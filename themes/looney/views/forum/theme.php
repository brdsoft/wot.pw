<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
$this->pageTitle=$theme->name;
?>

<div class="modul forum-theme">
	<div class="custom-title">
		<h4><?php echo CHtml::link('Форум', array('/forum')) ?> &rarr; <?php echo CHtml::link(CHtml::encode($theme->category->name), array('/forum/category', 'id'=>$theme->category->id)) ?></h4>
	</div>
	<div class="pastic">

		<h4><?php echo CHtml::encode($theme->name) ?></h4>
		
		<div id="messages"><!-- Не удалять тег! -->
		<?
		$dataProvider=new CActiveDataProvider('ForumMessages', array(
			'criteria'=>array(
				'condition'=>"`theme_id` = '{$theme->id}'",
			),
			'sort'=>array(
				'defaultOrder'=>"t.time",
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'ajaxUpdate'=>false,
			'cssFile'=>false,
			'hideHeader'=>true,
			'htmlOptions'=>array('class'=>'items'),
			'pager'=>array(
				'cssFile'=>false,
				'header'=>false,
			),
			'columns'=>array(
				array(
					'sortable'=>false,
					'type'=>'raw',
					'name'=>'account_id',
					'value'=>'"
						<div class=\"nickname\"><a name=\"message".$data->id."\"></a>".CHtml::link($data->account->nickname.($data->account->clan_abbreviation ? " [".$data->account->clan_abbreviation."]" : ""), array("/profile/account", "id"=>$data->account->id))."</div>
						<div class=\"avatar\"><img src=\"".($data->account->avatar ? "/upload/avatar/".$data->account->avatar : "/upload/avatar/no-avatar.png")."\" alt=\"".$data->account->nickname."\"></div>
						<div class=\"role\">".(in_array($data->account->id, Yii::app()->params["admins"]) ? "<span style=\"color: red; font-weight: bold;\">Разработчики</span>" : $data->account->role_i18n)."</div>
						".$data->getSiteUrl()."
					"',
					'header'=>'Автор',
				),
				array(
					'sortable'=>false,
					'type'=>'raw',
					'name'=>'message',
					'value'=>'
					"<div class=\"status\"><div class=\"time\">".date("d.m.Y H:i", $data->time+Yii::app()->params["moscow"])."</div>
					<div class=\"links\">".$data->adminLinks.CHtml::link("#", array("/forum/theme", "category_id"=>'.$theme->category_id.', "id"=>'.$theme->id.', "ForumMessages_page"=>$this->grid->dataProvider->pagination->currentPage+1, "#"=>"message".$data->id), array("title"=>"Ссылка на это сообщение"))."</div>
					</div><div class=\"message\">".Yii::app()->format->mhtml($data->message)."</div>
					".($data->updated_time ? "<div class=\"update\">Сообщение отредактировал ".CHtml::link($data->updated_account->nickname, array("/profile/account", "id"=>$data->updated_account->id)).": ".date("d.m.Y H:i", $data->updated_time+Yii::app()->params["moscow"])."</div>" : "")."
					".(trim($data->account->signature) ? "<div class=\"signature\">".Yii::app()->format->html($data->account->signature)."</div>" : "")."
					"',
				),

			),
			'emptyText'=>'Сообщений нет',
			'summaryText'=>false,
		));
		?>
		</div>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')){ ?>
			<div class="forum-button"><? if (!$theme->closed){ ?>
					<?php echo CHtml::link('Закрыть тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'close'))); ?>
				<? } else { ?>
					<?php echo CHtml::link('Открыть тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'open'))); ?>
				<? } ?>
				<? if (!$theme->fixed){ ?>
					<?php echo CHtml::link('Прикрепить тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'fix'))); ?>
				<? } else { ?>
					<?php echo CHtml::link('Открепить тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'unfix'))); ?>
				<? } ?>
			</div>
		<? } ?>
		<? if (!$theme->closed){ ?>
			<? if (Yii::app()->user->account){ ?>
				<a name="form"></a>
				<div class="form">
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'forumMessages-form',
						'enableAjaxValidation'=>false,
					)); ?>
					<?php echo $form->errorSummary($message); ?>
					<?php echo $form->hiddenField($message,'id'); ?>
					<div class="row">
						<?php echo $form->labelEx($message,'message'); ?>
						<?php echo $form->textArea($message,'message'); ?>
						<?php echo $form->error($message,'message'); ?>
					</div>
					<div class="row buttons">
						<?php echo CHtml::submitButton('Отправить'); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			<? } else { ?>
				<div class="user-message">Отвечать могут только <?php echo CHtml::link('авторизованные', array('/api/login')) ?> пользователи</div>
			<? } ?>
		<? } else { ?>
			<div class="forum-close">Тема закрыта!</div>
		<? } ?>
	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>'light')); ?>

<script type="text/javascript">
function editMessage(id)
{
	$.get(
		'<?php echo $this->createUrl('getMessage') ?>/'+id,
		function(data){
			if (!data.status)
				return;
			tinymce.activeEditor.setContent(data.data.message);
			$('#ForumMessages_id').val(id);
			$('#cancel_edit').remove();
			$('#forumMessages-form .buttons').append('<input id="cancel_edit" type="button" onClick="cancelEditMessage()" value="Отменить изменение">');
		},
		'json'
	);
	scroll('form');
	return false;
}

function answerMessage(id)
{
	$.get(
		'<?php echo $this->createUrl('getMessage') ?>/'+id,
		function(data){
			if (!data.status)
				return;
			tinymce.activeEditor.selection.setContent('<div class="forum-quote"><div class="fq-data">'+data.data.time+', '+data.data.author+' написал:</div><div class="fq-container">'+data.data.message+'</div></div>');
		},
		'json'
	);
	scroll('form');
	return false;
}

function cancelEditMessage()
{
	tinymce.activeEditor.setContent('');
	$('#ForumMessages_id').removeAttr('value');
	$('#cancel_edit').remove();
}
</script>