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
	<h2>Tools</h2>
	<h2>User List</h2>
	<h2>Hashtags</h2>
	<h2>Attachments</h2>
	<div><button class="exec">Call</button></div>
	<div><button class="info">Call</button></div>
	<div><button class="good">Call</button></div>
	<div><button class="warn">Call</button></div>
	<div><button class="fail">Fail</button></div>
	<div><pre id="chat-logs"></pre></div>
</div>

<script>
$(window).resize(function() {
	nuntio.do_size();
});
nuntio.do_size();
nuntio.join('<?php echo $_ENV['room_id']; ?>');
$('#chat-foot input').focus();
</script>
