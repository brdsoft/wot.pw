<?php
/* @var $this SiteController */

$this->pageTitle='Заявка на вступление в клан '.$recruitment->account->nickname.' от '.date('d.m.Y H:i', $recruitment->time+Yii::app()->params["moscow"]);

?>

<div class="modul recruitment">

	<div class="custom-title">
		<h4>Заявка на вступление в клан <?php echo $recruitment->account->nickname ?> от <?php echo date('d.m.Y H:i', $recruitment->time+Yii::app()->params["moscow"]) ?></h4>
	</div>
	<div class="pastic">
		<div class="table-data">
			<? $this->widget('zii.widgets.CDetailView', array(
					'cssFile'=>false,
					'data'=>$recruitment,
					'attributes'=>array(
							array(
								'label'=>'Кандидат',
								'type'=>'raw',
								'value'=>$recruitment->account->nickname.($recruitment->account->clan_id ? ' (состоит в клане ['.$recruitment->account->clan_abbreviation.'] '.$recruitment->account->clan_name.')' : ''),
							),
							'name',
							'age',
							array(
								'label'=>'Регион',
								'type'=>'raw',
								'value'=>$recruitment->account->city ? $recruitment->account->city : $recruitment->account->region.' (определен автоматически)',
							),
							'experience',
							'about',
							array(
								'label'=>'Профиль WoT',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://worldoftanks.ru/community/accounts/'.$recruitment->account->id.'-'.$recruitment->account->nickname.'/">http://worldoftanks.ru/community/accounts/'.$recruitment->account->id.'-'.$recruitment->account->nickname.'/</a>',
							),
							array(
								'label'=>'Профиль Бронесайт',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://armor.kiev.ua/wot/gamerstat/'.$recruitment->account->nickname.'">http://armor.kiev.ua/wot/gamerstat/'.$recruitment->account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль КТТС',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="https://kttc.ru/wot/ru/user/'.$recruitment->account->nickname.'">https://kttc.ru/wot/ru/user/'.$recruitment->account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль Wotomatic',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://wotomatic.net/?search='.$recruitment->account->nickname.'">http://wotomatic.net/?search='.$recruitment->account->nickname.'</a>',
							),
							array(
								'label'=>'Профиль Иванерр',
								'type'=>'raw',
								'value'=>'<a target="_blank" href="http://ivanerr.ru/lt/history/player/'.$recruitment->account->nickname.'">http://ivanerr.ru/lt/history/player/'.$recruitment->account->nickname.'</a>',
							),
							array(
								'label'=>'Боев',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBattleClass($recruitment->account->stat['b_al'] / 1000).'">'.$recruitment->account->stat['b_al'].'</span>',
							),
							array(
								'label'=>'Побед',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWinClass($recruitment->account->stat['wins']).'">'.$recruitment->account->stat['wins'].'%</span>',
							),
							array(
								'label'=>'Бронесайт',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getBSClass($recruitment->account->stat['bs']).'">'.round($recruitment->account->stat['bs']).'</span>&nbsp;&nbsp;&nbsp;'.$stat['bs'],
							),
							array(
								'label'=>'РЭ',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getREClass($recruitment->account->stat['re']).'">'.round($recruitment->account->stat['re']).'</span>&nbsp;&nbsp;&nbsp;'.$stat['re'],
							),
							array(
								'label'=>'WN8',
								'type'=>'raw',
								'value'=>'<span class="'.WGApi::getWN8Class($recruitment->account->stat['wn8']).'">'.round($recruitment->account->stat['wn8']).'</span>&nbsp;&nbsp;&nbsp;'.$stat['wn8'],
							),
							array(
								'label'=>'Кол-во техники 8 ур.',
								'type'=>'raw',
								'value'=>$recruitment->account->stat['t8'],
							),
							array(
								'label'=>'Кол-во техники 10 ур.',
								'type'=>'raw',
								'value'=>$recruitment->account->stat['t10'],
							),
							array(
								'label'=>'Список техники 10 ур.',
								'type'=>'raw',
								'value'=>WGApi::getTanksColored($recruitment->account->stat['tl10']),
							),
							array(
								'label'=>'Создан',
								'type'=>'raw',
								'value'=>$recruitment->account->created_at ? date('d.m.Y H:i', $recruitment->account->created_at + Yii::app()->params['moscow']) : '---',
							),
							array(
								'label'=>'Был в бою',
								'type'=>'raw',
								'value'=>$recruitment->account->stat['b_at'] ? date('d.m.Y H:i', $recruitment->account->stat['b_at'] + Yii::app()->params['moscow']) : '---',
							),
							array(
								'label'=>'Желаемый клан',
								'type'=>'raw',
								'value'=>isset($recruitment->clan) ? '['.$recruitment->clan->abbreviation.'] '.$recruitment->clan->name : '',
							),
							array(
								'label'=>'Статус',
								'type'=>'raw',
								'value'=>$recruitment->resolution == 0 ? $recruitment->resolutionColored : $recruitment->resolutionColored.' - Решение принял '.CHtml::link($recruitment->resolutionAccount->nickname, array('/profile/account', 'id'=>$recruitment->resolutionAccount->id)).' '.date('d.m.Y H:i', $recruitment->resolution_time+Yii::app()->params['moscow']),
							),
					),
			));
			?>
		</div>
		<? if (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('4') || Yii::app()->user->checkAccess('8')){ ?>
			<div class="form">
				<p style="line-height: 28px;">
					<select name="" id="select_resolution">
						<option value="3">Одобрен</option>
						<option value="4">Приглашен</option>
						<option value="1">Принят</option>
					</select> &nbsp;в&nbsp;
					<? foreach (Clans::model()->findAll(array('order'=>'`order`')) as $clan){ ?>
						<a href="##" onClick="document.location.href='/recruitment/one/<?php echo $recruitment->id ?>?invited=<?php echo $clan->abbreviation ?>&resolution='+$('#select_resolution').val();"><?php echo $clan->abbreviation ?></a>&nbsp;&nbsp;|&nbsp;
					<? } ?>
					<?php echo CHtml::link('Отказ', array('/recruitment/one', 'id'=>$recruitment->id, 'resolution'=>2)); ?>
				</p>
				<p class="note">* Не забудь пригласить игрока на <a target="_blank"  href="http://worldoftanks.ru/community/accounts/<?php echo $recruitment->account->id ?>-<?php echo $recruitment->account->nickname ?>/">официальном сайте</a>.</p>
				<div id="profile"></div>
			</div>
		<? } ?>
	</div>
</div>
<?php $this->widget('application.components.WMessages', array('name'=>'recruitment', 'object_id'=>$recruitment->id)); ?>
