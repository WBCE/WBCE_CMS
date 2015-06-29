<?php
/**
 *
 * @category        module
 * @package         droplets
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: droplets.php 1503 2011-08-18 02:18:59Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/droplets/droplets.php $
 * @lastmodified    $Date: 2011-08-18 04:18:59 +0200 (Do, 18. Aug 2011) $
 *
 *	droplets are small codeblocks that are called from anywhere in the template.
 * 	To call a droplet just use [[dropletname]]. optional parameters for a droplet can be used like [[dropletname?parameter=value&parameter2=value]]\
 *
 *  1.0.2, bugfix, Reused the evalDroplet function so the extracted parameters will be only available within the scope of the eval and cleared when ready.
 *  1.0.3, optimize, reduce memory consumption, increase speed, remove CSS, enable nested droplets
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

	function do_eval($_x_codedata, $_x_varlist, &$wb_page_data)
	{
		extract($_x_varlist, EXTR_SKIP);
		return(eval($_x_codedata));
	}

	function processDroplets( &$wb_page_data ) {
// collect all droplets from document
		$droplet_tags = array();
		$droplet_replacements = array();
		if( preg_match_all( '/\[\[(.*?)\]\]/', $wb_page_data, $found_droplets ) )
		{
			foreach( $found_droplets[1] as $droplet )
			{
				if(array_key_exists( '[['.$droplet.']]', $droplet_tags) == false)
				{
// go in if same droplet with same arguments is not processed already
					$varlist = array();
// split each droplet command into droplet_name and request_string
					$tmp = preg_split('/\?/', $droplet, 2);
					$droplet_name = $tmp[0];
					$request_string = (isset($tmp[1]) ? $tmp[1] : '');
					if( $request_string != '' )
					{
// make sure we can parse the arguments correctly
						$request_string = html_entity_decode($request_string, ENT_COMPAT,DEFAULT_CHARSET);
// create array of arguments from query_string
						$argv = preg_split( '/&(?!amp;)/', $request_string );
						foreach ($argv as $argument)
						{
// split argument in pair of varname, value
							list( $variable, $value ) = explode('=', $argument,2);
							if( !empty($value) )
							{
// re-encode the value and push the var into varlist
								$varlist[$variable] = htmlentities($value, ENT_COMPAT,DEFAULT_CHARSET);
							}
						}
					}
					else
					{
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
						if ($newvalue == '' && $newvalue !== true)
						{
							if(DEBUG === true)
							{
								$newvalue = '<span class="mod_droplets_err">Error in: '.$droplet.', no valid returnvalue.</span>';
							}
							else
							{
								$newvalue = true;
							}
						}
						if ($newvalue === true) { $newvalue = ""; }
// remove any defined CSS section from code. For valid XHTML a CSS-section is allowed inside <head>...</head> only!
						$newvalue = preg_replace('/<style.*>.*<\/style>/siU', '', $newvalue);
// push droplet-tag and it's replacement into Search/Replace array after executing only
						$droplet_tags[]         = '[['.$droplet.']]';
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

	function evalDroplets( &$wb_page_data, $max_loops = 3 ) {
		$max_loops = ((int)$max_loops = 0 ? 3 : (int)$max_loops);
		while( (processDroplets($wb_page_data) == true) && ($max_loops > 0))
		{ 
			$max_loops--;
		}
		return $wb_page_data;
	}
