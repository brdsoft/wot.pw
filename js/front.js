$(function(){
	/* $("body").on('click', 'div.pager a', function() {
		scroll('scrollTo');
	}); */

	setTimeout(showNotice, 10);

	$('body').on('click', '.forum-spoiler .button', function(){
		$(this).parent().find('.fs-container:first').slideToggle('fast');
	});
});

function scroll(name)
{
	$("html, body").animate({
			scrollTop: $('a[name='+name+']').offset().top + "px"
	}, {
			duration: 500
	});
}

function showNotice()
{
	if (typeof(notices) != "undefined")
	{
		var notice = notices.shift();
		if (!notice)
			return;
		var buttons = '';
		for (var i in notice.answers)
		{
			if (notice.answers[i] == '1')
				buttons += '<button onClick="answerNotice(\''+notice['id']+'\', \'1\');">Принял</button> ';
			if (notice.answers[i] == '2')
				buttons += '<button onClick="answerNotice(\''+notice['id']+'\', \'2\');">Согласен</button> ';
			if (notice.answers[i] == '3')
				buttons += '<button onClick="answerNotice(\''+notice['id']+'\', \'3\');">Не согласен</button> ';
		}
		$('body').append('<div class="notice" style="display: none;"><div class="notice-flex"><div class="notice-container"><div class="text">'+notice.notice+'</div><div class="buttons">'+buttons+'</div></div></div></div>');



		$('div.notice').fadeIn(300);
	}
}

function answerNotice(id, answer)
{
	$.get(
		'/ajax/answerNotice',
		{
			id: id,
			answer: answer
		},
		function(/*data*/){
			$('div.notice').remove();
			setTimeout(showNotice, 300);
		},
		'json'
	);
}

Number.prototype.round = function() {
	return Math.round(this);
};

Number.prototype.pad = function(size) {
    return ('00000000000000000000' + this).substr(-size);
}

Date.prototype.getClock = function() {
	return this.getHours() + ':' + this.getMinutes().pad(2);
}

function pollAnswer(id, csrf)
{
	$('#poll_'+id+' button').attr('disabled', true);
	var answer = $('#poll_'+id+' input:checked').val();
	if (!answer)
	{
		return;
	}
	$.post(
		'',
		{
			poll_action: 'poll',
			poll_id: id,
			poll_answer: answer,
			YII_CSRF_TOKEN: csrf
		},
		function(data)
		{
			$('#poll_'+id).html($(data).find('#poll_'+id).html());
		}
	);
}

var escapeHtml = (function() {
	var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#x27;', '`': '&#x60;' };
	return function(s) { return (s + '').replace(/[&<>"'`]/g, function(s) { return map[s] }) };
})();

function template(text) {
	function test(a) { return a + a }

	var idx = 0, esc = { "'": "'", '\\': '\\', '\r': 'r', '\n': 'n'},
		src = "var __t,__p='',print=function(){__p+=Array.prototype.join.call(arguments,'');};\nwith(obj||{}){\n__p+='";

	text.replace(/<%-([\s\S]+?)%>|<%=([\s\S]+?)%>|<%([\s\S]+?)%>|$/g, function(m, escp, intr, evlt, offset) {
		src += text.slice(idx, offset).replace(/\\|'|\r|\n/g, function(m) { return '\\' + esc[m] });
		idx = offset + m.length;

		     if (escp) src += "'+\n((__t=(" + escp + "))==null?'':escapeHtml(__t))+\n'";
		else if (intr) src += "'+\n((__t=(" + intr + "))==null?'':__t)+\n'";
		else if (evlt) src += "';\n" + evlt + "\n__p+='";
	});

	return new Function('obj', src + "';\n}\nreturn __p;\n");
}
