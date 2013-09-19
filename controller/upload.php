<?php
/**
	Receives the File Upload
*/

namespace Nuntio;

if (empty($_GET['r'])) {
	throw new Exception('Invalid Room');
}

if (empty($_GET['n'])) {
	throw new Exception('Invalid Name');
}

// $h = fopen('php://input','r');
// $buf = stream_get_contents($h);

Nuntio::$mdb->insert('chat_line',array(
	'room_id' => new \MongoId($_GET['r']),
	'user_id' => $_SESSION['uid'],
	'nick' => $_SESSION['nick'],
	'text' => 'Uploaded File: ' . $_GET['n'],
));

header('HTTP/1.0 201 Created');
header('Content-Type: application/json');

die(json_encode(array(
	'code' => 201,
	'text' => 'Message Created',
	'list' => $line_list,
)));

exit(0);