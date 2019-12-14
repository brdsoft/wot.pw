var SERIALIZATION_SALT = '************';

var maps = require('./maps');
var zlib = require('zlib');
var crypto = require('crypto');

function md5(str) {
    return crypto.createHash('md5').update(str).digest('hex');
}

function Layer(map, zoom) {
    this.cam = { x: 0, y: 0, z: zoom };
    this.m = map;
    this.objects = {};
}

Layer.prototype.applyChange = function(change) {
    switch (change.type) {
        case 'cam': this.cam = change.state; break;
        case 'obj':
        case 'new':
            this.objects[change.id] = change.state;
            break;
        case 'del': delete this.objects[change.id]; break;
    }
};

function createLayers(mapId) {
    var result = [];
    for (var i = 0; i < maps[mapId].layers.length; i++) result.push(new Layer(maps[mapId].layers[i], i ? -7 : -13));
    return result;
}

function unserializeRoom(data, callback) {
    data = data.split("\n").join("");

    var sign = data.substr(0, 32);
    data = data.substr(32);

    var realSign = md5(data + SERIALIZATION_SALT);

    if (sign !== realSign) {
        callback(false);
        return;
    }

    zlib.unzip(new Buffer(data, 'base64'), function(err, buffer) {
        if (err) {
            callback(false);
            return;
        }

        var json = buffer.toString();

        callback(JSON.parse(json));
    });
}

