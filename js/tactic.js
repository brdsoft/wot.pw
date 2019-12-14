// Eventizer -------------------------------------------------------------------

function eventize(o) {
    var e = {};

    o.on = function(n, f) {
        (e[n] = e[n] || []).push(f);
        return this;
    };

    o.emit = function(n, v) {
        (e[n] || []).forEach(function(f) {
            f.call(this, v);
        }, this);
        return this;
    };

    o.off = function(n, f) {
        if (e[n]) for (var i = e[n].length; i--;) if (e[n][i] == f) e[n].splice(i, 1);
    };
}

// SVG helpers -----------------------------------------------------------------

SVGElement.prototype.setLink = function(url) { this.setAttributeNS('http://www.w3.org/1999/xlink', 'href', url) };
function svgNode(name) { return document.createElementNS('http://www.w3.org/2000/svg', name) }

// Camera ----------------------------------------------------------------------

function Camera(state, extent) {
    eventize(this);
    this.element = svgNode('g');
    this.extent = extent;
    this.set(state || {x: 0, y: 0, z: 0});
}

Camera.FACTOR = 1.1;
Camera.VIEWPORT_WIDTH = 580;
Camera.VIEWPORT_HEIGHT = 580;

Camera.prototype.minScale = function() {
    return Math.max(Camera.VIEWPORT_WIDTH / this.extent.x, Camera.VIEWPORT_HEIGHT / this.extent.y);
};

Camera.prototype.scale = function() {
    var k = Math.pow(Camera.FACTOR, this.state.z);
    var min = this.minScale();
    if (k < min) return min;
    return k;
};

Camera.prototype.update = function() {
    this.element.setAttribute('transform', 'translate(' + this.state.x + ',' + this.state.y + ') scale(' + this.scale() + ')');
    this.emit('change', this.state);
};

Camera.prototype.fixPos = function() {
    var limitX = Camera.VIEWPORT_WIDTH - this.extent.x * this.scale();
    var limitY = Camera.VIEWPORT_HEIGHT - this.extent.y * this.scale();
    var s = this.state;
    if (s.x > 0) s.x = 0;
    if (s.y > 0) s.y = 0;
    if (s.x < limitX) s.x = limitX;
    if (s.y < limitY) s.y = limitY;
};

Camera.prototype.moveTo = function(x, y) {
    this.state.x = x;
    this.state.y = y;
    this.fixPos();
    this.update();
};

Camera.prototype.zoomOut = function(x, y) {
    if (this.scale() > this.minScale()) {
        var k = this.scale();
        this.state.z--;
        k = k / this.scale();
        this.state.x = (this.state.x - x) / k + x;
        this.state.y = (this.state.y - y) / k + y;
        this.fixPos();
        this.update();
    }
};

Camera.prototype.zoomIn = function(x, y) {
    if (this.state.z < 0) {
        var k = this.scale();
        this.state.z++;
        k = k / this.scale();
        this.state.x = (this.state.x - x) / k + x;
        this.state.y = (this.state.y - y) / k + y;
        this.fixPos();
        this.update();
    }
};

Camera.prototype.set = function(state) {
    this.state = state;
    this.update();
};

// Grid ------------------------------------------------------------------------

