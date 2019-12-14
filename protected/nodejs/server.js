var DEFAULT_PORT = 55709;

var port = process.argv.length > 2 ? process.argv[2] : DEFAULT_PORT;

var SALT = 'D=%b(1rb2RPKGDr-=)';

var md5 = require('/usr/lib/node_modules/MD5');
var io = require('/usr/lib/node_modules/socket.io').listen(port);

var users = {};
var online = {
	//timers: {}
};

var onlineCounter = 0;
var connectedCounter = 0;
var disconnectedCounter = 0;

var startTime = Date.now();

require('./chat').init(io.of('/chat'), users);
require('./tactic').init(io.of('/tactic'), users);
require('./pmsg').init(io.of('/pmsg'), users);

//var Profiler = require('./profiler');
//var profiler = new Profiler();

console.log('Server started. PID = ' + process.pid);
console.log(process.versions);

io.sockets.on('connection', function (socket) {
	var remote_ip = socket.handshake.address;
	onlineCounter++;
	connectedCounter++;
	//console.log('++' + socket.id);

	//socket.on('echo', function(data) {
	//	console.log('echo');
	//	console.log(data);
	//	this.emit('echo', data);
	//});

	socket.on('auth', function (data) {
		if (!data || !(data instanceof Array) || data.length != 2 || typeof data[0] != 'object' || typeof data[1] != 'string') {
			console.log('err: bad data ' + remote_ip);
			this.disconnect();
			return;
		}

		var token = data[1];
		data = data[0];

		var testStr = '';

		for (var i in data) testStr += data[i];
		testStr += SALT;
		if (md5(testStr) == token) {
			this.join('site' + data.site_id);

			online[data.site_id] = online[data.site_id] || {guests: {}, allies: {}, other: {}};

			if (!data.nickname) {
				data.nickname = 'Гость';
				data.online_id = data.client_id;
			}
			else data.online_id = data.nickname;

			//clearTimeout(online.timers[data.online_id + data.site_id]);

			var group = data.clan_on_site ? 'allies' : (data.nickname == 'Гость' ? 'guests' : 'other');

			if (typeof online[data.site_id][group][data.online_id] == 'undefined') {
				online[data.site_id][group][data.online_id] = {cnt: 1, id: data.account_id};
				//log(data.nickname + ' online on ' + data.site_id);
				this.broadcast.to('site' + data.site_id).emit('online', {nick: data.nickname, group: group, id: data.account_id});
			}
			else online[data.site_id][group][data.online_id].cnt++;

			users[this.id] = data;

			this.emit('authorized');
			this.emit('onlinelist', {
				guests: Object.keys(online[data.site_id].guests).length,
				allies: online[data.site_id].allies,
				other: online[data.site_id].other
			});

			this.on('disconnect', function() {

				if (typeof users[this.id] == 'undefined') { // Временный фикс
					console.log('==' + this.id);
					return;
				}

				var user = users[this.id];

				if (typeof online[user.site_id][group][user.online_id] != 'undefined' /* tmp fix */ && --online[user.site_id][group][user.online_id].cnt == 0)
					//online.timers[user.online_id + user.site_id] = setTimeout(function() {
						delete online[user.site_id][group][user.online_id];
						//log(user.nickname + ' offline on ' + user.site_id);
						io.to('site' + user.site_id).emit('offline', {nick: user.nickname, group: group});
					//}, 15000);

				delete users[this.id];
				//console.log('--' + this.id);
			});
		}
		else {
			console.log('auth failed');
			this.disconnect();
		}
	});

	socket.on('disconnect', function() {
		onlineCounter--;
		disconnectedCounter++;
		//log('disconnected ' + remote_ip + ' ' + this.id + ' online: ' + onlineCounter);
	});
});

setInterval(function() {
	console.log(
		'up: ' + ((Date.now() - startTime) / 3600000).toFixed(2) + 'h' +
		' +' + connectedCounter +
		' -' + disconnectedCounter +
		' =' + onlineCounter
	);
	disconnectedCounter = 0;
	connectedCounter = 0;
}, 60000);

//setInterval(function() {
//	console.log(profiler.times);
//}, 60000);