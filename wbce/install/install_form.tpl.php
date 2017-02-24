<?php
/**
    @file
    @brief Simply the Template file for the Installer form

    @todo Remove the spagetty ;-)
*/
?><!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>WBCE Installation Wizard</title>
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
        <table>
            <tbody>
                <tr class="header" >
                    <td>
                        <img class="logo"  src="logo.png" alt="WBCE CMS Logo" />
                    </td>
                    <td>
                        <h1 class="headline" >Installation Wizard</h1>
                    </td>
                </tr>
            </tbody>
        </table>

        <form name="website_baker_installation_wizard" action="save.php" method="post">
            <input type="hidden" name="url" value="" />
            <input type="hidden" name="username_fieldname" value="admin_username" />
            <input type="hidden" name="password_fieldname" value="admin_password" />
            <input type="hidden" name="remember" id="remember" value="true" />

            <div class="welcome">
                Welcome to the Installation Wizard of WBCE CMS.
            </div>
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
                        <th colspan="4" class="step-row">
                            <h1 class="step-row">Step 1</h1>
                            &nbsp;Please check the following requirements are met before continuing...
                        </th>
                    </tr>
                </thead>
                <tbody>
<?php if ($sSessionSupportClass == 'bad') :?>
                    <tr>
                        <td colspan="6" class="error">Please note: PHP Session Support may appear disabled if your browser does not support cookies.</td>
                    </tr>
<?php endif;?>
                    <tr>
                        <td >PHP Version >= 5.3.6</td>
                        <td>
                            <span class="<?php echo $sPhpVersion?>">
                                <?php echo PHP_VERSION;?>
                            </span>
                        </td>
                        <td >PHP Session Support</td>
                        <td>
                            <span class="<?php echo $sSessionSupportClass?>">
                                <?php echo $sSessionSupportText?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td >Server DefaultCharset</td>
                        <td>
                            <span class="<?php echo $chrval?>">
                                <?php echo (($chrval == 'good') ? $e_adc.'OK' : $e_adc)?>
                            </span>
                        </td>
                        <td>PHP Safe Mode</td>
                        <td>
                            <span class="<?php echo $sSaveModeClass?>">
                                <?php echo $sSaveModeText?>
                            </span>
                        </td>
                    </tr>
<?php if ($chrval == "bad") :?>
                    <tr>
                        <td colspan="6" style="font-size: 10px;" class="bad">
                            <p class="warning">
                                <b>Please note:</b> Yor webserver is configured to deliver <b><?php echo $e_adc;?></b> charset only.<br />
                                To display national special characters (e.g.: &auml; &aacute;) in clear manner, switch off this preset please(or let it do by your hosting provider).<br />
                                In any case you can choose <b><?php echo $e_adc;?></b> in the settings of WebsiteBaker CE.<br />
                                But this solution does not guarantee a correct displaying of the content from all modules!
                            </p>
                        </td>
                    </tr>
<?php endif;?>
                </tbody>
            </table><!-- class step1 -->

            <table class="step2">
                <thead>
                    <tr>
                        <th colspan="4" class="step-row">
                        <h1 class="step-row">Step 2</h1>&nbsp;Please check the following files/folders are writeable before continuing...
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php print $wb_root . $configFile?></td>
                        <td colspan="3"  ><?php echo $config?></td>
                    </tr>
                    <tr>
                        <td><?php print $wb_root?>/pages/</td>
                        <td><?php echo $sDirPages ?></td>
                        <td><?php print $wb_root?>/media/</td>
                        <td><?php echo $sDirMedia ?></td>
                    </tr>
                    <tr>
                        <td><?php print $wb_root?>/templates/</td>
                        <td><?php echo $sDirTemplates ?></td>
                        <td><?php print $wb_root?>/modules/</td>
                        <td><?php echo $sDirModules ?></td>
                    </tr>
                    <tr>
                        <td><?php print $wb_root?>/languages/</td>
                        <td><?php echo $sDirLanguages ?></td>
                        <td><?php print $wb_root?>/temp/</td>
                        <td><?php echo $sDirTemp ?></td>
                    </tr>
                    <tr>
                        <td><?php print $wb_root?>/config/</td>
                        <td><?php echo $sDirConfig ?></td>
                        <td><?php print $wb_root?>/var/</td>
                        <td><?php echo $sDirVar ?></td>
                    </tr>
                </tbody>
            </table><!-- class step2 -->

