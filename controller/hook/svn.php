<?php
/**
	Specify this Hook as http://nunt.io/hook/svn?room=ROOM_ID

	SVN Hook
	Cloudforge 
		@see https://help.cloudforge.com/entries/22298336-ticket-integration
		@see https://help.cloudforge.com/entries/22483702-using-web-hooks

*/

namespace Nuntio;

// if ([HTTP_USER_AGENT] => CloudForge Notifier/1.1)

$room = Nuntio::getRoom($_GET['room']);
if (empty($room['_id'])) {
	header('HTTP/1.1 400 Invalid Room');
	die('Invalid Room');
}

$msg['room_id'] = $room['_id'];
$msg['nick'] = 'SVN Hook';
$msg['text'] = 'Commit: ' . $_POST['youngest'] . ' by ' . $_POST['author'] . ' to project ' . $_POST['project'] . '<br>' . $_POST['log'];
$msg['text'].= nl2br($_POST['changed']);
$msg['text'].= '<br>' . print_r($_POST,true);

Nuntio::$mdb->insert('chat_line',$msg);

header('HTTP/1.1 201 Created');
header('Content-Type: application/json');

die(json_encode(array(
	'code' => 201,
	'text' => 'Message Created',
)));
