<?php
/**
    @file dbsession.php
    @brief This file contains a custom session save handler using the main database class. 
    
    @author Richard Willars (www.richardwillars.com)
    @author Stephen McIntyre (stevedecoded.com/)
    @author Norbert Heimsath (heimsath.org)
    
    @copyright GPLv2 or any later
    
    This Work is based on 
    Session class by Stephen McIntyre
    http://stevedecoded.com/blog/custom-php-session-class
    
    Which is derived from an article by Richard Willars. 
    http://www.richardwillars.com/articles/php/storing-sessions-in-the-database/
    (Link seems to be broken 
        http://web.archive.org/web/20120108100137/http://www.richardwillars.com/articles/php/storing-sessions-in-the-database 
    )
    

*/
/**
    @brief File Based PHP sessions where encoutering several problems on some shared Hostings so we now have our own SessionHandler 
    
    Filebased Default sessions encountered a Lot of Problems on different shared hostings. 
    Shared Temp directories caused GCs from other clients clearing our sessions to early. 
    Cron Scripts on some Debian derivates killing sessions after 24 Minutes ignoring all Settings. 
    
    I am not too happy whith this 2 Classes Solution , but it will fix the problems for now. 
    
    Setting the Return Values to true is a bad bad fix for PHP 7  but the only avaiable 
    http://stackoverflow.com/questions/34117651/php7-symfony-2-8-failed-to-write-session-data
    https://github.com/snc/SncRedisBundle/blob/master/Session/Storage/Handler/RedisSessionHandler.php 
*/

class DbSession
{
  private $alive = true;
  private $database = NULL;
 
  public function __construct()
  {   
  
  
    // Set handler to overide SESSION 
    session_set_save_handler(
      array(&$this, 'open'),
      array(&$this, 'close'),
      array(&$this, 'read'),
      array(&$this, 'write'),
      array(&$this, 'destroy'),
      array(&$this, 'clean'));     // Garbage collection gc
    
        
    // Start the session // not starting it here  at all 
    // session_start();
  }
 
  public function __destruct()
  {
    if($this->alive)
    {
      session_write_close();
      $this->alive = false;
    }
  }
 
  public function delete()
  {
  
    // Inactivate/Delete Cookies if used
    if(ini_get('session.use_cookies'))
    {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
      );
    }
 
    session_destroy();
 
    $this->alive = false;
  }
 
  public function open()
  {    
    // fetch database instance
    global $database;

    // Store to this class
    $this->database=$database;
 
    return true;
  }
 
  public  function close()
  { 
    // We do not need to disconnect DB here as we only use a DB handle from outside
    return true;
  }
 
  public  function read($sid)
  {
    $sql = "SELECT `data` FROM `{TP}dbsessions` WHERE `id` = '".$this->database->escapeString($sid)."' LIMIT 1";
    $res = $this->database->query($sql);
    if($this->database->is_error()) return '';
    if($res->numRows() == 1)
    {
      $fields = $res->fetchRow();
 
      return $fields['data'];
    }
    else
    {
      return '';
    }
  }
 
  public  function write($sid, $data)
  {
    $user=WbSession::get('USER_ID');
    if (!is_numeric($user)) $user='0';
    $sql = "REPLACE INTO `{TP}dbsessions` (`id`, `data`, `user`) 
    VALUES ('".$this->database->escapeString($sid)."', '".$this->database->escapeString($data)."', '$user')";
    $this->database->query($sql);
    
    return true;
  }
 
  public  function destroy($sid)
  {
    $sql = "DELETE FROM `{TP}dbsessions` WHERE `id` = '".$this->database->escapeString($sid)."'"; 
    $this->database->query($sql);
 
    $_SESSION = array();
 
    
    return true;
  }
 
  public  function clean($expire)
  {
    if (Settings::Get ("wb_secform_timeout") !==false)
        $expire=Settings::Get ("wb_secform_timeout");
    $q = "DELETE FROM `{TP}dbsessions` WHERE DATE_ADD(`last_accessed`, INTERVAL ".(int) $expire." SECOND) < NOW()"; 
    $this->database->query($q);
 
    return true;
  }
} 