function Grid(camera) {
    var me = this;

    var rulerPoints = [[0, Camera.VIEWPORT_HEIGHT], [0, 0], [Camera.VIEWPORT_WIDTH, 0]];

    me.underlay = svgNode('g');
    me.overlay = svgNode('g');

    me.ruler = svgNode('polyline');
    me.ruler.setAttribute('fill', 'none');
    me.ruler.setAttribute('stroke', Grid.BG_COLOR);
    me.ruler.setAttribute('stroke-width', Grid.RULER_WIDTH);
    me.ruler.setAttribute('points', rulerPoints.map(function(v){return v.join(',')}).join(' '));

    me.overlay.appendChild(me.ruler);
    me.camera = camera;

    me.v_lines = [];
    me.h_lines = [];
    me.v_chars = [];
    me.h_chars = [];

    function line(i, is_vert) {
        var node = svgNode('line');
        var attr = is_vert ? 'x' : 'y';
        var rtta = is_vert ? 'y' : 'x';
        var coord = camera.extent[attr] / 10 * i;
        node.setAttribute('stroke', Grid.BG_COLOR);
        node.setAttribute(attr + '1', coord);
        node.setAttribute(attr + '2', coord);
        node.setAttribute(rtta + '1', 0);
        node.setAttribute(rtta + '2', camera.extent[rtta]);
        return node;
    }

    for (var i = 1; i < 10; i++) {
        me.v_lines.push(me.underlay.appendChild(line(i, true)));
        me.h_lines.push(me.underlay.appendChild(line(i)));
    }

    function char(i, is_vert) {
        var node = svgNode('text');
        node.innerHTML = is_vert ? Grid.V_CHARS[i] : Grid.H_CHARS[i];
        node.setAttribute('font-family', Grid.FONT);
        node.setAttribute('font-size', Grid.RULER_WIDTH / 2);
        node.setAttribute('font-weight', 'bold');
        node.setAttribute('fill', Grid.FG_COLOR);

        if (is_vert) node.setAttribute('x', Grid.RULER_WIDTH / 14);
        else node.setAttribute('y', Grid.RULER_WIDTH / 2.2);

        return node;
    }

    for (i = 0; i < 10; i++) {
        me.v_chars.push(me.overlay.appendChild(char(i, true)));
        me.h_chars.push(me.overlay.appendChild(char(i)));
    }

    function updater() {
        me.update();
    }

    me.camera.on('change', updater);
    me.update();
}

Grid.H_CHARS = '1234567890';
Grid.V_CHARS = 'ABCDEFGHJK';
Grid.BG_COLOR = 'rgba(255, 255, 255, 0.3)';
Grid.FG_COLOR = 'black';
Grid.FONT = "Tahoma, Arial, sans-serif";
//Grid.FONT = "'UniversCondCBold', 'Arial Narrow', Arial, sans-serif";
Grid.RULER_WIDTH = 24;
Grid.LINE_WIDTH = 1;

Grid.prototype.update = function() {
    var cam = this.camera;
    var scale = 1 / cam.scale();

    for (var i = 0; i < 9; i++) {
        this.v_lines[i].setAttribute('stroke-width', Grid.LINE_WIDTH * scale);
        this.h_lines[i].setAttribute('stroke-width', Grid.LINE_WIDTH * scale);
    }

    for (i = 0; i < 10; i++) {
        this.h_chars[i].setAttribute('x', cam.state.x + (i + 0.5) * cam.extent.x / 10 / scale - 3);
        this.v_chars[i].setAttribute('y', cam.state.y + (i + 0.5) * cam.extent.y / 10 / scale + Grid.RULER_WIDTH / 8);
    }
};

// Layer -----------------------------------------------------------------------

function Layer(state, options) {
    var me = this;
    options = options || {};

    this.applying = false;

    this.objects = {};

    var svg = svgNode('svg');
    svg.setAttribute('width', Camera.VIEWPORT_WIDTH + 'px');
    svg.setAttribute('height', Camera.VIEWPORT_HEIGHT + 'px');

    var extent = {x: 100, y: 100};

    var camera = new Camera(state.cam, extent);

    camera.on('change', function(camState) {
        me.emit('change', {
            type: 'cam',
            state: camState
        });
    });

    var map = svgNode('image');
    var url = '/images/maps/' + state.m;
    map.setLink(url);
    camera.element.appendChild(map);

    var img = new Image();
    img.onload = function() {
        map.setAttribute('width', this.width + 'px');
        map.setAttribute('height', this.height + 'px');
        extent.x = this.width;
        extent.y = this.height;
        //while (camera.scale() > camera.minScale()) camera.state.z--;
        camera.update();
        if (options.grid) {
            var grid = new Grid(camera);
            camera.element.insertBefore(grid.underlay, map.nextSibling);
            svg.appendChild(grid.overlay);
        }
    };
    img.src = url;

    svg.appendChild(camera.element);

    svg.addEventListener('mousedown', function(down) {
        down.preventDefault();
        var x0 = camera.state.x;
        var y0 = camera.state.y;
        var hold = true;

        var moveScene = function(e) {
            camera.moveTo(x0 + e.clientX - down.clientX, y0 + e.clientY - down.clientY);
            hold = false;
        };

        $(window).on('mousemove', moveScene).one('mouseup', function(up) {
            $(window).off('mousemove', moveScene);
            if (hold) {
                var rect = svg.getBoundingClientRect();
                me.emit('click', {
                    x: (up.clientX - rect.left - camera.state.x) / camera.scale(),
                    y: (up.clientY - rect.top - camera.state.y) / camera.scale()
                });
            }
        });
    });

    svg.addEventListener('wheel', function(e) {
        e.preventDefault();
        var box = this.getBoundingClientRect();
        var x = e.clientX - box.left;
        var y = e.clientY - box.top;

        if (e.deltaY > 0) camera.zoomOut(x, y);
        else if (e.deltaY < 0) camera.zoomIn(x, y);
    });

    this.element = svg;
    this.camera = camera;

    eventize(this);

    var obj;

    for (var uid in state.objects) {
        obj = state.objects[uid];
        switch (obj.T) {
            case 'ar': this.addObject(new Arrow(obj.a, obj.c, camera, obj.b), uid); break;
            case 'cu': this.addObject(new Cursor(obj.n), uid); break;
            default: this.addObject(new Feature(obj.T, obj, camera), uid); break;
        }
    }
}

