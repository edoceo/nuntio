<?php
/**

*/

namespace Nuntio;

class Chat_Line
{
	private function __construct() { /* No */ }

	public static function format($line)
	{
		$line = trim($line);

		// General Hyperlinks
		// $ret = preg_replace('|(https?://[^ <]+)\.?|','<a href="$1" target="_blank">$1</a>',$ret);
		$line = self::_link($line);

		// Backticks become <code>
		$line = preg_replace('/\s`(.+?)`\s/',' <code>`$1`</code> ',$line);

		// * become <strong>
		$line = preg_replace('/\s\*(.+?)\*\s/',' <strong>*$1*</strong> ',$line);

		$line = nl2br($line);

		return $line;
	}

	/**
		Find and Convert Links
	*/
	private static function _link($text)
	{
		if (preg_match_all('|\b(https?://[^ ]+)|',$text,$m)) {
			$idx = __LINE__;
			$set = array();
			foreach ($m[1] as $uri) {
				$tok = sprintf('uri%08x',++$idx);
				$set[$tok] = $uri;
				$text = str_replace($uri,$tok,$text);
			}
			foreach ($set as $tok=>$uri) {
				// Handle Trailing Puncuation
				if (preg_match('/([\.\+\'\"])$/',$uri,$m)) {
					$uri = substr($uri,0,-1);
					$rep = '<a href="' . $uri . '" target="_blank">' . preg_replace('|^https?://|',null,$uri) . '</a>' . $m[1];
				} else {
					$rep = '<a href="' . $uri . '" target="_blank">' . preg_replace('|^https?://|',null,$uri) . '</a>';
				}
				$text = str_replace($tok,$rep,$text);
			}
		}
		return $text;
	}

}