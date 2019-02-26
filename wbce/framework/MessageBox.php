<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 * 
 * @brief      This extend is applying processTranslation() methods to message strings
 *             and implements class Insert methods for jQuery handling.
 *
 * @category   framework
 * @package    Message extend class for FlashMessages 
 * @copyright  2015 Mike Everhart & PlasticBrain Media LLC for parent class
 * @copyright  Christian M. Stefan <stefek@designthings.de> for child class 
 * @license    The MIT License (MIT) 
 *
 *
 * The parent class can be found here:
 * https://github.com/plasticbrain/PhpFlashMessages
 * http://mikeeverhart.net/php-flash-messages/
 */
 
 
require_once WB_PATH . "/include/FlashMessages/src/FlashMessages.php"; // get parent class

#WbAuto::AddFile("FlashMessages", "/framework/FlashMessages.php"); // parent class

class MessageBox extends FlashMessages {


    public    $ShowMissingKeys = false;
    
	
    protected $closeBtn = '<button type="button" class="close" 
        data-dismiss="alert" 
        aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>';
	
	// Message types and order
    // 
    // Note:  The order that message types are listed here is the same order 
    // they will be printed on the screen (ie: when displaying all messages)
    // 
    // This can be overridden with the display() method by manually defining
    // the message types in the order you want to display them. For example:
    // 
    // $msg->display([$msg::SUCCESS, $msg::INFO, $msg::ERROR, $msg::WARNING])
    // 
    protected $cssClassMap = [ 
        self::INFO    => 'alert alert-info',
        self::SUCCESS => 'alert alert-success',
        self::WARNING => 'alert alert-warning',
        self::ERROR   => 'alert alert-danger',
    ];

	
    // Each message gets wrapped in this
    // protected $msgWrapper = '<div class="%s"><h4>%s</h4>%s</div>'; 
    
	
    /**
     * @brief  Display the flash messages
     * 
     * @param  mixed   $mTypes   (null)  print all of the message types
     *                          (array)  print the given message types
     *                          (string)   print a single message type
     * @param  bool    $bPrint   Whether to print the data or return it
     * @return string
     * 
     */
    public function display($mTypes = null, $bPrint = true) 
    {

        if (!isset($_SESSION['flash_messages'])){
            return false;
        }

        $sRetVal = '';

        // Print all the message types
        if (is_null($mTypes) || !$mTypes || (is_array($mTypes) && empty($mTypes))) {
            $mTypes = array_keys($this->msgTypes);

            // Print multiple message types (as defined by an array)
        } elseif (is_array($mTypes) && !empty($mTypes)) {
            $theTypes = $mTypes;
            $mTypes = [];
            foreach ($theTypes as $type) {
                $mTypes[] = strtolower($type[0]);
            }

            // Print only a single message type
        } else {
            $mTypes = [strtolower($mTypes[0])];
        }


        // Retrieve and format the messages, then remove them from session data
        foreach ($mTypes as $type) {
            if (!isset($_SESSION['flash_messages'][$type]) || empty($_SESSION['flash_messages'][$type])){
                continue;
            }
            foreach ($_SESSION['flash_messages'][$type] as $msgData) {
                if (
                        strpos($msgData['message'], ':') !== false 
                ) {
                    $msgData['message'] = $this->processTranslation($msgData['message']);
                }
                $sRetVal .= $this->formatMessage($msgData, $type);
            }
            $this->clear($type);
        }
		
        // close button
        $sToJs = <<<_JsCode
    jQuery(document).ready(function($) {
        $('.close').on( 'click', function( event ) {
            $( event.target ).closest( '.dismissable' ).hide();
        });
_JsCode;
                
        $iTimer = defined('REDIRECT_TIMER') ? REDIRECT_TIMER : 0;
        $iTimer = $iTimer > 10000 ? 10000 : $iTimer;
        if($iTimer != -1){
            // timer isn't off, apply the fade off timer
            $sToJs .= <<<_JsCode
        $('.dismissable').delay($iTimer).fadeOut('slow');
_JsCode;
        }
        $sToJs .= "});";         
        I::insertJsCode($sToJs, 'BODY BTM-', 'MessageBox');

        // Print everything to the screen (or return the data)
        if ($bPrint) echo   $sRetVal;
        else         return $sRetVal;
    }
    
    
    /**
     * @brief  redirect or window location, depending on 
     *         whether or not headers already sent
     * 
     * @param  string $sUrl
     * @return object $this
     */
    public function redirect($sUrl = '')
    {   
        if ($sUrl != '') {
            if (!headers_sent()){
                header('Location: ' . $sUrl);
                exit();
            } else {
                echo '<script>window.location="' . $sUrl . '";</script>';
            }
        }
        return $this;
    }
    
    
    /**
     * 	processTranslation
     * 	-----------------------
     *
     * Correct formats are:
     *    'ARRAY:KEY' or 
     *    '{ARRAY:KEY}' (with curling braces)
     * example:
     *     processTranslation('TEXT:ACTIVE');
     *     processTranslation('{TEXT:ACTIVE}');
     *
     * 	@author   Christian M. Stefan <stefek@designthings.de>
     * 	@license  MIT, GNU/GPL v.2 and any higher
     * 	@param    string
     * 	@return   string Translated String
     */
    public function processTranslation($sStr) {
        $sRetVal = $sStr;
        if (strposm($sStr, ':') !== false) {
            $aToks = array();
            $aReplacements = array();
            if (strposm($sStr, '{') !== false) {
                preg_match_all('/{(.*?)}/', $sStr, $out);
                $aParts = array();
                foreach ($out[0] as $sToken) {
                    preg_match_all('/{(.*?)}/', $sToken, $out);
                    $aParts[] = explode(':', $out[1][0]);
                    $aToks[] = $sToken;
                }
                foreach ($aParts as $aTmp) {
                    $aReplacements[] = $this->_translate($aTmp[0], $aTmp[1]);
                }
                $sRetVal = str_replace($aToks, $aReplacements, $sStr);
            } else {
                $aTmp    = explode(':', $sStr);
                $sRetVal = $this->_translate($aTmp[0], $aTmp[1]);
                
            } 
        }
        return $sRetVal;
    }
    
    public function processTranslationX($sStr) {
        $sRetVal = $sStr;
        if (strposm($sStr, ':')) {
            
            if (strposm($sStr, '{')) {
                $aToks = array();
                $aParts = array();
                $aReplacements = array();
			
                preg_match_all('/{(.*?)}/', $sStr, $out);
                foreach ($out[0] as $sToken) {
                    preg_match_all('/{(.*?)}/', $sToken, $out);
                    $aParts[] = explode(':', $out[1][0]);
                    $aToks[]  = $sToken;
                }
                foreach ($aParts as $aTmp) {
                        $aReplacements[] = $this->_translate($aTmp[0], $aTmp[1]);
                }				
                $sRetVal = str_replace($aToks, $aReplacements, $sStr);
            } else {
                    $aTmp[] = explode(':', $sStr);
                    $sRetVal = $this->_translate($aTmp[0], $aTmp[1]);
            }
        }
        return $sRetVal;
    }
	
    private function _processTranslation($sStr) {
        $sRetVal = '';
        $sToken = '';		
        if (strpos($sStr, ':') !== false) {
            $aStrParts = explode(':', $sStr);
            $sArrName  = $aStrParts[0];
            $sToken    = $aStrParts[1];			
        }elseif (strpos($sStr, '_') !== false) {
            $aStrParts = explode('_', $sStr);
            $sArrName = array_shift($aStrParts);
            $sToken = implode('_', $aStrParts);
        }
        if($sToken != ''){
            $sRetVal = $this->_translate($sArrName, $sToken);
        }			
        return $sRetVal;
    }

    /**
     * this private method is only used with processTranslation method
     */
    private function _translate($sArrName, $sKey) {
        $sMissingKeyTPL = '<span style="color:purple">
        <b>Missing Translation: </b>
        <input style="width:450px" type="text" value="$%s[\'%s\']"></span>';

        if (is_array(@$GLOBALS[$sArrName])) {
            if (array_key_exists($sKey, $GLOBALS[$sArrName])) {
                return $GLOBALS[$sArrName][$sKey];
            } else {
                if ($this->ShowMissingKeys == true)
                    return sprintf($sMissingKeyTPL, $sArrName, $sKey);
                else
                    return '<span style="color:purple">' . str_replace('_', ' ', $sKey) . '</span>';
            }
        }else {
            if ($this->ShowMissingKeys == true)
                return 'Array <b>' . $sArrName . '</b> does not exist.<br>'
                        . sprintf($sMissingKeyTPL, $sArrName, $sKey);
            else
                return '<span style="color:purple">' . str_replace('_', ' ', $sKey) . '</span>';
        }
    }
	
    /**
     * Format a message
     * 
     * @param  array  $msgDataArray   Array of message data
     * @param  string $type           The $msgType
     * @return string                 The formatted message
     * 
    protected function formatMessage($msgDataArray, $type)
    {
     
        $msgType = isset($this->msgTypes[$type]) ? $type : $this->defaultType;
        $cssClass = $this->msgCssClass . ' ' . $this->cssClassMap[$type];
        $msgBefore = $this->msgBefore;

        // If sticky then append the sticky CSS class
        if ($msgDataArray['sticky']) {
            $cssClass .= ' ' . $this->stickyCssClass;

        // If it's not sticky then add the close button
        } else {
            $msgBefore = $this->closeBtn . $msgBefore;
        }

        // Wrap the message if necessary
        $formattedMessage = $msgBefore . $msgDataArray['message'] . $this->msgAfter; 

        return sprintf(
            $this->msgWrapper, 
            $cssClass, 
			$this->L_('TEXT:'.strtoupper($this->msgTypes[$type])),
            $formattedMessage
        );
    }
	
     */
	
    public function L_($sStr){
        return $this->processTranslation($sStr);
    }

}