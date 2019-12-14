<?php
	$this->pageTitle=$account->nickname;
?>

	<div class="modul myaccount">

		<div class="title">
			<h3><?php echo $account->nickname ?></h3>
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
								'label'=>'Профиль КТТС',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="https://kttc.ru/wot/ru/user/'.$account->nickname.'">https://kttc.ru/wot/ru/user/'.$account->nickname.'</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Noobometr Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://noobmeter.com/player/ru/'.$account->nickname.'">http://noobmeter.com/player/ru/'.$account->nickname.'</a>',
							),
							array(
								'label'=>Yii::t('wot', 'Wotomatic Profile'),
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://wotomatic.net/?search='.$account->nickname.'">http://wotomatic.net/?search='.$account->nickname.'</a>',
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
							array(
								'label'=>Yii::t('wot', 'Manage'),
								'type'=>'raw',
								'value'=>CHtml::link(Yii::t('wot', 'Ban '), array('/admin/bans/create?id='.$account->id)),
								'visible'=>Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8'),
							),
					),
			));			?>
			</div>

		</div>

	</div>

	<div class="modul myaccount">

		<div class="title">
			<h3><?php echo Yii::t('wot', 'Personal info')?></h3>
		</div>
		<div class="cell">

			<div class="table-data">
			<? $this->widget('zii.widgets.CDetailView', array(
					'cssFile'=>false,
					'data'=>$account,
					'attributes'=>array(
							array(
								'label'=>Yii::t('wot', 'Name '),
								'type'=>'text',
								'value'=>$account->name ? $account->name : Yii::t('wot', 'N/N'),
							),
							array(
								'label'=>Yii::t('wot', 'Region'),
								'type'=>'text',
								'value'=>$account->city ? $account->city : ($account->region ? $account->region.' '.Yii::t('wot', '(generated automatically) ') : Yii::t('wot', 'N/N')),
							),
							array(
								'label'=>Yii::t('wot', 'Phone'),
								'type'=>'text',
								'value'=>$account->tel ? $account->tel : Yii::t('wot', 'N/N'),
								'visible'=>Yii::app()->user->account && $account->clan_abbreviation != '' && $account->clan_abbreviation == Yii::app()->user->account->clan_abbreviation,
							),
							array(
								'label'=>'Skype',
								'type'=>'text',
								'value'=>$account->skype ? $account->skype : Yii::t('wot', 'N/N'),
								'visible'=>Yii::app()->user->account && $account->clan_abbreviation != '' && $account->clan_abbreviation == Yii::app()->user->account->clan_abbreviation,
							),
							array(
								'label'=>Yii::t('wot', 'About me'),
								'type'=>'html',
								'value'=>$account->about ? $account->about : Yii::t('wot', 'N/N'),
							),
							array(
								'label'=>Yii::t('wot', 'Signature'),
								'type'=>'html',
								'value'=>$account->signature ? '<div class="signature">'.$account->signature.'</div>' : Yii::t('wot', 'N/N '),
							),
							array(
								'label'=>Yii::t('wot', 'Avatar'),
								'type'=>'raw',
								'value'=>$account->avatar ? '<img style="max-width: 150px; max-height: 150px;" src="/upload/avatar/'.$account->avatar.'" alt="">' : '<img style="max-width: 150px; max-height: 150px;" src="/upload/avatar/no-avatar.png" alt=""/>',
							),
					),
			));			?>
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
