<?php 
/**
 * @file    EN.php
 * @brief   English language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'EN';
$INFO['language_name'] = 'English';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Installation Wizard';
$TXT['welcome_heading']      = 'Installation Wizard';
$TXT['welcome_sub']          = 'Complete all steps below to finish your installation';

$TXT['step1_heading']        = 'Step 1 — System Requirements';
$TXT['step1_desc']           = 'Verifying your server meets all prerequisites';
$TXT['step2_heading']        = 'Step 2 — Website Settings';
$TXT['step2_desc']           = 'Configure basic site parameters and locale';
$TXT['step3_heading']        = 'Step 3 — Database';
$TXT['step3_desc']           = 'Enter your MySQL / MariaDB connection details';
$TXT['step4_heading']        = 'Step 4 — Administrator Account';
$TXT['step4_desc']           = 'Create your backend login credentials';
$TXT['step5_heading']        = 'Step 5 — Install WBCE CMS';
$TXT['step5_desc']           = 'Review the license and start the installation';

$TXT['req_php_version']      = 'PHP Version >=';
$TXT['req_php_sessions']     = 'PHP Session Support';
$TXT['req_server_charset']   = 'Server Default Charset';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'File &amp; Directory Permissions';
$TXT['file_perm_descr']      = 'The following paths must be writeable by the web server';
$TXT['hint_not_empty']       = 'Not empty!';
$TXT['wbce_already_installed'] = 'WBCE already installed?';

$TXT['lbl_website_title']    = 'Website Title';
$TXT['lbl_absolute_url']     = 'Absolute URL';
$TXT['lbl_timezone']         = 'Default Timezone';
$TXT['lbl_language']         = 'Default Language';
$TXT['lbl_server_os']        = 'Server Operating System';
$TXT['lbl_linux']            = 'Linux / Unix based';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'World-writeable file permissions (777)';

$TXT['lbl_db_host']          = 'Host Name';
$TXT['lbl_db_name']          = 'Database Name';
$TXT['lbl_db_prefix']        = 'Table Prefix';
$TXT['lbl_db_user']          = 'Username';
$TXT['lbl_db_pass']          = 'Password';
$TXT['btn_test_db']          = 'Test Connection';
$TXT['db_testing']           = 'Connecting…';
$TXT['db_retest']            = 'Test again';

$TXT['lbl_admin_login']      = 'Login Name';
$TXT['lbl_admin_email']      = 'E-Mail Address';
$TXT['lbl_admin_pass']       = 'Password';
$TXT['lbl_admin_repass']     = 'Repeat Password';
$TXT['btn_gen_password']     = '⚙ Generate';
$TXT['pw_copy_hint']         = 'Copy password';

$TXT['btn_install']          = '▶  Install WBCE CMS';
$TXT['btn_check_settings']   = 'Check your Settings in Step 1 and reload with F5';

$TXT['error_prefix']         = 'Error';
$TXT['version_prefix']       = 'WBCE Version';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';

// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP Session Support may appear disabled if your browser does not support cookies.';

$MSG['charset_warning'] =
    'Your webserver is configured to deliver <b>%1$s</b> charset only. '
    . 'To display national special characters correctly, please disable this preset '
    . '(or ask your hosting provider). You may also select <b>%1$s</b> in the WBCE '
    . 'settings, though this may affect some module output.';

$MSG['world_writeable_warning'] =
    'Only recommended for testing environments. '
    . 'You can adjust this setting later in the Backend.';

$MSG['license_notice'] =
    'WBCE CMS is released under the <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. By clicking the install button below, you confirm '
    . 'that you have read and accept the terms of the license.';

// JS validation messages
$MSG['val_required']       = 'This field is required.';
$MSG['val_url']            = 'Please enter a valid URL (starting with http:// or https://).';
$MSG['val_email']          = 'Please enter a valid e-mail address.';
$MSG['val_pw_mismatch']    = 'Passwords do not match.';
$MSG['val_pw_short']       = 'Password must be at least 12 characters.';
$MSG['val_db_untested']    = 'Please test the database connection successfully before installing.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Please fill in host, database name and username first.';
$MSG['db_pdo_missing']        = 'PDO extension is not available on this server.';
$MSG['db_success']            = 'Connection successful: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Access denied. Please check username and password.';
$MSG['db_unknown_db']         = 'Database does not exist. Please create it first or check the name.';
$MSG['db_connection_refused'] = 'Could not connect to host. Please check hostname and port.';
$MSG['db_connection_failed']  = 'Connection failed: %s';

// ─── Streaming Progress Log (reduced & multilingual) ─────────────────────────
$TXT['log_writing_config']      = 'Writing config.php';
$TXT['log_connecting_db']       = 'Connecting to database';
$TXT['log_importing_sql']       = 'Importing SQL structure & data';
$TXT['log_writing_settings']    = 'Writing system settings';
$TXT['log_creating_admin']      = 'Creating administrator account';
$TXT['log_booting_framework']   = 'Booting WBCE CMS framework';
$TXT['log_installing_modules']  = 'Installing modules';
$TXT['log_installing_templates']= 'Installing templates';
$TXT['log_installing_languages']= 'Installing languages';
$TXT['log_required_mod_missing']= 'Required modules missing: ';
$TXT['log_finalizing']          = 'Finalizing installation';
$TXT['log_export_snapshot']     = 'Export `var/sys_constants.php` Snapshot';

$TXT['log_done']                = '✓ Done';
$TXT['log_complete']            = '━━━ Installation complete ━━━';
$TXT['log_failed']              = 'Installation failed – see errors above';

// ─── Keys for install_stream.js  ─────────────────────────────────────────────
$TXT['language']                = 'Language';
$TXT['module']                  = 'Module';
$TXT['template']                = 'Template';
$TXT['progress_title']          = 'Installing WBCE CMS';
$TXT['progress_starting']       = 'Starting installation…';
$TXT['progress_done']           = 'Installation complete';
$TXT['progress_btn_installing'] = 'Installing…';
$TXT['progress_success']        = 'Installation complete!';
$TXT['progress_failed']         = 'Installation failed — see errors above.';
$TXT['progress_go_frontend']    = 'Go to Frontend';
$TXT['progress_go_admin']       = 'Go to Admin Login ›';
$TXT['progress_try_again']      = '↻ Try again';

// ─── Upgrade Script specific ─────────────────────────────────────────────────
$TXT['upgrade_heading']         = 'Database & add-on migration';
$TXT['upgrade_subheading']      = 'This script modifies the database and replaces files.';
$TXT['current_version']         = 'Current version';
$TXT['target_version']          = 'Target version';
$TXT['upgrade_warning']         = 'The update script modifies the existing database and file structure. It is <strong>strongly recommended</strong> to create a manual backup of the <tt>%s</tt> folder and the entire database before proceeding.';
$TXT['upgrade_confirm']         = 'I confirm that I have created a manual backup of the <tt>%s</tt> folder and the database.';
$TXT['proceed_upgrade']         = 'Proceed with the upgrade';
$TXT['db_table_is_ready']       = 'Table `%s` is ready';
$TXT['db_field_added_to_table'] = ' Field `%s` was added to `%s` table';
$TXT['db_field_table_error']    = 'Field `%s` in table `%s`: '; 
$TXT['db_field_already_in_table']= 'Field `%s`.`%s` already exists — skipped';
$TXT['module_already_configured']= 'Module `%s` already configured — skipped'; 
$TXT['update_in_progress']      = 'Update in progress…';
$TXT['starting_update']         = 'Starting update…';
$TXT['update_complete']         = 'Update complete!';
$TXT['update_failed']           = 'Update had errors — check the log above.';
$TXT['run_again']               = '↻ Run again';
$TXT['wbce_seems_installed']    = "WBCE CMS seems to be installed already.";
$TXT['ask_wbce_upgrade']        = "Do you want to upgrade WBCE CMS?";
$TXT['goto_upgradescript']      = "Go to the upgrade script";
$TXT['disclaimer']              = "<b>DISCLAIMER:</b> The WBCE update script is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. One needs to confirm that a manual backup of the /pages folder (including all files and subfolders contained in it) and backup of the entire WBCE database was created before you can proceed.";
$TXT['paths_removed']           = 'REMOVED: Files: %d | Directories: %d';
$TXT['failed_to_remove']        = ' | Failed: %d';

// class AddonManager — Minimal Precheck/Install Signal Strings (English)

$SIGNAL['ADDON_PRECHECK_OK']       = '%s "%s": requirement met.';
$SIGNAL['ADDON_PRECHECK_FAILED']   = '%s "%s": requirement not met — installation aborted.';
$SIGNAL['ADDON_INSERTED_OK']       = 'The %s "%s" was installed successfully.';
$SIGNAL['ADDON_UPDATED_OK']        = 'The %s "%s" was updated successfully.';
$SIGNAL['ADDON_ALREADY_CURRENT']   = 'The %s "%s" is already at the current version.';
$SIGNAL['ADDON_SCRIPT_OK']         = '%s "%s": script executed successfully.';
$SIGNAL['ADDON_SCRIPT_NOT_FOUND']  = '%s "%s": no script found (skipped).';
$SIGNAL['ADDON_SCRIPT_ERROR']      = '%s "%s": script reported an error.';
$SIGNAL['ADDON_DB_ERROR']          = '%s "%s": a database error occurred.';
$SIGNAL['ADDON_INFO_INVALID']      = '%s "%s": info.php is missing or invalid.';
$SIGNAL['ADDON_PATH_NOT_FOUND']    = 'The %s "%s" was not found.';