<?php
/**

*/

namespace Nuntio;

class Chat_Line
{
	private function __construct() { /* No */ }

	public static function format($raw)
	{
		$ret = trim($raw);

		// Backticks become <code>
		$ret = preg_replace('/\s`(.+?)`\s/',' <code>`$1`</code> ',$ret);

		// * become <strong>
		$ret = preg_replace('/\s\*(.+?)\*\s/',' <strong>*$1*</strong> ',$ret);

		// General Hyperlinks
		$ret = preg_replace('|(https?://[\w\#\%\+\-\.\/\;\=\?\~]+)|','<a href="$1" target="_blank">$1</a>',$ret);

		return $ret;
	}

}