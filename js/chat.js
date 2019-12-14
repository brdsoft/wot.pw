$(function() {
	var lastTime = {all: '', site: ''};

	function addMessage(data, type) {
		if (type == 'both') {
			type = 'site';
			addMessage(data, 'all');
		}

		var time = new Date(data.time);

		time = ('0' + time.getHours()).substr(-2) + ':' + ('0' + time.getMinutes()).substr(-2);

		if (time == lastTime[type]) time = '';
		else lastTime[type] = time;

		var msg = $(
			'<div class="chat-message'+(['Machino', 'Iooney', 'Inrudiment', 'Ganta', 'AngrySpike'].indexOf(data.nickname) >= 0 ? ' admin' : '')+(data.nickname == 'GansGaintsev' ? ' moderator' : '')+'">' +
			'<div class="time">' + time + '</div>' +
			'<div class="nick">' + data.nickname + '</div>' +
			'<div class="msg">' + data.msg.replace(/(https?|ftp):\/\/([^\s.,;]|[.,;]\S)+/g, '<a href="$&" target="_blank">$&</a>') + '</div></div>'
		).appendTo(wnd[type]);

		wnd[type].each(function() {
			$(this).scrollTop(this.scrollHeight);
		});

		var msgs = $('.chat-message', wnd[type]);
		if (msgs.length > 100) msgs.first().remove();

		if (nodeData[0].nickname && data.msg.match(nodeData[0].nickname)) msg.addClass('to-me');
	}

	if (!nodeData[0].account_id) {
		$('.chat .controls').html('<span>Вы не можете отправлять сообщения, авторизуйтесь.</span>');
	}

	var sendbtn = $('.chat-send');
	var wnd = {
		'all': $('.chat .window.all'),
		'site': $('.chat .window.site')
	};

	$('.chat').on('click', '.chat-message .nick', function() {
		var input = $(this).parents('.chat').find('.message');
		input.val($(this).html() + ', ' + input.val()).focus();
	});

	$('.chat .tabs div').click(function() {
		var chat = $(this).parents('.chat');
		if (!$(this).hasClass('active')) {
			$('.tabs > div', chat).toggleClass('active');
			$('.window', chat).toggleClass('active');
			var w = $('.window.active', chat);
			w.scrollTop(w[0].scrollHeight);
		}
	});

	nodeAuthorization.done(function() {
		var chat = io.connect(nodeURL + '/chat', {
			reconnect: false
		});


		sendbtn.click(function() {
			var chatwgt = $(this).parents('.chat');
			var input = chatwgt.find('.message');
			var msg = input.val().trim();
			if (msg) {
				var e = $('.window.site', chatwgt).hasClass('active') ? 'msgsite' : 'msgall';
				chat.emit(e, msg);
				input.val('');
			}
		});

		$('.chat .message').keypress(function(e) {
			if (e.which == 13) {
				e.preventDefault();
				$(this).parents('.chat').find('.chat-send').click();
			}
		});

		chat.on('msgall', function(data) {
			addMessage(data, 'all');
		});

		chat.on('msgsite', function(data) {
			addMessage(data, 'site');
		});

		chat.on('list', function(data) {
			for (var i = 0; i < data.list.length; i++) {
				addMessage(data.list[i], data.type);
			}
		});

		chat.on('disconnect', function() {
			addMessage({
				nickname: 'Системное сообщение',
				time: Date.now(),
				msg: 'Соединение разорвано, обновите страницу',
				type: 'system'
			}, 'both');
		});
	});
});
