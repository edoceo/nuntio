<?php
/**
	Receives the File Upload
*/

namespace Nuntio;

session_write_close();

if (empty($_GET['r'])) {
	throw new Exception('Invalid Room');
}

if (empty($_GET['n'])) {
	throw new Exception('Invalid Name');
}

// /usr/lib/libreoffice/program/soffice.bin --headless --nologo --nofirststartwizard --accept=socket,host=127.0.0.1,port=8100;urp
// /opt/libreoffice3.4/program/soffice --headless --nologo --nofirststartwizard -convert-to $extension.pdf "$1" -outdir $folder

$ih = fopen('php://input','r');
$fn = tempnam(APP_ROOT . '/var',null);
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

$file = array(
	'_id' => new \MongoId(),
	'room_id' => new \MongoId($_GET['r']),
	'user_id' => $_SESSION['uid'],
	'nick' => $_SESSION['nick'],
	'path' => basename($fn),
	'name' => $name,
	'type' => $type,
	'size' => $size,
);
Nuntio::$mdb->insert('chat_file', $file);

$line = array(
	'room_id' => new \MongoId($_GET['r']),
	'user_id' => $_SESSION['uid'],
	'file_id' => $file['_id'],
	'nick' => $_SESSION['nick'],
	'text' => 'Uploaded: <a href="/file/download?id=' . strval($file['_id']) . '">' . $name . '</a> (' . $type . ') ' . \radix_format::niceSize($size),
);
Nuntio::$mdb->insert('chat_line', $line, array('safe' => true));

// Trigger Conversion
do_convert($file['name'],$fn);

header('HTTP/1.0 201 Created');
header('Content-Type: application/json');

die(json_encode(array(
	'code' => 201,
	'text' => 'Message Created',
)));

function do_convert($name,$path)
{
	// @see http://ask.libreoffice.org/en/question/2641/convert-to-command-line-parameter/
	// @see http://cgit.freedesktop.org/libreoffice/core/tree/filter/source/config/fragments/filters
	$cmd = 'soffice ';
	$cmd.= '--headless ';
	$cmd.= '--invisible ';
	$cmd.= '--nologo ';
	$cmd.= '--nofirststartwizard ';
	// $cmd.= '--norestore ';
	$cmd.= '--convert-to pdf ';
	$cmd.= '--outdir ' . escapeshellarg(dirname($path)) . ' ';
	$cmd.= "$path ";
	putenv('HOME=/tmp');
	$buf = shell_exec("set >/tmp/soffice.env; $cmd >/tmp/soffice.out 2>&1");

	$file = array(
		'_id' => new \MongoId(),
		'room_id' => new \MongoId($_GET['r']),
		'user_id' => $_SESSION['uid'],
		'nick' => 'Nuntio',
		'path' => basename("$path.pdf"),
		'name' => preg_replace('/\.\w+$/', '.pdf', $name),
		'type' => 'application/pdf',
		'size' => filesize("$path.pdf"),
	);
	Nuntio::$mdb->insert('chat_file', $file);

	// Two Chat Lines
	$line = array(
		'room_id' => new \MongoId($_GET['r']),
		'user_id' => $_SESSION['uid'],
		'file_id' => $file['_id'],
		'nick' => $_SESSION['nick'],
		'text' => 'Converted: <a href="/file/download?id=' . strval($file['_id']) . '">' . $file['name'] . '</a> (' . $file['type'] . ') ' . \radix_format::niceSize($file['size']) . '<br>' . $cmd . '<pre>' . $buf . '</pre>',
	);

	Nuntio::$mdb->insert('chat_line', $line);
}

