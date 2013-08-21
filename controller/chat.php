<?php
/**
	@file
	@brief Chat Room Controller
*/

namespace Nuntio;

switch ($_POST['a']) {
case 'join':

    // Find User
    $user = radix_filter::email($_POST['user']);
    if (!empty($user)) {
        $res = Nuntio::$mdb->find_one('contact',array('username' => $user));
        $_SESSION['uid'] = $res['_id'];
        $_SESSION['user'] = $res;
    } else {
        $_SESSION['uid'] = null;
        $_SESSION['user'] = array(
        	'nick' => 'Guest 1234',
		);
    }

    // Clean Name
    $name = Chat_Room::cleanName($_POST['room']);
    if (empty($name)) {
        throw new \Exception('FAIL: Invalid room');
    }
    if ($name != $_POST['room']) {
        throw new \Exception('FAIL: The room name contains invalid characters'); 
    }

    // Find Room
    $room = Nuntio::getRoom($name);
    if (empty($room)) {
        if (!empty($_SESSION['user']['room_create'])) {
            $room = array();
            $room['_id'] = new MongoId();
            $room['user_id'] = $_SESSION['uid'];
            $room['name'] = $name;
            Nuntio::$mdb->insert('chat_room',$room);
        } else {
            // radix::trace($_SESSION);
            throw new \Exception('Could not find or create this room the room');
        }
    }

	\radix::redirect(sprintf('/~%s',$room['name']));

    break;
}

if (empty($_GET['room'])) {
    \radix_session::flash('fail','Invalid Room ID');
    \radix::redirect('/');
}

// Load the Room
if (empty($_ENV['room_id'])) {
    $room = Nuntio::getRoom($_GET['room']);
    $_ENV['room_id'] = $room['_id'];
	$_ENV['room_name'] = $room['name'];
}
if (empty($_ENV['room_id'])) {
    throw new \Exception('Invalid Room ID, Need to Get a Room');
}

// $cmd = dirname(dirname(__FILE__)) . '/bin/chat-server.php >/tmp/nuntio-chat-server.log 2>&1 &';
// shell_exec($cmd);
