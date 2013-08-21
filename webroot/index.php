<?php
/**
	@file
	@brief Nuntio Front Controller
*/

define('APP_INIT',microtime(true));
define('APP_ROOT',dirname(dirname(__FILE__)));
define('APP_NAME','Nuntio');

require_once(APP_ROOT . '/boot.php');
error_reporting((E_ALL | E_STRICT) ^ E_NOTICE);

$info = array();
$info[] = radix::init(); // Good
$info[] = radix::route('~(?<room>\w{3,32})','/chat');
$info[] = radix::route('~(?<room>\w{3,32})/post','/chat/post');
$info[] = radix::route('~(?<room>\w{3,32})/hook/(\w+)','/hook/$2');
// http://nunt.io/~nuntio/hook/svn

$info[] = radix::stat(); // Empty
$info[] = radix::exec(); // 404
$info[] = radix::view(); // 404
$info[] = radix::info();
$info[] = radix::send();

echo "<!--\n";
print_r($info);
echo "\n-->";
