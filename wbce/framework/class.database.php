<?php
// All of those framework files has been replaced
// now if a file loads one of this Libs multiple times the
// include_once stops the script from throwing errors
// for classes this file can be even completely empty as
// the autoloader normally takes care

if(extension_loaded ('PDO' ) AND extension_loaded('pdo_mysql')){

    require_once __dir__.'/classes/persistence/database.php';
    require_once __dir__.'/classes/persistence/result.php';
    require_once __dir__.'/classes/persistence/databaseexception.php';
    require_once __dir__.'/classes/persistence/wdo.php';

    class database extends Persistence\Database
    {
        public function __construct()
        {
            //trigger_error('Manual include of class database is no longer needed as long as you include the config.php somewhere in your script. ', E_USER_DEPRECATED);
            parent::__construct();
        }
    }

}
else{
    require_once __dir__.'/classes/class.database.php';
//     trigger_error('Manual include of class database is no longer needed as long as you include the config.php somewhere in your script. ', E_USER_DEPRECATED);
//     trigger_error('Please try to get PDO suppport for your webspace as we sooner or later change to PDO database drivers ', E_USER_DEPRECATED);
}









