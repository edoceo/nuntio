<?php
/**
    @file
    @brief Nuntio - Latin: announce, report, relate, herald, give forth, intimate authoritatively;
            http://nunt.io
*/

namespace Nuntio;

use radix;
// radix::auto();

error_reporting(-1);

require_once(APP_ROOT . '/vendor/autoload.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Session.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/db/mongo.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Filter.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/Format.php');
require_once(APP_ROOT . '/vendor/edoceo/radix/Radix/db/redis.php');

require_once(APP_ROOT . '/lib/Chat.php');
require_once(APP_ROOT . '/lib/Chat_Room.php');
require_once(APP_ROOT . '/lib/Chat_Line.php');

// require_once('Radix/db/mongo.php');
radix\radix_db_mongo::init(array(
    'hostname'=>'localhost',
    'database'=>'nuntio'
));
radix\radix_db_redis::init(array(
    'hostname' => 'localhost',
    'database' => 0,
));

function html($x) { return htmlspecialchars($x,ENT_QUOTES,'utf-8',false); }