Layer.prototype.applyChange = function(change) {
    this.applying = true;
    switch (change.type) {
        case 'cam': this.camera.set(change.state); break;
        case 'obj': if (change.id in this.objects) this.objects[change.id].setState(change.state); break;
        case 'new':
            switch (change.state.T) {
                case 'ar':
                    this.addObject(new Arrow(change.state.a, change.state.c, this.camera, change.state.b), change.id);
                    break;
                case 'cu':
                    this.addObject(new Cursor(change.state.n), change.id);
                    break;
                default:
                    this.addObject(new Feature(change.state.T, change.state, this.camera), change.id);
                    break;
            }
            break;

        case 'del':
            if (change.id in this.objects) {
                this.objects[change.id].destroy();
                delete this.objects[change.id];
            }
            break;
    }
    this.applying = false;
};

Layer.prototype.addObject = function(obj, uid) {
    var me = this;
    uid = uid || (Math.random() * 1000000).round();
    this.objects[uid] = obj;

    obj.on('change', function() {
        me.emit('change', {
            type: 'obj',
            id: uid,
            state: obj.getState()
        });
    });

    obj.on('destroy', function() {
        delete me.objects[uid];
        me.emit('change', {
            type: 'del',
            id: uid
        });
    });

    this.emit('change', {
        type: 'new',
        id: uid,
        state: obj.getState()
    });

    if (!obj.offlayer) this.camera.element.appendChild(obj.element);
};

Layer.prototype.clear = function() {
    for (var uid in this.objects) if (!this.objects[uid].offlayer) this.removeObject(uid);
};

Layer.prototype.removeObject = function(uid) {
    if (uid in this.objects) {
        this.objects[uid].destroy();
        delete this.objects[uid];
        this.emit('change', {
            type: 'del',
            id: uid
        });
    }
};

Layer.prototype.destroy = function() {
    for (var uid in this.objects) this.objects[uid].destroy();
};

// DOM -------------------------------------------------------------------------

$(function() {
    $('input[name=room-access-create]').change(function() {
        $('input[name=room-access-update][value=' + $('input[name=room-access-create]:checked').val() + ']').prop('checked', true);
    });

    $('input[name=room-access-update]').change(function() {
        $('input[name=room-access-create][value=' + $('input[name=room-access-update]:checked').val() + ']').prop('checked', true);
    });
});

// Arrow helpers ---------------------------------------------------------------

function vector(a) {
    return { x: a.x, y: a.y };
}

function vectorSum(a, b) {
    return {
        x: a.x + b.x,
        y: a.y + b.y
    };
}

function vectorScale(a, k) {
    return {
        x: a.x * k,
        y: a.y * k
    };
}

function vectorRotate(a, fi) {
    var s = Math.sin(fi);
    var c = Math.cos(fi);
    return {
        x: c * a.x + s * a.y,
        y: c * a.y - s * a.x
    };
}

function vectorSet(v, newValue) {
    v.x = newValue.x;
    v.y = newValue.y;
}

function vectorRound(v) {
    return {
        x: v.x.round(),
        y: v.y.round()
    };
}

