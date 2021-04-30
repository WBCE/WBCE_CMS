<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//  Heavy patched version, idea for patches based on :
//  http://stackoverflow.com/questions/2695153/php-csrf-how-to-make-it-works-in-all-tabs/2695291#2695291
//  Whith this patch the token System now allows for multiple browser tabs but
//  denies the use of multiple browsers.
//  You can configure this class by adding several constants to your config.php
//  All Patches are Copyright Norbert Heimsath released under GPLv3
//  http://www.gnu.org/licenses/gpl.html
//  Take a look at  __construkt  for configuration options(constants).
//  Patch version 0.3.5

/*
 * If you want some special configuration put this somewhere in your config.php for
 * example or just uncomment the lines here
 *
 * This parameter now can be set with the admintool SecureForm Switcher coded by Luisehahne,
 * pls ask for it in the forum
 *
 * Secret can contain anything its the base for the secret part for the hash
 * define ('WB_SECFORM_SECRET','whatever you like');
 * after how many seconds a new secret is generated
 * define ('WB_SECFORM_SECRETTIME',86400);      #aprox one day
 * shall we use fingerprinting true/false
 * define ('WB_SECFORM_USEFP', true);
 * Timeout till the form token times out. Integer value between 0-86400 seconds (one day)
 * define ('WB_SECFORM_TIMEOUT', 3600);
 * Name for the token form element only alphanumerical string allowed that starts whith a charakter
 * define ('WB_SECFORM_TOKENNAME','my3form3');
 * how many blocks of the IP should be used in fingerprint 0=no ipcheck, possible values 0-4
 * define ('FINGERPRINT_WITH_IP_OCTETS',2);
 */

//no direct file access
if (count(get_included_files()) == 1) die(header("Location: ../index.php", TRUE, 301));

/**
 * Class WB extends this class, so all this functions are avainable in class WB
 */

class SecureForm
{

    const FRONTEND = 0;
    const BACKEND = 1;

    // additional private data
    private $_secret = '5609bnefg93jmgi99igjefg';
    private $_secrettime = 86400; #Approx. one day
    private $_tokenname = 'formtoken';
    private $_timeout = 7200;
    private $_useipblocks = 2;
    private $_usefingerprint = false;
    private $_page_uid = "";
    private $_page_id = "";

    // additional private data
    private $_FTAN = '';
    private $_IDKEYs = array('0' => '0');
    private $_idkey_name = '';
    private $_salt = '';
    private $_fingerprint = '';
    private $_serverdata = '';
    
    protected $_oDb = ''; // Establish class Database object

    /* Construtor */

    protected function __construct($mode = self::FRONTEND)
    {
        // Establish class Database object for 
        // use in this class and its extend 
        // classes Admin, Wb & Frontend
        // Introduced with WBCE 1.4.0 to save redundancy
        $this->_oDb = $GLOBALS['database'];

        // GLOBAL CONFIGURATION, additional constants and stuff

        // Secret can contain anything its the base for the secret part of the hash
        if (defined('WB_SECFORM_SECRET')) {
            $this->_secret = WB_SECFORM_SECRET;
        }

        // shall we use fingerprinting
        if (defined('WB_SECFORM_USEFP') and WB_SECFORM_USEFP === false) {
            $this->_usefingerprint = false;
        }

        // Timeout till the form token times out. Integer value between 0-86400 seconds (one day)
        if (defined('WB_SECFORM_TIMEOUT') and is_numeric(WB_SECFORM_TIMEOUT) and intval(WB_SECFORM_TIMEOUT) >= 0 and intval(WB_SECFORM_TIMEOUT) <= 86400) {
            $this->_timeout = intval(WB_SECFORM_TIMEOUT);
        }

        // Name for the token form element only alphanumerical string allowed that starts whith a charakter
        if (defined('WB_SECFORM_TOKENNAME') and !$this->_validate_alalnum(WB_SECFORM_TOKENNAME)) {
            $this->_tokenname = WB_SECFORM_TOKENNAME;
        }

        // how many bloks of the IP should be used 0=no ipcheck
        if (defined('FINGERPRINT_WITH_IP_OCTETS') and !$this->_is04(FINGERPRINT_WITH_IP_OCTETS)) {
            $this->_useipblocks = FINGERPRINT_WITH_IP_OCTETS;
        }

        // create a unique pageid for per page management of idkeys , especially
        // to identify and delete unused ones from same page call.
        $this->_page_uid = uniqid(rand(), true);

        // GLOBAL CONFIGURATION END

        // CONFIGURE VARS
        $this->_browser_fingerprint = $this->_browser_fingerprint(true);
        $this->_fingerprint = $this->_generate_fingerprint();
        $this->_serverdata = $this->_generate_serverdata();
        $this->_secret = $this->_generate_secret();
        $this->_salt = $this->_generate_salt();
        $this->_idkey_name = substr($this->_fingerprint, hexdec($this->_fingerprint[strlen($this->_fingerprint) - 1]), 16);

        // make sure there is a alpha-letter at first position
        $this->_idkey_name[0] = dechex(10 + (hexdec($this->_idkey_name[0]) % 5));

        // takeover id_keys from session if available
        if (isset($_SESSION[$this->_idkey_name]) && is_array($_SESSION[$this->_idkey_name])) {
            $this->_IDKEYs = $_SESSION[$this->_idkey_name];
        } else {
            $this->_IDKEYs = array('0' => '0');
            $_SESSION[$this->_idkey_name] = $this->_IDKEYs;
        }
    }

