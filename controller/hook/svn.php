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
$msg['text'] = 'Commit: ' . $_POST['youngest'] . ' to ' . $_POST['project'] . ' by ' . $_POST['author'] . '<br>';
$msg['text'].= '<strong>' . trim($_POST['log']) . '</strong><br>';
$msg['text'].= trim($_POST['changed']);
// $msg['text'].= '<br>' . print_r($_POST,true);

Nuntio::$mdb->insert('chat_line',$msg);

header('HTTP/1.1 201 Created');
header('Content-Type: application/json');

die(json_encode(array(
	'code' => 201,
	'text' => 'Message Created',
)));