function Handle(point, camera) {
    var me = this;
    eventize(this);
    this.element = svgNode('circle');
    this.setCoords(point);
    this.element.setAttribute('r', 6 / camera.scale());
    this.element.setAttribute('class', 'figure-handle');
    this.element.setAttribute('style', 'display:none');

    var updateRadius = function() {
        me.element.setAttribute('r', 6 / camera.scale());
    };

    camera.on('change', updateRadius);

    this.destroy = function() {
        this.element.remove();
        camera.off('change', updateRadius);
    };

    this.element.addEventListener('mousedown', function(down) {
        down.preventDefault();
        down.stopPropagation();

        var x0 = point.x;
        var y0 = point.y;

        var movePoint = function(e) {
            point.x = x0 + (e.clientX - down.clientX) / camera.scale();
            point.y = y0 + (e.clientY - down.clientY) / camera.scale();
            me.setCoords(point);
            me.emit('updated', point);
        };

        $(window).on('mousemove', movePoint).one('mouseup', function() {
            $(window).off('mousemove', movePoint);
            me.element.setAttribute('class', 'figure-handle');
            me.emit('stop');
        });

        me.element.setAttribute('class', 'figure-handle active');
        me.emit('start');
    });
}

Handle.prototype.setCoords = function(point) {
    this.element.setAttribute('cx', point.x);
    this.element.setAttribute('cy', point.y);
};

// Arrow -----------------------------------------------------------------------

function Arrow(tail, head, camera, middle) {
    this.camera = camera;
    eventize(this);
    var me = this;

    this.tail = tail;
    this.head = head;
    this.middle = middle || { x: (head.x + tail.x) / 2, y: (head.y + tail.y) / 2 };

    this.figure = svgNode('path');
    this.figure.setAttribute('class', 'draggable');
    this.makePath();

    this.element = svgNode('g');
    this.element.appendChild(this.figure);

    var leaveTimeout;

    this.element.addEventListener('mouseenter', function() {
        clearTimeout(leaveTimeout);
        me.handles.forEach(function(h) {
            h.element.setAttribute('style', 'display:inline');
        });
    });

    this.element.addEventListener('mouseleave', function() {
        leaveTimeout = setTimeout(function() {
            me.handles.forEach(function(h) {
                h.element.setAttribute('style', 'display:none');
            });
        }, 1000);
    });

    function update() {
        me.makePath();
        me.emit('change');
    }

    var midX, midY;

    function storeMid() {
        var head = vector(me.head);
        var middle = vector(me.middle);
        var tail = vector(me.tail);

        var invtail = vectorScale(tail, -1);

        head = vectorSum(head, invtail);
        middle = vectorSum(middle, invtail);

        var alpha = Math.atan2(head.y, head.x);

        head = vectorRotate(head, alpha);
        middle = vectorRotate(middle, alpha);

        midX = middle.x / head.x;
        midY = middle.y / head.x;
    }

    function updateEdge() {
        var head = vector(me.head);
        var tail = vector(me.tail);

        head = vectorSum(head, vectorScale(tail, -1));

        var alpha = Math.atan2(head.y, head.x);

        head = vectorRotate(head, alpha);

        var mid = vectorSum(vectorRotate({ x: midX * head.x, y: midY * head.x }, -alpha), tail);

        me.middle.x = mid.x;
        me.middle.y = mid.y;

        me.handles[2].setCoords(mid);

        me.makePath();
        me.emit('change');
    }

    this.handles = [];
    this.handles.push(new Handle(this.tail, camera).on('start', storeMid).on('updated', updateEdge));
    this.handles.push(new Handle(this.head, camera).on('start', storeMid).on('updated', updateEdge));
    this.handles.push(new Handle(this.middle, camera).on('updated', update));

    this.handles.forEach(function(h) {
        this.element.appendChild(h.element);
    }, this);

    this.figure.addEventListener('mousedown', function(down) {
        down.preventDefault();
        down.stopPropagation();

        var tail0 = vector(me.tail);
        var middle0 = vector(me.middle);
        var head0 = vector(me.head);

        var moveArr = function(e) {
            var d = {
                x: (e.clientX - down.clientX) / camera.scale(),
                y: (e.clientY - down.clientY) / camera.scale()
            };

            vectorSet(me.tail, vectorSum(tail0, d));
            vectorSet(me.middle, vectorSum(middle0, d));
            vectorSet(me.head, vectorSum(head0, d));

            me.handles[0].setCoords(me.tail);
            me.handles[1].setCoords(me.head);
            me.handles[2].setCoords(me.middle);

            me.makePath();
            me.emit('change');
        };

        $(window).on('mousemove', moveArr).one('mouseup', function() {
            $(window).off('mousemove', moveArr);
        });
    });

    this.figure.addEventListener('dblclick', function(e) {
        e.preventDefault();
        e.stopPropagation();
        me.emit('destroy');
        me.destroy();
    });

    this.cameraChangeHandler = function() {
        me.makePath();
    };

    camera.on('change', this.cameraChangeHandler);
}

