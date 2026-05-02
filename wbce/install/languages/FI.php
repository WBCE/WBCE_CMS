<?php 
/**
 * @file    FI.php
 * @brief   Finnish language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'FI';
$INFO['language_name'] = 'Suomi';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Asennusohjelma';
$TXT['welcome_heading']      = 'Asennusohjelma';
$TXT['welcome_sub']          = 'Suorita kaikki alla olevat vaiheet asennuksen lopettamiseksi';

$TXT['step1_heading']        = 'Vaihe 1 — Järjestelmän vaatimukset';
$TXT['step1_desc']           = 'Tarkistetaan, täyttääkö palvelimesi kaikki vaatimukset';
$TXT['step2_heading']        = 'Vaihe 2 — Sivuston asetukset';
$TXT['step2_desc']           = 'Määritä sivuston perusasetukset ja maa-asetukset';
$TXT['step3_heading']        = 'Vaihe 3 — Tietokanta';
$TXT['step3_desc']           = 'Anna MySQL / MariaDB -yhteyden tiedot';
$TXT['step4_heading']        = 'Vaihe 4 — Ylläpitäjän tili';
$TXT['step4_desc']           = 'Luo kirjautumistiedot ylläpitopaneeliin';
$TXT['step5_heading']        = 'Vaihe 5 — Asenna WBCE CMS';
$TXT['step5_desc']           = 'Lue lisenssi ja käynnistä asennus';

$TXT['req_php_version']      = 'PHP-versio >=';
$TXT['req_php_sessions']     = 'PHP-istuntotuki';
$TXT['req_server_charset']   = 'Palvelimen oletusmerkistö';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Tiedosto- &amp; kansio-oikeudet';
$TXT['file_perm_descr']      = 'Seuraavien polkujen on oltava kirjoitettavia web-palvelimelle';

$TXT['lbl_website_title']    = 'Sivuston otsikko';
$TXT['lbl_absolute_url']     = 'Absoluuttinen URL';
$TXT['lbl_timezone']         = 'Oletusaikavyöhyke';
$TXT['lbl_language']         = 'Oletuskieli';
$TXT['lbl_server_os']        = 'Palvelimen käyttöjärjestelmä';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Kaikkien kirjoitusoikeudet (777)';

$TXT['lbl_db_host']          = 'Palvelimen nimi (Host)';
$TXT['lbl_db_name']          = 'Tietokannan nimi';
$TXT['lbl_db_prefix']        = 'Taulujen etuliite';
$TXT['lbl_db_user']          = 'Käyttäjätunnus';
$TXT['lbl_db_pass']          = 'Salasana';
$TXT['btn_test_db']          = 'Testaa yhteys';
$TXT['db_testing']           = 'Yhdistetään…';
$TXT['db_retest']            = 'Testaa uudelleen';

$TXT['lbl_admin_login']      = 'Kirjautumisnimi';
$TXT['lbl_admin_email']      = 'Sähköpostiosoite';
$TXT['lbl_admin_pass']       = 'Salasana';
$TXT['lbl_admin_repass']     = 'Toista salasana';
$TXT['btn_gen_password']     = '⚙ Generoi';
$TXT['pw_copy_hint']         = 'Kopioi salasana';

$TXT['btn_install']          = '▶  Asenna WBCE CMS';
$TXT['btn_check_settings']   = 'Tarkista asetuksesi Vaiheessa 1 ja päivitä sivu painamalla F5';

$TXT['error_prefix']         = 'Virhe';
$TXT['version_prefix']       = 'WBCE-versio';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP-istuntotuki voi näyttää pois käytöstä, jos selaimesi ei tue evästeitä.';

$MSG['charset_warning'] =
    'Web-palvelimesi on asetettu lähettämään vain merkistön <b>%1$s</b>. '
    . 'Jotta erikoismerkit näkyvät oikein, poista tämä esiasetus käytöstä '
    . '(tai kysy hosting-palveluntarjoajaltasi). Voit myös valita <b>%1$s</b> WBCE-asetuksista, '
    . 'mutta se voi vaikuttaa joidenkin moduulien tulosteeseen.';

$MSG['world_writeable_warning'] =
    'Suositellaan vain testikäyttöön. '
    . 'Voit muuttaa tätä asetusta myöhemmin hallintapaneelissa.';

$MSG['license_notice'] =
    'WBCE CMS julkaistaan lisenssillä <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Napsauttamalla alla olevaa Asenna-painiketta vahvistat, '
    . 'että olet lukenut ja hyväksynyt lisenssin ehdot.';

// JS validation messages
$MSG['val_required']       = 'Tämä kenttä on pakollinen.';
$MSG['val_url']            = 'Anna kelvollinen URL (joka alkaa http:// tai https://).';
$MSG['val_email']          = 'Anna kelvollinen sähköpostiosoite.';
$MSG['val_pw_mismatch']    = 'Salasanat eivät täsmää.';
$MSG['val_pw_short']       = 'Salasanan on oltava vähintään 12 merkkiä pitkä.';
$MSG['val_db_untested']    = 'Testaa tietokantayhteys onnistuneesti ennen asennusta.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Täytä ensin palvelimen nimi, tietokannan nimi ja käyttäjätunnus.';
$MSG['db_pdo_missing']        = 'PDO-laajennus ei ole käytettävissä tällä palvelimella.';
$MSG['db_success']            = 'Yhteys onnistui: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Käyttöoikeus evätty. Tarkista käyttäjätunnus ja salasana.';
$MSG['db_unknown_db']         = 'Tietokantaa ei ole olemassa. Luo se ensin tai tarkista nimi.';
$MSG['db_connection_refused'] = 'Yhteyttä palvelimeen ei saatu. Tarkista palvelimen nimi ja portti.';
$MSG['db_connection_failed']  = 'Yhteys epäonnistui: %s';
