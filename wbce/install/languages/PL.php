<?php 
/**
 * @file    PL.php
 * @brief   Polish language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'PL';
$INFO['language_name'] = 'Polski';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Kreator instalacji WBCE CMS';
$TXT['welcome_heading']      = 'Kreator instalacji';
$TXT['welcome_sub']          = 'Wykonaj wszystkie poniższe kroki, aby zakończyć instalację';

$TXT['step1_heading']        = 'Krok 1 — Wymagania systemowe';
$TXT['step1_desc']           = 'Sprawdzanie, czy serwer spełnia wszystkie wymagania wstępne';
$TXT['step2_heading']        = 'Krok 2 — Ustawienia strony internetowej';
$TXT['step2_desc']           = 'Skonfiguruj podstawowe parametry witryny i ustawienia regionalne';
$TXT['step3_heading']        = 'Krok 3 — Baza danych';
$TXT['step3_desc']           = 'Wprowadź dane połączenia MySQL / MariaDB';
$TXT['step4_heading']        = 'Krok 4 — Konto administratora';
$TXT['step4_desc']           = 'Utwórz dane logowania do panelu administracyjnego';
$TXT['step5_heading']        = 'Krok 5 — Instalacja WBCE CMS';
$TXT['step5_desc']           = 'Przejrzyj licencję i rozpocznij instalację';

$TXT['req_php_version']      = 'Wersja PHP >=';
$TXT['req_php_sessions']     = 'Obsługa sesji PHP';
$TXT['req_server_charset']   = 'Domyślna strona kodowa serwera';
$TXT['req_safe_mode']        = 'Tryb bezpieczny PHP';
$TXT['files_and_dirs_perms'] = 'Uprawnienia plików i katalogów';
$TXT['file_perm_descr']      = 'Następujące ścieżki muszą być zapisywalne przez serwer WWW';
$TXT['hint_not_empty']       = 'Nie może być puste!';
$TXT['wbce_already_installed'] = 'WBCE już zainstalowany?';

$TXT['lbl_website_title']    = 'Tytuł strony internetowej';
$TXT['lbl_absolute_url']     = 'Bezwzględny URL';
$TXT['lbl_timezone']         = 'Domyślna strefa czasowa';
$TXT['lbl_language']         = 'Domyślny język';
$TXT['lbl_server_os']        = 'System operacyjny serwera';
$TXT['lbl_linux']            = 'Linux / oparty na Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Uprawnienia plików do zapisu przez wszystkich (777)';

$TXT['lbl_db_host']          = 'Nazwa hosta';
$TXT['lbl_db_name']          = 'Nazwa bazy danych';
$TXT['lbl_db_prefix']        = 'Prefiks tabel';
$TXT['lbl_db_user']          = 'Nazwa użytkownika';
$TXT['lbl_db_pass']          = 'Hasło';
$TXT['btn_test_db']          = 'Testuj połączenie';
$TXT['db_testing']           = 'Łączenie…';
$TXT['db_retest']            = 'Testuj ponownie';

$TXT['lbl_admin_login']      = 'Nazwa logowania';
$TXT['lbl_admin_email']      = 'Adres e-mail';
$TXT['lbl_admin_pass']       = 'Hasło';
$TXT['lbl_admin_repass']     = 'Powtórz hasło';
$TXT['btn_gen_password']     = '⚙ Generuj';
$TXT['pw_copy_hint']         = 'Skopiuj hasło';

$TXT['btn_install']          = '▶  Zainstaluj WBCE CMS';
$TXT['btn_check_settings']   = 'Sprawdź ustawienia w Kroku 1 i odśwież stronę klawiszem F5';

$TXT['error_prefix']         = 'Błąd';
$TXT['version_prefix']       = 'Wersja WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';

// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Obsługa sesji PHP może wydawać się wyłączona, jeśli Twoja przeglądarka nie obsługuje ciasteczek.';

$MSG['charset_warning'] =
    'Twój serwer WWW jest skonfigurowany do dostarczania wyłącznie zestawu znaków <b>%1$s</b>. '
    . 'Aby poprawnie wyświetlać narodowe znaki specjalne, wyłącz to ustawienie wstępne '
    . '(lub skontaktuj się z dostawcą hostingu). Możesz również wybrać <b>%1$s</b> w ustawieniach WBCE, '
    . 'choć może to wpłynąć na działanie niektórych modułów.';

$MSG['world_writeable_warning'] =
    'Zalecane tylko w środowiskach testowych. '
    . 'Możesz zmienić to ustawienie później w panelu administracyjnym.';

$MSG['license_notice'] =
    'WBCE CMS jest wydawany na licencji <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Klikając przycisk instalacji poniżej, potwierdzasz, '
    . 'że przeczytałeś i akceptujesz warunki licencji.';

// JS validation messages
$MSG['val_required']       = 'To pole jest wymagane.';
$MSG['val_url']            = 'Wprowadź prawidłowy adres URL (zaczynający się od http:// lub https://).';
$MSG['val_email']          = 'Wprowadź prawidłowy adres e-mail.';
$MSG['val_pw_mismatch']    = 'Hasła nie są identyczne.';
$MSG['val_pw_short']       = 'Hasło musi mieć co najmniej 12 znaków.';
$MSG['val_db_untested']    = 'Przed instalacją wykonaj pomyślny test połączenia z bazą danych.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Najpierw wypełnij pola hosta, nazwy bazy danych i nazwy użytkownika.';
$MSG['db_pdo_missing']        = 'Rozszerzenie PDO nie jest dostępne na tym serwerze.';
$MSG['db_success']            = 'Połączenie udane: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Odmowa dostępu. Sprawdź nazwę użytkownika i hasło.';
$MSG['db_unknown_db']         = 'Baza danych nie istnieje. Utwórz ją najpierw lub sprawdź nazwę.';
$MSG['db_connection_refused'] = 'Nie można połączyć się z hostem. Sprawdź nazwę hosta i port.';
$MSG['db_connection_failed']  = 'Połączenie nieudane: %s';

// ─── Streaming Progress Log (reduced & multilingual) ─────────────────────────
$TXT['log_writing_config']      = 'Zapisywanie config.php';
$TXT['log_connecting_db']       = 'Łączenie z bazą danych';
$TXT['log_importing_sql']       = 'Importowanie struktury SQL i danych';
$TXT['log_writing_settings']    = 'Zapisywanie ustawień systemu';
$TXT['log_creating_admin']      = 'Tworzenie konta administratora';
$TXT['log_booting_framework']   = 'Uruchamianie frameworka WBCE CMS';
$TXT['log_installing_modules']  = 'Instalowanie modułów';
$TXT['log_installing_templates']= 'Instalowanie szablonów';
$TXT['log_installing_languages']= 'Instalowanie języków';
$TXT['log_required_mod_missing']= 'Brak wymaganych modułów: ';
$TXT['log_finalizing']          = 'Finalizowanie instalacji';

$TXT['log_done']                = '✓ Gotowe';
$TXT['log_complete']            = '━━━ Instalacja zakończona ━━━';
$TXT['log_failed']              = 'Instalacja nieudana – zobacz błędy powyżej';

// ─── Keys for install_stream.js  ─────────────────────────────────────────────
$TXT['language']                = 'Język';
$TXT['module']                  = 'Moduł';
$TXT['template']                = 'Szablon';
$TXT['progress_title']          = 'Instalacja WBCE CMS';
$TXT['progress_starting']       = 'Rozpoczynanie instalacji…';
$TXT['progress_done']           = 'Instalacja zakończona';
$TXT['progress_btn_installing'] = 'Instalowanie…';
$TXT['progress_success']        = 'Instalacja zakończona!';
$TXT['progress_failed']         = 'Instalacja nieudana — zobacz błędy powyżej.';
$TXT['progress_go_frontend']    = 'Przejdź do strony głównej (Frontend)';
$TXT['progress_go_admin']       = 'Przejdź do logowania administratora ›';
$TXT['progress_try_again']      = '↻ Spróbuj ponownie';

// ─── Upgrade Script specific ─────────────────────────────────────────────────
$TXT['upgrade_heading']         = 'Migracja bazy danych i dodatków';
$TXT['upgrade_subheading']      = 'Ten skrypt modyfikuje bazę danych i zastępuje pliki.';
$TXT['current_version']         = 'Bieżąca wersja';
$TXT['target_version']          = 'Docelowa wersja';
$TXT['upgrade_warning']         = 'Skrypt aktualizacji modyfikuje istniejącą bazę danych i strukturę plików. <strong>Zdecydowanie zalecane</strong> jest utworzenie ręcznej kopii zapasowej folderu <tt>%s</tt> oraz całej bazy danych przed kontynuacją.';
$TXT['upgrade_confirm']         = 'Potwierdzam, że utworzyłem ręczną kopię zapasową folderu <tt>%s</tt> oraz bazy danych.';
$TXT['proceed_upgrade']         = 'Kontynuuj aktualizację';

$TXT['db_table_is_ready']       = 'Tabela `%s` jest gotowa';
$TXT['db_field_added_to_table'] = ' Pole `%s` zostało dodane do tabeli `%s`';
$TXT['db_field_table_error']    = 'Pole `%s` w tabeli `%s`: '; 
$TXT['db_field_already_in_table']= 'Pole `%s`.`%s` już istnieje — pominięto';
$TXT['module_already_configured']= 'Moduł `%s` jest już skonfigurowany — pominięto'; 

$TXT['update_in_progress']      = 'Trwa aktualizacja…';
$TXT['starting_update']         = 'Rozpoczynanie aktualizacji…';
$TXT['update_complete']         = 'Aktualizacja zakończona!';
$TXT['update_failed']           = 'Aktualizacja zawiera błędy — sprawdź dziennik powyżej.';
$TXT['run_again']               = '↻ Uruchom ponownie';

$TXT['wbce_seems_installed']    = "Wygląda na to, że WBCE CMS jest już zainstalowany.";
$TXT['ask_wbce_upgrade']        = "Czy chcesz zaktualizować WBCE CMS?";
$TXT['disclaimer']              = "<b>UWAGA PRAWNA (DISCLAIMER):</b> Skrypt aktualizacji WBCE jest rozpowszechniany w nadziei, że będzie użyteczny, ale BEZ JAKIEJKOLWIEK GWARANCJI; nawet bez domniemanej gwarancji PRZYDATNOŚCI HANDLOWEJ lub PRZYDATNOŚCI DO OKREŚLONEGO CELU. Przed kontynuacją należy potwierdzić, że utworzono ręczną kopię zapasową folderu /pages (w tym wszystkich zawartych w nim plików i podfolderów) oraz kopię zapasową całej bazy danych WBCE.";