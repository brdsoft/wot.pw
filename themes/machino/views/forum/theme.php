<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
/* @var $this SiteController */

$this->pageTitle=$theme->name;

?>

<div class="block">
	<div class="head head2">
		<?php echo CHtml::link('Форум', array('/forum')) ?> - <?php echo CHtml::link(CHtml::encode($theme->category->name), array('/forum/category', 'id'=>$theme->category->id)) ?> - <?php echo CHtml::encode($theme->name) ?>
	</div>
	<div class="body body2">
		<div class="forum-grid" id="messages">
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
					'pager'=>array(
						'cssFile'=>false,
						//'nextPageLabel'=>'>',
						//'lastPageLabel'=>'>>',
					),
					'columns'=>array(
						array(
							'sortable'=>false,
							'type'=>'raw',
							'name'=>'account_id',
							'value'=>'"<div class=\"nickname\" style=\"overflow:hidden; white-space: nowrap;\"><a name=\"message".$data->id."\"></a>".CHtml::link($data->account->nickname.($data->account->clan_abbreviation ? " [".$data->account->clan_abbreviation."]" : ""), array("/profile/account", "id"=>$data->account->id))."</div><div class=\"avatar\"><img src=\"".($data->account->avatar ? "/upload/avatar/".$data->account->avatar : "/upload/avatar/no-avatar.png")."\" alt=\"".$data->account->nickname."\"/></div><div class=\"role\">".$data->account->role_i18n."</div>"',
							'header'=>'Автор',
							'htmlOptions'=>array('style'=>'width: 190px;'),
						),
						array(
							'sortable'=>false,
							'name'=>'message',
							'type'=>'raw',
							'value'=>'"<div class=\"time\"><div style=\"float: left;\">".date("d.m.Y H:i", $data->time+Yii::app()->params["moscow"])."</div><div style=\"float: right;\">".$data->adminLinks."<a href=\"#message".$data->id."\">#</a></div><div class=\"both\"></div></div><div class=\"message\">".Yii::app()->format->mhtml($data->message)."</div>"
							.($data->updated_time ? "<div class=\"update\">Сообщение отредактировал ".CHtml::link($data->updated_account->nickname, array("/profile/account", "id"=>$data->updated_account->id)).": ".date("d.m.Y H:i", $data->updated_time+Yii::app()->params["moscow"])."</div>" : "")
							.(trim($data->account->signature) ? "<div class=\"signature\">".Yii::app()->format->html($data->account->signature)."</div>" : ""
							)',
						),
					),
					'emptyText'=>'Сообщений нет',
					'summaryText'=>false,
			));
			?>
		</div>
		<div class="block2">
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
							<div class="both"></div>
							<?php echo $form->error($message,'message'); ?>
						</div>
			
						<div class="both"></div>

						<div class="row buttons">
							<?php echo CHtml::submitButton('Отправить'); ?>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				<? } else { ?>
					Отвечать могут только <?php echo CHtml::link('авторизованные', array('/api/login')) ?> пользователи
				<? } ?>
			<? } else { ?>
				Тема закрыта
			<? } ?>
		</div>
			<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')){ ?>
			<hr/>
			<? if (!$theme->closed){ ?>
				<?php echo CHtml::link('Закрыть тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'close'))); ?>
			<? } else { ?>
				<?php echo CHtml::link('Открыть тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'open'))); ?>
			<? } ?>
			|
			<? if (!$theme->fixed){ ?>
				<?php echo CHtml::link('Прикрепить тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'fix'))); ?>
			<? } else { ?>
				<?php echo CHtml::link('Открепить тему', '#', array('submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'unfix'))); ?>
			<? } ?>
		<? } ?>
	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>preg_match('=^2$=', Yii::app()->controller->site->style_id) ? 'light' : 'dark')); ?>

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