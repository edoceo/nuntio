
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

/**
	Just Plays our Audio
*/
nuntio.beep = function()
{
	var audio = document.getElementById('chat-audio');
	audio.play();
}

nuntio.line = {
	history:[],
	history_idx:0,
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
	@see http://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes
*/
nuntio.line.listen = function() {
	$('#chat-foot-text').on('keydown',function(e) {
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
				var t = nuntio.line.get_clean();
				nuntio.send(t);
				nuntio.line.history_idx = 0;
				nuntio.line.history.push(t);
				e.preventDefault();
				e.stopPropagation();
				return false;
				break;
			case 33: // Page Up
				// Scroll Room
				break;
			case 34: // Page Dn
				break;
			case 38: // Up Arrow
				$('#chat-foot-text').val(nuntio.line.history[nuntio.line.history_idx]);
				nuntio.line.history_idx++;
				break;
			case 40: // Down Arrow
				nuntio.line.history_idx--;
				$('#chat-foot-text').val(nuntio.line.history[nuntio.line.history_idx]);
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

nuntio.upload = function(file_list)
{
	if(!window.FileReader){
		alert('The File API is not supported');
		return;
	}

	nuntio.upload_name = file_list[0].name;
	nuntio.upload_size = file_list[0].size;

	var fr = new FileReader();
	fr.onload = nuntio.upload_file;
	fr.readAsBinaryString(file_list[0]);

}

/**
	Does the Actual Upload via XHR
*/
nuntio.upload_file = function(e0)
{
	// Extend XHR
	XMLHttpRequest.prototype.sendAsBinary = function(data)
	{
		var byteValue = function(x) {
			return x.charCodeAt(0) & 0xff;
		}

		var ords = Array.prototype.map.call(data, byteValue);
		var ui8a = new Uint8Array(ords);

		try{
			this.send(ui8a);
		}catch(error){
			this.send(ui8a.buffer);
		}
	}

	var xhr = new XMLHttpRequest();
	xhr.open('PUT', '/upload?r=' + nuntio.room + '&n=' + nuntio.upload_name, true);
	xhr.onreadystatechange = function(e1)
	{
		if(e1.target.readyState != 4) return;
		$('#chat-drop').remove();
		$('#chat-foot').removeClass('drop');
	}

	xhr.onerror = function(e2)
	{
		alert('Error: ' + e2.target.responseText);
	}

	xhr.sendAsBinary(e0.target.result);
}

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

    $('body').on('dragleave',function(e) {
		dz.removeClass('drop');
		e.preventDefault();
		e.stopPropagation();
    });
    // 
    // $('body').on('mouseout',function() {
    //     dz.removeClass('drop');
    // });

    $('body').on('drop',function(e) {

		console.log('nuntio.drop(0)');

		var drop = e.originalEvent.dataTransfer;
    	// drop.dropEffect = 'none';
    	// drop.effectAllowed = 'all';
    	// drop.files = FileList object
    	// drop.items = DataTransferItemList
    	// drop.types = Array
    	switch (drop.effectAllowed) {
    	case 'all':
    		break;
    	case 'copyMove':
    		// Text Drop?
    		var text = drop.getData('text/plain');
    		var $x = $('#chat-foot-text');
    		$x.val(text).change();
    		break;
    	}

    	if (drop.files.length) {
    		var html = '<div id="chat-drop">Loading: ';
    		html += drop.files[0].name;
    		html += '</div>';
    		$('#chat-foot').append(html);
    		nuntio.upload(drop.files);
    	}

    	var c = drop.types.length;
    	for (var i=0; i<c; i++) {
    		var t = $('#chat-line-text').val();
    		t += drop.types[i];
    		$('#chat-line-text').val(t)
    	}

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
