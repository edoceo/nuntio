<?php
/**
	@file
	@brief Nuntio - Latin: announce, report, relate, herald, give forth, intimate authoritatively;
			http://nunt.io
*/

namespace Nuntio;
use radix;

error_reporting(-1);

if (!is_file(APP_ROOT . '/vendor/autoload.php')) {
	header('Content-Type: text/plain');
	echo "Autoloader not found, add Composer and Update\n";
	die(file_get_contents(__DIR__ . '/README.md'));
}
require_once(APP_ROOT . '/vendor/autoload.php');

require_once(APP_ROOT . '/vendor/edoceo/radix/Radix.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Session.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Filter.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Format.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/db/mongo.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/db/redis.php');

require_once(APP_ROOT . '/lib/Nuntio.php');
// require_once(APP_ROOT . '/lib/Chat.php');
require_once(APP_ROOT . '/lib/Chat_Room.php');
require_once(APP_ROOT . '/lib/Chat_Line.php');

// Load Config
$cfg = parse_ini_file(APP_ROOT . '/boot.ini', true, INI_SCANNER_RAW);
radix\radix_db_mongo::init($cfg['mongo']);
radix\radix_db_redis::init($cfg['redis']);

Nuntio::$mdb = new radix\radix_db_mongo();

function html($x) { return htmlspecialchars($x,ENT_QUOTES,'utf-8',false); }
