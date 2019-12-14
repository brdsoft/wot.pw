$(function() {
    'use strict';

    var KEY_ENTER = 13,
        //KEY_LEFT = 37,
        KEY_UP = 38,
        //KEY_RIGHT = 39,
        KEY_DOWN = 40;

    var autoCompleteTimer = -1;
    var completeXhr, previousTerm, originalTerm, talkHash, topTime, bottomTime;

    var needMore = true;

    var msgTemplate = template($('#pmsg_msg').html());
    var thumbTemplate = template($('#pmsg_thumb').html());
    var dateTemplate = template($('#pmsg_date').html());

    var avatarCache = {};

    var snd = document.getElementById('pmsg_incoming');

    function addThumb(thumb) {
        $('.pmsg-thumb[data-hash=' + thumb.hash + ']').remove();

        var $thumb = $(thumbTemplate(thumb));

        $('.pmsg-side').prepend($thumb);

        if (thumb.incoming == 1) {
            $thumb.find('.pmsg-incoming').show();
        }
    }

    function scrollDown() {
        var c = $('.pmsg-content');
        c.scrollTop(c[0].scrollHeight);
    }

    function loadMore() {
        if (!needMore) return;

        $.getJSON('/pmsg/loadmore', {
            time: topTime.getTime() / 1000,
            talk_hash: talkHash
        }, function(result) {
            if (result.error == 'ok') {
                if (!result.messages.length) {
                    needMore = false;
                    return;
                }

                var first = $('.pmsg-message').first();

                result.messages.forEach(function(msg) {
                    msg.time = new Date(msg.time * 1000);
                    msg.avatar = avatarCache[msg.nick];
                    addMessage(msg, true);
                });

                $('.pmsg-content').scrollTop(first.position().top);
            } else {
                alert(result.error);
            }
        });
    }

    function addMessage(msg, toTop) {
        msg.isMine = msg.nick == nodeData[0].nickname;

        if (toTop) {
            if ($('.pmsg-message').length) {
                if (msg.time.toDateString() != topTime.toDateString()) {
                    $('.pmsg-messages').prepend(dateTemplate({time: topTime}));
                }
            } else {
                bottomTime = msg.time;
            }

            $('.pmsg-messages').prepend(msgTemplate(msg));
            topTime = msg.time;
        } else {
            if ($('.pmsg-message').length && msg.time.toDateString() != bottomTime.toDateString()) {
                $('.pmsg-messages').append(dateTemplate({time: msg.time}));
            }

            $('.pmsg-messages').append(msgTemplate(msg));
            scrollDown();
            bottomTime = msg.time;
        }
    }

    function refreshDialog(data) {
        needMore = true;

        $('.pmsg-talk').removeClass('pmsg-hidden').find('.pmsg-messages').empty();
        $('.pmsg-selector').addClass('pmsg-hidden');
        $('textarea[name=pmsg_text]').focus();

        talkHash = data.talk_hash;
        avatarCache = $.extend(avatarCache, data.avatars);

        var lastVisit = data.last_visit ? data.last_visit * 1000 : Number.MAX_VALUE;

        data.last_messages.forEach(function(msg) {
            msg.time = new Date(msg.time * 1000);
            msg.avatar = avatarCache[msg.nick];

            if (msg.time > lastVisit) {
                msg.isNew = true;
            }

            addMessage(msg, true);
        });

        setTimeout(scrollDown, 200);
    }

    function prepareDialog(nick) {
        $.post('/pmsg/prepare', {
            nick: nick,
            YII_CSRF_TOKEN: CSRF_TOKEN
        }, function(result) {
            if (typeof result == 'object' && result.error == 'ok') {
                refreshDialog(result);
                addThumb({ avatar: result.avatars[nick], title: nick, hash: talkHash });
            }
        }, 'json');
    }

    function loadDialog(hash) {
        return $.get('/pmsg/load', {
            hash: hash
        }, function(result) {
            if (typeof result == 'object' && result.error == 'ok') {
                refreshDialog(result);
            }
        });
    }

    function hideTalk() {
        $('.pmsg-talk').addClass('pmsg-hidden');
        talkHash = null;
    }

    $(window).click(function(e) {
        if (!$(e.target).closest('.pmsg').length) {
            $('.pmsg-selector').addClass('pmsg-hidden');
            hideTalk();
        }
    });

    $('.pmsg-content').on('scroll', function() {
        if ($(this).scrollTop() == 0) {
            loadMore();
        }
    });

    $('.pmsg-write').click(function() {
        hideTalk();
        $('.pmsg-selector').toggleClass('pmsg-hidden');
        $('input[name=pmsg_nick]').focus();
    });

    $('.pmsg').on('mousedown', '.pmsg-thumb', function(e) {
        e.preventDefault();
    }).on('click', '.pmsg-close', function() {
        var thumb = $(this).closest('.pmsg-thumb');

        $.post('/pmsg/delthumb', {
            talk_hash: thumb.data('hash'),
            YII_CSRF_TOKEN: CSRF_TOKEN
        });

        thumb.remove();
    }).on('click', '.pmsg-thumb > img', function() {
        var thumb = $(this).closest('.pmsg-thumb');
        var hash = thumb.data('hash');
        var img = this;

        if (hash == talkHash) {
            hideTalk();
        } else {
            var prevSrc = img.src;
            img.src = '/images/712.gif';
            loadDialog(hash).then(function() {
                img.src = prevSrc;
            });
            thumb.find('.pmsg-incoming').hide();
        }
    }).on('mouseenter', '.pmsg-new', function() {
        console.log('boo');
        $(this).removeClass('pmsg-new');
    });

    $('input[name=pmsg_nick]').keyup(function(e) {
        if (e.which == KEY_UP || e.which == KEY_DOWN) {
            return;
        }

        var term = $(this).val();

        if (!term || term == previousTerm) {
            return;
        }

        previousTerm = term;

        clearTimeout(autoCompleteTimer);

        autoCompleteTimer = setTimeout(function() {
            completeXhr && completeXhr.abort();

            completeXhr = $.getJSON('/pmsg/completenick', {
                term: term
            }, function(nicks) {
                var list = $('.pmsg-list').empty();

                nicks.forEach(function(nick) {
                    $('<li>').html(nick).appendTo(list);
                });
            });
        }, 200);
    }).keydown(function(e) {
        var selected;

        switch (e.which) {
            case KEY_UP:
                e.preventDefault();
                selected = $('.pmsg-list > li.selected');

                if (selected.length) {
                    if (selected.is(':first-child')) {
                        break;
                    }

                    selected = selected.removeClass('selected').prev().addClass('selected');
                } else {
                    selected = $('.pmsg-list > li:last-child').addClass('selected');
                    originalTerm = $(this).val();
                }

                $(this).val(selected.html());

                break;
            case KEY_DOWN:
                e.preventDefault();
                selected = $('.pmsg-list > li.selected');

                if (selected.length) {
                    if (selected.is(':last-child')) {
                        $(this).val(originalTerm);
                        selected.removeClass('selected');
                        break;
                    }

                    $(this).val(selected.removeClass('selected').next().addClass('selected').html());
                }

                break;
            case KEY_ENTER:
                prepareDialog($(this).val());
                break;
        }
    });

    $('.pmsg-list').on('click', 'li', function() {
        prepareDialog($(this).html());
    });

    nodeAuthorization.done(function() {
        var sock = io.connect(nodeURL + '/pmsg');

        $('textarea[name=pmsg_text]').keydown(function(e) {
            if (e.which == KEY_ENTER && !e.shiftKey) {
                e.preventDefault();
                sock.emit('msg', {
                    talk_hash: talkHash,
                    content: $(this).val()
                });
                $(this).val('');
            }
        });

        sock.on('msg', function(data) {
            if (data.talk_hash == talkHash) {
                var msg = {
                    nick: data.talker,
                    content: data.content,
                    time: new Date(data.time),
                    avatar: avatarCache[data.talker]
                };

                addMessage(msg);

                $.post('/pmsg/visit', {
                    hash: talkHash,
                    YII_CSRF_TOKEN: CSRF_TOKEN
                });
            } else {
                snd.play();

                addThumb({
                    hash: data.talk_hash,
                    title: data.talker,
                    avatar: data.avatar,
                    incoming: true
                });
            }
        });
    });

    $.get('/pmsg/thumbs', function(result) {
        if (result instanceof Array) {
            result.forEach(function(thumb) {
                addThumb(thumb);
            });
        }
    })
});
