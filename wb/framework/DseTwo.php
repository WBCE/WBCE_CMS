<?php
/**
 * @category        ISTeasy
 * @package         DatabaseSearchEngine 1
 * @author          Werner von der Decken
 * @copyright       2011, ISTeasy-project
 * @license         http://www.gnu.org/licenses/gpl.html
 * @version         $Id: DseTwo.php 1499 2011-08-12 11:21:25Z DarkViper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/framework/DseTwo.php $
 * @description     Searchengine to browse whoole database for text.
 *                  Black- or whitelist is possible
 *                  min requirements: PHP 5.3.6, mySQL 5.1
 *                  this is a authorisised GPL-lizensed derivate from the original
 *                  ISTeasy class DseOne which is available under a cc-by-sa-3.0 license
*/
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
    require_once(dirname(__FILE__).'/globalExceptionHandler.php');
    throw new IllegalFileException();
}
/* -------------------------------------------------------- */

class DseTwo {

    const USE_ALL       = 0;
    const USE_BLACKLIST = 1;
    const USE_WHITELIST = 2;

    const RETURN_UNUSED = 0;
    const RETURN_USED   = 1;
    /**
     *
     * @var res database handle
     */
    private $_db;
    /**
     * @var object Database object
     */
    private $_oDb = null;
    /**
     *
     * @var string prefix of tables to search for
     */
    private $_TablePrefix;
    /**
     *
     * @var string name of the database
     */
    private $_db_name;
    /**
     *
     * @var array list of unneeded tables.fields
     */
    private $_ControllList;
    private $_ControllListTyp;
    private $_ControllListTypen = array('All','BlackList','WhiteList');

    private $_Queries;
    private $_BasePath = '';
    private $_CachePath = '';
    private $_TCacheFile = '';
    private $_DCachePrefix = '';
    private $_bUseCache = true;
    /**
     *
     * @param object $database global database object
     */
    public function __construct()
    {
        $this->_oDb = $GLOBALS['database'];
        $this->_ControllList = array();
        $this->_TCacheFile = 'Ie'.__CLASS__.'CacheTables';
        $this->_DCachePrefix = 'Ie'.__CLASS__.'CacheDir';
        $this->_Queries = array();
    }
    /**
     *
     * @param string $name name of the property
     *        (db_handle, db_name, table_prefix, base_dir, cache_dir, use_cache)
     * @param mixed $value value of the property
     */
    public function  __set($name, $value) {

        switch(strtolower($name)):
            case 'db_handle':
                if($value) { $this->_db = $value; }
                break;
            case 'db_name':
                if($value != '') { $this->_db_name = $value; }
                break;
            case 'table_prefix':
                if($value != '') { $this->_TablePrefix = $value; }
                break;
            case 'base_dir':
                if($value != '') {
                    $this->_BasePath = rtrim(str_replace('\\', '/', $value) , '/');
                }
                break;
            case 'cache_dir':
                $value = rtrim(str_replace('\\', '/', $value) , '/');
                if(!is_dir($value)) {
                    if(!mkdir($value, 0777, true)) {
                        $this->_CachePath = '';
                        $this->_bUseCache = false;
                        break;
                    }
                }
                if(is_writable($value)) {
                    $this->_CachePath = $value;
                    $this->_bUseCache = true;
                }else {
                    $this->_CachePath = '';
                    $this->_bUseCache = false;
                }
                break;
            default:
                throw new InvalidArgumentException( __CLASS__.'::'.$name );
                break;
        endswitch;
    }

