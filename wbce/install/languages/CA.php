<?php 
/**
 * @file    CA.php
 * @brief   Catalan language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'CA';
$INFO['language_name'] = 'Català';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Assistent d\'instal·lació del WBCE CMS';
$TXT['welcome_heading']      = 'Assistent d\'instal·lació';
$TXT['welcome_sub']          = 'Completa tots els passos següents per finalitzar la instal·lació';

$TXT['step1_heading']        = 'Pas 1 — Requisits del sistema';
$TXT['step1_desc']           = 'Verificant que el teu servidor compleix tots els requisits';
$TXT['step2_heading']        = 'Pas 2 — Configuració del lloc web';
$TXT['step2_desc']           = 'Configura els paràmetres bàsics del lloc i la configuració regional';
$TXT['step3_heading']        = 'Pas 3 — Base de dades';
$TXT['step3_desc']           = 'Introdueix les teves dades de connexió MySQL / MariaDB';
$TXT['step4_heading']        = 'Pas 4 — Compte d\'administrador';
$TXT['step4_desc']           = 'Crea les teves credencials d\'accés al backend';
$TXT['step5_heading']        = 'Pas 5 — Instal·lar WBCE CMS';
$TXT['step5_desc']           = 'Revisa la llicència i inicia la instal·lació';

$TXT['req_php_version']      = 'Versió de PHP >=';
$TXT['req_php_sessions']     = 'Suport de sessions PHP';
$TXT['req_server_charset']   = 'Joc de caràcters per defecte del servidor';
$TXT['req_safe_mode']        = 'Mode segur de PHP';
$TXT['files_and_dirs_perms'] = 'Permisos de fitxers i carpetes';
$TXT['file_perm_descr']      = 'Les següents rutes han de ser escribibles pel servidor web';

$TXT['lbl_website_title']    = 'Títol del lloc web';
$TXT['lbl_absolute_url']     = 'URL absoluta';
$TXT['lbl_timezone']         = 'Zona horària per defecte';
$TXT['lbl_language']         = 'Idioma per defecte';
$TXT['lbl_server_os']        = 'Sistema operatiu del servidor';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Permisos d\'escriptura per a tothom (777)';

$TXT['lbl_db_host']          = 'Nom del host';
$TXT['lbl_db_name']          = 'Nom de la base de dades';
$TXT['lbl_db_prefix']        = 'Prefix de taules';
$TXT['lbl_db_user']          = 'Nom d\'usuari';
$TXT['lbl_db_pass']          = 'Contrasenya';
$TXT['btn_test_db']          = 'Provar connexió';
$TXT['db_testing']           = 'Connectant…';
$TXT['db_retest']            = 'Provar de nou';

$TXT['lbl_admin_login']      = 'Nom d\'usuari';
$TXT['lbl_admin_email']      = 'Adreça d\'e-mail';
$TXT['lbl_admin_pass']       = 'Contrasenya';
$TXT['lbl_admin_repass']     = 'Repetir contrasenya';
$TXT['btn_gen_password']     = '⚙ Generar';
$TXT['pw_copy_hint']         = 'Copiar contrasenya';

$TXT['btn_install']          = '▶  Instal·lar WBCE CMS';
$TXT['btn_check_settings']   = 'Comprova les teves configuracions al Pas 1 i recarrega la pàgina amb F5';

$TXT['error_prefix']         = 'Error';
$TXT['version_prefix']       = 'Versió WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'El suport de sessions PHP pot aparèixer desactivat si el teu navegador no accepta galetes.';

$MSG['charset_warning'] =
    'El teu servidor web està configurat per lliurar només el joc de caràcters <b>%1$s</b>. '
    . 'Per mostrar correctament els caràcters especials, desactiva aquesta preconfiguració '
    . '(o pregunta al teu proveïdor d\'allotjament). També pots seleccionar <b>%1$s</b> a les opcions del WBCE, '
    . 'però això pot afectar la sortida d\'alguns mòduls.';

$MSG['world_writeable_warning'] =
    'Recomanat només per a entorns de prova. '
    . 'Pots canviar aquesta configuració més tard al panell d\'administració.';

$MSG['license_notice'] =
    'WBCE CMS es distribueix sota la llicència <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. En fer clic al botó d\'instal·lació de sota, confirmes '
    . 'que has llegit i acceptes els termes de la llicència.';

// JS validation messages
$MSG['val_required']       = 'Aquest camp és obligatori.';
$MSG['val_url']            = 'Si us plau, introdueix una URL vàlida (que comenci amb http:// o https://).';
$MSG['val_email']          = 'Si us plau, introdueix una adreça d\'e-mail vàlida.';
$MSG['val_pw_mismatch']    = 'Les contrasenyes no coincideixen.';
$MSG['val_pw_short']       = 'La contrasenya ha de tenir com a mínim 12 caràcters.';
$MSG['val_db_untested']    = 'Si us plau, prova primer amb èxit la connexió a la base de dades abans d\'instal·lar.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Si us plau, omple primer el host, el nom de la base de dades i el nom d\'usuari.';
$MSG['db_pdo_missing']        = 'L\'extensió PDO no està disponible en aquest servidor.';
$MSG['db_success']            = 'Connexió correcta: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Accés denegat. Comprova el nom d\'usuari i la contrasenya.';
$MSG['db_unknown_db']         = 'La base de dades no existeix. Crea-la primer o comprova el nom.';
$MSG['db_connection_refused'] = 'No s\'ha pogut connectar amb el host. Comprova el nom del host i el port.';
$MSG['db_connection_failed']  = 'La connexió ha fallat: %s';
