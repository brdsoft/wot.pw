<?php Yii::app()->clientScript->registerScriptFile('/js/tinymce/tinymce.min.js'); ?>
<?php
	$this->pageTitle=Yii::t('wot', 'My profile');
?>

	<div class="modul myaccount">

		<div class="title">
			<h3><?php echo Yii::t('wot', 'My profile')?></h3>
		</div>
		<div class="cell">

			<div class="table-data">
			<? $this->widget('zii.widgets.CDetailView', array(
					'cssFile'=>false,
					'data'=>$account,
					'attributes'=>array(
							array(
								'label'=>Yii::t('wot', 'Nickname'),
								'type'=>'raw',
								'value'=>$account->nickname,
							),
							array(
								'label'=>Yii::t('wot', 'Clan'),
								'type'=>'raw',
								'value'=>$account->clan_abbreviation ? '<a target="_blank" href="http://'.$this->site->cluster.'.wargaming.net/clans/'.$account->clan_id.'/">['.$account->clan_abbreviation.'] '.$account->clan_name.'</a>' : 'Нет',
							),
							array(
								'label'=>Yii::t('wot', 'Position'),
								'type'=>'raw',
								'value'=>WGApi::getClanRole($account->role),
							),
							array(
								'label'=>Yii::t('wot', 'WoT Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/">http://worldoftanks.ru/community/accounts/'.$account->id.'-'.$account->nickname.'/</a>',
							),
							array(
								'label'=>Yii::t('wot', 'BroneSite Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'">http://armor.kiev.ua/wot/gamerstat/'.$account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль КТТС',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="https://kttc.ru/wot/ru/user/'.$account->nickname.'">https://kttc.ru/wot/ru/user/'.$account->nickname.'</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Wotomatic Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://wotomatic.net/?search='.$account->nickname.'">http://wotomatic.net/?search='.$account->nickname.'</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Ivanerr Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://ivanerr.ru/lt/history/player/'.$account->nickname.'">http://ivanerr.ru/lt/history/player/'.$account->nickname.'</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Battles '),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBattleClass($stat['b_al'] / 1000).'">'.$stat['b_al'].'</span>',
							),
							array(
								'label'=>Yii::t('wot', 'Trees cut'),
								'value'=>$stat['t_cu'],
							),
							array(
								'label'=>Yii::t('wot', 'Winrate'),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWinClass($stat['wins']).'">'.$stat['wins'].'%</span>',
							),
							array(
								'label'=>Yii::t('wot', '"BroneSite" rating'),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBSClass($stat['bs']).'">'.round($stat['bs']).'</span>',
							),
							array(
								'label'=>Yii::t('wot', 'Efficiency rating'),
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getREClass($stat['re']).'">'.round($stat['re']).'</span>',
							),
							array(
								'label'=>'WN8',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWN8Class($stat['wn8']).'">'.round($stat['wn8']).'</span>',
							),
							array(
								'label'=>Yii::t('wot', 'Top-level (10) tanks'),
								'type'=>'raw',
								'value'=>$stat['t10'],
							),
							array(
								'label'=>Yii::t('wot', 'Top-level tanks list'),
								'type'=>'raw',
								'value'=>WGApi::getTanksColored($stat['tl10']),
							),
							array(
								'label'=>Yii::t('wot', 'Last seen on site '),
								'type'=>'raw',
								'value'=>$account->visited_at ? date('d.m.Y H:i', $account->visited_at+Yii::app()->params["moscow"]) : '---',
							),
							array(
								'label'=>Yii::t('wot', 'Last seen in game '),
								'type'=>'raw',
								'value'=>$stat['b_at'] ? date('d.m.Y H:i', $stat['b_at']+Yii::app()->params["moscow"]) : '---',
							),
							array(
								'label'=>Yii::t('wot', 'Last seen in TS '),
								'type'=>'raw',
								'value'=>$account->teamspeak_at ? date('d.m.Y H:i', $account->teamspeak_at+Yii::app()->params["moscow"]) : '---',
							),
					),
			)); ?>
			</div>

		</div>

	</div>



	<div class="modul myaccount">

		<div class="title">
			<h3><?php echo Yii::t('wot', 'Settings')?></h3>
		</div>
		<div class="cell">


			<div class="form">
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'profile-form',
					'enableAjaxValidation'=>false,
					'htmlOptions'=>array('enctype'=>'multipart/form-data'),
				)); ?>
				<p><?php echo Yii::t('wot', 'Your e-mail is hidden - we use it to send notifications and official letters. It is not necessary to use it.')?></p>
				<p><?php echo Yii::t('wot', 'Your Skype and Phone number will be available to clanmates only.')?></p>
				<?php echo $form->errorSummary($account); ?>
				<div class="row">
					<?php echo $form->textField($account,'name',array('maxlength'=>64)); ?>
					<?php echo $form->labelEx($account,'name'); ?>
				</div>
				<div class="row">
					<?php echo $form->textField($account,'email',array('maxlength'=>255)); ?>
					<?php echo $form->labelEx($account,'email'); ?>
				</div>
				<div class="row">
					<?php echo $form->textField($account,'tel',array('maxlength'=>64)); ?>
					<?php echo $form->labelEx($account,'tel'); ?>
				</div>
				<div class="row">
					<?php echo $form->textField($account,'skype',array('maxlength'=>64)); ?>
					<?php echo $form->labelEx($account,'skype'); ?>
				</div>
				<div class="row">
					<?php echo $form->textField($account,'city',array('maxlength'=>64)); ?>
					<?php echo $form->labelEx($account,'city'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'about'); ?>
					<?php echo $form->textArea($account,'about'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($account,'signature'); ?>
					<?php echo $form->textArea($account,'signature'); ?>
				</div>
				<style type="text/css">
					input[type=file] {
						background: none !important;
						padding: 0 !important;
						border: 0 !important;
					}
				</style>
				<div class="row" style="padding:0 0 0 2px;">

					<div><strong><?php echo Yii::t('wot', 'Avatar (should be < 2Mb)')?></strong></div>

					<?php if (empty($account->avatar)){ ?>
						<div><img style="max-width:120px; max-height:120px; margin:8px 0;" src="/upload/avatar/no-avatar.png" alt=""></div>
						<div><?php echo $form->fileField($account,'avatar'); ?></div>
					<? } else { ?>
						<? if ($account->hasErrors('avatar')){ ?>
							<div><?php echo $form->fileField($account,'avatar'); ?></div>
						<? } else { ?>
							<div><img style="max-width:150px; max-height:150px; margin:8px 0;" src="/upload/avatar/<?php echo $account->avatar ?>" alt=""></div>
							<div style="overflow:hidden;"><div style="float:left; padding:2px 5px 0 0;"><input type="checkbox" name="delete_avatar" value="1"></div><div style="float:left;"><?php echo Yii::t('wot', 'Delete')?></div></div>
						<? } ?>
					<?php } ?>

					<?php echo $form->error($account,'avatar'); ?>
				</div>

				<div class="row buttons">
					<?php echo CHtml::submitButton(Yii::t('wot', 'Save')); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>


		</div>

	</div>
	
	<? if ($account->clan_id){ ?>
		<div class="modul myaccount">
			<div class="title">
				<h3><?php echo Yii::t('wot', 'Battles Stats')?></h3>
			</div>
			<div class="cell">
				<?php echo WGApi::getOnline($account->online) ?>
			</div>
		</div>
	<? } ?>

<?php Yii::app()->controller->renderPartial('//tinymce/index', array('type'=>'tinymce_simple', 'skin'=>'dark')); ?>