<?php if ($installFlag == true) : ?>

            <table class="step3">
                <thead>
                    <tr>
                        <th colspan="4" class="step-row">
                            <h1 class="step-row">Step 3</h1>&nbsp;Please check URL settings, and select a default timezone and a default backend language...
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">Absolute URL:</td>
                        <td class="value">

                            <input <?php echo field_error('wb_url');?> type="text" tabindex="1" name="wb_url" style="width: 99%;" value="<?php echo $sWbUrl ?>" />
                        </td>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="name">Default Timezone:</td>
                        <td class="value">
                            <select <?php echo field_error('default_timezone');?> tabindex="3" name="default_timezone" style="width: 100%;">

                            <?php foreach ($aZones as $fOffset): ?>
                                <option value="<?php echo (string)$fOffset ?>" <?php if (TzSelected($fOffset)) echo 'selected="selected"' ?> >
                                    <?php echo 'GMT ' . (($fOffset > 0) ? '+' : '') . (($fOffset == 0) ? '' : (string) $fOffset . ' Hours') ?>
                                </option>
                            <?php endforeach ; ?>

                            </select>
                        </td><!-- class value -->
                    </tr>
                    <tr>
                        <td class="name">Default Language: </td>
                        <td class="value">
                            <select <?php echo field_error('default_language');?> tabindex="3" name="default_language" style="width: 100%;">

                            <?php foreach ($aAllowedLanguages as $sLangCode=>$Language): ?>

                                <option value="<?php echo $sLangCode ?>" <?php if (LangSelected($sLangCode)) echo 'selected="selected"' ?> >
                                    <?php echo$Language ?>
                                </option>

                            <?php endforeach ; ?>

                            </select>
                            <?php //echo "<pre>";print_r($aAllowedLanguages);echo "</pre>";?>
                        </td>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                </tbody>
            </table><!-- class step3 -->

            <table class="step4">
                <thead>
                    <tr>
                        <th class="step-row" colspan="4">
                            <h1 class="step-row">Step 4</h1>&nbsp;Please specify your operating system information below...
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">Server Operating System: </td>
                        <td >
                            <input type="radio" tabindex="4" name="operating_system" id="operating_system_linux" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="linux"<?php echo $sLinux ?> />
                            <span style="cursor: pointer;" onclick="javascript: change_os('linux');">Linux/Unix based</span>
                            <br />
                            <input type="radio" tabindex="5" name="operating_system" id="operating_system_windows" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="windows"<?php echo $sWindows ?> />
                            <span style="cursor: pointer;" onclick="javascript: change_os('windows');">Windows</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">&nbsp;</td>
                        <td class="value">
                            <div id="file_perms_box" style="display: <?php echo $sPermissionBlock?>;">
                                <input type="checkbox" tabindex="6" name="world_writeable" id="world_writeable" value="true" <?php echo $sWorldWriteableCheck?> />
                                <label style=" margin: 0;  " for="world_writeable">
                                    World-writeable file permissions (777)
                                </label>
                                <br />
                                <p class="warning">(Please note: only recommended for testing environments) <br />You can adjust this setting later in the Backend<br /></p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table><!-- class step4 -->

            <table class="step5">
                <thead>
                    <tr>
                        <th colspan="4" class="step-row">
                        <h1 class="step-row">Step 5</h1>&nbsp;Please enter your MySQL database server details below...
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">Host Name</td>
                        <td class="value">
                            <input <?php echo field_error('database_host');?> type="text" tabindex="7" name="database_host" value="<?php echo $sDatabaseHost ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="name">Database Name: </td>
                        <td class="value" style="white-space: nowrap;">
                            <input <?php echo field_error('database_name')?> type="text" tabindex="8" name="database_name" value="<?php echo $sDatabaseName  ?>" />
                        <span style="display: inline;">&nbsp;([a-zA-Z0-9_-])</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">Table Prefix: </td>
                        <td class="value" style="white-space: nowrap;">
                            <input <?php echo field_error('table_prefix')?> type="text" tabindex="9" name="table_prefix" value="<?php echo $sTablePrefix ?>" />
                            <span style="display: inline;">&nbsp;([a-z0-9_])</span>
                        </td>
                    </tr>
                    <tr>
                            <td class="name">Username:</td>
                            <td class="value">
                                <input <?php echo field_error('database_username');?> type="text" tabindex="10" name="database_username" value="<?php echo $sDatabaseUsername  ?>" />
                            </td>
                    </tr>
                    <tr>
                            <td class="name">Password:</td>
                            <td class="value">
                                <input type="password" tabindex="11" name="database_password" value="<?php echo $sDatabasePassword ?>" />
                            </td>
                    </tr>
                </tbody>
            </table><!-- class step5 -->

            <table class="step6">
                <thead>
                    <tr>
                        <th colspan="4" class="step-row">
                        <h1 class="step-row">Step 6</h1>&nbsp;Please enter your website title below...
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">Website Title:</td>
                        <td class="value">
                            <input <?php echo field_error('website_title');?> type="text" tabindex="13" name="website_title" value="<?php echo $sWebsiteTitle  ?>" />
                        </td>
                    </tr>
                </tbody>
            </table><!-- class step6 -->

            <table class="step7">
                <thead>
                    <tr>
                        <th colspan="4" class="step-row">
                        <h1 class="step-row">Step 7</h1> Please enter your Administrator account details below...
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">Loginname:</td>
                        <td class="value">
                            <input <?php echo field_error('admin_username');?> type="text" tabindex="14" name="admin_username" value="<?php echo $sAdminUsername  ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="name">E-Mail:</td>
                        <td class="value">
                            <input <?php echo field_error('admin_email');?> type="text" tabindex="15" name="admin_email" value="<?php echo $sAdminEmail ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="name">Password:</td>
                        <td class="value">
                            <input <?php echo field_error('admin_password');?> type="password" tabindex="16" name="admin_password" value="<?php echo $sAdminPassword ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="name">Repeat Password:</td>
                        <td class="value">
                            <input <?php echo field_error('admin_repassword');?> type="password" tabindex="17" name="admin_repassword" value="<?php echo $sAdminRepassword ?>"  />
                        </td>
                    </tr>
                </tbody>
            </table><!-- class step7 -->

