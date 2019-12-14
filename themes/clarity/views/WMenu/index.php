		<nav class="navigation">

			<div class="navigation-inner">

<?php $this->widget('Menu',array(
  'activateParents'=>true,
  'activateItems'=>true,
  'htmlOptions'=>array('class'=>'nav-menu'),
  'items'=>$this->controller->menuTop,
)); ?>


<?php $this->widget('Menu',array(
  'activateParents'=>true,
  'activateItems'=>true,
  'htmlOptions'=>array('class'=>'nav-login'),
  'items'=> array(
   array('label'=>Yii::t('wot', 'My profile'), 'url'=>array('/profile/index'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'myprofile')),
   array('label'=>Yii::t('wot', 'Log Out').' ('.(empty(Yii::app()->user->account) ? Yii::app()->user->id : Yii::app()->user->account->nickname).')', 'url'=>array('/api/logout'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'logout')),
   array('label'=>Yii::t('wot', 'Log In'), 'url'=>array('/api/login'), 'visible'=>Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'login')),
  ),
)); ?>

			</div>

		</nav>