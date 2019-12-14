'use strict';

var config = require('./config');
var db = require('mysql').createPool(config.db);

var accountSockets = {
    add: function(account_id, socket_id) {
        this[account_id] = this[account_id] || {};
        this[account_id][socket_id] = 1;
        console.log(this);
    },

    remove: function(account_id, socket_id) {
        this[account_id] = this[account_id] || {};
        delete this[account_id][socket_id];
        console.log(this);
    },

    get: function(account_id) {
        this[account_id] = this[account_id] || {};
        return Object.keys(this[account_id]);
    }
};

function getSubscribers(talkHash, callback) {
    db.query('select * from `pmsg_cc` where talk_hash = ?', [talkHash], function(err, rows, fields) {
        if (err) return;

        var cc = rows.map(function(row) {
            return row.account_id;
        });

        callback(cc);
    });
}

function saveMessage(msg) {
    db.query('insert into `pmsg` set ?', msg);
}

function addThumb(thumb) {
    db.query('insert ignore into `pmsg_thumbs` set ?', thumb);
}

//function updateLastVisit(talk_hash, account_ids) {
//    db.query('update `pmsg_cc` set last_visit = ? where talk_hash = ? and account_id in (?)', [
//        new Date(),
//        talk_hash,
//        account_ids
//    ]);
//}

function onMessage(data) {
    var sock = this;

    if (typeof data != 'object' || typeof data.talk_hash != 'string' || typeof data.content != 'string') {
        console.log('*** invalid data from ' + sock.userData.nickname);
        return;
    }

    data.content = data.content.trim().substr(0, 5000);
    if (!data.content) return;

    console.log('pmsg: ' + data.talk_hash + ' ' + data.content);

    var msgTime = new Date();

    getSubscribers(data.talk_hash, function(cc) {
        if (cc.indexOf(sock.userData.account_id) < 0) return;

        saveMessage({
            content: data.content,
            talk_hash: data.talk_hash,
            talker_id: sock.userData.account_id,
            msg_time: msgTime
        });

        var online = {};

        cc.forEach(function(account_id) {
            if (account_id != sock.userData.account_id) {
                addThumb({
                    account_id: account_id,
                    talk_hash: data.talk_hash,
                    avatar: sock.userData.avatar,
                    title: sock.userData.nickname
                });
            }

            var socks = accountSockets.get(account_id);

            if (socks.length) {
                online[account_id] = 1;
                socks.forEach(function(socket_id) {
                    var emitter = socket_id == sock.id ? sock : sock.to(socket_id);

                    emitter.emit('msg', {
                        talk_hash: data.talk_hash,
                        talker: sock.userData.nickname,
                        avatar: sock.userData.avatar,
                        content: data.content,
                        time: msgTime.getTime()
                    });
                });
            }
        });

        //updateLastVisit(data.talk_hash, Object.keys(online));
    });
}

module.exports = {
    init: function(io, users) {
        io.on('connection', function(socket) {
            var user = users[socket.id];

            if (user) {
                accountSockets.add(user.account_id, socket.id);

                socket.userData = user;
                socket.on('msg', onMessage);
                socket.on('disconnect', function() {
                    accountSockets.remove(user.account_id, socket.id);
                })
            }
            else {
                socket.disconnect();
            }
        });
    }
};
