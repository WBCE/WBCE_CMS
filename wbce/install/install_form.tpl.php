<?php
/**
    @file
    @brief Simply the Template file for the Installer form
*/
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>WBCE CMS Installation Wizard</title>
<link href="normalize.css" rel="stylesheet" type="text/css" />
<link href="stylesheet.css" rel="stylesheet" type="text/css" />
<script>
function confirm_link(message, url) {
    if(confirm(message)) location.href = url;
}
function change_os(type) {
    if(type == 'linux') {
        document.getElementById('operating_system_linux').checked = true;
        document.getElementById('operating_system_windows').checked = false;
        document.getElementById('file_perms_box').style.display = 'block';
    } else if(type == 'windows') {
        document.getElementById('operating_system_linux').checked = false;
        document.getElementById('operating_system_windows').checked = true;
        document.getElementById('file_perms_box').style.display = 'none';
    }
}
</script>
</head>
<body>
<div class="body">
    <table class="header">
        <tbody>
            <tr>
                <td><img class="logo"  src="logo.png" alt="WBCE CMS Logo" />
                    <h1>Welcome to the Installation Wizard</h1></td>
            </tr>
        </tbody>
    </table>
    <form name="website_baker_installation_wizard" action="save.php" method="post">
        <input type="hidden" name="url" value="" />
        <input type="hidden" name="username_fieldname" value="admin_username" />
        <input type="hidden" name="password_fieldname" value="admin_password" />
        <input type="hidden" name="remember" id="remember" value="true" />
        <?php if (isset($_SESSION['message']) and !empty($_SESSION['message'])): ?>
        <div class="warningbox"  >
            <?php foreach ($_SESSION['message'] as $message):?>
            <b>Error: </b><?php echo $message?><br>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
        <table class="step1" >
            <thead>
                <tr>
                    <th class="step-row"> <h2 class="step-row">Step 1</h2>
                        &nbsp;Please check the following requirements are met before continuing... </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($sSessionSupportClass == 'bad') :?>
                <tr>
                    <td colspan="4" class="error">Please note: PHP Session Support may appear disabled if your browser does not support cookies.</td>
                </tr>
                <?php endif;?>
                <tr>
                    <td >PHP Version >= 7.1.3</td>
                    <td><span class="<?php echo $sPhpVersion?>"> <?php echo PHP_VERSION;?> </span></td>
                    <td >PHP Session Support</td>
                    <td><span class="<?php echo $sSessionSupportClass?>"> <?php echo $sSessionSupportText?> </span></td>
                </tr>
                <tr>
                    <td >Server Default Charset</td>
                    <td><span class="<?php echo $chrval?>"> <?php echo(($chrval == 'good') ? $e_adc.' OK' : $e_adc)?> </span></td>
                    <td>PHP Safe Mode</td>
                    <td><span class="<?php echo $sSaveModeClass?>"> <?php echo $sSaveModeText?> </span></td>
                </tr>
                <?php if ($chrval == "bad") :?>
                <tr>
                    <td colspan="4" class="bad"><p class="warning"> <b>Please note:</b> Yor webserver is configured to deliver <b><?php echo $e_adc;?></b> charset only.<br />
                            To display national special characters (e.g.: &auml; &aacute;) in clear manner, switch off this preset please(or let it do by your hosting provider).<br />
                            In any case you can choose <b><?php echo $e_adc;?></b> in the settings of WBCE.<br />
                            But this solution does not guarantee a correct displaying of the content from all modules! </p></td>
                </tr>
                <?php endif;?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php print $wb_root . $configFile?></td>
                    <td><?php echo $config?></td>
                    <td><?php print $wb_root?>/languages/</td>
                    <td><?php echo $sDirLanguages ?></td>
                </tr>
                <tr>
                    <td><?php print $wb_root?>/pages/</td>
                    <td><?php echo $sDirPages ?></td>
                    <td><?php print $wb_root?>/templates/</td>
                    <td><?php echo $sDirTemplates ?></td>
                </tr>
                <tr>
                    <td><?php print $wb_root?>/media/</td>
                    <td><?php echo $sDirMedia ?></td>
                    <td><?php print $wb_root?>/modules/</td>
                    <td><?php echo $sDirModules ?></td>
                </tr>
                <tr>
                    <td><?php print $wb_root?>/var/</td>
                    <td><?php echo $sDirVar ?></td>
                    <td><?php print $wb_root?>/temp/</td>
                    <td><?php echo $sDirTemp ?></td>
                </tr>
            </tbody>
        </table>
        <?php if ($installFlag == true) : ?>
        <table class="step2">
            <thead>
                <tr>
                    <th class="step-row"> <h2 class="step-row">Step 2</h2>
                        &nbsp;Please check following settings... </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name">Website Title:</td>
                    <td class="value"><input <?php echo field_error('website_title');?> type="text" tabindex="1" name="website_title" value="<?php echo $sWebsiteTitle ?>" /></td>
                </tr>
                <tr>
                    <td class="name">Absolute URL:</td>
                    <td class="value"><input <?php echo field_error('wb_url');?> type="text" tabindex="2" name="wb_url" value="<?php echo $sWbUrl ?>" /></td>
                </tr>
                <tr>
                    <td class="name">Default Timezone:</td>
                    <td class="value"><select <?php echo field_error('default_timezone');?> tabindex="3" name="default_timezone">
                            <?php foreach ($aZones as $fOffset): ?>
                            <option value="<?php echo (string)$fOffset ?>" <?php if (TzSelected($fOffset)) {
    echo 'selected="selected"';
} ?> > 
                            <?php echo 'GMT ' . (($fOffset > 0) ? '+' : '') . (($fOffset == 0) ? '' : (string) $fOffset . ' Hours') ?> </option>
                            <?php endforeach ; ?>
                        </select></td>
                </tr>
                <tr>
                    <td class="name">Default Language:</td>
                    <td class="value"><select <?php echo field_error('default_language');?> tabindex="4" name="default_language">
                            <?php foreach ($aAllowedLanguages as $sLangCode=>$Language): ?>
                            <option value="<?php echo $sLangCode ?>" <?php if (LangSelected($sLangCode)) {
    echo 'selected="selected"';
} ?> > 
                            <?php echo$Language ?> </option>
                            <?php endforeach ; ?>
                        </select>
                        <?php // echo "<pre>";print_r($aAllowedLanguages);echo "</pre>";?></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr style="display: none;">
                    <td class="name">Server Operating System:</td>
                    <td ><input type="radio" tabindex="5" name="operating_system" id="operating_system_linux" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="linux"<?php echo $sLinux ?> />
                        <span style="cursor: pointer;" onclick="javascript: change_os('linux');">Linux/Unix based</span> <br />
                        <input type="radio" tabindex="6" name="operating_system" id="operating_system_windows" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="windows"<?php echo $sWindows ?> />
                        <span style="cursor: pointer;" onclick="javascript: change_os('windows');">Windows</span></td>
                </tr>
                <tr style="display: none;">
                    <td class="name">&nbsp;</td>
                    <td class="value"><div id="file_perms_box">
                            <input type="checkbox" name="world_writeable" id="world_writeable" value="true" <?php echo $sWorldWriteableCheck?> />
                            <label for="world_writeable"> World-writeable file permissions (777) </label>
                            <br />
                            <p class="warning">(Please note: only recommended for testing environments) <br />
                                You can adjust this setting later in the Backend<br />
                            </p>
                        </div></td>
                </tr>
            </tbody>
        </table>
        <table class="step3">
            <thead>
                <tr>
                    <th class="step-row"> <h2 class="step-row">Step 3</h2>
                        &nbsp;Please enter your database server details below... </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name">Host Name:</td>
                    <td class="value"><input <?php echo field_error('database_host');?> type="text" tabindex="7" name="database_host" value="<?php echo $sDatabaseHost ?>" /></td>
                </tr>
                <tr>
                    <td class="name">Database Name:</td>
                    <td class="value"><input <?php echo field_error('database_name')?> type="text" tabindex="8" name="database_name" pattern="[a-zA-Z0-9_-]+" value="<?php echo $sDatabaseName ?>" />
                        <span>&nbsp;([a-zA-Z0-9_-])</span></td>
                </tr>
                <tr>
                    <td class="name">Table Prefix:</td>
                    <td class="value"><input <?php echo field_error('table_prefix')?> type="text" tabindex="9" name="table_prefix" pattern="[a-z0-9_]+" value="<?php echo $sTablePrefix ?>" />
                        <span>&nbsp;([a-z0-9_])</span></td>
                </tr>
                <tr>
                    <td class="name">Username:</td>
                    <td class="value"><input <?php echo field_error('database_username');?> type="text" tabindex="10" name="database_username" value="<?php echo $sDatabaseUsername ?>" /></td>
                </tr>
                <tr>
                    <td class="name">Password:</td>
                    <td class="value"><input type="password" tabindex="11" name="database_password" value="<?php echo $sDatabasePassword ?>" /></td>
                </tr>
            </tbody>
        </table>
        <table class="step4">
            <thead>
                <tr>
                    <th class="step-row"> <h2 class="step-row">Step 4</h2>
                        Please enter your Administrator account details below... </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name">Loginname:</td>
                    <td class="value"><input <?php echo field_error('admin_username');?> type="text" tabindex="12" name="admin_username" value="<?php echo $sAdminUsername ?>" /></td>
                </tr>
                <tr>
                    <td class="name">E-Mail:</td>
                    <td class="value"><input <?php echo field_error('admin_email');?> type="email" tabindex="13" name="admin_email" value="<?php echo $sAdminEmail ?>" /></td>
                </tr>
                <tr>
                    <td class="name">Password:</td>
                    <td class="value"><input <?php echo field_error('admin_password');?> type="password" tabindex="14" name="admin_password" minlength="6" value="<?php echo $sAdminPassword ?>" /></td>
                </tr>
                <tr>
                    <td class="name">Repeat Password:</td>
                    <td class="value"><input <?php echo field_error('admin_repassword');?> type="password" tabindex="15" name="admin_repassword" minlength="6" value="<?php echo $sAdminRepassword ?>" /></td>
                </tr>
            </tbody>
        </table>
        <?php endif; // installFlag ?>
        <table class="step5">
            <tbody>
                <tr valign="top">
                    <td><strong>Please note: &nbsp;</strong></td>
                </tr>
                <tr valign="top">
                    <td><p class="warning"> WBCE is released under the <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU General Public License</a>. <br />
                            By clicking install, you are accepting the license. </p></td>
                </tr>
                <tr valign="top">
                    <td><p class="center">
                            <?php if ($installFlag == false): ?>
                            <input type="submit" disabled="disabled" tabindex="16" name="install" value="Check your Settings in Step 1 and reload with F5" />
                            <?php else :?>
                            <input type="submit" tabindex="16" name="install" value="Install WBCE CMS" />
                            <?php endif; // installFlag ?>
                        </p></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<div class="version">WBCE Version: <?php echo NEW_WBCE_VERSION ?></div>
</body>
</html>