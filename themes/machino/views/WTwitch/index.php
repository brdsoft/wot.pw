<? if ($this->chan) { ?>
	<div class="block">
		<div class="head head2">
			<?=CHtml::encode($this->title)?>
		</div>
		<div class="body body2">
			<object type="application/x-shockwave-flash" height="378" width="100%" id="live_embed_player_flash"
					  data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=<?=CHtml::encode($this->chan)?>"
					  bgcolor="#000000"><param name="allowFullScreen" value="true" />
				<param name="allowScriptAccess" value="always" />
				<param name="allowNetworking" value="all" />
				<param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" />
				<param name="flashvars" value="hostname=www.twitch.tv&channel=<?=CHtml::encode($this->chan)?>&auto_play=false&start_volume=25" /></object>
		</div>
	</div>
<? } ?>
