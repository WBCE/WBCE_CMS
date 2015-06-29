<?php
/**
 *
 * @category        framework
 * @package         include
 * @author		    Christophe Dolivet (EditArea), Christian Sommer (WB wrapper)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: wb_wrapper_edit_area.php 1533 2011-12-08 00:05:20Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/include/editarea/wb_wrapper_edit_area.php $
 * @lastmodified    $Date: 2011-12-08 01:05:20 +0100 (Do, 08. Dez 2011) $
 *
 */

function loader_help()
{

?>
<script type="text/javascript">
/*<![CDATA[*/
		var url  = '<?php print WB_URL; ?>/include/editarea/edit_area_full.js';
		try{
			script = document.createElement("script");
			script.type = "text/javascript";
			script.src  = url;
			script.charset= "UTF-8";
			head = document.getElementsByTagName("head")[0];
			head[0].appendChild(script);
		}catch(e){
			document.write("<script type='text/javascript' src='" + url + "' charset=\"UTF-8\"><"+"/script>");
		}
/*]]>*/
</script>

<?php

}
if (!function_exists('registerEditArea')) {
	function registerEditArea(
                $id = 'code_area',
                $syntax = 'php',
                $syntax_selection = true,
                $allow_resize = 'both',
                $allow_toggle = true,
                $start_highlight = true,
                $min_width = 600,
                $min_height = 450,
                $toolbar = 'default'  )
	{

		// set default toolbar if no user defined was specified
		if ($toolbar == 'default') {
			$toolbar = 'search, fullscreen, |, undo, redo, |, select_font, syntax_selection, |, highlight, reset_highlight, |, help';
			$toolbar = (!$syntax_selection) ? str_replace('syntax_selection,', '', $toolbar) : $toolbar;
		}

		// check if used Website Baker backend language is supported by EditArea
		$language = 'en';
		if (defined('LANGUAGE') && file_exists(dirname(__FILE__) . '/langs/' . strtolower(LANGUAGE) . '.js'))
        {
			$language = strtolower(LANGUAGE);
		}

		// check if highlight syntax is supported by edit_area
		$syntax = in_array($syntax, array('css', 'html', 'js', 'php', 'xml')) ? $syntax : 'php';

		// check if resize option is supported by edit_area
		$allow_resize = in_array($allow_resize, array('no', 'both', 'x', 'y')) ? $allow_resize : 'no';

		if(!defined('LOADER_HELP')) {
			loader_help();
	        define('LOADER_HELP', true);
		}

		// return the Javascript code
		$result = <<< EOT
		<script type="text/javascript">
			editAreaLoader.init({
				id: 				'$id',
				start_highlight:	$start_highlight,
				syntax:			    '$syntax',
				min_width:			$min_width,
				min_height:		    $min_height,
				allow_resize: 		'$allow_resize',
				allow_toggle: 		'$allow_toggle',
				toolbar: 			'$toolbar',
				language:			'$language'
			});
		</script>
EOT;
		return $result;
	}
}

if (!function_exists('getEditAreaSyntax')) {
	function getEditAreaSyntax($file) 
	{
		// returns the highlight scheme for edit_area
		$syntax = 'php';
		if (is_readable($file)) {
			// extract file extension
			$file_info = pathinfo($file);
		
			switch ($file_info['extension']) {
				case 'htm': case 'html': case 'htt':
					$syntax = 'html';
	  				break;

	 			case 'css':
					$syntax = 'css';
	  				break;

				case 'js':
					$syntax = 'js';
					break;

				case 'xml':
					$syntax = 'xml';
					break;

	 			case 'php': case 'php3': case 'php4': case 'php5':
					$syntax = 'php';
	  				break;

				default:
					$syntax = 'php';
					break;
			}
		}
		return $syntax ;
	}
}

?>