    /**
     * delete all table cache files
     */
    public function clearCache()
    {
        foreach($this->_ControllListTypen as $type) {
            $cFile = $this->_CachePath.'/'.$this->_TCacheFile.$type;
            if(file_exists($cFile)) { @unlink($cFile); }
        }
    }
    /**
     *
     * @param string $blacklist path/filename of the blacklist
     * @param int $type const USE_NO_LIST / USE_BLACKLIST / USE_WHITELIST
     * @return bool false if no or empty list is available
     */
    public function addControllList($sControllList, $type = self::USE_BLACKLIST)
    {
        $this->_ControllList = array();
        $this->_ControllListTyp = $type;
        if(is_readable($sControllList)) {
            if(($list = file($sControllList, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES)) !== false)
            {
                $list = preg_grep('/^\s*?[^#;]/', $list);
                $this->_ControllList = preg_replace('/^\s*?(.*)\s*?$/', $this->_TablePrefix.'$1', $list);
                unset($list);
            }
        }else {
            $this->_ControllListTyp = self::USE_ALL;
        }
        if(($type == self::USE_BLACKLIST) && (sizeof($this->_ControllList) > 0)) {
            $this->_ControllListTyp = self::USE_ALL;
        }
        return (sizeof($this->_ControllList) > 0);
    }
    /**
     *
     * @param string $sDirToSearch directory to scan (relative to base_dir)
     * @param integer $bRetunMode select matching or unmatching files
     * @return array list of matching files
     */
    public function getMatchesFromDir($sDirToSearch, $bRetunMode = self::RETURN_USED)
    {
        $aResultFileList = array();
        $aNewFileList = array();
        $sDirToSearch = trim(str_replace('\\', '/', $sDirToSearch) , '/');
        $sPathToSearch = $this->_BasePath.'/'.$sDirToSearch;
        $sCacheFile = $this->_DCachePrefix.$bRetunMode.urlencode('/'.$sDirToSearch);
        $sCacheFile = $this->_CachePath.'/'.$sCacheFile;
        if(sizeof($this->_Queries) <= 0) { $this->_getTableQueries(); }
        // read fileList from directory
        try{
            foreach( new DirectoryIterator($sPathToSearch) as $fileinfo ) {
            // at first collect all files from target directory
                $fileName = $fileinfo->getFilename();
                if(($fileinfo->isFile()) &&
                   (!$fileinfo->isDot()) &&
                   ($fileinfo->getFilename() != 'index.php')) {
                   $aNewFileList[] = $fileinfo->getFilename();
                }
            }
        }catch(UnexpectedValueException $e) {}
        // make checksum of current directory
        $bCacheValid = false;
        if($this->_bUseCache) {
            $checkSum = crc32(serialize($aNewFileList));
            if(is_readable($sCacheFile)){
            // read cachefile if available
                $aResultFileList = unserialize(file_get_contents($sCacheFile));
                if($checkSum == array_shift($aResultFileList)) {
                // compare new checksum against checksum from cachefile
                    $bCacheValid = true;
                }
            }
        }
        if(!$bCacheValid) {
        // skip this loop if valid cache is available
            $aResultFileList = array();
            while (list( , $sFilename) = each($aNewFileList)) {
                // iterate all tables and search for filename
                if( $this->_getMatch($sDirToSearch.'/'.$sFilename) !== false) {
                    if($bRetunMode == self::RETURN_USED) { $aResultFileList[] = $sFilename; }
                }else {
                    if($bRetunMode == self::RETURN_UNUSED) { $aResultFileList[] = $sFilename; }
                }
            }
            // calculate new checksum
            $newCheckSum = crc32(serialize($aResultFileList));
            // add checksum to array
            array_unshift($aResultFileList,  $newCheckSum);
            // try to write serialized array into new cachefile
            if(file_put_contents($sCacheFile, serialize($aResultFileList)) === false) {
                throw new RuntimeException();
            }
            // remove checksum again
            array_shift($aResultFileList);
        }
        unset($aNewFileList);
        return $aResultFileList;
    }
    /**
     *
     * @param <type> $sFilename
     * @return bool true if file found in db
     */
    private function _getMatch($sFilename)
    {
        $result = 0;
        $sFilename = str_replace('_', '\_', $sFilename);
        $sSearch = '%'.str_replace('/', '_', $sFilename).'%';
        while (list( , $sQuery) = each($this->_Queries)) {
            $sql = sprintf($sQuery, $sSearch);
            if( ($res = $this->_oDb->query($sql)) ) {
                if( ($result = intval($res->fetchRow(MYSQL_ASSOC))) > 0 )  { break; }
            }
        }
        return ($result != 0);
    }
    /**
     *
     */
    private function _getTableQueries()
    {
        if($this->_bUseCache) {
        // try to read queries from cace
            $sCacheFile = $this->_CachePath.'/'.$this->_TCacheFile.$this->_ControllListTypen[$this->_ControllListTyp];
            try {
                if(is_readable($sCacheFile)) {
                    $this->_Queries = unserialize(file_get_contents($sCacheFile));
                }
            }catch(Exception $e) {
                $this->_Queries = array();
            }
        }
        if(sizeof($this->_Queries) > 0) { return; } // queries alreade loaded from cache
        $TP = str_replace('_','\_', $this->_TablePrefix);
        $sql  = 'SELECT TABLE_NAME `table`, COLUMN_NAME `column` ';
        $sql .= 'FROM INFORMATION_SCHEMA.COLUMNS ';
        $sql .= 'WHERE `table_schema` = \''.$this->_db_name.'\' AND ';
        $sql .=        '`table_name` LIKE \''.$TP.'%\' AND ';
        $sql .=        '(`data_type` LIKE \'%text\' OR ';
        $sql .=           '(`data_type` = \'varchar\' AND `character_maximum_length` > 20)';
        $sql .=        ')' ;
        $sql .= 'ORDER BY `table`, `column`';
        if(($res = $this->_oDb->query($sql))) {
            $lastTable = '';
            $aOrStatements = array();
            $sPrefix = '';
            while($rec = $res->fetchRow(MYSQL_ASSOC))
            { // loop through all found tables/fields
                $sTableColumn = $rec['table'].'.'.$rec['column'];
                switch($this->_ControllListTyp):
                // test against controll list
                    case self::USE_BLACKLIST:
                        $needRecord = true;
                        if(in_array($rec['table'], $this->_ControllList) ||
                           in_array($sTableColumn, $this->_ControllList))
                        {
                            $needRecord = false;
                        }
                        break;
                    case self::USE_WHITELIST:
                        $needRecord = false;
                        if(in_array($rec['table'], $this->_ControllList) ||
                           in_array($sTableColumn, $this->_ControllList))
                        {
                            $needRecord = true;
                        }
                        break;
                    default: // self::USE_ALL
                        $needRecord = true;
                        break;
                endswitch;
                if($needRecord) {
                    if($lastTable != $rec['table']) {
                        if(sizeof($aOrStatements)!= 0){
                        // close previous table
                            $this->_Queries[] = $sPrefix.implode(') OR (', $aOrStatements).')';
                        }
                    // start a new table
                        $sPrefix = 'SELECT COUNT(*) `count` FROM `'.$rec['table'].'` WHERE( ';
                        $aOrStatements = array();
                        $lastTable = $rec['table'];
                    }
                    // add table.column to query
                    $aOrStatements[] = '`'.$rec['table'].'`.`'.$rec['column'].'` LIKE \'%1$s\'';
                }
            }
            if(sizeof($aOrStatements)!= 0){
            // close last table
                $this->_Queries[] = $sPrefix.implode(') OR (', $aOrStatements).')';
            }
            unset($res);
        }
        if($this->_bUseCache) {
        // try to write queries into the cache
            if(file_put_contents($sCacheFile, serialize($this->_Queries)) === false) {
                throw new RuntimeException('unable to write file ['.$sCacheFile.']');
            }
        }
    }

}
