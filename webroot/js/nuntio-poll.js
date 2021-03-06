nuntio.open = function()
{
	nuntio.stat('info','<i class="icon-comments-alt" title="ready"></i>');
	nuntio.line.focus();

	// var arg = {
	//	 room:this.room
	// };
	// $('#chat-list').load('/chat/list', arg, function(res,ret,xhr) {
	//	 var x = document.getElementById('chat-list');
	//	 x.scrollTop = x.scrollHeight;
	// });

	nuntio.poll();
}

nuntio.join = function(n)
{
	console.log('nuntio.join(' + n + ')');
	nuntio.room = n;
	nuntio.open();
	nuntio.line.listen();
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
			room:nuntio.room,
			line:nuntio.line_id
		}
	})
	.done(function(res,ret,xhr) {
			// console.log('nuntio.poll().ajax().success()');
			nuntio.recv(res,ret,xhr);
	})
	.fail(function(xhr,ret,err) {
			// console.log('nuntio.poll().ajax().error()');
			nuntio.mode = 'error';
			nuntio.error_count++;
			nuntio.stat('fail',ret + '; ' + err);
			// $('.chat-stat').html('Connection Error:' + nuntio.error_count + ' #' + this.readyState + '/' + e.eventPhase);
	})
	.always(function() {
		// console.log('nuntio.poll().ajax().complete()');
		if (nuntio.tick) clearTimeout(nuntio.tick);
		nuntio.tick = setTimeout(nuntio.poll,1234);
	});
};

// $('#chat-list').append('<div class="chat-line" id="l' + l + '">' + e.data + '</div>');
// $('#chat-list').append('<div class="chat-line">' + $('#chat-list').scrollTop() + '</div>');
// $('#chat-list').append('<div class="chat-line">' + parseInt($('#chat-list').scrollTop()) + ' of ' + $('#chat-list').height() + '</div>');

// Failed
// var x = $('#chat-list .chat-line:last').position();
// $('#chat-list').scrollTop(x.top);
// Also Failed
// document.getElementById('l' + l).scrollIntoView();
// $('#chat-list').scrollTop( parseInt($('#chat-list').scrollTop()) );

/**

*/
nuntio.recv = function(res,ret,xhr)
{
	console.log('nuntio.recv(' + res + ', ' + ret + ', ' + xhr + ')');
	nuntio.stat('warn','<i class="icon-comment" title="recv()"></i>');
	nuntio.line.enable();

	// Check Response, Promote Failure to Empty Fakes
	if ('undefined' == typeof res) {
		res = {
			code:100,
			text:'Internal Response',
			list:[]
		};
	}
	if ('undefined' == typeof res.list) res.list = [];
	if (null == res.list)  res.list = [];

	var c = parseInt(res.list.length) || 0;
	if (c) {
		nuntio.beep();
		for (var i = 0; i<c; i++) {

			var line = res.list[i];

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

	nuntio.stat('info','<i class="icon-comments-alt" title="ready"></i>');
}

/**
	Send Data to Server
*/
nuntio.send = function(t)
{
	nuntio.stat('good','<i class="icon-comment" title="send()"></i>');
	nuntio.line.disable();

	switch (t) {
	case '/wipe':
		$('#chat-list').empty();
		return;
	}
	var arg = {
		room:nuntio.room,
		text:t
	};

	$.post('/chat/post',arg,nuntio.recv);
}
