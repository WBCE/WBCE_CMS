<?php
/**
    @file filelogger.php
    @brief Simple filebased logger class
    @package core
    @author Norbert Heimsath (heimsath.org) 
    @copyright GPLv2 or any later version

    Simple filebased loging class , only speciality is that files can be truncated , so they never grow endless.

    This Class is inspired by the work of   
    Darko Bunic (http://www.redips.net/php/write-to-log-file/)
    and
    Huntly Cameron <huntly.cameron@gmail.com>
    https://bitbucket.org/huntlyc/simple-php-logger
    
    
    For finally displaying the Logfile please have a look here:
    http://www.codediesel.com/php/tail-functionality-in-php/
    
    Or even better :
    http://www.phpgangsta.de/tag/php-tail
    
    
*/

/**
@brief Simple logger class

Getting Started
---------------
Simply

@code
    $log = new SysLog("/path/to/log");
    $log->default_tag = "LOG TEST";

    //Print out some debug.
    $log->d("DEBUGGING FTW");

    //Print some info
    $log->i("Making cup of tea.");

    //you get the drift...
    $log->w( "OOPS! Spoke too soon, SERVER DOWN!" ,"REAL ALARM");
    
    
@endcode

This will give the following output:

@code
    [2012-02-15 17:24:40]: [DEBUG][LOG TEST] - DEBUGGING FTW
    [2012-02-15 17:24:40]: [INFO][LOG TEST] - Making cup of tea.
    [2012-02-15 17:24:40]: [WARNING][REAL ALARM] - OOPS! Spoke too soon, SERVER DOWN!
@endcode

Take a look at the source for all supported levels, it's not terribly exciting, but there you go..
*/     

class SysLog{
    /**
        @brief log_file - the log file to write to
        @var string $log_file
    */        
    public $log_file;

    /**
        @brief Maximum length of the Log file 
        @var integer $log_file_length
    */        
    public $log_file_max_lines=1000;

    /**
        @brief minimum length of the Log file 
        @var integer $log_file_length

        To what we truncate
    */        
    public $log_file_min_lines=800;

    /**
        @brief The probability on how often the logfile length is checkt , as this may be time consuming.
        @var integer $log_file_gc_probability

        50 stands for approx. once in 50 times writing. 
    */        
    public $log_file_gc_probability=50;

    /**
        @brief The Default Tag used for logging if no tag is given by the function
        @var string $default_tag

        Defaults to "WBCE SysLog" 
    */        
    public $default_tag="WBCE SysLog";


    /**
        @brief Constructor just stores filepath
        @param String logfile - [optional] Absolute file name/path. Defaults to WBCE /log/system.log.

        Uses WBCE core functions make_dir() and change_mode()
    */        
    public function __construct($log_file = WB_PATH. "/log/system.log") {
    
        $this->log_file = $log_file;

        if(!file_exists($log_file)){ //Attempt to create log file 
            // WBCE uses more detailed file creation here.
            make_dir(dirname($log_file));            
            touch($log_file);
            change_mode($log_file);
        }

        //Make sure we'ge got permissions
        if(!(is_writable($log_file) || $this->win_is_writable($log_file))){   
            //Cant write to file,
            Die("LOGGER ERROR: Can't write to log in $log_file check permissions for file and its containing folder");
        }
    }


    /**
        @brief d - Log Debug
        @param String tag - Log Tag
        @param String message - message to spit out

        Logs only if WB_DEBUG is set or WB_ER_LEVEL == 'E2' . 
        This logs nothing if debugging is off.  
    */        
    public function d($message, $tag=""){
        if (
            (defined ('WB_DEBUG') AND WB_DEBUG ==true )    OR
            (defined ('WB_ER_LEVEL') AND WB_ER_LEVEL == 'E2')      
        ) {
            $this->writeToLog("DEBUG", $tag, $message);
        }
    }


