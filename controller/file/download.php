<?php
/**
	Downloads a File
*/

namespace Nuntio;


// $file = sprintf('%s/var/%s',APP_ROOT,$_GET['f']);
// if (!is_file($file)) {
// 	\radix_session::flash('fail', 'Invalid File to Download');
// 	\radix::redirect();
// }

$file = Nuntio::$mdb->find_one('chat_file', array('_id' => new \MongoId($_GET['id'])));
// $type = trim(shell_exec('file -ib ' . escapeshellarg($file)));

// \radix::dump($file);

// exit(0);

// header('Content-Type: 
header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
header('Content-Type: ' . $file['type']);

readfile(sprintf('%s/var/%s',APP_ROOT, $file['path']));

exit(0);