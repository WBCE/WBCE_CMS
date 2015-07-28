<?php
/**
 * @category     Core
 * @package      Core_security
 * @author       Werner v.d.Decken
 * @copyright    ISTeasy-project(http://isteasy.de/)
 * @license      Creative Commons BY-SA 3.0 http://creativecommons.org/licenses/by-sa/3.0/
 * @version      $Id$
 * @filesource   $HeadURL:$
 * @since        Datei vorhanden seit Release 2.8.2
 * @lastmodified $Date:$
 *
 * this class works with salted md5-hashes with several rounds. 
 * For backward compatibility it can compare normal md5-hashes also.
 * Minimum requirements: PHP 5.2.2 or higher
 *
 * *****************************************************************************
 * This class is based on the Portable PHP password hashing framework.
 * Version 0.3 / genuine. Written by Solar Designer <solar at openwall.com>
 * in 2004-2006 and placed in the public domain. Revised in subsequent years,
 * still public domain. There's absolutely no warranty.
 * The homepage URL for this framework is: http://www.openwall.com/phpass/
 * *****************************************************************************
 */
class PasswordHash {

	const SECURITY_WEAK      = 6;
	const SECURITY_MEDIUM    = 8;
	const SECURITY_NORMAL    = 10;
	const SECURITY_STRONG    = 12;
	const SECURITY_STRONGER  = 16;

	private $_itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	private $_iterationCountLog2 = 8;
	private $_portableHashes = true;
	private $_randomState = '';
	
	/**
	 * @param int $iterationCountLog2 number of iterations as exponent of 2
	 * @param bool $portableHashes TRUE = use MD5 only | FALSE = automatic
	 */
	public function __construct($iterationCountLog2, $portableHashes = true)
	{

		if ($iterationCountLog2 < 4 || $iterationCountLog2 > 31) {
			$iterationCountLog2 = 8;
		}
		$this->_iterationCountLog2 = $iterationCountLog2;
		$this->_portableHashes = $portableHashes;
		$this->_randomState = microtime();
		if (function_exists('getmypid')) {
			$this->_randomState .= getmypid();
		}
	}


	private function _getRandomBytes($count)
	{
		$output = '';
		if (is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
			$output = fread($fh, $count);
			fclose($fh);
		}
		if (strlen($output) < $count) {
			$output = '';
			for ($i = 0; $i < $count; $i += 16) {
				$this->_randomState = md5(microtime() . $this->_randomState);
				$output .= pack('H*', md5($this->_randomState));
			}
			$output = substr($output, 0, $count);
		}
		return $output;
	}

	private function _Encode64($input, $count)
	{
		$output = '';
		$i = 0;
		do {
			$value = ord($input[$i++]);
			$output .= $this->_itoa64[$value & 0x3f];
			if ($i < $count) {
				$value |= ord($input[$i]) << 8;
			}
			$output .= $this->_itoa64[($value >> 6) & 0x3f];
			if ($i++ >= $count) { break; }
			if ($i < $count) {
				$value |= ord($input[$i]) << 16;
			}
			$output .= $this->_itoa64[($value >> 12) & 0x3f];
			if ($i++ >= $count) { break; }
			$output .= $this->_itoa64[($value >> 18) & 0x3f];
		} while ($i < $count);
		return $output;
	}

	private function _GenSaltPrivate($input)
	{
		$output = '$P$';
		$output .= $this->_itoa64[min($this->_iterationCountLog2 + 5, 30)];
		$output .= $this->_Encode64($input, 6);
		return $output;
	}

	private function _CryptPrivate($password, $setting)
	{
		$output = '*0';
		if (substr($setting, 0, 2) == $output) {
			$output = '*1';
		}
		$id = substr($setting, 0, 3);
		# We use "$P$", phpBB3 uses "$H$" for the same thing
		if ($id != '$P$' && $id != '$H$') {
			return $output;
		}
		$count_log2 = strpos($this->_itoa64, $setting[3]);
		if ($count_log2 < 7 || $count_log2 > 30) {
			return $output;
		}
		$count = 1 << $count_log2;
		$salt = substr($setting, 4, 8);
		if (strlen($salt) != 8) {
			return $output;
		}
		# We're kind of forced to use MD5 here since it's the only
		# cryptographic primitive available in all versions of PHP
		# currently in use.  To implement our own low-level crypto
		# in PHP would result in much worse performance and
		# consequently in lower iteration counts and hashes that are
		# quicker to crack (by non-PHP code).
		$hash = md5($salt . $password, TRUE);
		do {
			$hash = md5($hash . $password, TRUE);
		} while (--$count);
		$output = substr($setting, 0, 12);
		$output .= $this->_Encode64($hash, 16);
		return $output;
	}

