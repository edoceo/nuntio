
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

nuntio.line = {
	obj:$('#chat-foot textarea')
};
nuntio.line.disable = function() {
	// this.obj.attr('disabled', true);
}
nuntio.line.enable = function() {
	this.obj.removeAttr('disabled');
}
nuntio.line.focus = function() {
	this.obj.focus();
}
nuntio.line.get_clean = function() {
	var x = $('#chat-foot-text').val();
	$('#chat-foot-text').val('');
	return x;
}

/**
	Engage the Event Listener
*/
nuntio.line.listen = function() {
	$('#chat-foot-text').on('keypress',function(e) {
		var chord = '';
		chord += (e.altKey ? 'A' : '-');
		chord += (e.ctrlKey ? 'C' : '-');
		chord += (e.shiftKey ? 'S' : '-');
		switch (chord) {
		case '---':
			// Naked Enter Key
			switch (e.keyCode) {
			case 10:
			case 13:
				// Submit Text
				nuntio.send(nuntio.line.get_clean());
				e.preventDefault();
				e.stopPropagation();
				return false;
				break;
			}
			break;
		}
	});
}

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
	nuntio.line.enable();

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
		nuntio.line.disable();
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

/**
	http://stackoverflow.com/questions/6604622/file-drag-and-drop-event-in-jquery
	http://www.whatwg.org/specs/web-apps/current-work/multipage/dnd.html
*/

$(function() {

	// var dz = $('#drop-zone');
	// var dz = $('#chat-foot');
	var dz = $('#chat-foot');

    $('body').on('dragenter',function(e) {
		console.log('nuntio.drag(0)');
        dz.addClass('drop');
		e.preventDefault();
		e.stopPropagation();
    });

    $('body').on('dragover',function(e) {
		console.log('nuntio.drag(1)');
        dz.addClass('drop');
		e.preventDefault();
		e.stopPropagation();
    });

    // $('body').on('leave',function() {
    //     dz.removeClass('drop');
    // });
    // 
    // $('body').on('mouseout',function() {
    //     dz.removeClass('drop');
    // });

    $('body').on('drop',function(e) {

		var data = e.originalEvent.dataTransfer;
    	// data.dropEffect = 'none';
    	switch (data.effectAllowed) {
    	case 'all':
    		break;
    	case 'copyMove':
    		// Text Drop?
    		var text = data.getData('text/plain');
    		var $x = $('#chat-foot-text');
    		$x.val(text).change();
    		break;
    	}
    	// data.effectAllowed = 'all';
    	// data.files = FileList object
    	// data.items = DataTransferItemList
    	// data.types = Array

//    	var c = data.types.length;
//    	for (var i=0; i<c; i++) {
//    		
//    	}

		e.preventDefault();
		e.stopPropagation();
    });

//    $('#chat-list').on('drop',function(e) {
//    	debugger;
//		e.preventDefault();
//		e.stopPropagation();
//    });

	$('#chat-foot-text').on('blur change keyup focus',function() {
		// var l = parseInt( $(this).val().length ) || 0;
		// $(this).css('height','0px');
		document.getElementById('chat-foot-text').style.height = 0;
		var h = Math.max(32, $(this).prop('scrollHeight'));
		// console.log('New Height: ' + h);
		$(this).css('height',h + 'px');
	});
	$('#chat-foot-text').css({
		"transition": "none",
		"-moz-transition": "none",
		"-o-transition": "none",
		"-webkit-transition":  "none"
	});

});
