<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<a name="messages"></a>
<div class="block">
	<div class="head head2">
		Обсуждение
	</div>
	<div class="body body2">
		<a name="scrollTo"></a>
		<div class="messages-grid">
			<?
			$dataProvider=new CActiveDataProvider('Messages', array(
				'criteria'=>array(
					'condition'=>"t.name = '{$name}' AND t.object_id = '{$object_id}'",
					'with'=>array('account'),
				),
				'sort'=>array(
					'defaultOrder'=>"t.id ASC",
				),
				'pagination'=>array(
					'pageSize'=>500,
				),
			));
			$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'cssFile'=>false,
					'hideHeader'=>true,
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
								'value'=>'"<div class=\"time\"><div style=\"float: left;\">".date("d.m.Y H:i", $data->time+Yii::app()->params["moscow"])."</div><div style=\"float: right;\">".$data->adminLinks." <a href=\"#message".$data->id."\">#</a></div><div class=\"both\"></div></div><div class=\"message\">".Yii::app()->format->mhtml($data->message)."</div>"
								.(trim($data->account->signature) ? "<div class=\"signature\">".Yii::app()->format->html($data->account->signature)."</div>" : ""
								)',
							),
					),
					'summaryText'=>'Время Московское',
					'emptyText'=>'Сообщений нет',
			));
			?>
		</div>
		<? if (Yii::app()->user->account){ ?>
		<div class="block2">
			<div class="form">
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'messages-form',
					'enableAjaxValidation'=>false,
				)); ?>
				<?php echo $form->errorSummary($message); ?>
				<div class="row">
					<?php echo $form->labelEx($message,'message'); ?>
					<?php echo $form->textArea($message,'message'); ?>
					<?php echo $form->error($message,'message'); ?>
				</div>
				<div class="both"></div>
				<div class="row buttons">
					<?php echo CHtml::submitButton('Отправить'); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
		<? } ?>
	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_simple', 'skin'=>preg_match('=^2$=', Yii::app()->controller->site->style_id) ? 'light' : 'dark')); ?>