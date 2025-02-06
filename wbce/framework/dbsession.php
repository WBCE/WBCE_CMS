<?php
/**
 * @file dbsession.php
 * @brief This file contains a custom session save handler using the main database class.
 *
 * @author Richard Willars (www.richardwillars.com)
 * @author Stephen McIntyre (stevedecoded.com/)
 * @author Norbert Heimsath (heimsath.org)
 *
 * @copyright GPLv2 or any later
 *
 * This Work is based on
 * Session class by Stephen McIntyre
 * http://stevedecoded.com/blog/custom-php-session-class
 *
 * Which is derived from an article by Richard Willars.
 * http://www.richardwillars.com/articles/php/storing-sessions-in-the-database/
 * (Link seems to be broken http://web.archive.org/web/20120108100137/http://www.richardwillars.com/articles/php/storing-sessions-in-the-database)
 */

/**
 * @brief File Based PHP sessions where encoutering several problems on some shared Hostings so we now have our own SessionHandler
 *
 * Filebased Default sessions encountered a Lot of Problems on different shared hostings.
 * Shared Temp directories caused GCs from other clients clearing our sessions to early.
 * Cron Scripts on some Debian derivates killing sessions after 24 Minutes ignoring all Settings.
 *
 * I am not too happy whith this 2 Classes Solution , but it will fix the problems for now.
 * The second  Class used is class WbSession. (wbsession.php)
 *
 * Setting the Return Values to true is a bad bad fix for PHP 7  but the only avaiable
 * http://stackoverflow.com/questions/34117651/php7-symfony-2-8-failed-to-write-session-data
 * https://github.com/snc/SncRedisBundle/blob/master/Session/Storage/Handler/RedisSessionHandler.php
 */
class DbSession implements SessionHandlerInterface
{
    private $alive = true;
    private $database = null;

    /**
     * @brief The constructor fetches the global DB session and sets the save handlers
     *
     * If we are in an install or upgrade process we fall back to basic session functionality.
     * So the constructor stops after installing the DB table.
     *
     * In other use cases the session is started here too.
     * Here we do not want the session to be initialized here.
     */
    public function __construct()
    {
        $this->fetchGlobalDbInstanceOrDie();

        // We are installing somehow ?
        if (defined("WB_UPGRADE_SCRIPT") or defined("WB_INSTALLER")) {
            $this->createDbTablesIfNotThere();
        } else {
            // Set session handler to overide PHP default
            /*
            session_set_save_handler(
                array(
                    &$this,
                    'open'
                ),
                array(
                    &$this,
                    'close'
                ),
                array(
                    &$this,
                    'read'
                ),
                array(
                    &$this,
                    'write'
                ),
                array(
                    &$this,
                    'destroy'
                ),
                array(
                    &$this,
                    'gc'
                ) // Garbage collection gc
            );
            */
            session_set_save_handler( $this );
            $this->gc(1);
        }

        // Start the session // not starting it here at all
        // session_start();
    }

    /**
     * @brief Fetch the global DB instance, die if there is none.
     */
    protected function fetchGlobalDbInstanceOrDie()
    {
        // fetch database instance
        global $database;

        if (!is_object($database)) {
            die('DBSession $database is no resource (DbConnection)');
        }

        // Store to this class
        $this->database = $database;
    }

