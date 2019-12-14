<div class="cap-navigation">
	<?php $this->widget('Menu',array(
		'activateParents'=>true,
		'activateItems'=>true,
		'items'=>array_merge($this->controller->menuTop, array(
			array('label'=>'Выйти ('.(empty(Yii::app()->user->account) ? Yii::app()->user->id : Yii::app()->user->account->nickname).(empty(Yii::app()->user->account->clan_abbreviation) ? '' : ' ['.Yii::app()->user->account->clan_abbreviation.']').')', 'url'=>array('/api/logout'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'right logout')),
			array('label'=>'Мой аккаунт', 'url'=>array('/profile/index'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'right myprofile')),
			array('label'=>'Войти на сайт', 'url'=>array('/api/login'), 'visible'=>Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'right login')),
		)),
	)); ?>
</div>