    private function _validate_alalnum($input)
    {

        # alphanumerical string that starts whith a letter charakter
        if (preg_match('/^[a-zA-Z][0-9a-zA-Z]+$/u', $input)) {
            return false;
        }

        return "The given input is not an alphanumeric string.";
    }

    private function _is04($input)
    {

        # integer value between 0-4
        if (preg_match('/^[0-4]$/', $input)) {
            return false;
        }

        return "The given input is not an integer between 0-4.";
    }

    private function _browser_fingerprint($encode = true, $fpsalt = "My Fingerprint: ")
    {

        $fingerprint = $fpsalt;

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $fingerprint .= $_SERVER['HTTP_USER_AGENT'];
        }
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $fingerprint .= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        //if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])){ $fingerprint .= $_SERVER['HTTP_ACCEPT_ENCODING'];}
        if (isset($_SERVER['HTTP_ACCEPT_CHARSET'])) {
            $fingerprint .= $_SERVER['HTTP_ACCEPT_CHARSET'];
        }

        $fingerprint .= $this->_getip($this->_useipblocks);

        if ($encode) {
            $fingerprint = md5($fingerprint);
        }

        return $fingerprint;
    }

    private function _getip($ipblocks = 4)
    {

        $ip = "";    //Ip address result
        $cutip = ""; //Ip address cut to limit

        # mabe user is behind a Proxy but we need his real ip address if we got a nice Proxyserver,
        # it sends us the "HTTP_X_FORWARDED_FOR" Header. Sometimes there is more than one Proxy.
        # !!!!!! THIS PART WAS NEVER TESTED BECAUSE I ONLY GOT A DIRECT INTERNET CONNECTION !!!!!!
        # long2ip(ip2long($lastip)) makes sure we got nothing else than an ip into our script ;-)
        # !!!!! WARNING the 'HTTP_X_FORWARDED_FOR' Part is NOT TESTED Too MUCH!!!!!
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $lastip = array_pop($iplist);
            $ip .= long2ip(ip2long($lastip));
        } /* If theres no other supported info we just use REMOTE_ADDR
        If we have a fiendly proxy supporting  HTTP_X_FORWARDED_FOR its ok to use the full address.
        But if there is no HTTP_X_FORWARDED_FOR we can  not be sure if its a proxy or whatever, so we use the
        blocklimit for IP address.
         */
        else {
            $ip = long2ip(ip2long($_SERVER['REMOTE_ADDR']));

            # ipblocks used here defines how many blocks of the ip adress are checked xxx.xxx.xxx.xxx
            $blocks = explode('.', $ip);
            for ($i = 0; $i < $ipblocks; $i++) {
                $cutip .= $blocks[$i] . '.';
            }
            $ip = substr($cutip, 0, -1);
        }

        return $ip;
    }

    // fake funktion , just exits to avoid error message

    private function _generate_fingerprint()
    {

        // server depending values
        $fingerprint = $this->_generate_serverdata();

        // client depending values
        $fingerprint .= (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '17';
        $usedOctets = (defined('FINGERPRINT_WITH_IP_OCTETS')) ? intval(defined('FINGERPRINT_WITH_IP_OCTETS')) : 0;
        $clientIp = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

        if (($clientIp != '') && ($usedOctets > 0)) {
            $ip = explode('.', $clientIp);
            while (sizeof($ip) > $usedOctets) {
                array_pop($ip);
            }
            $clientIp = implode('.', $ip);
        } else {
            $clientIp = 19;
        }

        $fingerprint .= $clientIp;

        return md5($fingerprint);
    }

    /*
     * creates selfsigning Formular transactionnumbers for unique use
     * @access public
     * @param bool $asTAG: true returns a complete prepared, hidden HTML-Input-Tag (default)
     *                     false returns an GET argument 'key=value'
     * @return mixed:      string
     *
     * requirements: an active session must not be available but it makes no sense whithout :-)
     */

    private function _generate_serverdata()
    {

        $usedOctets = (defined('FINGERPRINT_WITH_IP_OCTETS')) ? (intval(FINGERPRINT_WITH_IP_OCTETS) % 5) : 2;
        $serverdata = '';
        $serverdata .= (isset($_SERVER['SERVER_SIGNATURE'])) ? $_SERVER['SERVER_SIGNATURE'] : '2';
        $serverdata .= (isset($_SERVER['SERVER_SOFTWARE'])) ? $_SERVER['SERVER_SOFTWARE'] : '3';
        $serverdata .= (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : '5';
        $serverIp = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '';

        if (($serverIp != '') && ($usedOctets > 0)) {
            $ip = explode('.', $serverIp);
            while (sizeof($ip) > $usedOctets) {
                array_pop($ip);
            }
            $serverdata .= implode('.', $ip);
        } else {
            $serverdata .= '7';
        }

        $serverdata .= (isset($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : '11';
        $serverdata .= (isset($_SERVER['SERVER_ADMIN'])) ? $_SERVER['SERVER_ADMIN'] : '13';
        $serverdata .= PHP_VERSION;

        return $serverdata;
    }

    /*
     * checks received form-transactionnumbers against itself
     * @access public
     * @param string $mode: requestmethode POST(default) or GET
     * @return bool:    true if numbers matches against stored ones
     *
     * requirements: no active session must be available but it makes no sense whithout.
     * this check will prevent from multiple sending a form. history.back() also will never work
     */

    private function _generate_secret()
    {

        $secret = $this->_secret;
        $secrettime = $this->_secrettime;

        // create a different secret every day
        $TimeSeed = floor(time() / $secrettime) * $secrettime; //round(floor) time() to whole days
        $DomainSeed = $_SERVER['SERVER_NAME'];                 // generate a numerical from server name.

        $Seed = $TimeSeed . $DomainSeed;
        $secret .= md5($Seed); //

        $secret .= $this->_secret . $this->_serverdata . session_id();
        if ($this->_usefingerprint) {
            $secret .= $this->_browser_fingerprint;
        }

        return $secret;
    }

    /*
     * save values in session and returns a ID-key
     * @access public
     * @param mixed $value: the value for witch a key shall be generated and memorized
     * @return string:      a MD5-Key to use instead of the real value
     *
     * @requirements: an active session must be available
     * @description: IDKEY can handle string/numeric/array - vars. Each key is a
     */

    private function _generate_salt()
    {

        if (function_exists('microtime')) {
            list($usec, $sec) = explode(" ", microtime());
            $salt = (string)((float)$usec + (float)$sec);
        } else {
            $salt = (string)time();
        }
        $salt = (string)rand(10000, 99999) . $salt . (string)rand(10000, 99999);
        return md5($salt);
    }

    /*
     * search for key in session and returns the original value
     * @access public
     * @param string $fieldname: name of the POST/GET-Field containing the key or hex-key itself
     * @param mixed $default: returnvalue if key not exist (default 0)
     * @param string $request: requestmethode can be POST or GET or '' (default POST)
     * @return mixed: the original value (string, numeric, array) or DEFAULT if request fails
     *
     * @requirements: an active session must be available
     * @description: each IDKEY can be checked only once. Unused Keys stay in list until the
     *               session is destroyed.
     */

    final public function getFTAN($as_tag = true)
    {

        $secret = $this->_secret;
        $timeout = time() + $this->_timeout;

        // mt_srand(hexdec(crc32(microtime()));
        $token = dechex(mt_rand());
        $hash = sha1($secret . '-' . $token . '-' . md5(WSession::Get('SessionTokenIdentifier')));
        $signed = $token . '-' . $hash;

        if ($as_tag == true) {
            // by default return a complete, hidden <input>-tag
            return '<input type="hidden" name="' . $this->_tokenname . '" value="' . htmlspecialchars($signed) . '" />';
        }

        return $this->_tokenname . '=' . $signed;
    }

    //helper function

    final public function checkFTAN($mode = 'POST')
    {

        $mode = (strtoupper($mode) != 'POST' ? '_GET' : '_POST');
        $isok = false;
        $secret = $this->_secret;

        if (isset($GLOBALS[$mode][$this->_tokenname])) {
            $latoken = $GLOBALS[$mode][$this->_tokenname];
        } else {
            return $isok;
        }

        $parts = explode('-', $latoken);

        if (count($parts) == 2) {
            list($token, $hash) = $parts;
            if ($hash == sha1($secret . '-' . $token . '-' . md5(WSession::Get('SessionTokenIdentifier')))) {
                $isok = true;
            }
        }

        return $isok;
    }

    //helper function

    public function getIDKEY($value)
    {

        // serialize value, if it's an array
        if (is_array($value) == true) {
            $value = serialize($value);
        }

        // cryptsome random stuff with salt into md5-hash
        $key = md5($this->_salt . rand() . uniqid('', true));

        //shorten hash a bit
        $key = str_replace(array("=", "$", "+"), array("-", "_", ""), base64_encode(pack('H*', $key)));

        // the key is unique, so store it in list
        if (!array_key_exists($key, $_SESSION[$this->_idkey_name])) {
            $_SESSION[$this->_idkey_name][$key]['value'] = $value;
            $_SESSION[$this->_idkey_name][$key]['time'] = time();
            $_SESSION[$this->_idkey_name][$key]['page'] = $this->_page_uid;
        } // if key already exist, try again, dont store as it already been stored
        else {
            $key = $this->getIDKEY($value);
        }

        return $key;
    }

    public function checkIDKEY($fieldname, $default = 0, $request = 'POST', $ajax = false)
    {

        //Remove timed out idkeys
        //echo"<pre>";print_r($_SESSION[$this->_idkey_name]);echo"<pre>";
        $_SESSION[$this->_idkey_name] = array_filter($_SESSION[$this->_idkey_name], array($this, "_timedout"));

        // set returnvalue to default
        $return_value = $default;
        switch (strtoupper($request)) {
            case 'POST':
                $key = isset($_POST[$fieldname]) ? $_POST[$fieldname] : $fieldname;
                break;
            case 'GET':
                $key = isset($_GET[$fieldname]) ? $_GET[$fieldname] : $fieldname;
                break;
            default:
                $key = $fieldname;
        }

        // key always must match the generated ones
        if (preg_match('/[0-9a-zA-Z\-\_]{1,32}$/', $key)) {
            // check if key is stored in IDKEYs-list
            if (array_key_exists($key, $_SESSION[$this->_idkey_name])) {
                // fetch stored value
                $return_value = $_SESSION[$this->_idkey_name][$key]['value'];
                // fetch page identifier used to remove all keys generated on the former pagecall
                $this->_page_id = $_SESSION[$this->_idkey_name][$key]['page'];

                if ($ajax === false) {
                    //Remove all keys from this page to prevent multiuse and session mem usage
                    $_SESSION[$this->_idkey_name] = array_filter($_SESSION[$this->_idkey_name], array($this, "_fpages"));
                    //unset($_SESSION[$this->_idkey_name][$key]);   // remove from list to prevent multiuse
                }

                if (preg_match('/.*(?<!\{).*(\d:\{.*;\}).*(?!\}).*/', $return_value)) {
                    // if value is a serialized array, then deserialize it
                    $return_value = unserialize($return_value);
                }
            }
        }

        return $return_value;
    }

    // ADDITIONAL FUNCTIONS needed cause the original ones lack some functionality
    // all are Copyright Norbert Heimsath, heimsath.org
    // released under GPLv3  http://www.gnu.org/licenses/gpl.html

    /*
    Made because ctype_ gives strange results using mb Strings
     */

    /* @access public
     * @return void
     *
     * @requirements: an active session must be available
     * @description: remove all entries from IDKEY-Array
     *
     */
    public function clearIDKEY()
    {

        $this->_IDKEYs = array('0' => '0');
    }

    //returns false on match and an error if no match

    final protected function createFTAN()
    {
    }

    /*
    Just a function to get User ip even if hes behind a proxy
     */

    private function _timedout($var)
    {
        // First element after a logout is 0 not sure why???....
        // ah got It clearIDKEY() does that. So there is always an array to run on i guess.
        if ($var == 0) {
            return false;
        }

        //echo "timedoutcall:<br>";print_r($var);
        if ($var['time'] < time() - $this->_timeout) {
            return false;
        }

        return true;
    }

    /*
    Creates a basic Browser Fingerprint for securing the session and forms.
     */

    private function _fpages($var)
    {
        if ($var['page'] == $this->_page_id) {
            return false;
        }

        return true;
    }

    //
    // ADDITIONAL FUNCTIONS END
    //
}