Arrow.WIDTH = 3;
Arrow.HEAD_WIDTH = 12;
Arrow.HEAD_LENGTH = 22;
Arrow.HEAD_SERIF = 5;
Arrow.TAIL_WIDTH = 18;
Arrow.TAIL_SERIF = 12;

Arrow.prototype.makePath = function() {
    var scale = 1 / this.camera.scale();
    var width = Arrow.WIDTH * scale;
    var head_width = Arrow.HEAD_WIDTH * scale;
    var head_length = Arrow.HEAD_LENGTH * scale;
    var head_serif = Arrow.HEAD_SERIF * scale;
    var tail_width = Arrow.TAIL_WIDTH * scale;
    var tail_serif = Arrow.TAIL_SERIF * scale;

    var points = [];

    var head = vector(this.head);
    var middle = vector(this.middle);
    var tail = vector(this.tail);

    var invmiddle = vectorScale(middle, -1);

    head = vectorSum(head, invmiddle);
    tail = vectorSum(tail, invmiddle);

    var alpha = Math.atan2(head.y, head.x);

    head = vectorRotate(head, alpha);
    tail = vectorRotate(tail, alpha);

    points.push(vector(head));
    points.push(vectorSum(head, { x: -head_length - head_serif, y: head_width }));
    points.push(vectorSum(head, { x: -head_length, y: width }));

    var beta = Math.atan2(tail.y, tail.x);
    var t3 = { x: width / Math.tan(beta / 2), y: width };
    points.push(t3);

    var rtail = vectorRotate(tail, beta);
    var t4 = vectorSum(rtail, { x: tail_serif, y: -tail_width });
    var t6 = vectorSum(rtail, { x: tail_serif, y: tail_width });

    points.push(vectorRotate(t4, -beta));
    points.push(vector(tail));
    points.push(vectorRotate(t6, -beta));
    points.push(vectorScale(t3, -1));

    points.push(vectorSum(head, { x: -head_length, y: -width }));
    points.push(vectorSum(head, { x: -head_length - head_serif, y: -head_width }));

    for (var i = 0; i < points.length; i++) {
        points[i] = vectorSum(vectorRotate(points[i], -alpha), middle);
    }

    var path = 'M' + points[0].x + ',' + points[0].y + ' ';
    path += 'L' + points[1].x + ',' + points[1].y + ' ';
    path += 'L' + points[2].x + ',' + points[2].y + ' ';
    path += 'Q' + points[3].x + ',' + points[3].y + ' ' + points[4].x + ',' + points[4].y + ' ';
    path += 'L' + points[5].x + ',' + points[5].y + ' ';
    path += 'L' + points[6].x + ',' + points[6].y + ' ';
    path += 'Q' + points[7].x + ',' + points[7].y + ' ' + points[8].x + ',' + points[8].y + ' ';
    path += 'L' + points[9].x + ',' + points[9].y + ' z';

    this.figure.setAttribute('d', path);
    this.figure.setAttribute('fill', '#800');
};

Arrow.prototype.getState = function() {
    return {
        T: 'ar',
        a: vectorRound(this.tail),
        b: vectorRound(this.middle),
        c: vectorRound(this.head)
    };
};

Arrow.prototype.setState = function(state) {
    vectorSet(this.tail, state.a);
    vectorSet(this.middle, state.b);
    vectorSet(this.head, state.c);

    this.handles[0].setCoords(this.tail);
    this.handles[1].setCoords(this.head);
    this.handles[2].setCoords(this.middle);

    this.makePath();
};

