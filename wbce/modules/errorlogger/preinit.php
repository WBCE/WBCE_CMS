<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga - www.dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.4+ / WB2.10+
 * @version         1.1.4.1
 * @lastmodified    July 30, 2022
 *
 */

/**
 * preinit.php is used to activate the errorhandler
 *
 * initialize.php is used to set the errorlevel to E_ALL regardless the WB setting
 *
 */
 
if (!defined('WB_PATH')) {
    die("Go");
}


$logDir = WB_PATH.'/var/logs';
if (!is_dir($logDir)) {
	mkdir($logDir, 0777, true);
}
$errorLogFilename = $logDir . '/php_error.log.php';
if (!file_exists($errorLogFilename)) {
    $sTmp = '<?php die(\'No access\'); ?>created: ['.date('c').']'.PHP_EOL;
    file_put_contents($errorLogFilename, $sTmp, FILE_APPEND);
}

class WBCE_Error
{
    private $errorLogFilename;
	private $url;

    public function __construct($errorLogFilename)
    {
        ini_set("display_errors", "off");
        ini_set('log_errors', 0);
        ini_set('error_log', $errorLogFilename);
		error_reporting(E_ALL);
        $this->errorLogFilename = $errorLogFilename;
        set_error_handler(array($this, 'scriptError'));
        set_exception_handler(array($this, 'exceptionError'));
        //register_shutdown_function(array($this, 'shutdown'));
		 $this->url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function scriptError($errno, $errstr, $errfile, $errline)
    {
        if (0 == error_reporting()) { // Error reporting is currently turned off or suppressed with @
            return;
        }
		if (4437 == error_reporting()) { // Error reporting is suppressed with @ php 8
            return;
        }																				
        
        //if($errno == E_DEPRECATED) return;
        //if($errno == E_USER_DEPRECATED) return;
        
        switch ($errno) {
            case E_ERROR:               $errseverity = "Error";             break;
            case E_WARNING:             $errseverity = "Warning";           break;
            case E_NOTICE:              $errseverity = "Notice";            break;
            case E_CORE_ERROR:          $errseverity = "Core Error";        break;
            case E_CORE_WARNING:        $errseverity = "Core Warning";      break;
            case E_COMPILE_ERROR:       $errseverity = "Compile Error";     break;
            case E_COMPILE_WARNING:     $errseverity = "Compile Warning";   break;
            case E_USER_ERROR:          $errseverity = "User Error";        break;
            case E_USER_WARNING:        $errseverity = "User Warning";      break;
            case E_USER_NOTICE:         $errseverity = "User Notice";       break;
            //case E_STRICT:              $errseverity = "Strict Standards";  break;
            case E_RECOVERABLE_ERROR:   $errseverity = "Recoverable Error"; break;
            case E_DEPRECATED:          $errseverity = "Deprecated";        break;
            case E_USER_DEPRECATED:     $errseverity = "User Deprecated";   break;
            default:                    $errseverity = "Error";             break;
        }

        $str_err = debug_backtrace();

        $x = sizeof($str_err) -1;
        $x = $x < 2 ? $x : 2;
        if (!$str_err[$x]['class']) {
            if ((substr($str_err[$x]['function'], 0, 7) == 'include') || (substr($str_err[$x]['function'], 0, 7) == 'require')) {
                $str_err[$x]['function'] = '';
            }
        }
		
        $out = date('c').' '.'['.$errseverity.'] '.str_replace(dirname(dirname(__DIR__)), '', $errfile).':['.$errline.'] '
            . ' from '.str_replace(dirname(dirname(__DIR__)), '', $str_err[$x]['file']).':['.$str_err[$x]['line'].'] '
            . (@$str_err[$x]['class'] ? $str_err[$x]['class'].$str_err[$x]['type'] : '').$str_err[$x]['function'].' '
            . '"'.$errstr.'"'.PHP_EOL;
            
            
        $this->writeError($out);
        return true;
    }
        
    public function exceptionError($exception)
    {
        $file = str_replace(dirname(dirname(__DIR__)), '', $exception->getFile());
        $out = date('c').' '.'[Exception] '.'There was an unknown exception: '.$exception->getMessage().' in line (' . $exception->getLine() . ') of ' . $file . '' . PHP_EOL;
        $this->writeError($out);
        return true;
    }
    
    
    private function writeError($out)
    {
		if($this->url) {
			$urlOut = htmlentities(strip_tags($this->url));
			$preout = date('c').' '.'[Visitor Request] '.$urlOut.PHP_EOL;
			file_put_contents($this->errorLogFilename, $preout, FILE_APPEND);
			$this->url = '';
		}
		
        file_put_contents($this->errorLogFilename, $out, FILE_APPEND);
    }
}

$_wbce_errors = new WBCE_Error($errorLogFilename);
