<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' - '.Yii::t('wot', 'TeamSpeak сервер');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'TeamSpeak сервер') ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php if (!$server) echo CHtml::link('Создать сервер', array('create')) ?>
			<?php echo CHtml::link('Правила', array('rules')) ?>
		</div>

		<div class="filling teamspeak">

			<? if ($server && $server_connect){ ?>
				
				<h3>Информация о сервере</h3>

				<ul>
					<li>Основной адрес сервера: <a href="ts3server://<?php echo $this->site->url ?>"><?php echo $this->site->url ?></a></li>
					<li>Резервный адрес сервера: <a href="ts3server://<?php echo $server->server['ip'] ?>:<?php echo $server->port ?>"><?php echo $server->server['ip'] ?>:<?php echo $server->port ?></a></li>
					<li>Адрес сервера для <?php echo CHtml::link('учета посещаемости', ['/admin/config']) ?>: <?php echo $server->server['ip'] ?>:<?php echo $server->port ?></li>
					<li>Порт сервера для <?php echo CHtml::link('учета посещаемости', ['/admin/config']) ?>: <?php echo $server->server['port'] ?></li>
					<li>Дата создания: <?php echo Yii::app()->dateFormatter->formatDateTime($server->time_created+Yii::app()->params['moscow'], 'long', false) ?></li>
					<li>Текущая цена слота: <?php echo CHtml::link($server->rate.' руб.', ['/admin/teamspeak/rules']) ?></li>
					<li>Статус: <?php echo $server_info ? $server->statusAvailable[$server_info['virtualserver_status']].' '.floor($server_info['virtualserver_uptime'] / 86400).' д. '.(($server_info['virtualserver_uptime'] / 3600) % 24).' ч. '.(($server_info['virtualserver_uptime'] / 60) % 60).' м.' : $server->statusAvailable['offline'].($server->time_down ? ' '.Yii::app()->dateFormatter->formatDateTime($server->time_down+Yii::app()->params['moscow'], 'long', 'short') : '') ?></li>
					<? if ($server_info){ ?>
					<li>Пользователей: <?php echo $server_info['virtualserver_clientsonline']-$server_info['virtualserver_queryclientsonline'] ?> / <?php echo $server_info['virtualserver_maxclients'] ?></li>
					<? } ?>
				</ul>

				<h3>Управление сервером</h3>
				<? if ($server->time_down > 0 && $this->site->balance <= 0){ ?>
					<p><strong style="color:red;">Запуск сервера невозможен. На балансе сайта недостаточно средств.</strong></p>
				<? } ?>
				<? if ($server->time_down > 0 && $server->time_down > time() - 3600*24){ ?>
					<p><strong>Удалить сервер можно будет <?php echo Yii::app()->dateFormatter->formatDateTime($server->time_down+3600*24+Yii::app()->params['moscow'], 'long', 'short') ?>.</strong></p>
				<? } ?>
				<? if ($server->time_down > 0){ ?>
					<p><strong>Выключенный сервер будет автоматически удален <?php echo Yii::app()->dateFormatter->formatDateTime($server->time_down+3600*24*14+Yii::app()->params['moscow'], 'long', 'short') ?>.</strong></p>
				<? } ?>

				<div class="manage">
					<? if ($this->site->balance > 0 && !$server_info){ ?><?php echo CHtml::link('Запустить сервер', '#', ['csrf'=>true, 'submit'=>array('start'),]) ?><? } ?>
					<? if ($server_info){ ?><?php echo CHtml::link('Остановить сервер', '#', ['csrf'=>true, 'submit'=>array('stop'), 'confirm'=>Yii::t('wot', 'Остановить сервер?'),]) ?><? } ?>
					<? if (!$server_info && $server->time_down > 0 && $server->time_down < time() - 3600*24){ ?><?php echo CHtml::link('Удалить сервер', '#', ['csrf'=>true, 'submit'=>array('delete'), 'confirm'=>Yii::t('wot', 'Внимание! Удаление сервера приведет к удалению всех каналов и привилегий. Это необратимая операция. После удаления сервера вы сможете создать новый пустой сервер. Удалить сервер?'),]) ?><? } ?>
				</div>
				
				<? if ($server_info){ ?>
					<h3>Список активных ключей привилегий (токенов)</h3>
					<? if ($token_list){ ?>
						<table style="margin-bottom: 20px;">
							<tr>
								<th>Ключ привилегий</th>
								<th>Тип</th>
								<th>Группа</th>
								<th>Канал</th>
								<th>Создан</th>
								<th>Описание</th>
								<th></th>
							</tr>
							<? foreach ($token_list as $value){ ?>
							<tr>
								<td><?php echo $value['token'] ?></td>
								<td><?php echo $server->tokenTypes[$value['token_type']] ?></td>
								<td><?php echo $value['token_id2'] && isset($channel_group_list[$value['token_id1']]) ? CHtml::encode(stripslashes($channel_group_list[$value['token_id1']]['name'])) : ($value['token_id1'] && isset($server_group_list[$value['token_id1']]) ? CHtml::encode(stripslashes($server_group_list[$value['token_id1']]['name'])) : '') ?></td>
								<td><?php echo $value['token_id2'] && isset($channel_list[$value['token_id2']]) ? CHtml::encode(stripslashes($channel_list[$value['token_id2']]['channel_name'])) : '' ?></td>
								<td><?php echo Yii::app()->dateFormatter->formatDateTime($value['token_created']+Yii::app()->params['moscow'], 'long', 'short') ?></td>
								<td><?php echo CHtml::encode(stripslashes($value['token_description'])) ?></td>
								<td><?php echo CHtml::link('x', '#', ['csrf'=>true, 'submit'=>array('deleteToken', 'token'=>$value['token']),]) ?></td>
							</tr>
							<? } ?>
						</table>
					<? } else { ?>
						<p style="margin-bottom: 20px;">Ключей привилегий нет</p>
					<? } ?>

					<h3>Создать ключ привилегий (токен)</h3>
					<form method="post" action="/admin/teamspeak/createToken">
						<input type="hidden" name="YII_CSRF_TOKEN" value="<?= Yii::app()->getRequest()->csrfToken ?>">
						<select name="id">
							<?php foreach ($server_group_list as $value) { ?>
								<?php if ($value['type'] == 1) { ?>
									<option value="<?= $value['sgid'] ?>">
										<?= $value['name'] ?>
									</option>
								<?php } ?>
							<?php } ?>
						</select>
						<button type="submit">Создать</button>
					</form>
					<div style="margin-bottom: 20px;"></div>

				<? } ?>
			<? } else { ?>
				<? if (!$server){ ?>
					<p>TeamSpeak сервер для вашего сайта не создан.</p>
				<? } elseif (!$server_connect){ ?>
					<p>TeamSpeak сервер в данный момент недоступен.</p>
				<? } ?>
			<? } ?>
			<? if ($stat){ ?>
				<h3>Статистика использования слотов</h3>
				<table style="margin-bottom: 20px;">
					<tr>
						<th>Дата</th>
						<th>Цена слота, руб.</th>
						<th>Максимальный онлайн</th>
						<th>Списано с баланса, руб.</th>
					</tr>
					<? foreach ($stat as $value){ ?>
					<tr>
						<td><?php echo Yii::app()->dateFormatter->formatDateTime($value->time+Yii::app()->params["moscow"], "long", false) ?></td>
						<td><?php echo $value->rate ?></td>
						<td><?php echo $value->online ?></td>
						<td><?php echo $value->amount ?></td>
					</tr>
					<? } ?>
				</table>
			<? } ?>
		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>
