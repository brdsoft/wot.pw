<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
	$this->pageTitle='Мой аккаунт';
?>

	<div class="modul myaccount">

		<div class="custom-title">
			<h4>Мой аккаунт</h4>
		</div>
		<div class="pastic">

			<div class="table-data">
			<? $this->widget('zii.widgets.CDetailView', array(
					'cssFile'=>false,
					'data'=>$account,
					'attributes'=>array(
							array(
								'label'=>'Никнейм в игре',
								'type'=>'raw',
								'value'=>$account->nickname,
							),
							array(
								'label'=>'Клан',
								'type'=>'raw',
								'value'=>$account->clan_abbreviation ? '<a target="_blank" href="http://'.$this->site->cluster.'.wargaming.net/clans/'.$account->clan_id.'/">['.$account->clan_abbreviation.'] '.$account->clan_name.'</a>' : 'Нет',
							),
							array(
								'label'=>'Должность',
								'type'=>'raw',
								'value'=>WGApi::getClanRole($account->role),
							),
							array(
								'label'=>'Профиль WoT',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/">http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/</a>',
							),
							array(
								'label'=>'Профиль Бронесайт',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'">http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль КТТС',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="https://kttc.ru/wot/ru/user/'.$account->nickname.'">https://kttc.ru/wot/ru/user/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль Wotomatic',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://wotomatic.net/?search='.$account->nickname.'">http://wotomatic.net/?search='.$account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль Иванерр',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://ivanerr.ru/lt/history/player/'.$account->nickname.'">http://ivanerr.ru/lt/history/player/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Боев',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBattleClass($stat['b_al'] / 1000).'">'.$stat['b_al'].'</span>',
							),
							array(
								'label'=>'Поваленных деревьев',
								'value'=>$stat['t_cu'],
							),
							array(
								'label'=>'Побед',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWinClass($stat['wins']).'">'.$stat['wins'].'%</span>',
							),
							array(
								'label'=>'Бронесайт',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBSClass($stat['bs']).'">'.round($stat['bs']).'</span>',
							),
							array(
								'label'=>'РЭ',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getREClass($stat['re']).'">'.round($stat['re']).'</span>',
							),
							array(
								'label'=>'WN8',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWN8Class($stat['wn8']).'">'.round($stat['wn8']).'</span>',
							),
							array(
								'label'=>'Кол-во ТОП техники',
								'type'=>'raw',
								'value'=>$stat['t10'],
							),
							array(
								'label'=>'Список ТОП Техники',
								'type'=>'raw',
								'value'=>WGApi::getTanksColored($stat['tl10']),
							),
							array(
								'label'=>'Был на сайте',
								'type'=>'raw',
								'value'=>$account->visited_at ? date('d.m.Y H:i', $account->visited_at+Yii::app()->params["moscow"]) : '---',
							),
							array(
								'label'=>'Был в бою',
								'type'=>'raw',
								'value'=>$stat['b_at'] ? date('d.m.Y H:i', $stat['b_at']+Yii::app()->params["moscow"]) : '---',
							),
							array(
								'label'=>'Был в TeamSpeak',
								'type'=>'raw',
								'value'=>$account->teamspeak_at ? date('d.m.Y H:i', $account->teamspeak_at+Yii::app()->params["moscow"]) : '---',
							),
					),
			)); ?>
			</div>

		</div>

	</div>



	<div class="modul myaccount">

		<div class="custom-title">
			<h4>Настройки</h4>
		</div>
		<div class="pastic">


			<div class="form">
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'profile-form',
					'enableAjaxValidation'=>false,
					'htmlOptions'=>array('enctype'=>'multipart/form-data'),
				)); ?>
				<p class="note">E-mail никому не виден, используется для получения уведомлений и новых сообщений. Вы можете его не указывать.</p>
				<p class="note">Телефон и скайп будут видны только клану. Вы можете их не указывать.</p>
				<p class="note">Подпись для форума пишите в HTML формате (BB-code не принимается).</p>
				<?php echo $form->errorSummary($account); ?>
				<div class="row">
					<?php echo $form->labelEx($account,'name'); ?>
					<?php echo $form->textField($account,'name',array('maxlength'=>64)); ?>
					<?php echo $form->error($account,'name'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'email'); ?>
					<?php echo $form->textField($account,'email',array('maxlength'=>255)); ?>
					<?php echo $form->error($account,'email'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'tel'); ?>
					<?php echo $form->textField($account,'tel',array('maxlength'=>64)); ?>
					<?php echo $form->error($account,'tel'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'skype'); ?>
					<?php echo $form->textField($account,'skype',array('maxlength'=>64)); ?>
					<?php echo $form->error($account,'skype'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'city'); ?>
					<?php echo $form->textField($account,'city',array('maxlength'=>64)); ?>
					<?php echo $form->error($account,'city'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'about'); ?>
					<?php echo $form->textArea($account,'about'); ?>
					<div class="both"></div>
					<?php echo $form->error($account,'about'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'signature'); ?>
					<?php echo $form->textArea($account,'signature'); ?>
					<div class="both"></div>
					<?php echo $form->error($account,'signature'); ?>
				</div>
				<style type="text/css">
					input[type=file] {
						background: none !important;
						padding: 0 !important;
						border: 0 !important;
					}
				</style>
				<div class="row">
					<?php echo $form->labelEx($account,'avatar'); ?>
					<div style="margin-left: 180px;">
						<?php if (empty($account->avatar)){ ?>
							<div><img style="max-width: 150px; max-height: 150px;" src="/upload/avatar/no-avatar.png" alt=""/></div>
							<div style="margin: 5px 0 0;"><?php echo $form->fileField($account,'avatar'); ?></div>
						<? } else { ?>
							<? if ($account->hasErrors('avatar')){ ?>
								<div style="margin: 5px 0 0;"><?php echo $form->fileField($account,'avatar'); ?></div>
							<? } else { ?>
								<div><img style="max-width: 150px; max-height: 150px;" src="/upload/avatar/<?php echo $account->avatar ?>" alt=""/></div>
								<div style="margin: 5px 0 0;"><input type="checkbox" name="delete_avatar" style="width: auto; margin: 0;" value="1"> Удалить</div>
							<? } ?>
						<?php } ?>
					</div>
					<?php echo $form->error($account,'avatar'); ?>
				</div>

				<div class="row buttons">
					<?php echo CHtml::submitButton('Отправить'); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>


		</div>

	</div>

	<? if ($account->clan_id){ ?>

	<div class="modul myaccount">

		<div class="custom-title">
			<h4>Статистика боев</h4>
		</div>
		<div class="pastic">

<?php echo WGApi::getOnline($account->online) ?>

		</div>

	</div>

	<? } ?>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_enlarge', 'skin'=>'light')); ?>