<?php endif; // installFlag ?>

            <table class="step8">
                <tbody>
                    <tr valign="top">
                        <td><strong>Please note: &nbsp;</strong></td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <p class="warning">
                                WBCE is released under the
                                <a href="http://www.gnu.org/licenses/gpl.html" target="_blank" tabindex="19">GNU General Public License</a>.
                                <br />By clicking install, you are accepting the license.
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <p class="center">
                                <?php if ($installFlag == true): ?>
                                    <input type="submit" tabindex="20" name="install" value="Install WBCE CMS" />
                                <?php else :?>
                                    <input type="button" tabindex="20" name="restart" value="Check your Settings in Step1 or Step2" class="submit" onclick="javascript: window.location = '<?php print $_SERVER['SCRIPT_NAME']?>';" />
                                <?php endif; //install flag ?>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table><!-- class step8 -->

        </form>
    </div> <!-- class body -->

    <div class="footer" >
        <!-- Please note: the below reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
        <a href="http://www.wbce.org/"  target="_blank">WBCE</a> is released under the
        <a href="http://www.gnu.org/licenses/gpl.html"  target="_blank">GNU General Public License</a>
        <!-- Please note: the above reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
        <br > WBCE Version: <?php echo NEW_WBCE_VERSION ?>
    </div > <!-- class footer -->

</body>
</html>


