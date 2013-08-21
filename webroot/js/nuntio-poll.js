nuntio.open = function()
{
	nuntio.stat('info','Connected');
	$('#chat-foot input').focus();

	// var arg = {
	//	 room:this.room
	// };
	// $('#chat-list').load('/chat/list', arg, function(res,ret,xhr) {
	//	 var x = document.getElementById('chat-list');
	//	 x.scrollTop = x.scrollHeight;
	// });

	this.poll();
}

nuntio.join = function(n)
{
	console.log('nuntio.join(' + n + ')');
	this.room = n;
	this.open();

	$('#chat-foot input').on('keypress',function(e) {
		var chord = '';
		chord += (e.altKey ? 'A' : '-');
		chord += (e.ctrlKey ? 'C' : '-');
		chord += (e.shiftKey ? 'S' : '-');
		switch (chord) {
		default:
			switch (e.keyCode) {
			case 13:
				// Submit Text
				nuntio.send($('#chat-foot input').val());
				$('#chat-foot input').val('');
				break;
			}
		}
	});
}

/**
	Data Received from Server
*/
nuntio.poll = function() {

	console.log('nuntio.poll()');

	// var l = e.line_id;
	// if (!l) l = (++this.line_id);

	// If JSON data?
	$.ajax({
		dataType:'json',
		url:'/chat/poll',
		timeout:16000,
		data:{
			line:nuntio.line_id
		},
		success:function(res,ret,xhr) {
			nuntio.recv(res,ret,xhr);
			nuntio.tick = setTimeout(nuntio.poll,1234);
		},
		error:function(xhr,ret,err) {
			nuntio.mode = 'error';
			nuntio.error_count++;
			nuntio.stat('fail',err);
			// $('.chat-stat').html('Connection Error:' + nuntio.error_count + ' #' + this.readyState + '/' + e.eventPhase);
		},
		complete:function() {
			// nuntio.tick = setTimeout(nuntio.poll,1234);
		}
	});

	// $('#chat-list').append('<div class="chat-line" id="l' + l + '">' + e.data + '</div>');
	// $('#chat-list').append('<div class="chat-line">' + $('#chat-list').scrollTop() + '</div>');
	// $('#chat-list').append('<div class="chat-line">' + parseInt($('#chat-list').scrollTop()) + ' of ' + $('#chat-list').height() + '</div>');

	// Failed
	// var x = $('#chat-list .chat-line:last').position();
	// $('#chat-list').scrollTop(x.top);
	// Also Failed
	// document.getElementById('l' + l).scrollIntoView();
	// $('#chat-list').scrollTop( parseInt($('#chat-list').scrollTop()) );
}

/**

*/
nuntio.recv = function(res,ret,xhr) {
	// no res? bail
	if ('undefined' == typeof res) return;
	// res has list, then use that
	if ('undefined' != typeof res.list) res = res.list;

	var c = res.length;
	for (var i = 0; i<c; i++) {

		var line = res[i];

		$('#chat-list').append('<div class="chat-line" id="l' + line._id + '">[' + line.time + '] ' + line.text + '</div>');
		var x = document.getElementById('chat-list');
		x.scrollTop = x.scrollHeight;

		if (line._id) nuntio.line_id = line._id;

		// If there is Rich Media in the Line
		// if (line.rich) {
		//	 $('#l' + line._id).load('/chat/line',{id:line._id});
		// }

	}
}

/**
	Send Data to Server
*/
nuntio.send = function(t)
{
	switch (t) {
	case '/wipe':
		$('#chat-list').empty();
		return;
	}
	var arg = {
		room:this.room,
		text:t
	};

	$.post('/chat/post',arg,nuntio.recv);
}

