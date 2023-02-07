<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
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
    // Special matches for FE and BE
    $match = ($position == 'backend') ? '/\[\[\[(.*?)\]\]\]/' : '/\[\[(.*?)\]\]/';
	$openTag = ($position == 'backend') ? '[[[' : '[[';
	$closeTag = ($position == 'backend') ? ']]]' : ']]';
    // now get the actual matches
	if( preg_match_all( $match, $wb_page_data, $found_droplets ) ) {
        // loop over all matches
		foreach( $found_droplets[1] as $droplet ){
			if(array_key_exists( $openTag.$droplet.$closeTag, $droplet_tags) == false) {
                // go in if same droplet with same arguments is not processed already
                $varlist = array();
                
                // split each droplet command into droplet_name and request_string
                $tmp = preg_split('/\?/', $droplet, 2);
                $droplet_name = $tmp[0];
                $request_string = (isset($tmp[1]) ? $tmp[1] : '');
                if( $request_string != '' ){

                    // make sure we can parse the arguments correctly
                    $request_string = html_entity_decode($request_string, ENT_COMPAT,DEFAULT_CHARSET);
                    
                    // create array of arguments from query_string
                    $argv = preg_split( '/&(?!amp;)/', $request_string );
                    foreach ($argv as $argument){
                        // split argument in pair of varname, value
                        list( $variable, $value ) = explode('=', $argument,2);
                        if( !empty($value) ){
                            // re-encode the value and push the var into varlist
                            $varlist[$variable] = htmlentities($value, ENT_COMPAT,DEFAULT_CHARSET);
                        }
                    }
                } else {
                    // no arguments given, so
                    $droplet_name = $droplet;
                }

                // request the droplet code from database
                $sql = 'SELECT `code` FROM `'.TABLE_PREFIX.'mod_droplets` WHERE `name` LIKE "'.$droplet_name.'" AND `active` = 1';
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
		$max_loops = ((int)$max_loops = 0 ? 3 : (int)$max_loops);
		while( (processDroplets($wb_page_data, $position) == true) && ($max_loops > 0)){ 
			$max_loops--;
		}
		return $wb_page_data;
	}
