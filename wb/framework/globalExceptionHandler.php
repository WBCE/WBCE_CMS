<?php
/**
 * @category        WebsiteBaker
 * @package         WebsiteBaker_core
 * @author          Werner v.d.Decken
 * @copyright       WebsiteBaker.org e.V.
 * @link            http://websitebaker2.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @version         $Id: class.order.php 1487 2011-08-10 13:20:15Z DarkViper $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/framework/class.order.php $
 *
 * Global exception-handler
 * This module will activate a global exception handler to catch all thrown exceptions
 *
 */
/**
 * define several default exceptions directly to prevent from extra loading requests
 */

/**
 * define Exception to show error after accessing a forbidden file
 */
	class IllegalFileException extends LogicException {
		public function __toString() {
			$file = str_replace(dirname(dirname(__FILE__)), '', $this->getFile());
			$out  = '<div style="color: #ff0000; text-align: center;"><br />';
			$out .= '<br /><br /><h1>Illegale file access</h1>';
			$out .= '<h2>'.$file.'</h2></div>';
			return $out;
		}
	} // end of class

/**
 *
 * @param Exception $e
 */
	function globalExceptionHandler($e) {
		// hide server internals from filename where the exception was thrown
		$file = str_replace(dirname(dirname(__FILE__)), '', $e->getFile());
		// select some exceptions for special handling
		if ($e instanceof IllegalFileException) {
			$sResponse  = $_SERVER['SERVER_PROTOCOL'].' 403 Forbidden';
			header($sResponse);
			echo $e;
		}else {
		// default exception handling
			$out  = 'There was an unknown exception:'."\n";
			$out .= $e->getMessage()."\n";
			$out .= 'in line ('.$e->getLine().') of ('.$file.')'."\n";
			echo $out;
		}
	}
/**
 * now activate the new defined handler
 */
	set_exception_handler('globalExceptionHandler');