	/**
	 * calculate the hash from a given password
	 * @param string $password password as original string
	 * @return string generated hash | '*' on error
	 */
	public function HashPassword($password, $md5 = false)
	{
		if ($md5) { return(md5($password)); }
		$random = '';
		if (strlen($random) < 6) {
			$random = $this->_getRandomBytes(6);
		}
		$hash = $this->_CryptPrivate($password, $this->_GenSaltPrivate($random));
		if (strlen($hash) == 34) {
			return $hash;
		}
		# Returning '*' on error is safe here, but would _not_ be safe
		# in a crypt(3)-like function used _both_ for generating new
		# hashes and for validating passwords against existing hashes.
		return '*';
	}

	/**
	 * encodes the password and compare it against the given hash
	 * @param string $password clear password
	 * @param string $stored_hash the hash to compare against
	 * @return bool
	 */
	public function CheckPassword($password, $stored_hash)
	{
	// compare against a normal, simple md5-hash
		if(preg_match('/^[0-9a-f]{32}$/i', $stored_hash)) {
			return md5($password) == $stored_hash;
		}
	// compare against a rounded, salted md5-hash
		$hash = $this->_CryptPrivate($password, $stored_hash);
		if ($hash[0] == '*') {
			$hash = crypt($password, $stored_hash);
		}
		return $hash == $stored_hash;
	}
	/**
	 * generate a case sensitive mnemonic password including numbers and special chars
	 * makes no use of lowercase 'l', uppercase 'I', 'O' or number '0'
	 * @param int $length length of the generated password. default = 8
	 * @return string
	 */
	public static function NewPassword($length = self::SECURITY_MEDIUM)
	{
		$chars = array(
			array('b','c','d','f','g','h','j','k','l','m','n','p','r','s','t','v','w','x','y','z'),
			array('a','e','i','o','u'),
			array('!','-','@','_',':','.','+','%','/','*')
		);
		if($length < self::SECURITY_WEAK) { $length = self::SECURITY_WEAK; }
		$length = ceil($length / 2);
		$Password = array();
	// at first fill array alternating with vowels and consonants
		for($x = 0; $x < $length; $x++) {
			$char = $chars[0][rand(1000, 10000) % sizeof($chars[0])];
			$Password[] = $char == 'l' ? 'L' : $char;
			$Password[] = $chars[1][rand(1000, 10000) % sizeof($chars[1])];
		}
	// transform some random chars into uppercase
		$pos = ((rand(1000, 10000) % 3) + 1);
		while($pos < sizeof($Password)) {
			$Password[$pos] = ($Password[$pos] == 'i' || $Password[$pos] == 'o')
			                  ? $Password[$pos] : strtoupper($Password[$pos]);
			$pos += ((rand(1000, 10000) % 3) + 1);
		}
	// insert some numeric chars, between 1 and 9
		$specialChars = array();
		$specialCharsCount = floor(sizeof($Password) / 4);
		while(sizeof($specialChars) < $specialCharsCount) {
			$key = (rand(1000, 10000) % sizeof($Password));
			if(!isset($specialChars[$key])) {
				$specialChars[$key] = (rand(1000, 10000) % 9) + 1;
			}
		}
	// insert some punctuation chars, but not leading or trailing
		$specialCharsCount += floor((sizeof($Password)-1) / 6);
		while(sizeof($specialChars) < $specialCharsCount) {
			$key = (rand(1000, 10000) % (sizeof($Password)-2))+1;
			if(!isset($specialChars[$key])) {
				$specialChars[$key] = $chars[2][(rand(1000, 10000) % sizeof($chars[2]))];
			}
		}
		foreach($specialChars as $key=>$val) {
			$Password[$key] = $val;
		}

		return implode($Password);
	}

} // end of class
