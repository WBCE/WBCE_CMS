<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright    Ryan Djurovich (2004-2009)
 * @copyright    WebsiteBaker Org. e.V. (2009-2015)
 * @copyright    WBCE Project (2015-)
 * @category     tool
 * @package      OPF E-Mail
 * @version      1.1.7
 * @authors      Martin Hecht (mrbaseman)
 * @link         https://forum.wbce.org/viewtopic.php?id=176
 * @license      GNU GPL2 (or any later version)
 * @platform     WBCE 1.x
 * @requirements OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    // Stop this file being access directly
    if (!headers_sent()) {
        header("Location: ../index.php", true, 301);
    }
    die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

/**
 * protect email addresses (replace '@' and '.' and obfuscate address
 * @param string $content
 * @return string
 */
function doFilterEmail($content)
{

    // check if required arguments are defined otherwise define
    if (!defined('OPF_AT_REPLACEMENT') or !defined('OPF_DOT_REPLACEMENT')) {
        Settings::Set('OPF_AT_REPLACEMENT', "(at)");
        Settings::Set('OPF_DOT_REPLACEMENT', "(dot)");
    }

    // Search for Maillinks

    // first search part to find all mailto email addresses
    $pattern = '#(<a[^<]*href\s*?=\s*?"\s*?mailto\s*?:\s*?)([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,25})([^"]*?)"([^>]*>)(.*?)</a>';

    // second part to find all non mailto email addresses
    $pattern .= '|(value\s*=\s*"|\')??\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,25})\b#i';
    /**
     *  Patterns explained
     *  Sub 1:\b(<a.[^<]*href\s*?=\s*?"\s*?mailto\s*?:\s*?)         --> "<a id="yyy" class="xxx" href = " mailto :" ignoring white spaces
     *  Sub 2:([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,25})         --> the email address in the mailto: part of the mail link
     *  Sub 3:([^"]*?)"                                             --> possible ?Subject&cc... stuff attached to the mail address
     *  Sub 4:([^>]*>)                                              --> all class or id statements after the mailto but before closing ..>
     *  Sub 5:(.*?)</a>\b                                           --> the mailto text; all characters between >xxxxx</a>
     *  Sub 6:|\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,25})\b    --> email addresses which may appear in the text (require word boundaries)
     */
    // Do the actual work
    // find all email addresses embedded in the content and filter them using a callback function

    $new_content = preg_replace_callback($pattern, '_cbDoExecuteFilter', $content);

    // If necessary (Both settings true) and email links have been found, include mdcr.js
    if (Settings::Get('OPF_MAILTO_FILTER')
        and Settings::Get('OPF_JS_MAILTO')
        and ($content != $new_content)) {
        // use insert class to include the needed js file
        I::insertJsFile(get_url_from_path(__DIR__ .'/js/mdcr.js', 'BODY BTM-'));
    }
    return $new_content;
}

/**
 * @brief  Obfuscate email address while keeping it in a human readable format.
 *         This solution is based on: http://www.wbwip.com/wbw/emailencoder.html
 * 
 * @param  string  $str
 * @return string
 */
function readable_obfuscation($str){
    $arr = [
        'a' => '&#97;',
        'b' => '&#98;',
        'c' => '&#99;',
        'd' => '&#100;',
        'e' => '&#101;',
        'f' => '&#102;',
        'g' => '&#103;',
        'h' => '&#104;',
        'i' => '&#105;',
        'j' => '&#106;',
        'k' => '&#107;',
        'l' => '&#108;',
        'm' => '&#109;',
        'n' => '&#110;',
        'o' => '&#111;',
        'p' => '&#112;',
        'q' => '&#113;',
        'r' => '&#114;',
        's' => '&#115;',
        't' => '&#116;',
        'u' => '&#117;',
        'v' => '&#118;',
        'w' => '&#119;',
        'x' => '&#120;',
        'y' => '&#121;',
        'z' => '&#122;',
        
        // further obfuscate `@` and `.` for email harvesting bots 
        '@' => '<span>&#64;</span>', 
        '.' => '<span>&#46;</span>',
    ];
    return strtr($str, $arr);
}

/**
 * callback-function for function _doFilterEmail() to proceed search results
 * @param array results from preg_replace
 * @return string proceeded replacement string
 */
