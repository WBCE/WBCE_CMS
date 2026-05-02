<?php 
/**
 * @file    IT.php
 * @brief   Italian language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'IT';
$INFO['language_name'] = 'Italiano';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Assistente di installazione WBCE CMS';
$TXT['welcome_heading']      = 'Assistente di installazione';
$TXT['welcome_sub']          = 'Completa tutti i passaggi seguenti per terminare l\'installazione';

$TXT['step1_heading']        = 'Passo 1 — Requisiti di sistema';
$TXT['step1_desc']           = 'Verifica che il tuo server soddisfi tutti i requisiti';
$TXT['step2_heading']        = 'Passo 2 — Impostazioni del sito';
$TXT['step2_desc']           = 'Configura i parametri di base del sito e le impostazioni locali';
$TXT['step3_heading']        = 'Passo 3 — Database';
$TXT['step3_desc']           = 'Inserisci i tuoi dati di connessione MySQL / MariaDB';
$TXT['step4_heading']        = 'Passo 4 — Account amministratore';
$TXT['step4_desc']           = 'Crea le tue credenziali di accesso al backend';
$TXT['step5_heading']        = 'Passo 5 — Installa WBCE CMS';
$TXT['step5_desc']           = 'Controlla la licenza e avvia l\'installazione';

$TXT['req_php_version']      = 'Versione PHP >=';
$TXT['req_php_sessions']     = 'Supporto sessioni PHP';
$TXT['req_server_charset']   = 'Set di caratteri predefinito del server';
$TXT['req_safe_mode']        = 'Modalità sicura PHP';
$TXT['files_and_dirs_perms'] = 'Permessi di file e cartelle';
$TXT['file_perm_descr']      = 'I seguenti percorsi devono essere scrivibili dal server web';

$TXT['lbl_website_title']    = 'Titolo del sito';
$TXT['lbl_absolute_url']     = 'URL assoluto';
$TXT['lbl_timezone']         = 'Fuso orario predefinito';
$TXT['lbl_language']         = 'Lingua predefinita';
$TXT['lbl_server_os']        = 'Sistema operativo del server';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Permessi di scrittura per tutti (777)';

$TXT['lbl_db_host']          = 'Nome host';
$TXT['lbl_db_name']          = 'Nome del database';
$TXT['lbl_db_prefix']        = 'Prefisso tabelle';
$TXT['lbl_db_user']          = 'Nome utente';
$TXT['lbl_db_pass']          = 'Password';
$TXT['btn_test_db']          = 'Testa connessione';
$TXT['db_testing']           = 'Connessione in corso…';
$TXT['db_retest']            = 'Testa di nuovo';

$TXT['lbl_admin_login']      = 'Nome di accesso';
$TXT['lbl_admin_email']      = 'Indirizzo e-mail';
$TXT['lbl_admin_pass']       = 'Password';
$TXT['lbl_admin_repass']     = 'Ripeti password';
$TXT['btn_gen_password']     = '⚙ Genera';
$TXT['pw_copy_hint']         = 'Copia password';

$TXT['btn_install']          = '▶  Installa WBCE CMS';
$TXT['btn_check_settings']   = 'Controlla le tue impostazioni nel Passo 1 e ricarica la pagina con F5';

$TXT['error_prefix']         = 'Errore';
$TXT['version_prefix']       = 'Versione WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Il supporto alle sessioni PHP può apparire disabilitato se il tuo browser non accetta i cookie.';

$MSG['charset_warning'] =
    'Il tuo server web è configurato per inviare solo il set di caratteri <b>%1$s</b>. '
    . 'Per visualizzare correttamente i caratteri speciali, disattiva questa impostazione predefinita '
    . '(o chiedi al tuo provider di hosting). Puoi anche selezionare <b>%1$s</b> nelle impostazioni di WBCE, '
    . 'ma questo potrebbe influenzare l\'output di alcuni moduli.';

$MSG['world_writeable_warning'] =
    'Consigliato solo per ambienti di test. '
    . 'Potrai modificare questa impostazione più tardi nel pannello di amministrazione.';

$MSG['license_notice'] =
    'WBCE CMS è rilasciato sotto la licenza <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Cliccando sul pulsante Installa qui sotto, confermi '
    . 'di aver letto e accettato i termini della licenza.';

// JS validation messages
$MSG['val_required']       = 'Questo campo è obbligatorio.';
$MSG['val_url']            = 'Inserisci un URL valido (che inizi con http:// o https://).';
$MSG['val_email']          = 'Inserisci un indirizzo e-mail valido.';
$MSG['val_pw_mismatch']    = 'Le password non corrispondono.';
$MSG['val_pw_short']       = 'La password deve contenere almeno 12 caratteri.';
$MSG['val_db_untested']    = 'Testa con successo la connessione al database prima di installare.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Compila prima il nome host, il nome del database e il nome utente.';
$MSG['db_pdo_missing']        = 'L\'estensione PDO non è disponibile su questo server.';
$MSG['db_success']            = 'Connessione riuscita: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Accesso negato. Controlla nome utente e password.';
$MSG['db_unknown_db']         = 'Il database non esiste. Crealo prima o verifica il nome.';
$MSG['db_connection_refused'] = 'Impossibile connettersi all\'host. Controlla il nome host e la porta.';
$MSG['db_connection_failed']  = 'Connessione fallita: %s';
