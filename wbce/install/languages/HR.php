<?php 
/**
 * @file    HU.php
 * @brief   Hungarian language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'HU';
$INFO['language_name'] = 'Magyar';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Telepítővarázsló';
$TXT['welcome_heading']      = 'Telepítővarázsló';
$TXT['welcome_sub']          = 'Hajtsd végre az alábbi összes lépést a telepítés befejezéséhez';

$TXT['step1_heading']        = '1. lépés — Rendszerkövetelmények';
$TXT['step1_desc']           = 'Ellenőrzi, hogy a szervered megfelel-e minden követelménynek';
$TXT['step2_heading']        = '2. lépés — Weboldal beállításai';
$TXT['step2_desc']           = 'Állítsd be az alapvető oldalparamétereket és a területi beállításokat';
$TXT['step3_heading']        = '3. lépés — Adatbázis';
$TXT['step3_desc']           = 'Add meg a MySQL / MariaDB kapcsolat adataidat';
$TXT['step4_heading']        = '4. lépés — Rendszergazda fiók';
$TXT['step4_desc']           = 'Hozd létre a backend bejelentkezési adataidat';
$TXT['step5_heading']        = '5. lépés — WBCE CMS telepítése';
$TXT['step5_desc']           = 'Olvasd el a licencet és indítsd el a telepítést';

$TXT['req_php_version']      = 'PHP verzió >=';
$TXT['req_php_sessions']     = 'PHP munkamenet-támogatás';
$TXT['req_server_charset']   = 'A szerver alapértelmezett karakterkészlete';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Fájl- &amp; könyvtárjogosultságok';
$TXT['file_perm_descr']      = 'Az alábbi útvonalaknak írhatónak kell lenniük a webszerver számára';

$TXT['lbl_website_title']    = 'Weboldal címe';
$TXT['lbl_absolute_url']     = 'Abszolút URL';
$TXT['lbl_timezone']         = 'Alapértelmezett időzóna';
$TXT['lbl_language']         = 'Alapértelmezett nyelv';
$TXT['lbl_server_os']        = 'Szerver operációs rendszere';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Mindenki által írható fájljogosultságok (777)';

$TXT['lbl_db_host']          = 'Kiszolgáló neve (Host)';
$TXT['lbl_db_name']          = 'Adatbázis neve';
$TXT['lbl_db_prefix']        = 'Táblák előtagja';
$TXT['lbl_db_user']          = 'Felhasználónév';
$TXT['lbl_db_pass']          = 'Jelszó';
$TXT['btn_test_db']          = 'Kapcsolat tesztelése';
$TXT['db_testing']           = 'Kapcsolódás…';
$TXT['db_retest']            = 'Újra tesztelés';

$TXT['lbl_admin_login']      = 'Bejelentkezési név';
$TXT['lbl_admin_email']      = 'E-mail cím';
$TXT['lbl_admin_pass']       = 'Jelszó';
$TXT['lbl_admin_repass']     = 'Jelszó ismét';
$TXT['btn_gen_password']     = '⚙ Generálás';
$TXT['pw_copy_hint']         = 'Jelszó másolása';

$TXT['btn_install']          = '▶  WBCE CMS telepítése';
$TXT['btn_check_settings']   = 'Ellenőrizd az 1. lépésben lévő beállításaidat és frissítsd az oldalt F5-tel';

$TXT['error_prefix']         = 'Hiba';
$TXT['version_prefix']       = 'WBCE verzió';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'A PHP munkamenet-támogatás letiltottnak tűnhet, ha a böngésződ nem támogatja a sütiket.';

$MSG['charset_warning'] =
    'A webszervered úgy van beállítva, hogy csak a <b>%1$s</b> karakterkészletet küldje. '
    . 'A nemzeti különleges karakterek helyes megjelenítéséhez kérlek tiltsd le ezt az előbeállítást '
    . '(vagy kérdezd meg a tárhelyszolgáltatódat). A WBCE beállításaiban is választhatod a <b>%1$s</b>-t, '
    . 'de ez befolyásolhatja egyes modulok kimenetét.';

$MSG['world_writeable_warning'] =
    'Csak tesztkörnyezetekhez ajánlott. '
    . 'Ezt a beállítást később megváltoztathatod az adminisztrációs felületen.';

$MSG['license_notice'] =
    'A WBCE CMS a <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a> licenc alatt kerül kiadásra. Az alábbi telepítés gombra kattintva '
    . 'megerősíted, hogy elolvastad és elfogadod a licenc feltételeit.';

// JS validation messages
$MSG['val_required']       = 'Ez a mező kötelező.';
$MSG['val_url']            = 'Kérlek, adj meg egy érvényes URL-t (http:// vagy https:// kezdetű).';
$MSG['val_email']          = 'Kérlek, adj meg egy érvényes e-mail címet.';
$MSG['val_pw_mismatch']    = 'A jelszavak nem egyeznek.';
$MSG['val_pw_short']       = 'A jelszónak legalább 12 karakter hosszúnak kell lennie.';
$MSG['val_db_untested']    = 'Kérlek, előbb sikeresen teszteld az adatbázis-kapcsolatot a telepítés előtt.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Kérlek, először töltsd ki a kiszolgáló nevét, az adatbázis nevét és a felhasználónevet.';
$MSG['db_pdo_missing']        = 'A PDO bővítmény nem érhető el ezen a szerveren.';
$MSG['db_success']            = 'Kapcsolat sikeres: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Hozzáférés megtagadva. Ellenőrizd a felhasználónevet és a jelszót.';
$MSG['db_unknown_db']         = 'Az adatbázis nem létezik. Először hozd létre, vagy ellenőrizd a nevét.';
$MSG['db_connection_refused'] = 'Nem sikerült csatlakozni a kiszolgálóhoz. Ellenőrizd a kiszolgáló nevét és a portot.';
$MSG['db_connection_failed']  = 'Kapcsolat sikertelen: %s';