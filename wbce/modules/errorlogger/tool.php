<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga - www.dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.4+
 * @version         1.0
 * @lastmodified    November 12, 2019
 *
 */

/**
 * preinit.php is used to activate the errorhandler
 *
 * initialize.php is used to set the errorlevel to E_ALL regardless the WB setting
 *
 */

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if (!$admin->get_permission('admintools')) {
    die(header('Location: ../../index.php'));
}

$link = ADMIN_URL.'/admintools/tool.php?tool=errorlogger';
$del = "javascript:confirm_link('Are you sure you want to delete the errorlog?','$link&delete=1');";


// Use colored view, stored in cookie
$cookie = 'errorlog_colors';
$color_set = false;
if (isset($_GET['color'])) {
    if ($_GET['color']=='1') {
        setcookie($cookie, '1', time() + (86400 * 365));
        $_COOKIE[$cookie] = '1';
    } elseif ($_GET['color']=='2') {
        setcookie($cookie, '2', time() + (86400 * 365));
        $_COOKIE[$cookie] = '2';
    } else {
        setcookie($cookie, null, 1);
        unset($_COOKIE[$cookie]);
    }
}

$view = 0;
$colorclass = "";
if (isset($_COOKIE[$cookie])) {
    $view = $_COOKIE[$cookie];
    $colorclass = " color";
}

$res = array("Great news. No errors reported");

$lines = 250;
$warning = '';
$logfile = ini_get('error_log');
if (!$logfile) {
    $logfile = WB_PATH.'/var/logs/php_error.log.php';
}

if (isset($_GET['delete'])) {
    if (file_exists($logfile)) {
        $now = date("Ymd_Hi");
        rename($logfile, dirname($logfile).'/'.$now.'_php_error.log.php');
        $lname = str_replace(WB_PATH, '', dirname($logfile).'/'.$now.'_php_error.log.php');
        $warning .= '<div class="messagelevel">Message: Logfile renamed to '.$lname.'</div>';
    }
}

if (!isset($_SESSION['lastview'])) {
    $_SESSION['lastview'] = date('c');
}

if (file_exists($logfile)) {
    $res = file($logfile);
    unset($res[0]); // = strip_tags($res[0]);
    $res = array_slice($res, -$lines);
}
if (count($res) == 0) {
    $res = array("Great news. No errors reported");
}
$row = "even";
$last = strtotime($_SESSION['lastview']);

$tmpErrorLevel = error_reporting(-1);
$warning .= $tmpErrorLevel != E_ALL ? '<div class="errorlevel">Note: Do not forget to set your error reporting to the maximum level! Currently this is not the case.</div>':'';
?>

<div class="tool-header">
    <h2>Errorlog viewer</h2>
    <ul class="header-links">
        <li class="reload-link"><a href="<?=$link ?>"><i class="fa fa-refresh"></i> Reload</a></li>
        <li class="delete-link"><a href="<?=$del ?>"><i class="fa fa-trash"></i> Delete logfile</a></li>
        <li class="viewlink<?=$view == '0' ? ' active':''?>"><a href="<?=$link.'&color=0'?>">Plain View</a></li> 
        <li class="viewlink<?=$view == '1' ? ' active':''?>"><a href="<?=$link.'&color=1'?>">Color View</a></li> 
        <li class="viewlink<?=$view == '2' ? ' active':''?>"><a href="<?=$link.'&color=2'?>">Table View</a></li>
    </ul>
</div>
<?=$warning ?>
<div class="logviewWrapper<?=$colorclass ?>">
<?php
if ($view == '0' or $view == '1') {
    foreach ($res as $line) {
        $row = $row == "odd" ? "even" : "odd";
        $curline = strtotime(str_replace(array('[',']'), '', substr($line, 0, 26)));
        $row = $curline > $last ? " newline": $row;
        $type = '';
        if (strpos($line, "Notice]")!== false) {
            $type = " lognotice";
        }
        if (strpos($line, "Warning]")!== false) {
            $type = " logwarning";
        }
        if (strpos($line, "Error]")!== false) {
            $type = " logerror";
        }
        if (strpos($line, "PHP Parse error")!== false) {
            $type = " logerror";
        }
        if (strpos($line, "Exception]")!== false) {
            $type = " logerror";
        }
        if (strpos($line, "Deprecated]")!== false) {
            $type = " logdeprecated";
        }
        echo '<div class="logline '.$row.$type.'">'.$line.'</div>';
    }
} else {
    $aLines = log_to_array($res);
    if (count($aLines) >= 1) {
        ?>
    <table class="table-errlog">
        <tr>
            <th><?=$TEXT['DATE'].'/',$TEXT['TIME']?></th>
            <th>Type</th>
            <th>Source</th>
            <th>Message</th>
        </tr>
    <?php foreach ($aLines as $rec) {
            $row = $row == "odd" ? "even" : "odd"; ?>
        <tr class="<?=$row?> color-<?=$rec['color']?>">
            <td>
                <?=$rec['date']?> <b><?=$rec['time']?></b>
            </td>    
            <td style="font-weight:bold;color: <?=$rec['color']?>;text-align: center;">
                <?=$rec['type']?>
            </td> 
            <?php if ($rec['type'] == 'Exception'): ?>
                <td colspan="2" style="text-align:left; color: red;">
                    <?=$rec['primary']?>
                </td>
            <?php else: ?>
                <td>
                    <span class="file"><?=$rec['primary']?></span> <?php if ($rec['type'] != 'Exception'): ?>
                        <b>L:<?=$rec['p_line']?></b>
                    <?php endif; ?><br>
                    <?php if ($rec['type'] != 'Exception'): ?>
                    <i>from</i> <span class="file"><?=$rec['secondary']?></span> <b>L:<?=$rec['s_line']?></b>
                    <?php endif; ?>
                </td>    
                <td>
                    <?=$rec['msg']?>
                </td>
            <?php endif; ?>
        </tr>
    <?php
        } ?>
    </table>
    <?php
    } else {
        echo 'Great news. No errors reported?';
    }
}
?>
</div>
<?php
function log_to_array($aLines)
{
    $aRetVal = [];
    foreach ($aLines as $line) {
        $arr = explode('[', $line);
        $arr2 = [];
        foreach ($arr as $parts) {
            $arr2[] = explode(']', $parts);
        }
        if (!empty($arr2) && isset($arr2[1][0])) {
            $sType = $arr2[1][0];
        } else {
            continue;
        }
        $aDateTime = explode('T', $arr2[0][0]);
        $sColor = 'inherit';
        if ($sType == 'Exception') {
            $sColor = 'red';
        }
        if ($sType == 'Warning') {
            $sColor = 'red';
        }
        $aRetVal[] = array(
            'color'     => $sColor,
            'date'      => $aDateTime[0],
            'time'      => trim(str_replace('+00:00', '', $aDateTime[1])),
            'type'      => $sType,
            'primary'   => str_replace(':', '', $arr2[1][1]),
            'p_line'    => isset($arr2[2]) ? $arr2[2][0] : '',
            'secondary' => trim(str_replace(['from', ':'], '', $arr2[2][1])),
            's_line'    => $arr2[3][0],
            'msg'       => trim($arr2[3][1]),
        );
    }
    return $aRetVal;
}
$_SESSION['lastview'] = date('c');