module.exports = {
    init: function(io, users) {
        var rooms = {};
        var tusers = {};

        io.on('connection', function(socket) {
            //console.log(io);

            if (!users[socket.id]) {
                console.log('err: unauthorized tactic disconnected');
                socket.disconnect();
                return;
            }

            if (
                !users[socket.id].account_id ||
                !users[socket.id].clan_on_site && ["Inrudiment", "Machino"].indexOf(users[socket.id].nickname) < 0
            ) {
                socket.emit('forbidden');
                return;
            }

            var nick = users[socket.id].nickname;
            var site = users[socket.id].site_id;
            var clan = users[socket.id].clan_id;

            if (tusers[nick]) {
                socket.emit('exists');
                return;
            }

            var tuser = {
                sock: socket
            };

            tusers[nick] = tuser;

            //console.log('tactic: connected: ' + socket.id + ' ' + nick);

            socket.on('create', function(options) {
                var sock = this;
                var room = options;

                function createRoom(data) {
                    if (data === false) {
                        sock.emit('deserialization-error');
                        return;
                    }

                    delete room.serialized;

                    if (data) {
                        room.map = data.m;
                    }

                    room.creator = nick;
                    room.clan = clan;
                    room.layers = createLayers(room.map);
                    room.currentLayer = 0;
                    room.users = {};
                    room.users[nick] = tuser;
                    room.name = site + nick;

                    if (data) {
                        for (var i = 0; i < data.l.length; i++) {
                            data.l[i].forEach(function(state) {
                                room.layers[i].applyChange({
                                    type: "new",
                                    id: Math.round(Math.random() * 1000000),
                                    state: state
                                });
                            });
                        }
                    }

                    rooms[site] = rooms[site] || {};
                    rooms[site][nick] = room;

                    sock.join(room.name);

                    tuser.room = room;
                    tuser.writer = true;

                    sock.emit('created', {
                        creator: nick,
                        map: room.map,
                        layers: room.layers,
                        current: room.currentLayer
                    });

                    sock.emit('joined', nick);
                }

                if (options.serialized) {
                    unserializeRoom(options.serialized, createRoom);
                } else {
                    createRoom();
                }
            });

            socket.on('update', function(options) {
                var room;
                if ((room = tuser.room) && nick == room.creator) {
                    var users = room.users;
                    room = options;
                    room.creator = nick;
                    room.clan = clan;
                    room.layers = createLayers(room.map);
                    room.currentLayer = 0;
                    room.users = users;
                    room.users[nick] = tuser;
                    room.name = site + nick;

                    rooms[site] = rooms[site] || {};
                    rooms[site][nick] = room;

                    io.to(room.name).emit('created', {
                        creator: nick,
                        map: room.map,
                        layers: room.layers,
                        current: room.currentLayer
                    });

                    for (var nickname in users) {
                        users[nickname].room = room;
                        io.to(room.name).emit('joined', nickname);
                    }
                }
            });

            socket.on('serialize', function() {
                if (tuser.room && tuser.room.layers) {
                    var layers = [];

                    tuser.room.layers.forEach(function(layer) {
                        var objects = [];

                        for (var key in layer.objects) {
                            if (layer.objects[key].T != 'cu') {
                                objects.push(layer.objects[key]);
                            }
                        }

                        layers.push(objects);
                    });

                    var data = {
                        m: tuser.room.map,
                        l: layers
                    };

                    var serialized = JSON.stringify(data);

                    var sock = this;

                    zlib.deflate(serialized, function(err, buffer) {
                        if (!err) {
                            var encoded = buffer.toString('base64');

                            var sign = md5(encoded + SERIALIZATION_SALT);
                            var data = sign + encoded;

                            sock.emit('serialized', data);
                        }
                    });
                }
            });

            socket.on('getrooms', function() {
                var roomlist = [];

                if (site in rooms) for (var i in rooms[site]) {
                    if (rooms[site][i].access == 'ally' || rooms[site][i].clan == clan) {
                        roomlist.push([rooms[site][i].map, i, Object.keys(rooms[site][i].users).length]);
                    }
                }

                this.emit('roomlist', roomlist);
            });

            socket.on('change', function(data) {
                if (tuser.room && tuser.writer) {
                    tuser.room.layers[data.i] && tuser.room.layers[data.i].applyChange(data.e);
                    this.broadcast.to(tuser.room.name).emit('change', data);
                }
            });

            socket.on('switch', function(index) {
                if (tuser.room && tuser.writer && index in tuser.room.layers) {
                    tuser.room.currentLayer = index;
                    this.broadcast.to(tuser.room.name).emit('switch', index);
                }
            });

            socket.on('allow', function(nick_) {
                if (tuser.room && tuser.room.creator == nick && tuser.room.users[nick_]) {
                    tuser.room.users[nick_].writer = true;
                    tuser.room.users[nick_].sock.emit('allowed');
                }
            });

            socket.on('deny', function(nick_) {
                if (nick_ != nick && tuser.room && tuser.room.creator == nick && tuser.room.users[nick_]) {
                    tuser.room.users[nick_].writer = false;
                    tuser.room.users[nick_].sock.emit('denied');
                }
            });

            socket.on('join', function(creator) {
                var room;
                if (rooms[site] && (room = rooms[site][creator])) {
                    room.users[nick] = tuser;
                    tuser.room = room;
                    tuser.writer = false;
                    this.join(room.name);
                    this.broadcast.to(room.name).emit('joined', nick);
                    this.emit('created', {
                        creator: creator,
                        map: room.map,
                        layers: room.layers,
                        current: room.currentLayer
                    });

                    for (var nickname in room.users) {
                        this.emit('joined', nickname);
                    }
                }
            });

            socket.on('exit', function() {
                var room;
                if (room = tuser.room) {
                    if (room.creator == nick) {
                        for (var nickname in room.users) {
                            var s = room.users[nickname].sock;
                            s.emit('closed', nick);
                            s.leave(room.name);
                            room.users[nickname].room = null;
                        }

                        delete rooms[site][nick];
                    }
                    else {
                        this.leave(room.name);
                        this.broadcast.to(room.name).emit('left', nick);
                        this.emit('closed', nick);
                        delete room.users[nick];
                        tuser.writer = false;

                        if (Object.keys(tuser.room.users).length == 0) {
                            delete rooms[site][tuser.room.creator];
                            //console.log('room deleted');
                        }

                        tuser.room = null;
                    }
                }
            });

            socket.on('kick', function(nickname) {
                var room;
                if ((room = tuser.room) && room.creator == nick && nickname != nick && nickname in room.users) {
                    var s = room.users[nickname].sock;
                    delete room.users[nickname];
                    s.leave(room.name);
                    s.broadcast.to(room.name).emit('left', nickname);
                    s.emit('kicked');
                }
            });

            socket.on('givemyrooms', function() {                   // for debug
                this.emit('takeyourrooms', this.adapter.rooms);
            });

            socket.on('disconnect', function() {
                //console.log(nick + ' disconnected');

                if (tuser.room) {
                    //console.log(nick + ' has room. exiting...');

                    delete tuser.room.users[nick];
                    this.broadcast.to(tuser.room.name).emit('left', nick);

                    if (tuser.room.creator == nick) {
                        this.broadcast.to(tuser.room.name).emit('denied');
                        for (var i in tuser.room.users) {
                            this.broadcast.to(tuser.room.name).emit('change', {
                                i: 0,
                                e: {
                                    type: 'del',
                                    id: 'cur-' + i
                                }
                            });
                            tuser.room.users[i].writer = false;
                        }
                        //console.log('writers reset');
                    }

                    //console.log('room population: ' + Object.keys(tuser.room.users).length);

                    if (Object.keys(tuser.room.users).length == 0) {
                        delete rooms[site][tuser.room.creator];
                        //console.log('room deleted');
                    }

                    tuser.room = null; // на всякий
                    tuser = null;      // случай
                }

                delete tusers[nick];
            });

            socket.emit('maps', maps);

            if (rooms[site] && rooms[site][nick]) {
                socket.emit('created', {
                    creator: nick,
                    map: rooms[site][nick].map,
                    layers: rooms[site][nick].layers,
                    current: rooms[site][nick].currentLayer
                });

                socket.join(rooms[site][nick].name);
                socket.emit('joined', nick);
                tuser.room = rooms[site][nick];
                tuser.writer = true;

                for (var nickname in rooms[site][nick].users) {
                    socket.emit('joined', nickname);
                    rooms[site][nick].users[nickname].sock.emit('joined', nick);
                }

                rooms[site][nick].users[nick] = tuser;
            }
        });
    }
};
