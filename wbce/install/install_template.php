<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/install_template.php
 * @brief      Template file for the WBCE CMS Installer
 * @copyright  WBCE Project (2026)
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 *
 * New features vs. original:
 * ==========================
 *  Step 2  – $_GET['lang'] pre-selects default_language in the dropdown
 *  Step 3  – AJAX database connection test (db_conn_check.php)
 *  Step 4  – Random password generator (≥ 12 chars, install_save.php charset)
 *  Validation – HTML5 native (required / pattern / minlength / type=email)
 *                 + custom JS (password match, DB-tested gate)
 */

// $_GET['lang'] for Step 2 pre-selection (sanitised)
$urlLang = '';
if (isset($_GET['lang']) && is_string($_GET['lang'])) {
    $urlLang = strtoupper(preg_replace('/[^a-zA-Z]/', '', $_GET['lang']));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $TXT['page_title'] ?></title>
        <link href="./assets/install.css" rel="stylesheet" type="text/css">
        <link href="./favicon.png" rel="icon" type="image/png">
    </head>
    <body>
        <div class="page-wrapper">

            <!-- ── Header ─────────────────────────────────────────── -->
            <header class="site-header">
                <div class="logo-wrap">
                    <img src="./assets/wbce_logo.svg" alt="WBCE CMS Logo">
                </div>
                <div class="wizard-title">
                    <span class="version-badge">Installation Wizard for
                        <svg width="10" height="10" viewBox="0 0 10 10"><circle cx="5" cy="5" r="4" fill="#5599ff" opacity=".7"/></svg>
                        <?= $TXT['version_prefix'] ?>: <?= NEW_WBCE_VERSION ?>
                    </span>
                </div>

                <?php if (!empty($languages)): ?>
                    <div class="language-switcher">
                        <?php foreach ($languages as $lang):
                            $cls = $lang['current'] ? ' curr-lang' : '';
                            ?>
                            <span class="lang-badge<?= $cls ?>">
                                <img src="../languages/<?= $lang['code'] ?>.png" alt="">
                                <a href="<?= htmlspecialchars($lang['url']) ?>"><?= htmlspecialchars($lang['name']) ?></a>
                            </span>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>

                <div class="header-subtitle"><?= $TXT['welcome_sub'] ?></div>
            </header>

            <form id="install-form"
                  name="website_baker_installation_wizard"
                  action="install_save.php"
                  method="post"
                  novalidate>

                <input type="hidden" name="url" value="">
                <!-- Install stream: URLs echoed into these fields by PHP for JS actions -->
                <input type="hidden" id="install-wb-url"    name="_wb_url_hint"    value="<?= htmlspecialchars($sWbUrl ?? '') ?>">
                <input type="hidden" id="install-admin-url" name="_admin_url_hint" value="<?= htmlspecialchars(rtrim($sWbUrl ?? '', '/') . '/admin') ?>">
                <input type="hidden" name="username_fieldname" value="admin_username">
                <input type="hidden" name="password_fieldname" value="admin_password">
                <input type="hidden" name="remember" id="remember" value="true">
                <!-- Set to 1 by JS once DB test succeeds; validated before submit -->
                <input type="hidden" id="db_tested" name="db_tested" value="0">

                <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
                    <div class="warningbox">
                        <?php foreach ($_SESSION['message'] as $message): ?>
                            <b><?= $TXT['error_prefix'] ?>:</b> <?= $message ?><br>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>

                <!-- ── Step 1 — Requirements ──────────────────────── -->
                <div class="card">
                    <div class="card-header">
                        <div class="step-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        </div>
                        <div class="card-header-text">
                            <h2><?= $TXT['step1_heading'] ?></h2>
                            <p><?= $TXT['step1_desc'] ?></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="requirements">

                            <?php if ($sSessionSupportClass == 'bad'): ?>
                                <div class="inline-warning" style="grid-column:1/-1">
                                    <?= $MSG['session_cookie_warning'] ?>
                                </div>
                            <?php endif ?>

                            <div class="req-item">
                                <span class="req-label text"><?= $TXT['req_php_version'] ?> <?= $requiredVersion ?></span>
                                <span class="<?= $sPhpVersion ?>"><?= PHP_VERSION ?></span>
                            </div>
                            <div class="req-item">
                                <span class="req-label text"><?= $TXT['req_php_sessions'] ?></span>
                                <span class="<?= $sSessionSupportClass ?>"><?= $sSessionSupportText ?></span>
                            </div>
                            <div class="req-item">
                                <span class="req-label text"><?= $TXT['req_server_charset'] ?></span>
                                <span class="<?= $chrval ?>"><?= ($chrval == 'good') ? $e_adc . ' OK' : $e_adc ?></span>
                            </div>
                            <div class="req-item">
                                <span class="req-label text"><?= $TXT['req_safe_mode'] ?></span>
                                <span class="<?= $sSaveModeClass ?>"><?= $sSaveModeText ?></span>
                            </div>

                            <?php if ($chrval == 'bad'): ?>
                                <div class="inline-warning"><?= sprintf($MSG['charset_warning'], $e_adc) ?></div>
                            <?php endif ?>

                        </div>

                        <h3><?= $TXT['files_and_dirs_perms'] ?></h3>
                        <h4><?= $TXT['file_perm_descr'] ?></h4>

                        <div class="requirements">
                            <div class="req-item"><span class="req-label"><?= $configFile ?></span><?= $config ?></div>
                            <div class="req-item"><span class="req-label">📁 languages/</span><?= $sDirLanguages ?></div>
                            <div class="req-item"><span class="req-label">📁 pages/</span><?= $sDirPages ?></div>
                            <div class="req-item"><span class="req-label">📁 templates/</span><?= $sDirTemplates ?></div>
                            <div class="req-item"><span class="req-label">📁 media/</span><?= $sDirMedia ?></div>
                            <div class="req-item"><span class="req-label">📁 modules/</span><?= $sDirModules ?></div>
                            <div class="req-item"><span class="req-label">📁 var/</span><?= $sDirVar ?></div>
                            <div class="req-item"><span class="req-label">📁 temp/</span><?= $sDirTemp ?></div>
                        </div>
                        <?php if ($installFlag == false): ?>
                            <div class="warning-box">
                                <?= $TXT['btn_check_settings'] ?>        
                            </div>   
                        <?php endif ?>		
                    </div>
                </div>
                <?php if ($installFlag == false): ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="step-badge">
                                ↻
                            </div>
                            <div class="card-header-text">
                                <h2><?= $TXT['ask_wbce_upgrade'] ?></h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="license-box">
                                <?= $TXT['wbce_seems_installed'] ?> <br>
                                <?= $TXT['ask_wbce_upgrade'] ?>
                            </div>
                            <a class="install-btn" href="./update.php"><?= $TXT['goto_upgradescript'] ?> &raquo;</a>
                        </div> 
                    </div>
                <?php endif ?>



                <?php if ($installFlag == true): ?>
                    <!-- ── Step 2 — Website Settings ─────────────────── -->
                    <div class="card">
                        <div class="card-header">
                            <div class="step-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                            </div>
                            <div class="card-header-text">
                                <h2><?= $TXT['step2_heading'] ?></h2>
                                <p><?= $TXT['step2_desc'] ?></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="field-row">
                                <label for="website_title"><?= $TXT['lbl_website_title'] ?></label>
                                <input <?= field_error('website_title') ?> type="text" id="website_title"
                                                                          tabindex="1" name="website_title"
                                                                          value="<?= $sWebsiteTitle ?>" required>
                            </div>

                            <div class="field-row">
                                <label for="wb_url"><?= $TXT['lbl_absolute_url'] ?></label>
                                <input <?= field_error('wb_url') ?> type="url" id="wb_url"
                                                                   tabindex="2" name="wb_url"
                                                                   value="<?= $sWbUrl ?>" required>
                            </div>

                            <div class="field-row">
                                <label for="default_timezone"><?= $TXT['lbl_timezone'] ?></label>
                                <div class="select-wrap">
                                    <select <?= field_error('default_timezone') ?> id="default_timezone"
                                                                                  tabindex="3" name="default_timezone" required>
                                                                                      <?php foreach ($aZones as $fOffset): ?>
                                            <option value="<?= (string) $fOffset ?>"
                                                    <?php TzSelected($fOffset) ? print('selected') : '' ?>>
                                                        <?= $TXT['gmt'] . ' ' . (($fOffset > 0) ? '+' : '') . (($fOffset == 0) ? '' : (string) $fOffset . ' Hours') ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <div class="field-row">
                                <label for="default_language"><?= $TXT['lbl_language'] ?></label>
                                <div class="select-wrap">
                                    <select <?= field_error('default_language') ?> id="default_language"
                                                                                  tabindex="4" name="default_language" required>
                                                                                      <?php
                                                                                      foreach ($aAllowedLanguages as $sLangCode => $Language):
                                                                                          // Priority: session value → $_GET['lang'] → default EN
                                                                                          if (isset($_SESSION['default_language'])) {
                                                                                              $isSelected = ($_SESSION['default_language'] === $sLangCode);
                                                                                          } elseif ($urlLang !== '') {
                                                                                              $isSelected = ($urlLang === $sLangCode);
                                                                                          } else {
                                                                                              $isSelected = ($sLangCode === 'EN');
                                                                                          }
                                                                                          ?>
                                            <option value="<?= $sLangCode ?>" <?= $isSelected ? 'selected' : '' ?>>
                                            <?= $Language ?>
                                            </option>
    <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <!-- OS / world-writeable: hidden, kept for install_save.php compatibility -->
                            <input type="hidden" name="operating_system" value="linux">
                            <div id="file_perms_box" style="display:none">
                                <input type="checkbox" name="world_writeable" id="world_writeable" value="true" <?= $sWorldWriteableCheck ?>>
                            </div>

                        </div>
                    </div>

                    <!-- ── Step 3 — Database ──────────────────────────── -->
                    <div class="card">
                        <div class="card-header">
                            <div class="step-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                            </div>
                            <div class="card-header-text">
                                <h2><?= $TXT['step3_heading'] ?></h2>
                                <p><?= $TXT['step3_desc'] ?></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="field-row">
                                <label for="database_host"><?= $TXT['lbl_db_host'] ?></label>
                                <input <?= field_error('database_host') ?> type="text" id="database_host"
                                                                          tabindex="7" name="database_host"
                                                                          value="<?= $sDatabaseHost ?>" required>
                            </div>

                            <div class="field-row">
                                <label for="database_name"><?= $TXT['lbl_db_name'] ?></label>
                                <input <?= field_error('database_name') ?> type="text" id="database_name"
                                                                          tabindex="8" name="database_name"
                                                                          pattern="[a-zA-Z0-9_-]+" value="<?= $sDatabaseName ?>" required>
                                <span class="field-hint">[a-zA-Z0-9_-]</span>
                            </div>

                            <div class="field-row">
                                <label for="table_prefix"><?= $TXT['lbl_db_prefix'] ?></label>
                                <input <?= field_error('table_prefix') ?> type="text" id="table_prefix"
                                                                         tabindex="9" name="table_prefix"
                                                                         pattern="[a-z0-9_]+" value="<?= $sTablePrefix ?>" required>
                                <span class="field-hint">[a-z0-9_]</span>
                            </div>

                            <div class="field-row">
                                <label for="database_username"><?= $TXT['lbl_db_user'] ?></label>
                                <input <?= field_error('database_username') ?> type="text" id="database_username"
                                                                              tabindex="10" name="database_username"
                                                                              value="<?= $sDatabaseUsername ?>" required>
                            </div>

                            <div class="field-row">
                                <label for="database_password"><?= $TXT['lbl_db_pass'] ?></label>
                                <input type="password" id="database_password"
                                       tabindex="11" name="database_password"
                                       value="<?= $sDatabasePassword ?>">
                            </div>

                            <!-- AJAX test row -->
                            <div class="field-row">
                                <div></div><!-- keep 2-col grid alignment -->
                                <div class="db-test-row">
                                    <button type="button" id="db-test-btn">
                                        <span class="spinner"></span>
                                        <span class="btn-icon">⚡</span>
    <?= $TXT['btn_test_db'] ?>
                                    </button>
                                    <span id="db-status" role="status" aria-live="polite"></span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ── Step 4 — Admin Account ─────────────────────── -->
                    <div class="card">
                        <div class="card-header">
                            <div class="step-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div class="card-header-text">
                                <h2><?= $TXT['step4_heading'] ?></h2>
                                <p><?= $TXT['step4_desc'] ?></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="field-row">
                                <label for="admin_username"><?= $TXT['lbl_admin_login'] ?></label>
                                <input <?= field_error('admin_username') ?> type="text" id="admin_username"
                                                                           tabindex="12" name="admin_username"
                                                                           value="<?= $sAdminUsername ?>" required>
                            </div>

                            <div class="field-row">
                                <label for="admin_email"><?= $TXT['lbl_admin_email'] ?></label>
                                <input <?= field_error('admin_email') ?> type="email" id="admin_email"
                                                                        tabindex="13" name="admin_email"
                                                                        value="<?= $sAdminEmail ?>" required>
                            </div>

                            <div class="field-row">
                                <label for="admin_password"><?= $TXT['lbl_admin_pass'] ?></label>
                                <div class="pw-gen-wrap">
                                    <input <?= field_error('admin_password') ?> type="password" id="admin_password"
                                                                               tabindex="14" name="admin_password"
                                                                               minlength="12" value="<?= $sAdminPassword ?>" required
                                                                               autocomplete="new-password">
                                    <button type="button" id="btn-gen-pw" title="<?= $TXT['btn_gen_password'] ?>">
    <?= $TXT['btn_gen_password'] ?>
                                    </button>
                                </div>
                                <span class="pw-generated-hint" id="pw-hint" aria-live="polite"></span>
                            </div>

                            <div class="field-row">
                                <label for="admin_repassword"><?= $TXT['lbl_admin_repass'] ?></label>
                                <input <?= field_error('admin_repassword') ?> type="password" id="admin_repassword"
                                                                             tabindex="15" name="admin_repassword"
                                                                             minlength="12" value="<?= $sAdminRepassword ?>" required
                                                                             autocomplete="new-password">
                            </div>

                        </div>
                    </div>


                    <!-- ── Step 5 — Install ───────────────────────────── -->

                    <div class="card">
                        <div class="card-header">
                            <div class="step-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            </div>
                            <div class="card-header-text">
                                <h2><?= $TXT['step5_heading'] ?></h2>
                                <p><?= $TXT['step5_desc'] ?></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="license-box">
                            <?= sprintf($MSG['license_notice'], $TXT['license_link_text']) ?>
                            </div>
    <?php if ($installFlag == true): ?>
                                <input type="submit" class="install-btn" tabindex="16" name="install" id="btn-install"
                                       value="<?= $TXT['btn_install'] ?>">
    <?php endif ?>

                        </div>
                    </div>

<?php endif // installFlag  ?>

            </form>

            <!-- ── Installation Progress Card (hidden until install starts) ── -->
            <div id="progress-card" class="card" style="display:none">
                <div class="card-header">
                    <div class="step-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <div class="card-header-text">
                        <h2><?= $TXT['progress_title'] ?? 'Installing WBCE CMS' ?></h2>
                        <p id="phase-label"><?= $TXT['progress_starting'] ?? 'Starting…' ?></p>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Progress bar -->
                    <div class="progress-wrap">
                        <div class="progress-track">
                            <div class="progress-fill" id="progress-fill" style="width:0%"></div>
                        </div>
                        <span class="progress-pct" id="progress-label">0%</span>
                    </div>

                    <!-- Live log -->
                    <div class="install-log" id="install-log"></div>

                    <!-- Actions (shown when stream ends) -->
                    <div class="install-actions" id="install-actions" style="display:none"></div>

                </div>
            </div>

            <div class="page-footer"></div>

        </div><!-- /page-wrapper -->

        <!-- ── i18n strings for JS ────────────────────────────────── -->
        <script>
            const I18N = {
                currLang: <?= json_encode($urlLang) ?>, // for db_conn_check.php
                required: <?= json_encode($MSG['val_required']) ?>,
                url: <?= json_encode($MSG['val_url']) ?>,
                email: <?= json_encode($MSG['val_email']) ?>,
                pwMismatch: <?= json_encode($MSG['val_pw_mismatch']) ?>,
                pwShort: <?= json_encode($MSG['val_pw_short']) ?>,
                dbUntested: <?= json_encode($MSG['val_db_untested']) ?>,
                dbTesting: <?= json_encode($TXT['db_testing']) ?>,
                dbRetest: <?= json_encode($TXT['db_retest']) ?>,
                btnTest: <?= json_encode($TXT['btn_test_db']) ?>,
                pwCopyHint: <?= json_encode($TXT['pw_copy_hint']) ?>,
                installing: <?= json_encode($TXT['progress_btn_installing'] ?? 'Installing…') ?>,
                phaseStarting: <?= json_encode($TXT['progress_starting'] ?? 'Starting…') ?>,
                phaseDone: <?= json_encode($TXT['progress_done'] ?? 'Complete') ?>,
                installSuccess: <?= json_encode($TXT['progress_success'] ?? 'Installation complete!') ?>,
                installFailed: <?= json_encode($TXT['progress_failed'] ?? 'Installation failed — see errors above.') ?>,
                goFrontend: <?= json_encode($TXT['progress_go_frontend'] ?? 'Go to Frontend') ?>,
                goAdmin: <?= json_encode($TXT['progress_go_admin'] ?? 'Go to Admin Login') ?>,
                tryAgain: <?= json_encode($TXT['progress_try_again'] ?? '← Try again') ?>,
                // Keys for the Streaming-Log 
                logWritingConfig: <?= json_encode($TXT['log_writing_config'] ?? 'Writing config.php') ?>,
                logConnectingDb: <?= json_encode($TXT['log_connecting_db'] ?? 'Connecting to database') ?>,
                logImportingSql: <?= json_encode($TXT['log_importing_sql'] ?? 'Importing SQL structure & data') ?>,
                logWritingSettings: <?= json_encode($TXT['log_writing_settings'] ?? 'Writing system settings') ?>,
                logCreatingAdmin: <?= json_encode($TXT['log_creating_admin'] ?? 'Creating administrator account') ?>,
                logBootingFramework: <?= json_encode($TXT['log_booting_framework'] ?? 'Booting WBCE CMS framework') ?>,
                logInstallingModules: <?= json_encode($TXT['log_installing_modules'] ?? 'Installing modules') ?>,
                logInstallingTemplates:<?= json_encode($TXT['log_installing_templates'] ?? 'Installing templates') ?>,
                logInstallingLanguages:<?= json_encode($TXT['log_installing_languages'] ?? 'Installing languages') ?>,
                logFinalizing: <?= json_encode($TXT['log_finalizing'] ?? 'Finalizing installation') ?>,
                logDone: <?= json_encode($TXT['log_done'] ?? '✓ Done') ?>,
                logComplete: <?= json_encode($TXT['log_complete'] ?? '━━━ Installation complete ━━━') ?>,
                logFailed: <?= json_encode($TXT['log_failed'] ?? 'Installation failed – see errors above') ?>
            };
        </script>
        <script src="./assets/install.js" type="text/javascript"></script>
        <script src="./assets/install_stream.js" type="text/javascript"></script>
    </body>
</html>
