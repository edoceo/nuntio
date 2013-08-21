<?php
/**
	@file
	@brief Finds the First Mongo chat_line > this one
*/

namespace Nuntio;

session_write_close();
header('Content-Type: application/json');

$room = Nuntio::getRoom($_GET['room']);

$q = array(
	'room_id' => $room['_id'],
);
if (!empty($_GET['line'])) {
	$q['_id'] = array(
		'$gt' => new \MongoId($_GET['line'])
	);
}

// Try Three Time to Get Stuff
$try = 0;
do {

	$res = $this->mdb->find('chat_line',$q);
	$res->sort(array('_id' => -1));
	$res->limit(1);

	if ($res->count(true)) {
		$ret = array();
		foreach ($res as $rec) {
			$rec['time'] = strftime('%m/%d %H:%M',$rec['_id']->getTimestamp());
			// $rec['rich'] = true;
			$rec['text'] = Chat_Line::format($rec['text']);
			$rec['_id'] = strval($rec['_id']);
			$ret[] = $rec;
		}
		die(json_encode($ret));
	}
	$try++;
	sleep($try);
} while ($try < 4);

// Empty
// die(json_encode(array('list' => array())));
die(json_encode(array(
	'code' => 200,
	'text' => 'No New Lines',
	'list' => null,
)));
