<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga - www.dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.4+ / WB2.10+
 * @version         1.1.4.1
 * @lastmodified    July 30, 2022
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
$del = "javascript:confirm_link('Are you sure you want to delete the errorlog?','{$link}&delete=1');";


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
        setcookie($cookie, '0', 1);
        unset($_COOKIE[$cookie]);
    }
}

$view = 0;
$colorclass = "";
if (isset($_COOKIE[$cookie])) {
    $view = $_COOKIE[$cookie];
    $colorclass = " color";
}

$res = array($TXT['NO_ERROR_REPORT']);

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

// ── File Based Settings — toggle handlers ────────────────────────────────────
// Active (1) → set constant to true.
// Inactive (0) → delete the entry entirely to keep the global scope clean.
foreach (['PDO_CANONICAL_DEBUG', 'SQL_DEBUG', 'WBCE_DEBUG'] as $cfg) {
    if (isset($_GET[$cfg]) && in_array($_GET[$cfg], ['0', '1'], true)) {
        if ($_GET[$cfg] === '1') {
            Settings::setFileBasedSetting($cfg, true);
        } else {
            Settings::deleteFileBasedSetting($cfg);
        }
        header("Location: " . $link);
        exit;
    }
}
// ── END: File Based Settings ─────────────────────────────────────────────────


if (!isset($_SESSION['lastview'])) {
    $_SESSION['lastview'] = date('c');
}

if (file_exists($logfile)) {
    $res = file($logfile);
    unset($res[0]); // = strip_tags($res[0]);
    $res = array_slice($res, -$lines);
}
if (count($res) == 0) {
    $res = array($TXT['NO_ERROR_REPORT']);
}
$row = "even";
$last = strtotime($_SESSION['lastview']);

$tmpErrorLevel = error_reporting(-1);
$warning .= $tmpErrorLevel != E_ALL ? '<div class="errorlevel">Note: Do not forget to set your error reporting to the maximum level! Currently this is not the case.</div>':'';
?>

    <h2><?=$module_name?></h2>
<div class="tool-header">
    <ul class="header-links">
        <li class="reload-link"><a href="<?=$link ?>"><i class="fa fa-refresh"></i> <?=$TXT['RELOAD']?></a></li>
        <li class="delete-link"><a href="<?=$del ?>"><i class="fa fa-trash"></i> <?=$TXT['DELETE_LOGFILE']?></a></li>
        <li class="viewlink<?=$view == '0' ? ' active':''?>"><a href="<?=$link.'&color=0'?>"><?=$TXT['PLAIN_VIEW']?></a></li> 
        <li class="viewlink<?=$view == '1' ? ' active':''?>"><a href="<?=$link.'&color=1'?>"><?=$TXT['COLOR_VIEW']?></a></li> 
        <li class="viewlink<?=$view == '2' ? ' active':''?>"><a href="<?=$link.'&color=2'?>"><?=$TXT['TABLE_VIEW']?></a></li>
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
		if (strpos($line, "Visitor Request]")!== false) {
            $type = " usedurl";
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
            <td style="white-space: nowrap;">
                <?=$rec['date']?> <b><?=$rec['time']?></b>
            </td>    
            <td style="font-weight:bold;color: <?=$rec['color']?>;text-align: center;">
                <?=$rec['type']?>
            </td> 
            <?php if ($rec['type'] == 'Exception'): ?>
                <td colspan="2" style="text-align:left; color: red;">
                    <?=$rec['primary']?>
                </td>
            <?php elseif ($rec['type'] == 'Visitor Request'): ?>
                <td colspan="2" style="text-align:left; color: #355ff9;">
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
        echo $TXT['NO_ERROR_REPORT'];
    }
}

function debugToggleLink(string $url, string $param, string $label): string {
    $state = defined($param) ? (int) constant($param) : 0;
    $icon  = $state
        ? '<span style="color:green"><i class="fa fa-check-circle"></i></span>'
        : '<span style="color:red"><i class="fa fa-ban"></i></span>';
    $next  = $state ? '0' : '1';
    // WBCE_DEBUG is locked while WB_DEBUG is still hardcoded in config.php —
    // DEPRECATED_WB_DEBUG is set by initialize.php in exactly that case.
    if ($param === 'WBCE_DEBUG' && defined('DEPRECATED_WB_DEBUG')) {
        $str = L_('MSG:HARDCODED_PARAM_DETECTED', 'WB_DEBUG');
        return "<li><a class='disabled' title='{$str}'>{$icon} {$label}</a></li>";
    }
    return "<li><a href=\"{$url}&{$param}={$next}\">{$icon} {$label}</a></li>";
}
?>
</div>
<?php if (defined('DEPRECATED_WB_DEBUG')): ?>
<br>
<div class="constants-info">
    <p><?= $MSG['WB_DEBUG_DEPRECATED'] ?></p>
</div>
<?php endif ?>
<ul class="footer-links">
    <?= debugToggleLink($link, 'WBCE_DEBUG',           'WBCE Debug') ?>
    <?= debugToggleLink($link, 'SQL_DEBUG',           'SQL Debug') ?>
    <?= debugToggleLink($link, 'PDO_CANONICAL_DEBUG', 'PDO Syntax Debug') ?>
</ul>
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
		if (!array_key_exists(1,$aDateTime)) {$aDateTime[1]="00:00:00";}		
        $sColor = 'inherit';
        if ($sType == 'Visitor Request') {
            $sColor = '#355ff9';
        }
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
            'primary'   => isset($arr2[1][1]) ? $arr2[1][1]:'',
            'p_line'    => isset($arr2[2][0]) ? $arr2[2][0] : '?',
            'secondary' => isset($arr2[2][1]) ? trim(str_replace(['from', ':'], '', $arr2[2][1])):'',
            's_line'    => isset($arr2[3][0]) ? $arr2[3][0] :'?',
            'msg'       => isset($arr2[3][1]) ? trim($arr2[3][1]) : '??',
        );
    }
    return $aRetVal;
}
$_SESSION['lastview'] = date('c');