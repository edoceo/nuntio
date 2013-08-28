<?php
/**
	@file
	@brief Shows the List of Users
*/

namespace Nuntio;

if (empty($_GET['room'])) {
	_bail('Missing Parameter: room');
}

$room = Nuntio::getRoom($_GET['room']);
if (empty($room['_id'])) {
	_bail('Invalid Room');
}

$html = null;

$list = Nuntio::$mdb->find('contact',array()); // 'username' => $user));
foreach ($list as $u) {
	$html.= '<div>';
	$html.= html($u['username']);
	$html.= '</div>';
}

$ret = array(
	'user' => $html
);

$html = null;
$list = Nuntio::$mdb->find('chat_room_htag',array(
	'room_id' => $room['_id'],
));
foreach ($list as $u) {
	$html.= '#' . html($u['tag']) . ' ';
}
$ret['tags'] = $html;

die(json_encode($ret));

function _bail($text)
{
	header("HTTP/1.0 400 $text");
	header('Content-Type: application/json');
	die(json_encode(array(
		'code' => 400,
		'text' => $text,
	)));
}