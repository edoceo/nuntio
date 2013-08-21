
var nuntio = {
	auto_reconnect:true,
	error_count:0,
	line_id:0,
	room:null,
	send_queue:[],
	tick:null,	  // Timer Handle
	uri:null,
	ws:null
};

nuntio.do_size = function() {

	var w = $(window).width();
	var h = $(window).height() - $('#head').height();

	$('#chat').css({
		top:0,
		left:0,
		width:w - 200,
		height:h
	});

	$('#chat-list').css({
		height:(h - $('#chat-head').height() - $('#chat-foot').height() - 4)
	});

	$('#tool').css({
		top:0,
		left:(w - 200),
		width:200,
		height:h
	});
};

nuntio.stat = function(kind,text) {
	var se = $('.chat-stat');
	// se.removeClass('good');
	// se.removeClass('info');
	// se.removeClass('warn');
	se.removeClass('fail');
	$('#chat-foot input').attr('disabled',false);

	switch (kind) {
	case 'good':
		// se.addClass('good');
		se.css({color:'#5cb85c'});
		se.html(text);
		break;
	case 'info':
		se.css({color:'#357ebd'});
		se.html(text);
		break;
	case 'warn':
		se.css({color:'#eea236'});
		se.html(text);
		break;
	case 'fail':
		se.css({color:'#000'});
		se.addClass('fail');
		se.html('Error:' + text);
		$('#chat-foot input').attr('disabled',true);
		break;
	default:
		se.css({color:'inherit'});
		se.html(text);
		break;
	}
};

// Interface
nuntio.join = function() { };
nuntio.open = function() { };
nuntio.send = function() { };
nuntio.recv = function() { };