Arrow.prototype.destroy = function() {
    this.handles.forEach(function(h) { h.destroy() });
    this.element.remove();
    this.camera.off('change', this.cameraChangeHandler);
};

// Icon object -----------------------------------------------------------------

function Feature(type, pos, camera) {
    eventize(this);
    var me = this;

    this.type = type;
    this.pos = pos;
    this.svg = document.getElementById('icon_' + type).contentDocument.firstElementChild.cloneNode(true);

    var hwidth = parseFloat(this.svg.getAttribute('width')) / 2;
    var hheight = parseFloat(this.svg.getAttribute('height')) / 2;

    //setTimeout(function() {
    //	me.svg.setAttribute('style', 'display:inline');
    //}, 0);

    this.updateTransform = function() {
        var sc = camera.scale();
        var offsetX = hwidth / sc;
        var offsetY = hheight / sc;
        me.element.setAttribute(
            'transform',
            'translate(' + (me.pos.x - offsetX) + ', ' + (me.pos.y - offsetY) + ') ' +
                'scale(' + 1/sc + ')'
        );
    };

    camera.on('change', me.updateTransform);

    this.element = svgNode('g');
    this.updateTransform();
    this.element.setAttribute('class', 'draggable');
    this.element.appendChild(this.svg);

    this.element.addEventListener('mousedown', function(down) {
        down.preventDefault();
        down.stopPropagation();

        var pos0 = vector(me.pos);

        var moveArr = function(e) {
            var d = {
                x: (e.clientX - down.clientX) / camera.scale(),
                y: (e.clientY - down.clientY) / camera.scale()
            };

            vectorSet(me.pos, vectorSum(pos0, d));

            me.updateTransform();

            me.emit('change');
        };

        $(window).on('mousemove', moveArr).one('mouseup', function() {
            $(window).off('mousemove', moveArr);
        });
    });

    this.element.addEventListener('dblclick', function(e) {
        e.preventDefault();
        e.stopPropagation();
        me.emit('destroy');
        me.destroy();
    });

    this.destroy = function() {
        this.element.remove();
        camera.off('change', this.updateTransform);
    };
}

Feature.prototype.getState = function() {
    return {
        T: this.type,
        x: this.pos.x.round(),
        y: this.pos.y.round()
    };
};

Feature.prototype.setState = function(state) {
    this.pos.x = state.x;
    this.pos.y = state.y;
    this.updateTransform();
};

// Cursor object ---------------------------------------------------------------

function Cursor(nick) {
    var me = this;
    eventize(this);
    this.nick = nick;
    this.x = -1000;
    this.y = -1000;
    this.offlayer = true;

    this._moveHandler = function(e) {
        var mapPos = $('.tactic-display').offset();

        me.x = e.pageX - mapPos.left;
        me.y = e.pageY - mapPos.top;

        if (me.element) {
            $(me.element).css('left', me.x + mapPos.left);
            $(me.element).css('top', me.y + mapPos.top);
        }

        me.emit('change');
    };

    if (nodeData[0].nickname == nick) {
        $(window).on('mousemove', this._moveHandler);
    }
    else {
        this.element = $('<div style="position: absolute; z-index: 100; width: 12px; height: 19px; left: -1000px; top: -1000px; background: url(/images/cursor.png)"><div style="position: absolute; left: 12px; top: 19px; color: red;">' + nick + '</div></div>').appendTo('body')[0];
    }
}

Cursor.prototype.getState = function() {
    return {
        T: 'cu',
        x: this.x,
        y: this.y,
        n: this.nick
    };
};

Cursor.prototype.setState = function(state) {
    this.x = state.x;
    this.y = state.y;
    var mapPos = $('.tactic-display').offset();

    if (this.element) {
        $(this.element).css('left', this.x + mapPos.left);
        $(this.element).css('top', this.y + mapPos.top);
    }
};

Cursor.prototype.destroy = function() {
    if (this.element) this.element.remove();
    $(window).off('mousemove', this._moveHandler);
};

// Socket ----------------------------------------------------------------------