function _cbDoExecuteFilter($match)
{
    $search = array('@', '.');
    $replace = array(Settings::Get('OPF_AT_REPLACEMENT') ,Settings::Get('OPF_DOT_REPLACEMENT'));

    // check if the match contains the expected number of subpatterns (6|8)
    switch (count($match)) {

        case 8:
        /** OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN TEXT **/
        // 1.. text mails only, 3.. text mails + mailto (no JS), 7 text mails + mailto (JS)
            if (!Settings::Get('OPF_EMAIL_FILTER')) {
                return $match[0];
            }
            // do not filter mail addresses included in input tags (<input ... value = "test@mail)
            if (strpos($match[6], 'value') !== false) {
                return $match[0];
            }
            // filtering of non mailto email addresses enabled
            $replacement = str_replace($search, $replace, $match[0]);
            return readable_obfuscation($replacement);
        break;

        case 6:
        /** OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN MAILTO LINKS **/
        // 2.. mailto only (no JS), 3.. text mails + mailto (no JS), 6.. mailto only (JS), 7.. all filters active
            if (!Settings::Get('OPF_MAILTO_FILTER')) {
                return $match[0];
            }
            // check if last part of the a href link: >xxxx</a> contains a email address we need to filter
            $pattern = '#[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,25}#i';
            if (preg_match_all($pattern, $match[5], $matches)) {
                foreach ($matches as $submatch) {
                    foreach ($submatch as $value) {
                        // replace all . and all @ in email address parts by (dot) and (at) strings
                        $match[5] = str_replace($value, str_replace($search, $replace, $value), $match[5]);
                        $match[5] = readable_obfuscation($match[5]);
                    }
                }
            }
            // check if Javascript encryption routine is enabled
            if (Settings::Get('OPF_JS_MAILTO')) {
                /** USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/
                // extract possible class and id attribute from ahref link
                preg_match('/class\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $class_attr);
                $class_attr = empty($class_attr) ? '' : 'class="' . $class_attr[2] . '" ';
                preg_match('/title\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $title_attr);
                $title_attr = empty($title_attr) ? '' : 'title="' . $title_attr[2] . '" ';

                preg_match('/id\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $id_attr);
                $id_attr = empty($id_attr) ? '' : 'id="' . $id_attr[2] . '" ';
                preg_match('/style\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $style_attr);
                $style_attr = empty($style_attr) ? '' : 'style="' . $style_attr[2] . '" ';
                // preprocess mailto link parts for further usage
                $search = array('@', '.', '_', '-');
                $replace = array('F', 'Z', 'X', 'K');
                $email_address = str_replace($search, $replace, strtolower($match[2]));
                $email_subject = rawurlencode(html_entity_decode($match[3]));
                // create a random encryption key for the Caesar cipher
                $shift = mt_rand(1, 25);
                // encrypt the email using an adapted Caesar cipher
                $encrypted_email = "";
                for ($i = strlen($email_address) -1; $i > -1; $i--) {
                    if (preg_match('#[FZXK0-9]#', $email_address[$i], $characters)) {
                        $encrypted_email .= $email_address[$i];
                    } else {
                        $encrypted_email .= chr((ord($email_address[$i]) -97 + $shift) % 26 + 97);
                    }
                }
                $encrypted_email .= chr($shift + 97);
                // build the encrypted Javascript mailto link
                if (strpos($class_attr, 'fa-') == false) {
                    $mailto_link  = "<a {$class_attr}{$title_attr}{$id_attr}{$style_attr}href=\"javascript:mdcr('$encrypted_email','$email_subject')\">" .$match[5] ."</a>";
                } else {
                    $mailto_link  = "<a {$title_attr}{$id_attr}{$style_attr}href=\"javascript:mdcr('$encrypted_email','$email_subject')\">" .$match[5] ."</a>";
                }
                return $mailto_link;
            } else {
                /** DO NOT USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/
                // as minimum protection, replace @ in the mailto part by (at)
                // dots are not transformed as this would transform my.name@domain.com into: my(dot)name(at)domain(dot)com
                // rebuild the mailto link from the subpatterns (at the missing characters " and </a>")
                return $match[1] .str_replace('@', Settings::Get('OPF_AT_REPLACEMENT'), $match[2]) .$match[3] .'"' .$match[4] .$match[5] .'</a>';
                // if you want to protect both, @ and dots, comment out the line above and remove the comment from the line below
            // return $match[1] .str_replace($search, $replace, $match[2]) .$match[3] .'"' .$match[4] .$match[5] .'</a>';
            }
        break;

        default:
        // number of subpatterns do not match the requirements ... do nothing
            return $match[0];
        break;
    }
}

function opff_mod_opf_email(&$content, $page_id, $section_id, $module, $wb)
{
    if (Settings::Get('opf_email', true)) {
        if (!(Settings::Get('OPF_MAILTO_FILTER') || Settings::Get('OPF_JS_MAILTO') || Settings::Get('OPF_EMAIL_FILTER'))) {
            Settings::Set('OPF_MAILTO_FILTER', 1);
            Settings::Set('OPF_JS_MAILTO', 1);
            Settings::Set('OPF_EMAIL_FILTER', 1);
        }
        $content = doFilterEmail($content);
    }
    return(true);
}
