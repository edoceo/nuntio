<?php
/**
	Specify this Hook as http://nunt.io/hook/git?room=ROOM_ID

	Git Hook
	github, gitorious, bitbucket

	@see https://help.github.com/articles/post-receive-hooks

*/

namespace Nuntio;

$room = Nuntio::getRoom($_GET['room']);
if (empty($room['_id'])) {
	header('HTTP/1.1 400 Invalid Room');
	die('Invalid Room');
}

$msg['room_id'] = $room['_id'];
$msg['nick'] = 'GIT Hook';

$push = json_decode($_POST['payload'],true);

// Basic Mesage
$msg['text'] = 'Git Push: ' . $push['repository']['name'] . ' <strong>' . $push['head_commit']['message'] . '</strong> by ' . $push['pusher']['name'] . '; ';
$msg['text'].= 'Head: ' . $push['head_commit']['url'] . '<br>';
$msg['text'].= 'Commits: ' . count($push['commits']) . ';<br>';

// Added Files
// Removed Files
$buf = array();
foreach ($push['commits'] as $c) {
	$buf += $c['added'];
}
if (count($buf)) $msg['text'].= 'Added: ' . implode(', ',$buf);

// Modified Files
$msg['text'].= 'Modified: ';
foreach ($push['commits'] as $c) {
	$msg['text'].= implode(', ',$c['modified']);
}
$msg['text'].= '<br>';

// Removed Files
$buf = array();
foreach ($push['commits'] as $c) {
	$buf += $c['removed'];
}
if (count($buf)) $msg['text'].= 'Removed: ' . implode(', ',$buf);

Nuntio::$mdb->insert('chat_line',$msg);

header('HTTP/1.1 201 Created');
header('Content-Type: application/json');

die(json_encode(array(
	'code' => 201,
	'text' => 'Message Created',
)));
