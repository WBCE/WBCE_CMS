<?php
/**
 * WBCE CMS AdminTool: Tool Account Settings
 * 
 * @platform    WBCE CMS 1.3.2 and higher
 * @package     modules/UserBase
 * @author      Christian M. Stefan <stefek@designthings.de>
 * @copyright   Christian M. Stefan
 * @license     see LICENSE.md of this package
 */
 
// prevent this file from being accessed directly
defined('WB_PATH') or exit("insufficient privileges" . __FILE__);

// check if user is allowed to use admin-tools (to prevent this file 
// to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) exit("insuficient privileges");
?>
<p><b><?=$TOOL_TXT['OVERVIEW_DESCRIPTION']; ?></b></p>
<?php
$sPluginsURL = get_url_from_path(__DIR__).'/js';

I::insertCssFile($sPluginsURL."/jquery.tablesorter/theme.wbEasy.css",                    "HEAD BTM-");
I::insertJsFile( $sPluginsURL."/jquery.tablesorter/jquery.tablesorter.js",               "HEAD BTM-");
I::insertJsFile( $sPluginsURL."/jquery.tablesorter/jquery.tablesorter.widgets.js",       "HEAD BTM-");
I::insertJsFile( $sPluginsURL."/jquery.tablesorter/tablesorter_accout_tool_settings.js", "BODY BTM-");

// show all users?
$aUsers = $oAccounts->get_users_overview();
if(!empty($aUsers)){
    $field_row_framer_none = true; // global var, used in field_row_framer() function		
?>
<div style="min-height:600px">
<table class="tablesorter">
    <thead>
        <tr>			
            <th style="width:20px;"><small><?=$TOOL_TXT['USER_ID']?></small></th>			
            <th><?=$TEXT['DISPLAY_NAME']?> <i>(<?=$TEXT['USERNAME']?>)</i></th>
            <th style="width:20px;"><?=$TEXT['LANGUAGE']?></th>
            <th><?=$TEXT['EMAIL']?></th>
            <th><?=$TOOL_TXT['GROUPS']?></th>
            <th><?=$TOOL_TXT['DATE_REGISTERED']?></th>
            <th><?=$TOOL_TXT['LATEST_LOGIN']?></th>
            <th data-filter="false"><?=$TOOL_TXT['USER_CORE_ACTIVATED']?></th>
            <?php if (in_array('users_modify', $_SESSION['SYSTEM_PERMISSIONS'])): ?>
            <th data-sorter="false" data-filter="false"></th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($aUsers as $rec){
            $sLangFlag = is_file(WB_PATH.'/languages/'.$rec['language'].'.png') ? $rec['language'] : strtolower($rec['language']);
            ?>
            <tr>
                <td style="text-align:right;"><small><?=$rec['user_id']?></small></td>
                <td>
                    <b><?=$rec['username']?></b>
                </td>
                <td style="text-align:center;">
                    <img src="<?=WB_URL.'/languages/'.$sLangFlag?>.png" /> <small><?=$rec['language']?></small>
                </td>
                <td><?=$rec['email']?></td>
                <td>
                    <?php 
                        foreach($rec['groups'] as $sGroupName){
                            echo '<span class="usergroup">'.$sGroupName.'</span>';
                        }				
                    ?>
                </td>
                <td>
                    <i class="fa fa-1x fa-calendar<?=($rec['signup_timestamp']) ? '' : '-o';?>"></i>&nbsp;
                    <small><?=$rec['signup_timestamp'] ? gmdate("Y-m-d", $rec['signup_timestamp']) : 'N/A'?></small>
                </td>
                <td>
                    <i class="fa fa-1x fa-calendar<?=($rec['login_when']) ? '' : '-o';?>"></i> &nbsp;
                    <small><?=$rec['login_when'] ? gmdate("Y-m-d  H:i", $rec['login_when']) : 'N/A'?></small>
                </td>
                <td class="center">
                    <?php 
                        $bActive = $database->get_one("SELECT `active` FROM `{TP}users` WHERE `user_id` = ".$rec['user_id']);
                    ?>
                    <i class="fa fa-1x fa-user-circle<?=$bActive ? '-o' : ''; ?>" style="color:<?=$bActive ? '#48bf40' : '#edabab'; ?>"></i>
                </td>
                <?php if (in_array('users_modify', $_SESSION['SYSTEM_PERMISSIONS'])): 				
                    $JsOnClick = 'onclick="javascript: setTimeout(function(){location.reload();}, 1000);return true;"';
                    // the onclick function above will force the window to reload and doing so will refresh all the IdKEY values
                ?>
                    <td style="text-align:center;">
                        <?php if($rec['user_id'] == 1): 
                                if($admin->isSuperAdmin()):
                        ?>
                                <a class="button" href="<?=ADMIN_URL?>/preferences/" <?=$JsOnClick?> title="<?=$TEXT['MODIFY']?>" target="_blank">
                                     &nbsp;<i class="fa fa-pencil-square-o "></i></a>
                            <?php else: ?>
                                nothing to click
                            <?php endif; ?>
                        <?php else: ?>
                            <a class="button" href="<?=ADMIN_URL?>/users/users.php?user_id=<?=$admin->getIDKEY($rec['user_id'])?>&modify=1" <?=$JsOnClick?> title="<?=$TEXT['MODIFY'].' '.$TEXT['USER']?>" target="_blank">
                                     &nbsp;<i class="fa fa-pencil-square-o "></i></a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
            <?php 
        }// endforeach
        ?>		        
    </tbody>
</table>
<?php 
    $sToCss = "
        .tablesorter thead .disabled {
            display:none;
        }
    ";
    I::insertCssCode($sToCss, 'HEAD BTM-', 'tablesorter');

} //endif