<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: tool.php 1520 2011-11-09 00:12:37Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/tool.php $
 * @lastmodified    $Date: 2011-11-09 01:12:37 +0100 (Mi, 09. Nov 2011) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
require_once( dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
if(!defined('WB_PATH')) { throw new IllegalFileException(); }
/* -------------------------------------------------------- */

    $modPath = str_replace('\\', '/', dirname(__FILE__)).'/';
    $msgTxt = '';
    $msgCls = 'msg-box';
// include the modules language definitions
    if(!is_readable($modPath.'languages/'.LANGUAGE .'.php')) {
        require_once($modPath.'languages/EN.php');
    } else {
        require_once($modPath.'languages/'.LANGUAGE .'.php');
    }
// check if data was submitted
    if($doSave) {
    // take over post - arguments
        $data = array();
        $data['sys_rel']       = (int)(intval(isset($_POST['sys_rel']) ? $_POST['sys_rel'] : 0) != 0);
        $data['email_filter']  = (int)(intval(isset($_POST['email_filter']) ? $_POST['email_filter'] : 0) != 0);
        $data['mailto_filter'] = (int)(intval(isset($_POST['mailto_filter']) ? $_POST['mailto_filter'] : 0) != 0);
        $data['at_replacement']  = isset($_POST['at_replacement']) ? trim(strip_tags($_POST['at_replacement'])) : '';
        $data['dot_replacement'] = isset($_POST['dot_replacement']) ? trim(strip_tags($_POST['dot_replacement'])) : '';
        if ($admin->checkFTAN()) {
        // update database settings
            $sql = 'UPDATE `'.TABLE_PREFIX.'mod_output_filter` SET '.
                      '`email_filter`='.$data['email_filter'].', '.
                      '`sys_rel`='.$data['sys_rel'].', '.
                      '`mailto_filter`='.$data['mailto_filter'].', '.
                      '`at_replacement`=\''.$database->escapeString($data['at_replacement']).'\', '.
                      '`dot_replacement`=\''.$database->escapeString($data['dot_replacement']).'\'';
            if($database->query($sql)) {
            //anything ok
                $msgTxt = $MESSAGE['RECORD_MODIFIED_SAVED'];
                $msgCls = 'msg-box';
            }else {
            // database error
                $msgTxt = $MESSAGE['RECORD_MODIFIED_FAILED'];
                $msgCls = 'error-box';
            }
        }else {
        // FTAN error
            $msgTxt = $MESSAGE['GENERIC_SECURITY_ACCESS'];
            $msgCls = 'error-box';
        }
    }else {
    // read settings from the database to show
        require_once($modPath.'filter-routines.php');
        $data = getOutputFilterSettings();
    }
    // write out header if needed
    if(!$admin_header) { $admin->print_header(); }
    if( $msgTxt != '') {
    // write message box if needed
        echo '<div class="'.$msgCls.'">'.$msgTxt.'</div>';
    }
?>
<h2><?php echo $MOD_MAIL_FILTER['HEADING']; ?></h2>
<p><?php echo $MOD_MAIL_FILTER['HOWTO']; ?></p>
<form name="store_settings" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
    <?php echo $admin->getFTAN(); ?>
    <input type="hidden" name="action" value="save" />
    <table width="98%" cellspacing="0" cellpadding="5px" class="row_a">
    <tr><td colspan="2"><strong><?php echo $MOD_MAIL_FILTER['BASIC_CONF'];?>:</strong></td></tr>
    <tr>
        <td width="35%"><?php echo $MOD_MAIL_FILTER['SYS_REL'];?>:</td>
        <td>
            <input type="radio" <?php echo ($data['sys_rel']=='1') ? 'checked="checked"' :'';?>
                name="sys_rel" value="1"><?php echo $MOD_MAIL_FILTER['ENABLED'];?>
            <input type="radio" <?php echo (($data['sys_rel'])=='0') ? 'checked="checked"' :'';?>
                name="sys_rel" value="0"><?php echo $MOD_MAIL_FILTER['DISABLED'];?>
        </td>
    </tr>
    <tr>
        <td width="35%"><?php echo $MOD_MAIL_FILTER['EMAIL_FILTER'];?>:</td>
        <td>
            <input type="radio" <?php echo ($data['email_filter']=='1') ?'checked="checked"' :'';?>
                name="email_filter" value="1"><?php echo $MOD_MAIL_FILTER['ENABLED'];?>
            <input type="radio" <?php echo (($data['email_filter'])=='0') ?'checked="checked"' :'';?>
                name="email_filter" value="0"><?php echo $MOD_MAIL_FILTER['DISABLED'];?>
        </td>
    </tr>
    <tr>
        <td><?php echo $MOD_MAIL_FILTER['MAILTO_FILTER'];?>:</td>
        <td>
            <input type="radio" <?php echo ($data['mailto_filter']=='1') ?'checked="checked"' :'';?>
                name="mailto_filter" value="1"><?php echo $MOD_MAIL_FILTER['ENABLED'];?>
            <input type="radio" <?php echo (($data['mailto_filter'])=='0') ?'checked="checked"' :'';?>
                name="mailto_filter" value="0"><?php echo $MOD_MAIL_FILTER['DISABLED'];?>
        </td>
    </tr>
    <tr><td colspan="2"><br /><strong><?php echo $MOD_MAIL_FILTER['REPLACEMENT_CONF'];?>:</strong></td></tr>
    <tr>
        <td><?php echo $MOD_MAIL_FILTER['AT_REPLACEMENT'];?>:</td>
        <td><input type="text" style="width: 160px" value="<?php echo $data['at_replacement'];?>" 
            name="at_replacement"/></td>
    </tr>
    <tr>
        <td><?php echo $MOD_MAIL_FILTER['DOT_REPLACEMENT'];?>:</td>
        <td><input type="text" style="width: 160px" value="<?php echo $data['dot_replacement'];?>" 
            name="dot_replacement"/></td>
    </tr>
    </table>
    <input type="submit" style="margin-top:10px; width:140px;" value="<?php echo $TEXT['SAVE']; ?>" />
</form>
