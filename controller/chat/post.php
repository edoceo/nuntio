<?php
/**
    http://developer.asana.com/documentation/

    curl -v --data 'text=Some Text' http://nunt.io/~nuntio/post

    Return JSON Array(
        'code' => 201
        'text' => 'Some Text',
        'line' => array(Line Objects)
    );
*/

namespace Nuntio;

if (empty($_SESSION['nick'])) {
    $_SESSION['nick'] = 'Guest ' . substr(uniqid(),-4);
}

session_write_close();

if (empty($_POST['room'])) {
    _bail('Missing Parameter: room');
}

$room = Nuntio::getRoom($_POST['room']);
if (empty($room['_id'])) {
    _bail('Invalid Room');
}
$_ENV['room_id'] = $room['_id'];
$_ENV['room_name'] = $room['name'];

$code = 200;

$line_list = array();

$msg = array();
$msg['nick'] = '-Nick-';
$msg['kind'] = 'hook';

// @todo Lookup
$_SESSION['uid'] = '1';
if (empty($_SESSION['uid'])) {

    $user = $_SERVER['PHP_AUTH_USER'];
    $pass = $_SERVER['PHP_AUTH_PW'];

} else {
    $msg['room_id'] = $_ENV['room_id'];
    $msg['nick'] = $_SESSION['nick'];
}

// Update my Room+User Data
if (!empty($msg['room'])) {

//     $q = array(
//         'user_id' => $_SESSION['uid'],
//         'room_id' => $_SESSION['room_id'],
//     );
//     $chk = $this->mdb->find_one('chat_room_user',$q);
//     if (empty($chk)) {
//         // $this->mdb->find_one('chat_room_user',array(
//         //     'user_id' => $_SESSION['uid'],
//         //     'user_name' => $_SESSION['user']['username'],
//         //     'room_id' => $_SESSION['room_id'],
//         //     'room_name' => $_SESSION['room_name'],
//         // ));
//     }
//     // $q = array('_id' => $_SESSION['room_id']);
//     // $this->mdb->find_and_modify('chat_room',$q,$u);

}

// $log = print_r($_GET,true);
// $log.= print_r($_POST,true);
// $log.= print_r($_SERVER,true);
// file_put_contents('/tmp/nuntio-post',$log);

// Format Message
if (!empty($_POST['text'])) {
    $msg['text'] = $_POST['text'];
}

$line_list = array();

// Parse Message
$pre = substr($msg['text'],0,1);
switch ($pre) {
// Room Commands
case '!':
    $line_list = Chat_Room::_onRoomCommand($msg['text']);
    if (empty($line_list)) $line_list = "Command: {$msg['text']} was not understood";
    break;
case '#': // Always means a Ticket/Task Number?
    $q = array('room_id' => $_POST['room']);
    $res = Nuntio::$mdb->find('chat_task',$q);
    foreach ($res as $rec) {
        $line_list[] = array(
            '_id' => $rec['_id'],
            'time' => radix_format::niceDate($rec['_id']->getTimestamp()),
            'user_id' => strval($rec['user_id']),
            'text' => $rec['text'],
        );
    }
    break;
// A Vote Up
case '+':
    break;
// A Vote Down
case '-':
    break;
// System Commands
case '/': 
    $line_list = Chat_Room::_onSystemCommand($msg['text']);
    // if (!empty($res)) {
    //     $from->send($res);
    // }
    // return;
    break;
// Help
case '?':
    $line_list = Chat_Room::help();
    break;
// Talk Directly to user
case '@':
    // $from->send('Cannot Talk Directly to users yet');
    break;
// Search
case '~':
    $line_list = 'Should search for: <em>' . trim(substr($msg['text'],1)) . '</em>';
    break;
default:
    $code = 201;
    if (empty($msg['room_id'])) {
        throw new Exception('Cannot add lines w/o a Room ID');
    }
    // Nuntio::addLine($line);
    Nuntio::$mdb->insert('chat_line',$msg);
    break;
}

// Publish to Redis
// $this->_r->publish('chat',json_encode($msg));

// $log.= print_r($msg,true);
// $msg = WebSocketMessage::create(json_encode($msg));

// Connect to WebSocket
// try {
//     // Acutally, doesn't throw exceptions - does errors
//     $wsc = new WebSocket('ws://127.0.0.1:8080');
//     $ret = $wsc->open();
//     $log.= " Open Says: $ret\n";
//     // return value from here is bullshit
//     $ret = $wsc->sendMessage($msg);
// //    if (empty($ret)) {
// //        header('HTTP/1.1 500 Server Error');
// //        die(json_encode(array(
// //            'code' => 500,
// //            'text' => 'Server Error',
// //        )));
// //    }
// //    $log.= " Send Says: $ret\n";
// } catch (Exception $e) {
//     // Ignore
//     $log.= $e->toString();
// }
// 
// header('HTTP/1.1 201 Created');

if (!is_array($line_list)) {
    $line_list = array(array(
        '_id' => null,
        'nick' => 'Bot',
        'time' => $_SERVER['REQUEST_TIME'],
        'text' => $line_list,
    ));
}

header('Content-Type: application/json');

die(json_encode(array(
    'code' => 201,
    'text' => 'Message Created',
    'list' => $line_list,
)));

function _bail($text)
{
    header("HTTP/1.0 400 $text");
    header('Content-Type: application/json');
    die(json_encode(array(
        'code' => 400,
        'text' => $text,
    )));
}