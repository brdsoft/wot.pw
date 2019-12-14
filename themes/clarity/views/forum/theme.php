<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
$this->pageTitle=$theme->name;

function getThemeAdminLinks($theme)
{
	$links = '';
	
	if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')){
		$links .= '<div class="forum-button">';
		if (!$theme->closed){
			$links .= CHtml::link(Yii::t('wot', 'Close topic'), '#', array('class'=>'close-theme', 'submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'close')));
		} else {
			$links .= CHtml::link(Yii::t('wot', 'Reopen topic'), '#', array('class'=>'open-theme', 'submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'open')));
		}
		if (!$theme->fixed){
			$links .= CHtml::link(Yii::t('wot', 'Stick topic'), '#', array('class'=>'fixed-theme', 'submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'fix')));
		} else {
			$links .= CHtml::link(Yii::t('wot', 'Unstick topic'), '#', array('class'=>'unfixed-theme', 'submit'=>'#', 'csrf'=>true, 'params'=>array('action'=>'unfix')));
		}
		$links .= '</div>';
	}
	
	return $links;
}
?>

<div class="modul forum theme">
	<div class="title">
		<h3><?php echo Yii::t('wot', 'Forum')?></h3>
	</div>
	<div class="cell">

		<h3 class="category-title"><span class="small"><?php echo CHtml::link(Yii::t('wot', 'Forum'), array('/forum')) ?> &rarr; <?php echo CHtml::link(CHtml::encode($theme->category->name), array('/forum/category', 'id'=>$theme->category->id)) ?></span><span><?php echo CHtml::encode($theme->name) ?></span></h3>

		<div id="messages"><!-- Не удалять тег! -->
		<?
		$dataProvider=new CActiveDataProvider('ForumMessages', array(
			'criteria'=>array(
				'condition'=>"`theme_id` = '{$theme->id}'",
				'with'=>['account'=>['with'=>'forumMessagesCount']],
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
			'template'=>'<div class="forum-nav">{pager}'.getThemeAdminLinks($theme).'</div>{items}<div class="forum-nav">{pager}'.getThemeAdminLinks($theme).'</div>',
			'columns'=>array(
				array(
					'sortable'=>false,
					'type'=>'raw',
					'name'=>'account_id',
					'value'=>'"
						<div class=\"nickname\"><a name=\"message".$data->id."\"></a>".CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account->id), array("class"=>"user"))."</div>
						<div class=\"role\">".(in_array($data->account->id, Yii::app()->params["admins"]) ? "<span class=\"adm-ex\" style=\"color: red; font-weight: bold;\">Разработчики</span>" : $data->account->role_i18n)."</div>
						<div class=\"avatar\"><img src=\"".($data->account->avatar ? "/upload/avatar/".$data->account->avatar : "/upload/avatar/no-avatar.png")."\" alt=\"".$data->account->nickname."\"></div>
						".(Yii::app()->user->account ? "<div class=\"messages\">Сообщения: <span>".$data->account->forumMessagesCount."</span></div>" : "")."
						".($data->account->clan_id ? "<div class=\"clan\">".CHtml::link("[".$data->account->clan_abbreviation."]", "http://ru.wargaming.net/clans/".$data->account->clan_id."/", ["target"=>"_blank", "style"=>"background-image:url(http://".Yii::app()->controller->site->cluster.".wargaming.net/clans/media/clans/emblems/cl_".substr($data->account->clan_id, -3)."/".$data->account->clan_id."/emblem_24x24.png);"])."</div>" : "")."
						".$data->getSiteUrl()."
					"',
					'header'=>'Автор',
				),
				array(
					'sortable'=>false,
					'type'=>'raw',
					'name'=>'message',
					'value'=>'
					"<div class=\"status\"><div class=\"time\"><span>Отправлено ".date("d.m.Y", $data->time+Yii::app()->params["moscow"])." - ".date("H:i", $data->time+Yii::app()->params["moscow"])."</span></div>
					<div class=\"links\">".$data->adminLinks.CHtml::link("#", array("/forum/theme", "category_id"=>'.$theme->category_id.', "id"=>'.$theme->id.', "ForumMessages_page"=>$this->grid->dataProvider->pagination->currentPage+1, "#"=>"message".$data->id), array("title"=>"Ссылка на это сообщение"))."</div>
					</div><div class=\"message\">".Yii::app()->format->mhtml($data->message)."</div>
					".($data->updated_time ? "<div class=\"update\">Сообщение отредактировал ".CHtml::link($data->updated_account->nickname, array("/profile/account", "id"=>$data->updated_account->id)).": ".date("d.m.Y H:i", $data->updated_time+Yii::app()->params["moscow"])."</div>" : "")."
					".(trim($data->account->signature) ? "<div class=\"signature\">".Yii::app()->format->html($data->account->signature)."</div>" : "")."
					"',
				),

			),
			'emptyText'=>Yii::t('wot', 'No posts found'),
			'summaryText'=>false,
		));
		?>
		</div>

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
						<?php echo $form->textArea($message,'message'); ?>
						<?php echo $form->error($message,'message'); ?>
					</div>
					<div class="row buttons">
						<?php echo CHtml::submitButton(Yii::t('wot', 'Send')); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			<? } else { ?>
				<div class="user-message"><?php echo Yii::t('wot', 'Please, ')?><?php echo CHtml::link(Yii::t('wot', 'login'), array('/api/login')) ?> <?php echo Yii::t('wot', 'to post a message, ')?></div>
			<? } ?>
		<? } else { ?>
			<div class="theme-close"><?php echo Yii::t('wot', 'Topic closed!')?></div>
		<? } ?>
	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>'dark')); ?>

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