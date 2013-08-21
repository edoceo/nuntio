<?php

namespace Nuntio;

class Chat_Room
{
	/**

	*/
	public static function _onRoomCommand($msg)
	{
		$pre = strtok($msg,' ');
		switch ($pre) {
		case '!info':
			return $_SESSION;
			break;
		case '!seen': // When User Last Seen
			break;
		case '!last': // Show ME last X lines
			$idx = intval(strtok(''));
			$idx = max(0,min(50,$idx));

			$q = array(
				'room_id' => $_SESSION['room_id'],
			);
			$q = array();

			$res = Nuntio::$mdb->find('chat_line',$q);
			$res->sort(array('_id' => -1));
			$res->limit($idx);
			// $ret = iterator_to_array($ret);
			$ret = array();
			foreach ($res as $rec) {
				if (empty($rec['text'])) $rec['text'] = null;
				$ret[] = array(
					'_id' => strval($rec['_id']),
					'time' => strftime('%m/%d %H:%M',$rec['_id']->getTimestamp()),
					'nick' => $rec['nick'],
					'text' => Chat_Line::format($rec['text'])
				);
			}
			$ret = array_reverse($ret);
			return $ret;
			
		case '!task': // Create a Task
			// !task Some Text Until @assign_to_user
			// !task @some_user Title of the Task
			// API to Make Task?  API to Assign Task?  API To Lookup Task?
			$rec = array(
				'user_id' => $_SESSION['uid'],
				'room_id' => $_SESSION['room_id'],
				'time' => $_SERVER['REQUEST_TIME'],
				'text' => strtok(''),
			);
			$res = Nuntio::$mdb->insert('chat_task',$rec);
			return "Task #???? Created" . $res;
			break;
		case '!json':
			return 'Jason is not here today';
			break;
		// Start a Vote
		case '!vote':
			break;
		}
		return 'FAIL: Invalid Command';
	}

	/**
		
	*/
	public static function _onSystemCommand($msg)
	{
		$pre = strtok($msg,' ');
		switch ($pre) {
		case '/bark':
			return 'BARK BARK BARK!';
			// return '<audio autoplay src=""></audio>';
			break;

		case '/info':
			$room = Nuntio::getRoom($_POST['room']);
			$ret = array();
			$ret[] = 'Room: ' . $room['name'] . ' with ID:' . strval($room['_id']);
			$ret[] = print_r($room,true);
			if (!empty($_SESSION['uid'])) {
				$ret[] = 'User: ' . print_r($_SESSION,true);
			}
			$ret[] = 'Nick: ' . $_SESSION['nick'];
			return implode('<br>',$ret);
			break;

		case '/join': // Commands

			// Clean room name
			$room = strtok('');
			$room = preg_replace('/[^\w]+/',null,$room);
			if (empty($room)) {
				return 'FAIL: Invalid room';
			}

			$room = Nuntio::$mdb->find_one('chat_room',array('name' => $room));
			if (empty($room)) {
				return 'FAIL: You may not create room';
			}

			// $q = array('_id' => '000');
			// $u = array('$set' => array('room_id' => $room['_id']));
			// $mdb->find_and_modify('chat_user',$q,$u);
			$ret[] = 'You have joined: ' . $room['name'];
//			 // $this->_client_data[ $ctx['conn'] ]['room'] = $room['name'];
//			 // Find and Modify?
// 
//			 // Previous 20 Lines
//			 $line_list = $mdb->find('chat_room_line',array(
//				 'room_id' => $room['_id'],
//			 ));
//			 $line_list->sort(array('_id' => -1));
//			 $line_list->limit(20);
//			 foreach ($line_list as $line) {
//				 $ret[] = $line['line'];
//			 }
			return implode('<br>',$ret);
			break;
		//case '/list': // User List
		//	$res = $mdb->find('chat_user',array('room' => $ctx['room']));
		//	// Client Information
		//	$res = iterator_to_array($res);
		//	$ret = print_r($res,true);
		//	return nl2br($ret);
		//	break;
		case '/kick': // Kick someone out
			break;
		case '/nick': // Set Nickname
			$nick = strtok('');
			if (empty($_SESSION['uid'])) {
				return 'Invalid Session State';
			}
			$q = array('_id' => $_SESSION['uid']);
			$u = array('$set' => array('nick' => $nick));
			Nuntio::$mdb->find_and_modify('chat_user',$q,$u);
			return "Nick: $nick";
			break;
		}
		return 'FAIL: Invalid Command';
	}
	
	static function help()
	{
		$ret = array();
		$ret[] = 'First character means someting';
		$ret[] = '! Room Commands';
		$ret[] = '# Ticket Information';
		$ret[] = '+ Vote Up';
		$ret[] = '- Vote Down';
		$ret[] = '/ Operator/System Commands';
		$ret[] = '? Help';
		$ret[] = '@ Direct Messaging';
		$ret[] = '~ Perform a Search';
		$ret[] = '';
		$ret[] = 'Operator Commands';
		$ret[] = '/join <em>room</em>';
		$ret[] = '/info';
		$ret[] = '/kick';
		$ret[] = '/nick';
		$ret[] = '';
		$ret[] = 'General User Commands';
		$ret[] = '!seen';
		$ret[] = '!last [1-50]';
		$ret[] = '!task';
		$ret[] = '!vote <em>topic</em>';
		$ret[] = '';
		$ret[] = 'Direct Message';
		$ret[] = '@user some direct message here';
		$ret[] = '';
		$ret[] = 'Search:';
		$ret[] = '~some terms';
		return '<pre>' . implode('<br>',$ret) . '</pre>';
	}

	/**
		Create a Nicely Formatted Name
	*/
	static function cleanName($n)
	{
		$n = trim($n);
		$n = preg_replace('/[^\w\.\-]+/',null,$n);
		$n = strtolower($n);
		return $n;
	}
}