    /**
        @brief e - Log Error
        @param String tag - Log Tag
        @param String message - message to spit out 
    */        
    public function e($message, $tag=""){
        $this->writeToLog("ERROR", $tag, $message);            
    }


    /**
        @brief w - Log Warning
        @param String tag - Log Tag
        @param String message - message to spit out 
    */        
    public function w($message, $tag=""){
        $this->writeToLog("WARNING", $tag, $message);            
    }


    /**
        @brief i - Log Info
        @param String tag - Log Tag
        @param String message - message to spit out
    */        
    public function i($message, $tag=""){
        $this->writeToLog("INFO", $tag, $message);            
    }


    /**
        @brief writeToLog - writes out timestamped message to the log file as 
        defined by the $log_file class variable.
        
        @param String status - "INFO"/"DEBUG"/"ERROR" e.t.c.
        @param String tag - "Small tag to help find log entries"
        @param String message - The message you want to output.
    */        
    protected function writeToLog($status, $tag, $message) {
        if ($tag=="") $tag= $this->default_tag;
        $date = date('[Y-m-d H:i:s]');
        $msg = "$date: [$tag][$status] - $message" . PHP_EOL;
        file_put_contents($this->log_file, $msg, FILE_APPEND);

        // Check to truncate file 
        if ($this->log_file_gc_probability > 0){  // Activated??
            $rnd= mt_rand(1, $this->log_file_gc_probability); 
            if ($rnd==1) { 
                if ($this->getFileLines ($this->log_file) > $this->log_file_max_lines){
                    $this->truncateLogFile ($this->log_file);
                }
            }
        }
    }


    /**
        @brief getFileLines returns the number of lines contained by a file.
        
        http://stackoverflow.com/questions/2162497/efficiently-counting-the-number-of-lines-of-a-text-file-200mb
        Object oriented Solution (pretty fast)        

        @param 
    */
    protected function  getFileLines ($file_name_path){
        $file = new \SplFileObject($file_name_path, 'r');
        $file->seek(PHP_INT_MAX);
        return $file->key() + 1;
    }


    /**
        @brief Returns the last $log_file_min_lines(default 800) of a log file 

        http://stackoverflow.com/questions/3570947/php-grab-last-15-lines-in-txt-file

        @param string $file_name_path the full path to the Logfile
    */
    protected function truncateLogFile ($file_name_path) {
        $fp    = fopen($file_name_path, 'r');
        $idx   = 0;
        $lines = array();
    
        while(($line = fgets($fp)))
        {
            $lines[$idx] = $line;
            $idx = ($idx + 1) % $this->log_file_min_lines;
        }

        $p1 = array_slice($lines,    $idx);
        $p2 = array_slice($lines, 0, $idx);
        $ordered_lines = array_merge($p1, $p2);

        file_put_contents($this->log_file, $ordered_lines);

    }


    /**
        @brief Function to check writability of a file , even under Windows 

        Function lifted from wordpress
        see: http://core.trac.wordpress.org/browser/tags/3.3/wp-admin/includes/misc.php#L537

        Will work in despite of Windows ACLs bug
        see http://bugs.php.net/bug.php?id=27609
        see http://bugs.php.net/bug.php?id=30931
        @attention Use a trailing slash for folders!!!
    */
    protected function win_is_writable( $path ) {
  
        if ( $path[strlen( $path ) - 1] == '/' ) // recursively return a temporary file path
            return win_is_writable( $path . uniqid( mt_rand() ) . '.tmp');
        else if ( is_dir( $path ) )
            return win_is_writable( $path . '/' . uniqid( mt_rand() ) . '.tmp' );
        
        // check tmp file for read/write capabilities
        $should_delete_tmp_file = !file_exists( $path );
        $f = @fopen( $path, 'a' );
        if ( $f === false )
            return false;
        
        fclose( $f );

        if ( $should_delete_tmp_file )
            unlink( $path );

        return true;
    }   
    
    
    
    
    
}






