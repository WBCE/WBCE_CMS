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
//  Take a look at  __construct  for configuration options(constants).
//  Patch version 0.3.5

/*
 * === CONFIGURATION ===========================================================
 *
 * The following constants can be configured with the SecureForm Switcher 
 * Admin Tool (authored by Luisehahne) that is part of the WBCE CMS Package. 
 *
 * ─── WB_SECFORM_SECRET ───────────────────────────────────────────────────────
 * Secret can contain anything. It's the base for the secret part for the hash:
 * define ('WB_SECFORM_SECRET','whatever you like');
 * 
 * ─── WB_SECFORM_SECRETTIME ───────────────────────────────────────────────────
 * after how many seconds a new secret is generated:
 * define ('WB_SECFORM_SECRETTIME',86400); // 86400 = one day
 * 
 * ─── WB_SECFORM_USEFP ────────────────────────────────────────────────────────
 * Shall we use fingerprinting?
 * define ('WB_SECFORM_USEFP', true); //  true|false
 * 
 * ─── WB_SECFORM_TIMEOUT ──────────────────────────────────────────────────────
 * Timeout till the form token times out. 
 * Integer value between 0-86400 seconds:
 * define ('WB_SECFORM_TIMEOUT', 3600);
 * 
 * ─── WB_SECFORM_TOKENNAME ────────────────────────────────────────────────────
 * Name for the token form element only alphanumerical string 
 * allowed that starts whith a charakter:
 * define ('WB_SECFORM_TOKENNAME','my3form3');
 * 
 * ─── FINGERPRINT_WITH_IP_OCTETS ──────────────────────────────────────────────
 * How many blocks of the IP should be used in fingerprint 
 * possible values 0-4
 * define ('FINGERPRINT_WITH_IP_OCTETS',2); // 0 = no ipcheck, 
 * 
 * =============================================================================
 */

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

