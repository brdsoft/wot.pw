<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'Магазин дополнений');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Магазин дополнений')?></h2>

<div class="content-inner-page">
	
	<div class="content-column">
		
		<div class="filling">
			<div class="depot">
				<div class="stock">					
					<p class="description"><strong>Отключение рекламы</strong> Вы можете улучшить внешний вид сайта отключив всю рекламу. Реклама отключается навсегда.</p>
					<? if ($this->site->premium_reklama){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[1]['amount_discount'] ? '<span class="old">'.$orders->items_ru[1]['amount'].'</span> <span class="new">'.$orders->items_ru[1]['amount_discount'].'</span>' : $orders->items_ru[1]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[1]['amount_discount'] && $this->site->balance < $orders->items_ru[1]['amount_discount']) || (!$orders->items_ru[1]['amount_discount'] && $this->site->balance < $orders->items_ru[1]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>1], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Собственный домен</strong> Измените адрес своего сайта на любой свободный в зоне .RU (например myclan.ru). Если у вас нет собственного домена, мы зарегистрируем его для вас. <span style="font-weight:bold;color:red;">Обязательно ознакомьтесь с <a href="http://wot.pw/rshop/domain" target="_blank">регламентом подключения и продления домена</a>.</span></p>
					<? if ($this->site->premium_domain != 'Не подключен'){ ?>
						<p class="price status-success"><?php echo $this->site->premium_domain ?></p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[2]['amount_discount'] ? '<span class="old">'.$orders->items_ru[2]['amount'].'</span> <span class="new">'.$orders->items_ru[2]['amount_discount'].'</span>' : $orders->items_ru[2]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[2]['amount_discount'] && $this->site->balance < $orders->items_ru[2]['amount_discount']) || (!$orders->items_ru[2]['amount_discount'] && $this->site->balance < $orders->items_ru[2]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>2], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Увеличение количества кланов</strong> Здесь можно увеличить лимит кланов на сайте на 10. Ваш текущий лимит кланов - <b><?php echo $this->site->premium_clans ?></b>. После активации станет - <b><?php echo $this->site->premium_clans + 10 ?></b>.</p>
					<p class="price">
						<span class="new"><?php echo $orders->items_ru[3]['amount_discount'] ? '<span class="old">'.$orders->items_ru[3]['amount'].'</span> <span class="new">'.$orders->items_ru[3]['amount_discount'].'</span>' : $orders->items_ru[3]['amount'] ?></span> <span class="rub">руб.</span>
						<? if (($orders->items_ru[3]['amount_discount'] && $this->site->balance < $orders->items_ru[3]['amount_discount']) || (!$orders->items_ru[3]['amount_discount'] && $this->site->balance < $orders->items_ru[3]['amount'])){ ?>
							<span class="status-fail">Недостаточно средств</span>
						<? } else { ?>
							<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>3], 'confirm'=>'Активировать услугу?']) ?>
						<? } ?>
					</p>
				</div>
				<div class="stock">					
					<p class="description"><strong>Редактор HTML кода</strong> Подключите редактор HTML и JavaSript кода и сделайте свой сайт уникальным. Перед покупкой обязательно ознакомьтесь с <a href="http://wot.pw/rshop/html" target="_blank">регламентом подключения редактора</a>.</p>
					<? if ($this->site->premium_html){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[4]['amount_discount'] ? '<span class="old">'.$orders->items_ru[4]['amount'].'</span> <span class="new">'.$orders->items_ru[4]['amount_discount'].'</span>' : $orders->items_ru[4]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[4]['amount_discount'] && $this->site->balance < $orders->items_ru[4]['amount_discount']) || (!$orders->items_ru[4]['amount_discount'] && $this->site->balance < $orders->items_ru[4]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>4], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Разделение кланов на роты</strong> Разделите ваши кланы на подразделения. После покупки в штабе и в консоли появится раздел "Роты". Функционал рот будет постепенно расширяться.</p>
					<? if ($this->site->premium_companies){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[5]['amount_discount'] ? '<span class="old">'.$orders->items_ru[5]['amount'].'</span> <span class="new">'.$orders->items_ru[5]['amount_discount'].'</span>' : $orders->items_ru[5]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[5]['amount_discount'] && $this->site->balance < $orders->items_ru[5]['amount_discount']) || (!$orders->items_ru[5]['amount_discount'] && $this->site->balance < $orders->items_ru[5]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>5], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Загрузка реплеев</strong> Открывайте результаты боя на сайте клана так же как в игровом клиенте! Реплеи можно добавлять в новости, комментарии, в сообщения на форуме и на статичные страницы. <a href="http://wot.pw/rshop/replay" target="_blank">Подробнее</a>.</p>
					<? if (in_array(10, $this->site->premium_widgets)){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[6]['amount_discount'] ? '<span class="old">'.$orders->items_ru[6]['amount'].'</span> <span class="new">'.$orders->items_ru[6]['amount_discount'].'</span>' : $orders->items_ru[6]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[6]['amount_discount'] && $this->site->balance < $orders->items_ru[6]['amount_discount']) || (!$orders->items_ru[6]['amount_discount'] && $this->site->balance < $orders->items_ru[6]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>6], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Чат на вашем сайте</strong> Общайтесь с друзьями в быстром и удобном чате. Чат разделен на внутрисайтовый и общий. В общем чате можно писать игрокам других сайтов проекта WoT.pw. <a href="http://wot.pw/rshop/chat" target="_blank">Подробнее</a>.</p>
					<? if (in_array(1, $this->site->premium_widgets)){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[7]['amount_discount'] ? '<span class="old">'.$orders->items_ru[7]['amount'].'</span> <span class="new">'.$orders->items_ru[7]['amount_discount'].'</span>' : $orders->items_ru[7]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[7]['amount_discount'] && $this->site->balance < $orders->items_ru[7]['amount_discount']) || (!$orders->items_ru[7]['amount_discount'] && $this->site->balance < $orders->items_ru[7]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>7], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Виджет опросов</strong> Виджет позволяет размещать в произвольном месте сайта блок опроса содержащий до 10 вариантов ответов. Количество опросов неограничено. <a href="http://wot.pw/rshop/poll" target="_blank">Подробнее</a>.</p>
					<? if (in_array(12, $this->site->premium_widgets)){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[8]['amount_discount'] ? '<span class="old">'.$orders->items_ru[8]['amount'].'</span> <span class="new">'.$orders->items_ru[8]['amount_discount'].'</span>' : $orders->items_ru[8]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[8]['amount_discount'] && $this->site->balance < $orders->items_ru[8]['amount_discount']) || (!$orders->items_ru[8]['amount_discount'] && $this->site->balance < $orders->items_ru[8]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>8], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Тактический планшет</strong> Модуль предназначен для проведения предбоевого брифинга с игроками. Планшет позволяет всем игрокам в реальном времени видеть указания командира на карте. Цена указана с учетом скидки 17%.</p>
					<? if ($this->site->premium_tactic){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[9]['amount_discount'] ? '<span class="old">'.$orders->items_ru[9]['amount'].'</span> <span class="new">'.$orders->items_ru[9]['amount_discount'].'</span>' : $orders->items_ru[9]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[9]['amount_discount'] && $this->site->balance < $orders->items_ru[9]['amount_discount']) || (!$orders->items_ru[9]['amount_discount'] && $this->site->balance < $orders->items_ru[9]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>9], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
				<div class="stock">					
					<p class="description"><strong>Продление домена</strong> Вы можете продлить подключенный домен на один год.  <span style="font-weight:bold;color:red;">Обязательно ознакомьтесь с <a href="http://wot.pw/rshop/domain" target="_blank">регламентом подключения и продления домена</a>.</span></p>
					<p class="price">
						<span class="new"><?php echo $orders->items_ru[10]['amount_discount'] ? '<span class="old">'.$orders->items_ru[10]['amount'].'</span> <span class="new">'.$orders->items_ru[10]['amount_discount'].'</span>' : $orders->items_ru[10]['amount'] ?></span> <span class="rub">руб.</span>
						<? if (($orders->items_ru[10]['amount_discount'] && $this->site->balance < $orders->items_ru[10]['amount_discount']) || (!$orders->items_ru[10]['amount_discount'] && $this->site->balance < $orders->items_ru[10]['amount'])){ ?>
							<span class="status-fail">Недостаточно средств</span>
						<? } else { ?>
							<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>10], 'confirm'=>'Активировать услугу?']) ?>
						<? } ?>
					</p>
				</div>
				<div class="stock">					
					<p class="description"><strong>Личные сообщения</strong> Виджет для обмена личными сообщениями между всеми пользователями проекта WoT.pw. Виджет сделан похожим на аналогичный от ВК.</p>
					<? if (in_array(15, $this->site->premium_widgets)){ ?>
						<p class="price status-success">Услуга подключена</p>
					<? } else { ?>
						<p class="price">
							<span class="new"><?php echo $orders->items_ru[11]['amount_discount'] ? '<span class="old">'.$orders->items_ru[11]['amount'].'</span> <span class="new">'.$orders->items_ru[11]['amount_discount'].'</span>' : $orders->items_ru[11]['amount'] ?></span> <span class="rub">руб.</span>
							<? if (($orders->items_ru[11]['amount_discount'] && $this->site->balance < $orders->items_ru[11]['amount_discount']) || (!$orders->items_ru[11]['amount_discount'] && $this->site->balance < $orders->items_ru[11]['amount'])){ ?>
								<span class="status-fail">Недостаточно средств</span>
							<? } else { ?>
								<?php echo CHtml::link('Активировать', '#', ['class'=>'shop-pay-btn', 'submit'=>['activate'], 'csrf'=>true, 'params'=>['id'=>11], 'confirm'=>'Активировать услугу?']) ?>
							<? } ?>
						</p>
					<? } ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>