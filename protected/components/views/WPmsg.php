<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/pmsg.css?v=2'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/pmsg.js?v=2'); ?>
<div class="pmsg">
	<audio id="pmsg_incoming">
		<source src="/audio/incoming.ogg" type="audio/ogg">
		<source src="/audio/incoming.mp3" type="audio/mpeg">
	</audio>
	<script type="text/template" id="pmsg_msg">
		<div class="pmsg-message<% if (isMine) { %> pmsg-mine<% } %><% if (typeof isNew != 'undefined' && isNew) { %> pmsg-new<% } %>">
			<div class="pmsg-talker">
				<img src="/upload/avatar/thumb/<%= avatar %>" alt="<%= nick %>" title="<%= nick %>">
			</div><div class="pmsg-msg-content">
				<%= escapeHtml(content).replace(/(https?|ftp):\/\/([^\s.,;]|[.,;]\S)+/g, '<a href="$&" target="_blank">$&</a>') %>
			</div><div class="pmsg-msg-time" title="<%= time.toLocaleDateString() %>">
				<%= time.getClock() %>
			</div><div class="pmsg-clear"></div>
		</div>
	</script>
	<script type="text/template" id="pmsg_thumb">
		<div class="pmsg-thumb" data-hash="<%= hash %>">
			<img src="/upload/avatar/thumb/<%= avatar %>" alt="<%= title %>" title="<%= title %>">
			<div class="pmsg-close"></div>
			<div class="pmsg-online"></div>
			<div class="pmsg-incoming"></div>
		</div>
	</script>
	<script type="text/template" id="pmsg_date">
		<div class="pmsg-date">
			<%= time.toLocaleDateString() %>
		</div>
	</script>
	<div class="pmsg-main">
		<div class="pmsg-selector pmsg-hidden">
			<ul class="pmsg-list"></ul>
			<div class="pmsg-nick-input">
				<input name="pmsg_nick" type="text" placeholder="Начните вводить ник">
			</div>
		</div>
		<div class="pmsg-talk pmsg-hidden">
			<div class="pmsg-caption"></div>
			<div class="pmsg-content prevent-parent-scroll">
				<div class="pmsg-messages"></div>
			</div>
			<div class="pmsg-memo">
				<textarea name="pmsg_text" rows="3" maxlength="5000"></textarea>
			</div>
		</div>
	</div>
	<div class="pmsg-side">
		<div class="pmsg-write">
		</div>
	</div>
</div>
