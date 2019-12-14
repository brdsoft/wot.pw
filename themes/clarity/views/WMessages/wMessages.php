<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<a name="messages"></a>

<div class="modul news-comment">
	<div class="title">
		<a name="scrollTo"></a>
		<h3>Комментарии</h3>
	</div>
	<div class="cell">
		<div class="messages-grid"><!-- Не удалять! -->
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
				'htmlOptions'=>array('class'=>'items'),
				'columns'=>array(
						array(
							'sortable'=>false,
							'type'=>'raw',
							'name'=>'account_id',
							'value'=>'"
								<div class=\"avatar\"><img src=\"".($data->account->avatar ? "/upload/avatar/".$data->account->avatar : "/upload/avatar/no-avatar.png")."\" alt=\"".$data->account->nickname."\"></div>
								<div class=\"time\"><span>".date("d.m.Y", $data->time+Yii::app()->params["moscow"])."</span> <span>".date("H:i", $data->time+Yii::app()->params["moscow"])."</span></div>
							"',
							'header'=>'Автор',
						),
						array(
							'sortable'=>false,
							'name'=>'message',
							'type'=>'raw',
							'value'=>'
							"<div class=\"status\">
								<div class=\"nickname\"><a name=\"message".$data->id."\"></a>".CHtml::link($data->account->nickname.($data->account->clan_abbreviation ? " [".$data->account->clan_abbreviation."]" : ""), array("/profile/account", "id"=>$data->account->id))."</div>
								<div class=\"role\">".($data->account->id == 11944945 || $data->account->id == 7208418 || $data->account->id == 59963605 || $data->account->id == 4388762 ? "<span style=\"color: red;\">Разработчики</span>" : $data->account->role_i18n)."</div>
								<div class=\"links\">".$data->adminLinks."</div>
							</div>
							<div class=\"message\">".Yii::app()->format->mhtml($data->message)."</div>".(trim($data->account->signature) ? "" : ""
							)',
						),
				),
				'summaryText'=>false,
				'emptyText'=>'Сообщений нет',
		));
		?>
		</div>
		<? if (Yii::app()->user->account){ ?>
			<div class="form">
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'messages-form',
					'enableAjaxValidation'=>false,
				)); ?>
				<?php echo $form->errorSummary($message); ?>
				<div class="row">
					<?php echo $form->textArea($message,'message'); ?>
					<?php echo $form->error($message,'message'); ?>
				</div>
				<div class="row buttons">
					<?php echo CHtml::submitButton('Отправить'); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		<? } ?>
	</div>
</div>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_simple', 'skin'=>'dark')); ?>