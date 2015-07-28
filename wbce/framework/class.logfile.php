<?php
/**
 *
 * @category        event logging
 * @package         core
 * @author          Independend-Software-Team
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.2
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: class.logfile.php 1533 2011-12-08 00:05:20Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/framework/class.logfile.php $
 * @lastmodified    $Date: 2011-12-08 01:05:20 +0100 (Do, 08. Dez 2011) $
 * @description     definition of all core constants.
 */

/**
 * Description of classlog
 *
 * @author wkl
 */
class LogFile {

	private $_fh;                  // file-handle for logfile
	private $_log_path;            // path to logfile
	private $_log_file;            // name of logfile
	private $_error = false;       // store internal errors
/*
 * class can not be instanciated standalone
 */
	protected function __construct( $log_file )
	{
		$this->_log_file = $log_file;
	}

/*
 * open the logfile for append
 */
	private function openLogFile()
	{
		$this->_fh = fopen($this->_log_path.$this->_log_file, 'ab');
		return isset($this->_fh);
	}
/*
 * provide read-only properties
 */
	public function __get($property)
	{
		switch(strtolower($property)):
			case 'error':
				return $this->_error;
				break;
			default:
				return null;
		endswitch;
	}
/*
 * flush and close logfile
 */
	private function closeLogFile()
	{
		if( isset($this->_fh) )
		{
			fflush($this->_fh);
			fclose($this->_fh);
			unset($this->_fh);
		}
	}

/*
 * @param  string $logdir: directory to place the logfile
 * @return bool: true if directory is valid and writeable
 * @description:
 */
	public function setLogDir( $logdir )
	{
		$this->_error = false;
		$retval = false;
		if( ($logdir = realpath($logdir)) )
		{
			$logdir = rtrim(str_replace('\\', '/', $logdir), '/');
			if( defined('WB_PATH') )
			{
				$sysroot = WB_PATH;
			}
			else
			{
				$script_filename = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
				$script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
				$sysroot = str_replace($script_name, '', $script_filename);
			}
			if( stripos($logdir, $sysroot) === 0 )
			{
				if( is_writable($logdir))
				{
					if( file_exists($logdir.'/'.$this->_log_file) )
					{
						if( is_writable($logdir.'/'.$this->_log_file) )
						{
							$this->_log_path = $logdir.'/';
							$retval = true;
						}else
						{
							$this->_error = 'existing logfile is not writable! ['.$logdir.$this->_log_file.']';
						}
					}
					else
					{
						$this->_log_path = $logdir.'/';
						$retval = true;
					}
				}else
				{
					$this->_error = 'access denied for directory ['.$logdir.']';
				}
			}else
			{
				$this->_error = 'logdir [ '.$logdir.' ] points outside of DOCUMENT_ROOT [ '.$sysroot.' ]';
			}
		}else
		{
			$this->_error = 'logdir can not be resolved ['.$logdir.']';
		}
		return $retval;
	}

/*
 * @param string $line: preformatted message to write into the logfile
 * @return none: an error will throw a exception
 */
	protected function writeRaw( $message )
	{
		array_unshift( $message, (defined($_SESSION['USER_ID'])?$_SESSION['USER_ID']:0) );
		array_unshift( $message, (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'#') );
		array_unshift( $message, gmdate(DATE_W3C) );
		if( isset($this->_log_path) ){
			if($this->openLogFile())
			{
				if( fputcsv($this->_fh, $message, ',', '"') !== false )
				{
					$this->closeLogFile();
				}
				else
				{
					throw new Exception('unable to append line ['.$this->_log_path.$this->_log_file.']');
				}
			}
			else
			{
				throw new Exception('unable to open logfile ['.$this->_log_path.$this->_log_file.']');
			}
		}else
		{
			throw new Exception('undefined path for logfile ['.$this->_log_file.']');
		}
	}

} // end of class

/*
 *  Errorlog handler
 */
class ErrorLog extends LogFile{

	private static $_instance;

	protected function __construct()
	{
		parent::__construct('error.log');
	}

	private function __clone() {}

    public static function handle()
    {
        if (!isset(self::$_instance)) {
            $c = __CLASS__;
            self::$_instance = new $c;
        }
        return self::$_instance;
    }

/*
 * @param string $message: message to write into the logfile
 * @param string $file: (optional) name of the file where the error occures
 * @param string $function: (optional) name of the function where the error occures
 * @param string $line: (optional) number of the line where the error occures
 * @return none: an error will throw a exception
 */
	public function write( $message, $file = '#', $function = '#', $line = '#' )
	{
		if( !is_array($message) )
		{
			$message = array($file, $function, $line, $message);
		}
		self::handle()->writeRaw( $message );
	}
} // end of class

/*
 *  Accesslog handler
 */
class AccessLog extends LogFile{

	private static $_instance;

	protected function __construct()
	{
		parent::__construct('access.log');
	}

	private function __clone() {}

    public static function handle()
    {
        if (!isset(self::$_instance)) {
            $c = __CLASS__;
            self::$_instance = new $c;
        }
        return self::$_instance;
    }

/*
 * @param string $message: message to write into the logfile
 * @return none: an error will throw a exception
 */
	public function write( $message )
	{
		if( !is_array($message) )
		{
			$message = array($message);
		}
		self::handle()->writeRaw( $message );
	}
} // end of class


?>
