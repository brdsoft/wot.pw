var Banlist = require('./banlist');
var fs = require('fs');

var MSG_FILE_NAME = __dirname + '/messages.json';
var MSG_SAVE_INTERVAL = 600000;

console.log(MSG_FILE_NAME);

var admins = {
	'Machino': 1,
	'Ganta': 1,
	'Inrudiment': 1,
	'AngrySpike': 1,
	'Iooney': 1
};

function parseCommand(str, successCallback, failCallback) {
	var matches = str.match(/^\/(\S+)((\s+(\S+))*)/);

	if (matches) {
		var result = {params: [], name: matches[1]};
		var paramStr = matches[2];

		var paramReg = /\s+(\S+)/g;
		var p;
		while (p = paramReg.exec(paramStr)) result.params.push(p[1]);

		if (typeof successCallback == 'function') successCallback(result);

		return result;
	}

	if (typeof failCallback == 'function') failCallback();

	return false;
}

module.exports = {
	init: function(io, users) {
		var messages;

		try {
			messages = JSON.parse(fs.readFileSync(MSG_FILE_NAME, 'utf8'));
			console.log('msg loaded');
		} catch (e) {
			messages = {
				all: [],
				site: {}
			};
			console.log('msg reset');
		}

		setInterval(function() {
			fs.writeFile(MSG_FILE_NAME, JSON.stringify(messages));
		}, MSG_SAVE_INTERVAL);

		process.on('SIGINT', function() {
			process.stdout.write('writing chat to file...');
			fs.writeFileSync(MSG_FILE_NAME, JSON.stringify(messages));
			console.log('ok');
			process.exit();
		});

		var ban = new Banlist();

		function onMsg(chat, data) {
			if (chat == 'all' && ban.banned(users[this.id].nickname)) {
				this.emit('msgall', {
					msg: 'Вы забанены',
					nickname: 'Системное сообщение',
					time: Date.now()
				});
				return;
			}

			if (typeof data != 'string' || !data) {
				console.log('err: invalid data chat disconnected');
				this.disconnect();
				return;
			}


			data = escapeHtml(data.substr(0, 700));

			if (users[this.id].chat_write) {
				console.log('msg: ' + users[this.id].nickname + ': ' + data);

				var msg;

				if (users[this.id].nickname in admins) {
					parseCommand(data, function(cmd) {
						if (cmd.name == 'ban' && 0 in cmd.params) {
							var time = (1 in cmd.params) ? (+cmd.params[1]) * 86400000 /* day */ : 604800000 /* week */;

							ban.add(cmd.params[0], time);

							msg = {
								msg: cmd.params[0] + (time ? ' забанен' : ' разбанен'),
								nickname: 'Системное сообщение',
								time: Date.now()
							};
						}
					});
				}

				msg = msg || {
					msg: data,
					nickname: users[this.id].nickname,
					time: Date.now()
				};

				if (chat == 'all') {
					messages.all.push(msg);
					if (messages.all.length > 100) messages.all.shift();

					io.emit('msgall', msg);
				}
				else if (users[this.id].clan_on_site) {
					var site_id = users[this.id].site_id;
					messages.site[site_id].push(msg);
					if (messages.site[site_id].length > 100) messages.site[site_id].shift();

					io.to('site' + site_id).emit('msgsite', msg);
				}
				else {
					console.log('err: attempt to write in site chat without permission: ' + users[this.id].nickname);
					this.disconnect();
				}
			}
			else {
				console.log('err: user can\'t write: ' + users[this.id].nickname);
				this.disconnect();
			}
		}

		io.on('connection', function(socket) {
			if (users[socket.id]) {
				//console.log('chat connected ' + socket.id + ' ' + users[socket.id].nickname);
				var site_id = users[socket.id].site_id;
				messages.site[site_id] = messages.site[site_id] || [];

				socket.on('msgall', function(data) {
					onMsg.call(this, 'all', data);
				});

				socket.on('msgsite', function(data) {
					onMsg.call(this, 'site', data);
				});

				socket.emit('list', {type: 'all', list: messages.all});

				if (users[socket.id].clan_on_site) {
					socket.join('site' + users[socket.id].site_id);
					socket.emit('list', {type: 'site', list: messages.site[site_id]});
				}
			}
			else {
				console.log('err: unauthorized chat disconnected');
				socket.disconnect();
			}
		});
	}
};

var entityMap = {
	'&': '&amp;',
	'<': '&lt;',
	'>': '&gt;',
	'"': '&quot;'
};

function escapeHtml(string) {
	return string.replace(/[&<>"]/g, function (s) {
		return entityMap[s];
	});
}