$(function() {
    nodeAuthorization.done(function() {
        var sock = io.connect(nodeURL + '/tactic');
        var maps = [];
        var layers = [];
        var room = {};
        var tool;
        var writer = false;
        var currentLayer;
        var display = $('.tactic-display')[0];

        function mapName(id) {
            for (var i = maps.length; i--;) if (maps[i].id == id) return maps[i]['name_' + nodeData[0].language];
            return '';
        }

        function mapThumb(id) {
            for (var i = maps.length; i--;) if (maps[i].id == id) return maps[i].layers[0];
            return '';
        }

        //display.addEventListener('mousedown', function(e) {
        //	if (!writer) e.stopPropagation();
        //}, true);
        //
        //display.addEventListener('wheel', function(e) {
        //	if (!writer) e.stopPropagation();
        //}, true);

        function switchLayer(index) {
            $('.layer-thumbs .active').removeClass('active');
            $('.layer-thumbs li').eq(index).addClass('active');
            $('.tactic-display > svg').hide().eq(index).show();
            currentLayer = layers[index];
        }

        sock.on('maps', function(data) {
            maps = data;

            maps.forEach(function(m,i) {
                m.id = i;
            });

            maps.sort(function(a, b) {
                if (a['name_' + nodeData[0].language] > b['name_' + nodeData[0].language]) return 1;
                if (a['name_' + nodeData[0].language] < b['name_' + nodeData[0].language]) return -1;
                return 0;
            });

            var selects = $('.map-select');
            selects.empty();

            for (var i = 0; i < maps.length; i++) {
                $('<option value="' + maps[i].id + '">' + maps[i]['name_' + nodeData[0].language] + '</option>').appendTo(selects);
            }

            $('.map-case.connecting').hide();
            $('.map-case.create').show();

            sock.emit('getrooms');
            setInterval(function() {
                if ($('.map-case.create:visible').length) sock.emit('getrooms');
            }, 2000);
        });

        sock.on('roomlist', function(list) {
            var ul = $('.room-list ul');
            ul.find('li').not(':first').remove();

            for (var i = list.length; i--;) {
                $('<li data-creator="' + list[i][1] + '"><span><img src="/images/maps/thumbs/' + mapThumb(list[i][0]) + '"></span><span><a href="#">' + mapName(list[i][0]) + '</a></span><span>' +
                list[i][1] + '</span><span>' + list[i][2] + '</span></li>').appendTo(ul);
            }
        });

        sock.on('forbidden', function() {
            $('.map-case.connecting .title').html('Тактический планшет доступен только авторизованным пользователям альянса');
        });

        sock.on('disconnect', function() {
            $('.map-case').hide();
            $('.map-case.connecting').show().find('.title').html('Соединение разорвано, обновите страницу');
        });

        sock.on('created', function(_room) {
            room = _room;
            $('.map-case').hide();
            var roomdiv = $('.map-case.room').show().removeClass('admin').removeClass('writer');
            $('.list-users').empty();

            var thumbs = $('.layer-thumbs').empty();
            $('.tactic-display').empty();

            layers.forEach(function(l) {
                l.destroy();
            });

            layers = [];

            room.layers.forEach(function(layerState, index) {
                var layer = new Layer(layerState, {
                    grid: index == 0
                });

                $('<li><img src="/images/maps/thumbs/' + layerState.m + '"></li>').appendTo(thumbs).click(function() {
                    switchLayer(index);
                    sock.emit('switch', index);
                });

                layer.on('change', function(e) {
                    if (!this.applying) {
                        sock.emit('change', {
                            i: index,
                            e: e
                        });
                    }
                });

                layer.on('click', function(e) {
                    if (writer && tool) {
                        if (tool == 'arrow') {
                            layer.addObject(new Arrow({x: e.x, y: e.y}, {x: e.x, y: e.y - 100 / layer.camera.scale()}, layer.camera));
                        }
                        else {
                            layer.addObject(new Feature(tool, {x: e.x, y: e.y}, layer.camera));
                        }
                    }
                });

                $('.tactic-display').append(layer.element);

                layers.push(layer);
            });

            if (room.creator == nodeData[0].nickname) {
                writer = true;
                $('.glass').hide();
                roomdiv.addClass('admin');
                layers[0].addObject(new Cursor(room.creator), 'cur-' + room.creator);
            } else {
                writer = false;
                $('.glass').show();
            }

            switchLayer(room.current);

            $('.current-map-name').html(mapName(room.map));
        });

        sock.on('change', function(data) {
            layers[data.i].applyChange(data.e);
        });

        sock.on('switch', switchLayer);

        sock.on('joined', function(nick) {
            var role = nick == room.creator ? 'commander' : '';
            var item = createUserListItem(role, nodeData[0].nickname == room.creator, nick);
            if (role == 'commander') item.prependTo('.list-users');
            else item.appendTo('.list-users');
        });

        sock.on('left', function(nick) {
            $('.ul-item-' + nick).remove();
            layers[0].removeObject('cur-' + nick);
        });

        sock.on('closed', function(/*nick*/) {
            layers.forEach(function(l) {
                l.destroy();
            });
            $('.map-case').hide();
            $('.create-room').prop('disabled', false);
            $('.map-case.create').show();
            $('.map-case.room').removeClass('writer');
        });

        sock.on('kicked', function() {
            alert('Вас исключили');
            layers.forEach(function(l) {
                l.destroy();
            });
            $('.map-case').hide();
            $('.map-case.create').show();
            $('.map-case.room').removeClass('writer');
        });

        //sock.on('takeyourrooms', function(data) {
        //	console.log('rooms', data);
        //})

        sock.on('exists', function() {
            $('.map-case.connecting .title').html('Тактический планшет уже открыт в другой вкладке');
        });

        sock.on('allowed', function() {
            writer = true;
            $('.map-case.room').addClass('writer');
            $('.glass').hide();
        });

        sock.on('denied', function() {
            writer = false;
            $('.map-case.room').removeClass('writer');
            $('.glass').show();
        });

        sock.on('serialized', function(data) {
            prompt("Скопируйте и сохраните себе код шаблона созданной тактики. " +
                "В будущем вы можете использовать его при создании комнаты.", data);
        });

        sock.on('deserialization-error', function() {
            alert('Ошибка при чтении шаблона');
            $('.create-room').prop('disabled', false);
        });

        $('#debugbtn').click(function() {
            sock.emit('givemyrooms');
        });

        $('.create-room').click(function() {
            $(this).prop('disabled', true);
            sock.emit('create', {
                map: +$('.map-select.create').val(),
                access: $('input[name="room-access-create"]:checked').val(),
                serialized: $('input[name="room-serialized"]').val()
            });
        });

        $('.update-room').click(function() {
            sock.emit('update', {
                map: +$('.map-select.update').val(),
                access: $('input[name="room-access-update"]:checked').val()
            });
        });

        $('.room-list').on('click', 'li:not(:first)', function() {
            sock.emit('join', $(this).data('creator'));
            return false;
        });

        $('.exit-room').click(function() {
            if (room.creator != nodeData[0].nickname || confirm('Комната будет уничтожена')) {
                sock.emit('exit');
            }
        });

        $('.save-room').click(function() {
            sock.emit('serialize');
        });

        $('.list-users').on('change', '.granter', function() {
            var nick = $(this).data('nick');
            if ($(this).prop('checked')) {
                sock.emit('allow', nick);
                layers[0].addObject(new Cursor(nick), 'cur-' + nick);
            }
            else {
                sock.emit('deny', nick);
                layers[0].removeObject('cur-' + nick);
            }
        }).on('click', '.kick-user', function() {
            sock.emit('kick', $(this).data('nick'));
        });

        $('.board-button .btn').click(function() {
            var cmd = $(this).data('cmd');

            if (cmd == 'clear') {
                currentLayer.clear();
            } else {
                tool = cmd;
                $('.board-button .btn').removeClass('active');
                $(this).addClass('active');
            }
        });
    });
});

// helpers

function createUserListItem(role, editable, nick) {
    var item = $('<div>').addClass(role).addClass('ul-item-' + nick);

    if (editable) {
        item.append('<span class="del kick-user" data-nick="' + nick + '"></span>').append(
            '<input type="checkbox" class="granter" data-nick="' + nick + '" ' +
            (role == 'commander' ? 'disabled' : '') + ' id="ul_item_' + nick + '">'
        );
    }

    item.append('<label for="ul_item_' + nick + '">' + nick + '</label>');

    return item;
}
