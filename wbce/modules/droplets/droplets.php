<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ruud Eisinga (Ruud) John (PCWacht)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

function do_eval($_x_codedata, $_x_varlist, &$wb_page_data)
{
    extract($_x_varlist, EXTR_SKIP);
    try {
        // till PHP 5 eval returns false and proceeds code execution in case of errors
        if (defined('WB_DEBUG') && WB_DEBUG) {
            return(eval($_x_codedata));
        } else {
            return(@eval($_x_codedata));
        }
    } catch (ParseError $e) {
        // PHP 7+ throws a ParseError exception if error occur inside eval
        // show error message caused by missformed Droplet code so we know what to be fixed
        if (defined('WB_DEBUG') && WB_DEBUG) {
            return '<strong>Droplet error: </strong>' . $e->getMessage() . '<br />';
        }
    }
    return false;
	}



/**
    @brief process all droplets
    @param string $wb_page_data
    @param string $position ('frontend'|'backend')
*/
function processDroplets( &$wb_page_data, $position = 'frontend' ) {
    
    // collect all droplets from document
    $droplet_tags = array();
    $droplet_replacements = array();
    // tags already handled in this run (assoc: tag => true); $droplet_tags is a
    // numeric list, so array_key_exists() on it never detected a duplicate
    $processed_tags = array();
    // Special matches for FE and BE
    $match = ($position == 'backend') ? '/\[\[\[(.*?)\]\]\]/' : '/\[\[(.*?)\]\]/';
	$openTag = ($position == 'backend') ? '[[[' : '[[';
	$closeTag = ($position == 'backend') ? ']]]' : ']]';
    // now get the actual matches
	if( preg_match_all( $match, $wb_page_data, $found_droplets ) ) {
        // loop over all matches
		foreach( $found_droplets[1] as $droplet ){
			if(array_key_exists( $openTag.$droplet.$closeTag, $processed_tags) == false) {
                // go in if same droplet with same arguments is not processed already
                $processed_tags[$openTag.$droplet.$closeTag] = true;
                $varlist = array();
                
                // split each droplet command into droplet_name and request_string
                $tmp = preg_split('/\?/', $droplet, 2);
                $droplet_name = $tmp[0];
                $request_string = (isset($tmp[1]) ? $tmp[1] : '');
                if( $request_string != '' ){

                    // make sure we can parse the arguments correctly
                    $request_string = html_entity_decode($request_string, ENT_QUOTES,DEFAULT_CHARSET);

                    // create array of arguments from query_string
                    $argv = preg_split( '/&(?!amp;)/', $request_string );
                    foreach ($argv as $argument){
                        // split argument in pair of varname, value
                        list( $variable, $value ) = array_pad(explode('=', $argument,2), 2, '');
                        // only valid PHP variable names (they are extract()ed in do_eval)
                        if( !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $variable) ){
                            continue;
                        }
                        if( !empty($value) ){
                            // re-encode the value and push the var into varlist
                            $varlist[$variable] = htmlentities($value, ENT_QUOTES,DEFAULT_CHARSET);
                        }
                    }
                } else {
                    // no arguments given, so
                    $droplet_name = $droplet;
                }

                // only names a droplet can actually have (same rule as
                // droplets_valid_name() in functions.inc.php); anything else
                // in [[...]] is no droplet call and is skipped without a query
                if (!preg_match('/^[A-Za-z0-9_\-]{1,32}$/', $droplet_name)) {
                    continue;
                }

                // request the droplet code from database
                // $droplet_name comes from page content ([[Name]]); escape it so
                // it cannot break out of the "..." literal (SQL injection,
                // whose result would then be eval()'d). '=' instead of LIKE, so
                // '_' is no wildcard; the column collation stays case-insensitive.
                $sNameEsc = $GLOBALS['database']->escapeString($droplet_name);
                $sql = 'SELECT `code` FROM `'.TABLE_PREFIX.'mod_droplets` WHERE `name` = "'.$sNameEsc.'" AND `active` = 1';
                $codedata = $GLOBALS['database']->get_one($sql);
                if (!is_null($codedata))
                {
                    $newvalue = do_eval($codedata, $varlist, $wb_page_data);
// check returnvalue (must be a string of 1 char at least or (bool)true
                    if ($newvalue == '' && $newvalue !== true) {
                        // do debug output on debug mode
                        if(defined ('WB_DEBUG') and WB_DEBUG === true){
                            $newvalue = '<span class="mod_droplets_err">Error in: '.$droplet.', no valid returnvalue.</span>';
                        } else {
                            $newvalue = true;
                        }
                    }
                    if ($newvalue === true) { $newvalue = ""; }

                    // remove any defined CSS section from code. For valid XHTML a CSS-section is allowed inside <head>...</head> only!
                    // deactivated this filter as we have an output filter that moves all CSS up to head . so no need for struggle
                    //$newvalue = preg_replace('/<style.*>.*<\/style>/siU', '', $newvalue);

                    // push droplet-tag and it's replacement into Search/Replace array after executing only
                    $droplet_tags[]         = $openTag.$droplet.$closeTag;
                    $droplet_replacements[] = $newvalue;
                }
            }
        }	// End foreach( $found_droplets[1] as $droplet )
        // replace each Droplet-Tag with coresponding $newvalue
        $wb_page_data = str_replace($droplet_tags, $droplet_replacements, $wb_page_data);
    }
    // returns TRUE if droplets found in content, FALSE if not
    return( count($droplet_tags)!=0 );
}

/**
    @brief eval droplets 
    @param string $wb_page_data
    @param string $position ('frontend'|'backend')
    @param int    $max_loops

    Multiple runns so you get droplets created by droplets  up to 3 levels 
*/
	function evalDroplets( &$wb_page_data, $position = 'frontend', $max_loops = 3 ) {
		$max_loops = (int)$max_loops;
		if ($max_loops <= 0) { $max_loops = 3; }
		// check the counter first, so processDroplets() runs at most $max_loops times
		while( ($max_loops > 0) && (processDroplets($wb_page_data, $position) == true) ){
			$max_loops--;
		}
		return $wb_page_data;
	}
