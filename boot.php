<?php
/**
	@file
	@brief Nuntio - Latin: announce, report, relate, herald, give forth, intimate authoritatively;
			http://nunt.io
*/

namespace Nuntio;
use radix;

error_reporting(-1);

require_once(APP_ROOT . '/vendor/autoload.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Session.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Filter.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Format.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/db/mongo.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/db/redis.php');

require_once(APP_ROOT . '/lib/Nuntio.php');
require_once(APP_ROOT . '/lib/Chat.php');
require_once(APP_ROOT . '/lib/Chat_Room.php');
require_once(APP_ROOT . '/lib/Chat_Line.php');

// Load Config
$_ENV = parse_ini_file(APP_ROOT . '/boot.ini');
radix\radix_db_mongo::init($_ENV['mongo']);
radix\radix_db_redis::init($_ENV['redis']);

unset($_ENV['mongo']);
unset($_ENV['redis']);

Nuntio::$mdb = new radix\radix_db_mongo();

function html($x) { return htmlspecialchars($x,ENT_QUOTES,'utf-8',false); }
