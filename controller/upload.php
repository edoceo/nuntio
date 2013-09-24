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

// /usr/lib/libreoffice/program/soffice.bin --headless --nologo --nofirststartwizard --accept=socket,host=127.0.0.1,port=8100;urp
// /opt/libreoffice3.4/program/soffice --headless --nologo --nofirststartwizard -convert-to $extension.pdf "$1" -outdir $folder

$ih = fopen('php://input','r');
$fn = tempnam(APP_ROOT . '/var','upload');
$oh = fopen($fn,'a');
$fs = 0;
while ($x = fread($ih,1024)) {
	$fs += fwrite($oh,$x);
}
fclose($ih);
fclose($oh);

$name = $_GET['n'];
$type = trim(shell_exec('file -ib ' . escapeshellarg($fn)));
$size = $fs;

Nuntio::$mdb->insert('chat_line',array(
	'room_id' => new \MongoId($_GET['r']),
	'user_id' => $_SESSION['uid'],
	'nick' => $_SESSION['nick'],
	'text' => 'Uploaded File: ' . $name . ' (' . $type . ')',
));

header('HTTP/1.0 201 Created');
header('Content-Type: application/json');

die(json_encode(array(
	'code' => 201,
	'text' => 'Message Created',
	'list' => $line_list,
)));

exit(0);