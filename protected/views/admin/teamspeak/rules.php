<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'Правила использования TeamSpeak сервера');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo CHtml::link(Yii::t('wot', 'TeamSpeak сервер'), array('index')) ?> <span class="rarr"></span> <?php echo Yii::t('wot', 'Правила использования TeamSpeak сервера')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('index')) ?>
		</div>

		<div class="filling teamspeak">
			<h4>Наши преимущества</h4>
			<p>- Интеграция с сайтом. Адрес вашего сервера совпадает с адресом сайта.</p>
			<p>- Вы платите только за фактически использованные слоты.</p>
			<p>- Мощный и надежный сервер. Мы не используем дешевые виртуальные хостинги и т.п.</p>
			<p>- Готовые шаблоны для вашего клана или альянса с предустановленными правами.</p>

			<h4>Стоимоть услуги и порядок списания средств</h4>
			<p>
				Сервер TeamSpeak создается бесплатно, но для его создания баланс вашего сайта должен быть не менее <?= TsServers::model()->minBalance ?> рублей. Стоимость одного слота составляет <?= TsServers::model()->defaultRate ?> руб. в сутки.
			</p>
			<p>
				При расчете использованных слотов учитывается максимальный онлайн за истекшие сутки. Контроль онлайна производится один раз в десять минут. Списание средств с баланса сайта происходит в 3:00-3:10 по времени UTC. Если после списания средств баланс сайта стал нулевым или отрицательным, сервер автоматически остановится. Включить сервер можно будет только после пополнения баланса сайта на любую сумму.
			</p>
			<p>Пример расчета: Сегодня на вашем сервере в 12:00 было 20 человек, в 15:00 - 50 человек, в 20:00 - 17 человек. Это означает что завтра в 3 ночи с баланса вашего сайта будет списано 10 рублей за 50 человек (50 - максимальное число одновременно занятых слотов).</p>
			<p>
				Внимание! Если суточный онлайн меньше 10 человек, он автоматически округляется до 10. То есть, если вы создали сервер и не пользуетесь им, с баланса вашего сайта будет списываться 2 рубля в день (примерно 60 рублей в месяц). Если ваш сервер выключен более 2 недель он будет автоматически удален.
			</p>
			<h4>Управление сервером</h4>
			<p>Вы можете запускать и останавливать сервер в любое время. Удалить сервер можно через 24 часа после его остановки.</p>
			<h4>Адрес сервера без порта</h4>
			<p>Созданный сервер уже имеет доступ по имени сайта, и вашим бойцам будет легко запомнить его адрес. Например, если адрес вашего сайта myclansite.wclan.ru, то и адрес TeamSpeak сервера будет myclansite.wclan.ru.</p>
			<h4>Готовые шаблоны</h4>
			<p>Для вашего удобства мы создали 2 шаблона с готовыми настроенными каналами и группами. Скриншоты каналов:</p>
			<table style="margin-top: 10px;">
				<tr>
					<th>Шаблон для одного клана</th>
					<th>Шаблон для альянса</th>
				</tr>
				<tr>
					<td><img src="/images/admin/ts_clan.jpg" alt=""/></td>
					<td><img src="/images/admin/ts_alliance.jpg" alt=""/></td>
				</tr>
			</table>
		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>