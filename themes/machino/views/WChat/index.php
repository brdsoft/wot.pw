<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/chat.js?v=2'); ?>
<div class="block chat">
	<div class="head head1">
		<?=CHtml::encode($this->title)?>
	</div>
	<div class="body">
		<div class="tabs">
			<?php if ($clan_on_site = Yii::app()->user->account && isset($this->controller->clans[Yii::app()->user->account->clan_id])) { ?>
			<div class="site active">Сайт</div>
			<?php } ?>
			<div class="all <?php if (!$clan_on_site) { ?>active<?php } ?>">Общий</div>
		</div>
		<?php if ($clan_on_site) { ?>
		<div class="window site active" style="height: <?=$this->height?>px">

		</div>
		<?php } ?>
		<div class="window all <?php if (!$clan_on_site) { ?>active<?php } ?>" style="height: <?=$this->height?>px">

		</div>
		<div class="controls">
			<textarea class="message"></textarea>
			<div class="buttons">
				<button class="chat-send">Отправить</button>
			</div>
		</div>
	</div>
</div>