<?php
/**
	@file
	@brief Nuntio Application Helper
*/

namespace Nuntio;

class Nuntio
{
	public static $mdb;
	
	
	/**
		@param $x Room ID or Name
	*/
	public static function getRoom($x)
	{
		$q = array('name' => Chat_Room::cleanName($x));
		if (preg_match('/^[0-9a-f]{24}$/',$x)) {
			$q = array(
				'_id' => new MongoId($x),
			);
		}
		$r = self::$mdb->find_one('chat_room',$q);
		return $r;
	}
}