    /**
     * @brief protected function to create DB tables if they do not exist.
     */
    protected function createDbTablesIfNotThere()
    {

        // ALTER TABLE `wbce_dbsessions` CHANGE `id` `id` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session Id';

        $sql = "
            CREATE TABLE IF NOT EXISTS `{TP}dbsessions` (
                `id` varchar(148) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session Id',
                `data` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session Data',
                `last_accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last timestamp',
                `user` int(11) NOT NULL COMMENT 'User Id',
                PRIMARY KEY (`id`),
                INDEX (`last_accessed`),
                INDEX (`user`) 
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='WBCE Session Table';
        ";
        $this->database->query($sql);

        // Doing this in multiple steps as i had problems whith the index
        $sql = "
            ALTER TABLE 
                `{TP}dbsessions` 
            DROP PRIMARY KEY;
        ";
        $this->database->query($sql);

        $sql = "
            ALTER TABLE 
                `{TP}dbsessions` 
            CHANGE 
                `id` `id` VARCHAR(148) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session Id';
        ";
        $this->database->query($sql);

        $sql = "
            ALTER TABLE 
                `{TP}dbsessions` 
            ADD PRIMARY KEY(`id`);
        ";
        $this->database->query($sql);
    }

    /**
     * @brief Handler: Clean Session
     *
     * Delete session and its entire data if lifetime has exeded.
     */
    public function gc($expire): int|false
    {
        $expire = ini_get("session.gc_maxlifetime");

        if (Settings::Get("wb_session_timeout") !== false) {
            $expire = Settings::Get("wb_session_timeout");
        } elseif (Settings::Get("wb_secform_timeout") !== false) {
            $expire = Settings::Get("wb_secform_timeout");
        }
        $q = "DELETE FROM `{TP}dbsessions` WHERE DATE_ADD(`last_accessed`, INTERVAL " . (int)$expire . " SECOND) < NOW()";
        $this->database->query($q);

        return true;
    }

    /**
     * @brief Destructor to handle script end
     *
     * Store the session if the script ends for some reason.
     */
    public function __destruct()
    {
        if ($this->alive) {
            session_write_close();
            $this->alive = false;
        }
    }

    /**
     * @brief Handler: Delete Session
     *
     * Delete session cookie and the destroy session
     */
    public function delete()
    {

        // Inactivate/Delete Cookies if used
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        // Triggers the destroy Method
        session_destroy();

        $this->alive = false;
    }

    /**
     * @brief Handler: Open Session
     *
     * As DB is connected by the constructor we need to do nothing here.
     */
    public function open(string $path, string $name): bool
    {
        return true;
    }

    /**
     * @brief Handler: Close Session
     *
     * We do not need to disconnect DB here as we only use a DB handle from outside.
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * @brief Handler: Read Session
     *
     * Read session data from DB
     */
    public function read($sid): string|false
    {
        $sql = "SELECT `data` FROM `{TP}dbsessions` WHERE `id` = '" . $this->database->escapeString($sid) . "' LIMIT 1";
        $res = $this->database->query($sql);
        if ($this->database->is_error()) {
            return '';
        }
        if ($res->numRows() == 1) {
            $fields = $res->fetchRow();

            return $fields['data'];
        } else {
            return '';
        }
    }

    /*****************************
     * Helper Functions
     *****************************/

    /**
     * @brief Handler: Write Session
     *
     * Write session data to DB
     */
    public function write($sid, $data): bool
    {
        $user = WSession::get('USER_ID');
        if (!is_numeric($user)) {
            $user = '0';
        }
        $sql = "REPLACE INTO `{TP}dbsessions` (`id`, `data`, `user`) 
        VALUES ('" . $this->database->escapeString($sid) . "', '" . $this->database->escapeString($data) . "', '$user')";
        $this->database->query($sql);

        return true;
    }

    /**
     * @brief Handler: Destroy Session
     *
     * Delete session and its entire data to DB
     */
    public function destroy($sid): bool
    {
        $sql = "DELETE FROM `{TP}dbsessions` WHERE `id` = '" . $this->database->escapeString($sid) . "'";
        $this->database->query($sql);

        $_SESSION = array();


        return true;
    }

    /**
     * @brief protected function to check if DB tables are existing.
     *
     * @param string $sTableName Name of the table to look for
     * @return boolean TRUE on Table exists FALSE otherwise
     * @todo Move this thing to class Db
     *
     */
    protected function checkDbTableExist($sTableName)
    {
        $sql = "SHOW TABLES LIKE '{$sTableName}'";
        $result = $this->database->query($sql);
        if ($result->num_rows == 1) {
            return true;
        }
        return false;
    }
}
