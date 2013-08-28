<?php
/**
	@file
	@brief The Chatroom
*/

?>

<!-- Chat -->
<div id="chat">
	<div id="chat-head">
	<div>
		<h1>Nuntio ~<?php echo $_ENV['room_name']; ?></h1>
		<p>We are building a neat-o Team Media Communication platform.</p>
	</div>
	<div class="chat-stat"><i class="icon-refresh"></i></div>
	</div>
	<div id="chat-list">
		<!-- <div class="chat-line"></div> -->
	</div>
	<div id="chat-foot">
		<div>
			<textarea id="chat-foot-text" type="text" style="margin:0px;"></textarea>
		</div>
	</div>
</div>


<div id="tool">
	<h2 style="padding-top:0;">User List</h2>
	<div id="user-list"></div>
	<h2>Tools</h2>
	<h2>Hashtags</h2>
	<div id="htag-list"></div>
	<h2>Attachments</h2>
	<div><button class="exec">Call</button></div>
	<div><button class="info">Call</button></div>
	<div><button class="good">Call</button></div>
	<div><button class="warn">Call</button></div>
	<div><button class="fail">Fail</button></div>
	<div><pre id="chat-logs"></pre></div>
	<!-- @see http://www.trekcore.com/audio/ -->
	<audio id="chat-audio" preload="auto">
		<source src="http://www.trekcore.com/audio/computer/computerbeep_45.mp3"html>
	</audio>
</div>

<script>
$(window).resize(function() {
	nuntio.do_size();
});
nuntio.do_size();
nuntio.join('<?php echo $_ENV['room_id']; ?>');
$('#chat-foot input').focus();

$(function() {
	setInterval(function() {
		$.getJSON('/room/stat',{room:nuntio.room},function(res) {
			if (res.user) {
				$('#user-list').html(res.user);
			}
			if (res.tags) {
				$('#htag-list').html(res.tags);
			}
		});
	}, 5678);
});
</script>
