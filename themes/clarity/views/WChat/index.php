<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/chat.js?v=2'); ?>
<div class="modul chat">
	<div class="title">
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>
	<div class="cell">
		<div class="tabs">
			<?php if ($clan_on_site = Yii::app()->user->account && isset($this->controller->clans[Yii::app()->user->account->clan_id])) { ?>
			<div class="site active"><?php echo Yii::t('wot', 'Site')?></div>
			<?php } ?>
			<div class="all <?php if (!$clan_on_site) { ?>active<?php } ?>"><?php echo Yii::t('wot', 'Public')?></div>
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
				<button class="chat-send"><?php echo Yii::t('wot', 'Send')?></button>
			</div>
		</div>
	</div>
</div>