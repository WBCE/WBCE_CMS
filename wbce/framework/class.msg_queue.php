<?php
/**
 * Description of class
 *
 * @author wkl
 */
class msgQueue {

	const RETVAL_ARRAY  = 0;
	const RETVAL_STRING = 1; // (default)

	private static $_instance;

	private $_error = array();
	private $_success = array();

	protected function __construct() {
		$this->_error = array();
		$this->_success = array();
	}
	private function __clone() { throw new Exception('cloning Class '.__CLASS__.' is illegal'); }

    public static function handle()
    {
        if (!isset(self::$_instance)) {
            $c = __CLASS__;
            self::$_instance = new $c;
        }
        return self::$_instance;
	}

	public static function add($message = '', $type = false)
	{
		if($type)
		{
			self::handle()->_success[] = $message;
		}else
		{
			self::handle()->_error[] = $message;
		}
	}

	public static function clear()
	{
		self::handle()->_error = array();
		self::handle()->_success = array();
	}

	public static function isEmpty()
	{
		return (sizeof(self::handle()->_success) == 0 && sizeof(self::handle()->_error) == 0 );
	}
	
	public static function getError($retval_type = self::RETVAL_STRING)
	{
		if(sizeof(self::handle()->_error))
		{
			if($retval_type == self::RETVAL_STRING)
			{
				return implode('<br />', self::handle()->_error);
			}else
			{
				return self::handle()->_error;
			}
		}
	}

	public static function getSuccess($retval_type = self::RETVAL_STRING)
	{
		if(sizeof(self::handle()->_success))
		{
			if($retval_type == self::RETVAL_STRING)
			{
				return implode('<br />', self::handle()->_success);
			}else
			{
				return self::handle()->_success;
			}
		}
	}


}
?>
