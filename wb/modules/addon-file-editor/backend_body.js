/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file includes the AFE JavaScript code required to toggle the Add-on overview sections.
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
*/

// check if jQuery is loaded in the WebsiteBaker backend
if (typeof jQuery != 'undefined' && typeof(WB_URL) != "undefined") {
	// include JavaScript code to toggle the addon overview sections
	$.getScript(WB_URL + "/modules/cwsoft-addon-file-editor/javascript/addon-file-editor.js", function(){
	});
}