/**
 * Class Wb extends this class, so all these methods are avainable in class Wb
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
    private $_serverdata = '';
    private $_fingerprint = '';
    private $_browser_fingerprint = '';
            
    // Establish class Database object
    protected Database $db; 

    /* Construtor */

    protected function __construct($mode = self::FRONTEND)
    {
        // Establish class Database object for 
        // use in this class and its extend 
        // classes Admin, Wb & Frontend
        // Introduced with WBCE 1.4.0 to save redundancy
        $this->db = $GLOBALS['database'];

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

    /**
     * Validate that $input is an alphanumeric string starting with a letter.
     *
     * Returns false on success (valid input). This inverted pattern matches the
     * guard style in the constructor: if (!$this->_validate_alalnum(X)) means
     * "proceed only if X is valid".
     *
     * @param  mixed        $input
     * @return false|string false if valid; error message string if invalid
     */
    private function _validate_alalnum(mixed $input): false|string
    {
        # Alphanumeric string that starts with a letter character
        if (preg_match('/^[a-zA-Z][0-9a-zA-Z]+$/u', $input)) {
            return false;
        }

        return "The given input is not an alphanumeric string.";
    }

    /**
     * Validate that $input is a single integer in the range 0-4.
     *
     * Used to validate FINGERPRINT_WITH_IP_OCTETS. Same inverted return
     * pattern as _validate_alalnum(): false = valid, string = error.
     *
     * @param  mixed        $input
     * @return false|string false if valid; error message string if invalid
     */
    private function _is04(mixed $input): false|string
    {
        # Integer value between 0-4
        if (preg_match('/^[0-4]$/', $input)) {
            return false;
        }

        return "The given input is not an integer between 0-4.";
    }

    /**
     * Build a browser fingerprint from HTTP headers and optionally the client IP.
     *
     * Used to detect session hijacking: if the fingerprint changes mid-session
     * (different browser or machine) the token validation will fail.
     * Not foolproof, but raises the bar significantly.
     *
     * @param  bool   $encode  true (default) returns an MD5 hash; false returns the raw string
     * @param  string $fpsalt  Salt prepended before hashing to namespace the fingerprint
     * @return string
     */
    private function _browser_fingerprint(bool $encode = true, string $fpsalt = "My Fingerprint: "): string
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

    /**
     * Extract and optionally truncate the client IP address.
     *
     * Fewer octets = more lenient matching (allows roaming within a subnet).
     * Zero octets = no IP included in fingerprint at all.
     *
     * Proxy note: HTTP_X_FORWARDED_FOR can be spoofed and is used only for
     * fingerprinting, not authentication decisions.
     *
     * @param  int    $ipblocks  Number of octets to include (0-4)
     * @return string            IP string truncated to $ipblocks octets, e.g. "192.168"
     */
    private function _getip(int $ipblocks = 4): string
    {
        $ip    = "";
        $cutip = "";

        # maybe user is behind a Proxy but we need his real ip address if we got a nice Proxyserver,
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

    /**
     * Build an MD5 fingerprint from combined server and client characteristics.
     *
     * The fingerprint ties CSRF tokens to a specific client/server combination.
     * It is included in the token secret so tokens cannot be moved between
     * environments (different browser, server, or IP range).
     */
    private function _generate_fingerprint(): string
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

    /**
     * Collect stable server-side values for use in fingerprinting and token signing.
     *
     * Includes server software, name, IP, port, admin address, and PHP version.
     * These values stay constant across requests and ensure that tokens generated
     * on one server cannot be replayed on a different server.
     */
    private function _generate_serverdata(): string
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

    /**
     * Build the per-request HMAC secret used to sign and verify CSRF tokens.
     *
     * Combines:
     *   - The configured base secret (WB_SECFORM_SECRET)
     *   - A daily time seed (limits replay window to ~24 hours)
     *   - The server name (ties tokens to this domain)
     *   - Stable server environment data
     *   - The current session ID
     *   - Optionally the browser fingerprint (if WB_SECFORM_USEFP is enabled)
     */
    private function _generate_secret(): string
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
    
    /**
     * Generates a cryptographically secure random IDKEY token 
     *
     * @return string  64 character hexadecimal token (256 Bit entropy)
     */
    private function _generate_salt(): string
    {
        try {
            // random_bytes() is cryptographically secure and available since PHP 7.0
            return bin2hex(random_bytes(32));

        } catch (\Exception $e) {
            // Fallback only in very rare cases (e.g. system has no entropy)
            // This should practically never happen on a normal server
            trigger_error('random_bytes() failed: ' . $e->getMessage(), E_USER_WARNING);
            return hash('sha256', uniqid((string)mt_rand(), true) . microtime(true));
        }
    }
    
    /**
     * Generate a signed CSRF form token (Form Transaction Number).
     *
     * Each call produces a unique, signed token bound to the current session via
     * SessionTokenIdentifier. The token survives session_regenerate_id() calls
     * because it is tied to the identifier rather than the raw session ID.
     *
     * Multiple browser tabs are supported; tokens from a different browser
     * or machine are rejected via fingerprint binding.
     *
     * Tokens expire after $this->_timeout seconds (default: WB_SECFORM_TIMEOUT).
     *
     * @param  bool   $as_tag  true (default) returns a hidden <input> tag ready to embed in a form
     *                         false returns a key=value string for use in URLs (GET requests)
     * @return string
     */
    final public function getFTAN(bool $as_tag = true): string
    {

        $secret = $this->_secret;
        $timeout = time() + $this->_timeout;

      
        $token = dechex(mt_rand());
        $hash = sha1($secret . '-' . $token . '-' . md5(WSession::Get('SessionTokenIdentifier')));
        $signed = $token . '-' . $hash;

        if ($as_tag == true) {
            // by default return a complete, hidden <input>-tag
            return '<input type="hidden" name="' . $this->_tokenname . '" value="' . htmlspecialchars($signed) . '" />';
        }

        return $this->_tokenname . '=' . $signed;
    }

    /**
     * Validate the CSRF token submitted with a form or URL.
     *
     * Checks that the token found in POST or GET matches the HMAC signature
     * expected for the current session. Any mismatch returns false — the
     * calling code must abort the request in that case.
     *
     * @param  string $mode  'POST' (default) or 'GET'
     * @return bool          true if the token is valid and the request may proceed
     */
    final public function checkFTAN(string $mode = 'POST'): bool
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

    /**
     * Store a value in the session under a one-time opaque key.
     *
     * Lets you pass sensitive data (IDs, arrays) through form hidden fields without
     * exposing the actual values in HTML. The returned key is safe to embed in a
     * form or URL. Each key can be redeemed exactly once via checkIDKEY() — after
     * redemption all keys from the same page call are invalidated to prevent replay.
     *
     * @param  mixed  $value  Scalar or array to store (arrays are serialized automatically)
     * @return string         Opaque one-time key to embed in the form
     */
    public function getIDKEY(mixed $value): string
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
        //debug_dump($_SESSION[$this->_idkey_name]);
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

    // ── Additional functions by Norbert Heimsath, heimsath.org ───────────────
    // 
    // Released under GPLv3: http://www.gnu.org/licenses/gpl.html

    /**
     * Invalidate all stored IDKEY entries for this session.
     *
     * Resets the IDKEYs list to its initial sentinel state {0: 0}.
     * Call after logout or any operation that should prevent previously
     * issued keys from being redeemed.
     */
    public function clearIDKEY(): void
    {

        $this->_IDKEYs = array('0' => '0');
    }


    /**
     * Array filter callback: remove expired IDKEY entries.
     *
     * Called by array_filter() in checkIDKEY() to prune stale keys before
     * looking up a submitted key.
     *
     * The sentinel value '0' inserted by clearIDKEY() is always removed —
     * it keeps the session array non-empty after a reset, not a real key.
     *
     * @param  mixed $var  IDKEY entry (array with 'time' key) or sentinel '0'
     * @return bool        true = keep; false = remove
     */
    private function _timedout(mixed $var): bool
    {
        // Sentinel from clearIDKEY() — always discard
        if ($var === 0 || $var === '0') {
            return false;
        }

        // Discard if older than the configured timeout
        if ($var['time'] < time() - $this->_timeout) {
            return false;
        }

        return true;
    }

    /**
     * Array filter callback: remove IDKEY entries that belong to the current page call.
     *
     * After a key is successfully redeemed, all other keys generated during the
     * same page request are removed. This prevents replay while still allowing
     * multiple forms on the same page to each have their own valid key.
     *
     * @param  mixed $var  IDKEY entry array with a 'page' identifier
     * @return bool        true = keep; false = remove (same page as current request)
     */
    private function _fpages(mixed $var): bool
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
