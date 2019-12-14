var nodeAuthorization;

(function() {
	function Online(init) {
		var obj = init || {
			allies: {},
			guests: 0,
			other: {}
		};

		obj.otherAndGuests = function() {
			return Object.keys(this.other).length + this.guests;
		};

		obj.alliesCnt = function() {
			return Object.keys(this.allies).length;
		};

		return obj;
	}

	var online;

	var authDefer = $.Deferred();
	var onlineDefer = $.Deferred();
	var onlinePromise = onlineDefer.promise();

	var socket = io.connect(nodeURL);

	nodeAuthorization = authDefer.promise();

	socket.on('connect', function() {
		socket.emit('auth', nodeData);
	});

	socket.on('disconnect', function() {
		authDefer.reject();
	});

	//_echo = function(data) {
	//	socket.emit('echo', data);
	//};
	//
	//socket.on('echo', function(data) {
	//	console.log('echo', globResponse = data);
	//});

	socket.on('authorized', function() {
		authDefer.resolve();
		this.on('online', function(data) {
			if (data.group == 'guests') online.guests++;
			else online[data.group][data.nick] = {id: data.id};

			updateOnline();
		});

		this.on('offline', function(data) {
			if (data.group == 'guests') online.guests--;
			else delete online[data.group][data.nick];

			updateOnline();
		});

		this.on('onlinelist', function(data) {
			online = Online(data);
			onlineDefer.resolve();
		});
	});

	function updateOnline() {
		$('.online-guests').html(online.otherAndGuests());
		$('.online-allies').html(online.alliesCnt());
		var list = [];

		for (var i in online.allies) list.push('<a href="/profile/account/' + online.allies[i].id + '">' + i + '</a>');

		$('.online-allies-list').html(list.join(''));
	}

	$(function() {
		$.when(onlinePromise).done(updateOnline);
	});
})();
