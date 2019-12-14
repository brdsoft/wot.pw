<?php
	$this->pageTitle=$account->nickname;
?>

	<div class="modul myaccount">

		<div class="custom-title">
			<h4><?php echo $account->nickname ?></h4>
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
							array(
								'label'=>'Управление',
								'type'=>'raw',
								'value'=>CHtml::link('Забанить', array('/admin/bans/create?id='.$account->id)),
								'visible'=>Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8'),
							),
					),
			));			?>
			</div>

		</div>

	</div>

	<div class="modul myaccount">

		<div class="custom-title">
			<h4>Личные данные</h4>
		</div>
		<div class="pastic">

			<div class="table-data">
			<? $this->widget('zii.widgets.CDetailView', array(
					'cssFile'=>false,
					'data'=>$account,
					'attributes'=>array(
							array(
								'label'=>'Имя',
								'type'=>'text',
								'value'=>$account->name ? $account->name : 'Не указано',
							),
							array(
								'label'=>'Регион',
								'type'=>'text',
								'value'=>$account->city ? $account->city : ($account->region ? $account->region.' (определен автоматически)' : 'Не указан'),
							),
							array(
								'label'=>'Телефон',
								'type'=>'text',
								'value'=>$account->tel ? $account->tel : 'Не указан',
								'visible'=>Yii::app()->user->account && $account->clan_abbreviation != '' && $account->clan_abbreviation == Yii::app()->user->account->clan_abbreviation,
							),
							array(
								'label'=>'Skype',
								'type'=>'text',
								'value'=>$account->skype ? $account->skype : 'Не указан',
								'visible'=>Yii::app()->user->account && $account->clan_abbreviation != '' && $account->clan_abbreviation == Yii::app()->user->account->clan_abbreviation,
							),
							array(
								'label'=>'О себе',
								'type'=>'html',
								'value'=>$account->about ? $account->about : 'Не указано',
							),
							array(
								'label'=>'Подпись на форуме',
								'type'=>'html',
								'value'=>$account->signature ? '<div class="signature">'.$account->signature.'</div>' : 'Не указана',
							),
							array(
								'label'=>'Аватарка',
								'type'=>'raw',
								'value'=>$account->avatar ? '<img style="max-width: 150px; max-height: 150px;" src="/upload/avatar/'.$account->avatar.'" alt="">' : '<img style="max-width: 150px; max-height: 150px;" src="/upload/avatar/no-avatar.png" alt=""/>',
							),
					),
			));			?>
			</div>

		</div>

	</div>




	<style type="text/css">
		.signature img {
			background: #f0f0f0;
		}
	</style>




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