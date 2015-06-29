<?php
/**
 * protect email addresses (replace '@' and '.' and obfuscate address
 * @param string $content
 * @return string
 */
	function doFilterEmail($content, $output_filter_mode) {
	// test if js-decryption is installed
		if( preg_match('/<head.*<.*src=\".*\/mdcr.js.*>.*<\/head/siU', $content) ) {
			$output_filter_mode |= pow(2, 2); // n | 2^2
		}else {
		// try to insert js-decrypt into <head> if available
			$script = str_replace('\\', '/',str_replace(WB_PATH,'', dirname(__FILE__)).'/js/mdcr.js');
			if(is_readable(WB_PATH.$script)) {
				$scriptLink = '<script src="'.WB_URL.$script.'" type="text/javascript"></script>';
				$regex = '/(.*)(<\s*?\/\s*?head\s*>.*)/isU';
				$replace = '$1'.$scriptLink.'$2';
				$content = preg_replace ($regex, $replace, $content);
				$output_filter_mode |= pow(2, 2); // n | 2^2
			}
		}
	// define some constants so we do not call the database in the callback functions again
		define('OUTPUT_FILTER_MODE', (int)$output_filter_mode);
/* *** obfuscate mailto addresses by js:mdcr *** */
		// work out the defined output filter mode: possible output filter modes: [0], 1, 2, 3, 6, 7
		// 2^0 * (0.. disable, 1.. enable) filtering of mail addresses in text
		// 2^1 * (0.. disable, 1.. enable) filtering of mail addresses in mailto links
		// 2^2 * (0.. disable, 1.. enable) Javascript mailto encryption (only if mailto filtering enabled)

		// first search part to find all mailto email addresses
		$pattern = '#(<a[^<]*href\s*?=\s*?"\s*?mailto\s*?:\s*?)([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})([^"]*?)"([^>]*>)(.*?)</a>';
		// second part to find all non mailto email addresses
		$pattern .= '|(value\s*=\s*"|\')??\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})\b#i';
/*
	Sub 1:\b(<a.[^<]*href\s*?=\s*?"\s*?mailto\s*?:\s*?)			-->	"<a id="yyy" class="xxx" href = " mailto :" ignoring white spaces
	Sub 2:([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})			-->	the email address in the mailto: part of the mail link
	Sub 3:([^"]*?)"												--> possible ?Subject&cc... stuff attached to the mail address
	Sub 4:([^>]*>)												--> all class or id statements after the mailto but before closing ..>
	Sub 5:(.*?)</a>\b											--> the mailto text; all characters between >xxxxx</a>
	Sub 6:|\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})\b		--> email addresses which may appear in the text (require word boundaries)
*/
		// find all email addresses embedded in the content and filter them using a callback function
		$content = preg_replace_callback($pattern, '_cbDoExecuteFilter', $content);
		return $content;
	}
/* ************************************************************************** */
/**
 * callback-function for function _doFilterEmail() to proceed search results
 * @param array results from preg_replace
 * @return string proceeded replacement string
 */
	function _cbDoExecuteFilter($match) {
		// check if required arguments are defined
		if(!( defined('OUTPUT_FILTER_MODE')
		      && defined('OUTPUT_FILTER_AT_REPLACEMENT')
		      && defined('OUTPUT_FILTER_MODE')
    	    ) ) {
			return $match[0];
		}
		$search = array('@', '.');
		$replace = array(OUTPUT_FILTER_AT_REPLACEMENT ,OUTPUT_FILTER_DOT_REPLACEMENT);
		// check if the match contains the expected number of subpatterns (6|8)
		switch (count($match)) {
			case 8:
			/** OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN TEXT **/
			// 1.. text mails only, 3.. text mails + mailto (no JS), 7 text mails + mailto (JS)
				if(!in_array(OUTPUT_FILTER_MODE, array(1,3,7))) return $match[0];
				// do not filter mail addresses included in input tags (<input ... value = "test@mail)
				if (strpos($match[6], 'value') !== false) return $match[0];
				// filtering of non mailto email addresses enabled
				return str_replace($search, $replace, $match[0]);
			break;
			case 6:
			/** OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN MAILTO LINKS **/
			// 2.. mailto only (no JS), 3.. text mails + mailto (no JS), 6.. mailto only (JS), 7.. all filters active
				if(!in_array(OUTPUT_FILTER_MODE, array(2,3,6,7))) return $match[0];
				// check if last part of the a href link: >xxxx</a> contains a email address we need to filter
				$pattern = '#[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}#i';
				if(preg_match_all($pattern, $match[5], $matches)) {
					foreach($matches as $submatch) {
						foreach($submatch as $value) {
						// replace all . and all @ in email address parts by (dot) and (at) strings
							$match[5] = str_replace($value, str_replace($search, $replace, $value), $match[5]);
						}
					}
				}
				// check if Javascript encryption routine is enabled
				if(in_array(OUTPUT_FILTER_MODE, array(6,7))) {
				/** USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/
				// extract possible class and id attribute from ahref link
					preg_match('/class\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $class_attr);
					$class_attr = empty($class_attr) ? '' : 'class="' . $class_attr[2] . '" ';
					preg_match('/id\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $id_attr);
					$id_attr = empty($id_attr) ? '' : 'id="' . $id_attr[2] . '" ';
				// preprocess mailto link parts for further usage
					$search = array('@', '.', '_', '-'); $replace = array('F', 'Z', 'X', 'K');
					$email_address = str_replace($search, $replace, strtolower($match[2]));
					$email_subject = rawurlencode(html_entity_decode($match[3]));
				// create a random encryption key for the Caesar cipher
					mt_srand((double)microtime()*1000000);	// (PHP < 4.2.0)
					$shift = mt_rand(1, 25);
				// encrypt the email using an adapted Caesar cipher
					$encrypted_email = "";
					for($i = strlen($email_address) -1; $i > -1; $i--) {
						if(preg_match('#[FZXK0-9]#', $email_address[$i], $characters)) {
							$encrypted_email .= $email_address[$i];
						} else {
							$encrypted_email .= chr((ord($email_address[$i]) -97 + $shift) % 26 + 97);
						}
					}
					$encrypted_email .= chr($shift + 97);
				// build the encrypted Javascript mailto link
					$mailto_link  = "<a {$class_attr}{$id_attr}href=\"javascript:mdcr('$encrypted_email','$email_subject')\">" .$match[5] ."</a>";
					return $mailto_link;
				} else {
				/** DO NOT USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/
				// as minimum protection, replace @ in the mailto part by (at)
				// dots are not transformed as this would transform my.name@domain.com into: my(dot)name(at)domain(dot)com
				// rebuild the mailto link from the subpatterns (at the missing characters " and </a>")
					return $match[1] .str_replace('@', OUTPUT_FILTER_AT_REPLACEMENT, $match[2]) .$match[3] .'"' .$match[4] .$match[5] .'</a>';
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
