<?php
namespace Datec\DatecBlog\ViewHelpers\Format;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Formats a string value to shorten it to given length.
 * Basically a wrapper for PHP substr() with dotted end of text.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <dv:format.shortenStr length="*target length*" start="*starting point (optional)*">{text}</dv:format.shortenStr>
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the current date)
 * </output>
 */
class ShortenStrViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;
	
	/**
	 * Substring the given string
	 *
	 * @param int $length the length to shorten to
	 * @param string $str the string to be shortened (optional)
	 * @param int $start the starting point in string (optional, default = 0) 
	 * @return string
	 */
	public function render($length, $str = NULL,  $start = 0) {
		if ($str == NULL) {
			$str = $this->renderChildren();
		}
		
		return substr($str, $start, $length) . '...';
	}
}

?>