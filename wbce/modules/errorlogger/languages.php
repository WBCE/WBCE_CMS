<?php
// modules/errorlogger/language.php
// Translates the String in Admin-Tools overview.
// (This file has no effect in WB CMS)
//
// Lang::loadLanguage() merges the EN block first as the base, then overlays the
// active locale on top. So EN must be complete; every other locale only needs
// the keys it actually translates — anything missing falls back to EN.

return [
    'EN' => [
        'module_name' => 'Errorlog viewer',
        'module_description' => 'PHP warnings and errors are written to a private logfile and shown in a comfortable backend view — plus a CodeVet tab with the audit log of every Droplet, Outputfilter, Code2 save or add-on upload that CodeVet blocked or flagged for dangerous code.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Great news. No errors reported.',
            'RELOAD'          => 'Reload',
            'DELETE_LOGFILE'  => 'Delete logfile',
            'PLAIN_VIEW'      => 'Plain View',
            'COLOR_VIEW'      => 'Color View',
            'TABLE_VIEW'      => 'Table View',

            // ── Chrome ──────────────────────────────────────────────────────
            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Settings',
            'SEARCH_PLACEHOLDER' => 'Search in log …',
            'NO_RESULTS'         => 'No log lines match your search.',
            'NOT_MAX_LEVEL'      => 'Note: PHP error reporting is not at its maximum level right now — some errors may be missing from the log below. Raise it in the Settings tab.',
            'LOGFILE_RENAMED'    => 'Logfile archived as %s',

            'COL_TYPE'    => 'Type',
            'COL_TIME'    => 'Time',
            'COL_MESSAGE' => 'Message',
            'COL_FILE'    => 'File',
            'COL_LINE'    => 'Line',

            // ── Settings tab ────────────────────────────────────────────────
            'SETTINGS_INTRO'  => 'These switches control how much diagnostic detail WBCE produces. They are meant for development and staging — keep them off on a live site.',

            'ERL_CARD'  => 'PHP error reporting level',
            'ERL_DESC'  => 'Sets PHP\'s error_reporting() and display_errors for the whole site (applied by wbce_setup_error_reporting() in framework/initialize.php). Stored in the database (settings table, key "er_level"). While "WBCE Debug" below is on it is ignored — debug mode always forces E_ALL.',
            'ERL_LABEL' => 'Reporting level',

            'DEBUG_CARD' => 'Debug switches',
            'DEBUG_DESC' => 'Stored in var/config_constants.ini.php, so they survive updates. Each one only adds diagnostic output — none of them change how pages are rendered.',

            'WBCE_DEBUG_LABEL' => 'WBCE Debug',
            'WBCE_DEBUG_DESC'  => 'Global development mode. Forces error_reporting to E_ALL and display_errors on (framework/initialize.php), and is mirrored as the legacy WB_DEBUG constant for older add-ons. It additionally enables: visible Droplet syntax errors, admin-only console.error() output from the asset manager (framework/Assets/AssetQueue.php), extra output on the "forgot password" form, and a few other module debug paths. Never enable this on a public site.',
            'WBCE_DEBUG_LOCKED' => 'Locked: WB_DEBUG is still hard-coded in config.php. Remove it there first (see the notice above), then WBCE_DEBUG can be toggled here.',

            'SQL_DEBUG_LABEL' => 'SQL Debug',
            'SQL_DEBUG_DESC'  => 'Database diagnostics. When a query fails, framework/Database.php raises a PHP warning containing the error message and the offending SQL statement, instead of only keeping it internally for hasError(). It also warns when code reads an unknown $database->… property. Use it to track down broken queries in the core or in modules.',

            'PDO_DEBUG_LABEL' => 'PDO Syntax Debug',
            'PDO_DEBUG_DESC'  => 'Developer migration aid. framework/Database.php maps the old mysqli-style names (get_one, get_array, updateRow, delRow, is_error …) onto the new PDO methods. With this on, every such legacy call raises an E_USER_DEPRECATED notice naming the canonical replacement, and non-canonical SQL is flagged too. Only useful while actively porting code to the new API — very noisy otherwise.',

            'FILE_BASED_NOTE' => 'Written to var/config_constants.ini.php — update-safe.',
            'BADGE_PREVIEW'   => 'Shows in the log table as',
            'DOCS_LINK'       => 'Module documentation',

            // ── CodeVet tab ────────────────────────────────────────────────
            'TAB_CODEVET'           => 'CodeVet',
            'CV_SEARCH_PLACEHOLDER' => 'Search the CodeVet log …',
            'CV_ARCHIVE'            => 'Archive log',
            'CV_ARCHIVE_CONFIRM'    => 'Archive the current CodeVet log? It is renamed with a timestamp; nothing is deleted.',
            'CV_ARCHIVED'           => 'CodeVet log archived.',
            'CV_EVENTS'             => 'events',
            'CV_EMPTY'              => 'Nothing to report. CodeVet found no problem with any saved Droplet, Outputfilter, Code2 section or uploaded add-on.',
            'CV_COL_ACTION'         => 'Trigger',
            'CV_COL_FINDINGS'       => 'Findings',
            'CV_COL_WHERE'          => 'Where',
            'CV_SEV_BLOCK'          => 'Blocked',
            'CV_SEV_WARN'           => 'Flagged',
            'CV_ACT_DROPLET'        => 'Droplet saved',
            'CV_ACT_OUTPUTFILTER'   => 'Outputfilter saved',
            'CV_ACT_CODE2'          => 'Code2 section saved',
            'CV_ACT_ADDON'          => 'Add-on ZIP uploaded',
            'CV_ACT_OTHER'          => 'Code saved',
            'CV_LINE'               => 'line',
            'CV_MORE_FINDINGS'      => 'more findings',
            'CV_LOAD_ARCHIVES'      => 'Include archived logs',
            'CV_CURRENT_ONLY'       => 'Current log only',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Notice:</b> <code>WB_DEBUG</code> appears to be set in <code>config.php</code>. This constant is deprecated since WBCE 1.7.0 — please remove it from <code>config.php</code>. Use <code>WBCE_DEBUG</code> instead (see the switch below). Once <code>WB_DEBUG</code> has been removed, <code>WBCE_DEBUG</code> can be toggled here.',
        ]
    ],

    'DE' => [
        'module_name' => 'Errorlog-Viewer',
        'module_description' => 'Schreibt PHP-Warnungen und -Fehler in eine private Logdatei und zeigt sie komfortabel im Backend — dazu ein CodeVet-Tab mit dem Prüfprotokoll aller Droplet-, Outputfilter-, Code2- oder Addon-Speicherungen, die CodeVet wegen gefährlichem Code blockiert oder markiert hat.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Gute Neuigkeiten. Es liegt momentan kein Error Report vor.',
            'RELOAD'          => 'Aktualisieren',
            'DELETE_LOGFILE'  => 'Logfile löschen',
            'PLAIN_VIEW'      => 'Standardansicht',
            'COLOR_VIEW'      => 'Farbliche Darstellung',
            'TABLE_VIEW'      => 'Tabelarische Darstellung',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Einstellungen',
            'SEARCH_PLACEHOLDER' => 'Im Log suchen …',
            'NO_RESULTS'         => 'Keine Logzeile passt zur Suche.',
            'NOT_MAX_LEVEL'      => 'Hinweis: Die PHP-Fehlerausgabe steht gerade nicht auf der höchsten Stufe — im Log unten könnten Fehler fehlen. Im Tab „Einstellungen“ hochsetzen.',
            'LOGFILE_RENAMED'    => 'Logdatei archiviert als %s',

            'COL_TYPE'    => 'Typ',
            'COL_TIME'    => 'Zeit',
            'COL_MESSAGE' => 'Meldung',
            'COL_FILE'    => 'Datei',
            'COL_LINE'    => 'Zeile',

            'SETTINGS_INTRO'  => 'Diese Schalter steuern, wie viele Diagnose-Informationen WBCE erzeugt. Sie sind für Entwicklung und Staging gedacht — im Live-Betrieb ausgeschaltet lassen.',

            'ERL_CARD'  => 'PHP-Fehlerausgabe (Level)',
            'ERL_DESC'  => 'Legt <code>error_reporting()</code> und <code>display_errors</code> für die gesamte Website fest (angewendet in <code>wbce_setup_error_reporting()</code>, <code>framework/initialize.php</code>). Gespeichert in der Datenbank (Tabelle <code>settings</code>, Schlüssel <code>er_level</code>). Solange „WBCE Debug“ unten aktiv ist, wird dieser Wert ignoriert — der Debug-Modus erzwingt immer <code>E_ALL</code>.',
            'ERL_LABEL' => 'Fehler-Level',

            'DEBUG_CARD' => 'Debug-Schalter',
            'DEBUG_DESC' => 'Werden in <code>var/config_constants.ini.php</code> gespeichert und überstehen damit Updates. Jeder Schalter erzeugt nur zusätzliche Diagnose-Ausgaben — keiner verändert die Darstellung der Seiten.',

            'WBCE_DEBUG_LABEL' => 'WBCE Debug',
            'WBCE_DEBUG_DESC'  => 'Globaler Entwicklungsmodus. Erzwingt <code>error_reporting = E_ALL</code> und <code>display_errors</code> an (<code>framework/initialize.php</code>) und wird für ältere Addons als veraltete Konstante <code>WB_DEBUG</code> gespiegelt. Zusätzlich aktiv: sichtbare Droplet-Syntaxfehler, <code>console.error()</code>-Ausgaben des Asset-Managers nur für Admins (<code>framework/Assets/AssetQueue.php</code>), Mehr-Ausgabe im „Passwort vergessen“-Formular sowie einige weitere Modul-Debug-Pfade. Niemals auf einer öffentlichen Website aktivieren.',
            'WBCE_DEBUG_LOCKED' => 'Gesperrt: <code>WB_DEBUG</code> ist noch fest in der <code>config.php</code> gesetzt. Bitte dort zuerst entfernen (siehe Hinweis oben), danach lässt sich <code>WBCE_DEBUG</code> hier schalten.',

            'SQL_DEBUG_LABEL' => 'SQL Debug',
            'SQL_DEBUG_DESC'  => 'Datenbank-Diagnose. Schlägt eine Query fehl, löst <code>framework/Database.php</code> eine PHP-Warnung mit der Fehlermeldung <em>und</em> dem betroffenen SQL aus, statt sie nur intern für <code>hasError()</code> vorzuhalten. Außerdem eine Warnung, wenn Code eine unbekannte <code>$database->…</code>-Eigenschaft liest. Praktisch, um fehlerhafte Queries im Kern oder in Modulen aufzuspüren.',

            'PDO_DEBUG_LABEL' => 'PDO Syntax Debug',
            'PDO_DEBUG_DESC'  => 'Migrationshilfe für Entwickler. <code>framework/Database.php</code> bildet die alten mysqli-Methodennamen (<code>get_one</code>, <code>get_array</code>, <code>updateRow</code>, <code>delRow</code>, <code>is_error</code> …) auf die neuen PDO-Methoden ab. Mit diesem Schalter löst <em>jeder</em> solche Legacy-Aufruf eine <code>E_USER_DEPRECATED</code>-Meldung mit dem kanonischen Ersatz aus; nicht-kanonisches SQL wird ebenfalls markiert. Nur sinnvoll, während man Code aktiv auf die neue API umstellt — sonst sehr gesprächig.',

            'FILE_BASED_NOTE' => 'Wird in <code>var/config_constants.ini.php</code> geschrieben — updatesicher.',
            'BADGE_PREVIEW'   => 'Erscheint in der Log-Tabelle als',
            'DOCS_LINK'       => 'Modul-Dokumentation',

            // ── CodeVet-Tab ───────────────────────────────────────────────
            'TAB_CODEVET'           => 'CodeVet',
            'CV_SEARCH_PLACEHOLDER' => 'Im CodeVet-Log suchen …',
            'CV_ARCHIVE'            => 'Log archivieren',
            'CV_ARCHIVE_CONFIRM'    => 'Aktuelles CodeVet-Log archivieren? Es wird mit Zeitstempel umbenannt; nichts wird gelöscht.',
            'CV_ARCHIVED'           => 'CodeVet-Log archiviert.',
            'CV_EVENTS'             => 'Ereignisse',
            'CV_EMPTY'              => 'Nichts zu beanstanden. CodeVet hat bei keinem gespeicherten Droplet, Outputfilter, Code2-Abschnitt oder hochgeladenen Addon ein Problem gefunden.',
            'CV_COL_ACTION'         => 'Auslöser',
            'CV_COL_FINDINGS'       => 'Findings',
            'CV_COL_WHERE'          => 'Wo',
            'CV_SEV_BLOCK'          => 'Blockiert',
            'CV_SEV_WARN'           => 'Markiert',
            'CV_ACT_DROPLET'        => 'Droplet gespeichert',
            'CV_ACT_OUTPUTFILTER'   => 'Outputfilter gespeichert',
            'CV_ACT_CODE2'          => 'Code2-Abschnitt gespeichert',
            'CV_ACT_ADDON'          => 'Addon-ZIP hochgeladen',
            'CV_ACT_OTHER'          => 'Code gespeichert',
            'CV_LINE'               => 'Zeile',
            'CV_MORE_FINDINGS'      => 'weitere Findings',
            'CV_LOAD_ARCHIVES'      => 'Archive einbeziehen',
            'CV_CURRENT_ONLY'       => 'Nur aktuelles Log',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Hinweis:</b> <code>WB_DEBUG</code> scheint in der <code>config.php</code> gesetzt zu sein. Diese Konstante ist seit WBCE 1.7.0 veraltet (deprecated) — bitte aus der <code>config.php</code> entfernen. Stattdessen bitte <code>WBCE_DEBUG</code> (siehe Schalter unten) verwenden. Sobald die <code>WB_DEBUG</code>-Konstante gelöscht wurde, kann <code>WBCE_DEBUG</code> hier ein-/ausgeschaltet werden.',
        ]
    ],

    'NL' => [
        'module_name' => 'Errorlog-Viewer',
        'module_description' => 'Schrijft PHP-waarschuwingen en -fouten naar een privé-logbestand en toont ze overzichtelijk in de backend — plus een CodeVet-tabblad met het controlelogboek van elke Droplet-, Outputfilter-, Code2- of add-on-opslag die CodeVet wegens gevaarlijke code heeft geblokkeerd of gemarkeerd.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Goed nieuws. Er zijn momenteel geen fouten gemeld.',
            'RELOAD'          => 'Vernieuwen',
            'DELETE_LOGFILE'  => 'Logbestand verwijderen',
            'PLAIN_VIEW'      => 'Standaardweergave',
            'COLOR_VIEW'      => 'Kleurenweergave',
            'TABLE_VIEW'      => 'Tabelweergave',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Instellingen',
            'SEARCH_PLACEHOLDER' => 'In log zoeken …',
            'NO_RESULTS'         => 'Geen logregel komt overeen met de zoekopdracht.',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Opmerking:</b> <code>WB_DEBUG</code> lijkt ingesteld te zijn in <code>config.php</code>. Deze constante is verouderd (deprecated) sinds WBCE 1.7.0 — verwijder hem uit <code>config.php</code>. Gebruik in plaats daarvan <code>WBCE_DEBUG</code> (zie de schakelaar hieronder). Zodra <code>WB_DEBUG</code> is verwijderd, kan <code>WBCE_DEBUG</code> hier worden in-/uitgeschakeld.',
        ]
    ],

    'PL' => [
        'module_name' => 'Errorlog-Viewer',
        'module_description' => 'Zapisuje ostrzeżenia i błędy PHP do prywatnego pliku logu i wygodnie pokazuje je w panelu — wraz z zakładką CodeVet z dziennikiem kontroli każdego zapisu Dropletu, Outputfiltera, Code2 lub dodatku, który CodeVet zablokował lub oznaczył z powodu niebezpiecznego kodu.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Dobra wiadomość. Nie zgłoszono żadnych błędów.',
            'RELOAD'          => 'Odśwież',
            'DELETE_LOGFILE'  => 'Usuń plik logu',
            'PLAIN_VIEW'      => 'Widok zwykły',
            'COLOR_VIEW'      => 'Widok kolorowy',
            'TABLE_VIEW'      => 'Widok tabelaryczny',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Ustawienia',
            'SEARCH_PLACEHOLDER' => 'Szukaj w logu …',
            'NO_RESULTS'         => 'Żadna linia logu nie pasuje do wyszukiwania.',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Uwaga:</b> <code>WB_DEBUG</code> wydaje się być ustawiona w <code>config.php</code>. Ta stała jest przestarzała (deprecated) od WBCE 1.7.0 — usuń ją z <code>config.php</code>. Zamiast niej użyj <code>WBCE_DEBUG</code> (patrz przełącznik poniżej). Po usunięciu <code>WB_DEBUG</code> przełącznik <code>WBCE_DEBUG</code> będzie dostępny tutaj.',
        ]
    ],

    'NO' => [
        'module_name' => 'Errorlog-Viewer',
        'module_description' => 'Skriver PHP-advarsler og feil til en privat loggfil og viser dem oversiktlig i administrasjonen — pluss en CodeVet-fane med revisjonsloggen for hver Droplet-, Outputfilter-, Code2- eller tilleggslagring som CodeVet blokkerte eller merket for farlig kode.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Gode nyheter. Ingen feil rapportert.',
            'RELOAD'          => 'Oppdater',
            'DELETE_LOGFILE'  => 'Slett loggfil',
            'PLAIN_VIEW'      => 'Enkel visning',
            'COLOR_VIEW'      => 'Fargevisning',
            'TABLE_VIEW'      => 'Tabellvisning',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Innstillinger',
            'SEARCH_PLACEHOLDER' => 'Søk i loggen …',
            'NO_RESULTS'         => 'Ingen logglinje samsvarer med søket.',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Merknad:</b> <code>WB_DEBUG</code> ser ut til å være satt i <code>config.php</code>. Denne konstanten er utdatert (deprecated) siden WBCE 1.7.0 — fjern den fra <code>config.php</code>. Bruk <code>WBCE_DEBUG</code> i stedet (se bryteren nedenfor). Når <code>WB_DEBUG</code> er fjernet, kan <code>WBCE_DEBUG</code> slås av/på her.',
        ]
    ],

    'FR' => [
        'module_name' => 'Visualiseur de logs d\'erreurs',
        'module_description' => 'Écrit les avertissements et erreurs PHP dans un fichier journal privé et les affiche confortablement dans le backend — avec un onglet CodeVet présentant le journal d’audit de chaque enregistrement de Droplet, Outputfilter, Code2 ou module bloqué ou signalé par CodeVet pour code dangereux.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Bonne nouvelle. Aucun rapport d\'erreur.',
            'RELOAD'          => 'Actualiser',
            'DELETE_LOGFILE'  => 'Supprimer le fichier journal',
            'PLAIN_VIEW'      => 'Vue brute',
            'COLOR_VIEW'      => 'Vue colorée',
            'TABLE_VIEW'      => 'Vue tableau',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Réglages',
            'SEARCH_PLACEHOLDER' => 'Rechercher dans le journal …',
            'NO_RESULTS'         => 'Aucune ligne du journal ne correspond à la recherche.',
        ],
        'MSG' => [
           'WB_DEBUG_DEPRECATED'      => '<b>Remarque :</b> <code>WB_DEBUG</code> semble être définie dans <code>config.php</code>. Cette constante est obsolète (deprecated) depuis WBCE 1.7.0 — veuillez la supprimer de <code>config.php</code>. Utilisez plutôt <code>WBCE_DEBUG</code> (voir l\'interrupteur ci-dessous). Une fois <code>WB_DEBUG</code> supprimée, <code>WBCE_DEBUG</code> pourra être activée/désactivée ici.',
        ]
    ],

    'IT' => [
        'module_name' => 'Visualizzatore Errorlog',
        'module_description' => 'Scrive avvisi ed errori PHP in un file di log privato e li mostra comodamente nel backend, con una scheda CodeVet che riporta il registro di controllo di ogni salvataggio di Droplet, Outputfilter, Code2 o add-on bloccato o segnalato da CodeVet per codice pericoloso.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Buone notizie. Nessun errore segnalato.',
            'RELOAD'          => 'Ricarica',
            'DELETE_LOGFILE'  => 'Elimina file di log',
            'PLAIN_VIEW'      => 'Vista semplice',
            'COLOR_VIEW'      => 'Vista a colori',
            'TABLE_VIEW'      => 'Vista a tabella',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Impostazioni',
            'SEARCH_PLACEHOLDER' => 'Cerca nel log …',
            'NO_RESULTS'         => 'Nessuna riga del log corrisponde alla ricerca.',
        ],
        'MSG' => [
             'WB_DEBUG_DEPRECATED'      => '<b>Nota:</b> <code>WB_DEBUG</code> sembra essere impostata in <code>config.php</code>. Questa costante è deprecata da WBCE 1.7.0 — rimuoverla da <code>config.php</code>. Utilizzare invece <code>WBCE_DEBUG</code> (vedi l\'interruttore in basso). Una volta rimossa <code>WB_DEBUG</code>, sarà possibile attivare/disattivare <code>WBCE_DEBUG</code> qui.',
        ]
    ],

    'ES' => [
        'module_name' => 'Visor de Errorlog',
        'module_description' => 'Escribe advertencias y errores PHP en un archivo de registro privado y los muestra cómodamente en el backend, con una pestaña CodeVet que recoge el registro de auditoría de cada guardado de Droplet, Outputfilter, Code2 o complemento que CodeVet bloqueó o marcó por código peligroso.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Buenas noticias. No hay errores reportados.',
            'RELOAD'          => 'Recargar',
            'DELETE_LOGFILE'  => 'Eliminar archivo de registro',
            'PLAIN_VIEW'      => 'Vista simple',
            'COLOR_VIEW'      => 'Vista en color',
            'TABLE_VIEW'      => 'Vista en tabla',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Ajustes',
            'SEARCH_PLACEHOLDER' => 'Buscar en el registro …',
            'NO_RESULTS'         => 'Ninguna línea del registro coincide con la búsqueda.',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Aviso:</b> <code>WB_DEBUG</code> parece estar definida en <code>config.php</code>. Esta constante está obsoleta (deprecated) desde WBCE 1.7.0 — elimínala de <code>config.php</code>. Usa <code>WBCE_DEBUG</code> en su lugar (ver el interruptor abajo). Una vez eliminada <code>WB_DEBUG</code>, podrás activar/desactivar <code>WBCE_DEBUG</code> aquí.',
        ]
    ],

    'RU' => [
        'module_name' => 'Просмотрщик лога ошибок',
        'module_description' => 'Записывает предупреждения и ошибки PHP в приватный лог-файл и удобно показывает их в бэкенде, а вкладка CodeVet содержит журнал проверки каждого сохранения Droplet, Outputfilter, Code2 или дополнения, заблокированного или помеченного CodeVet из-за опасного кода.',
        'TXT' => [
            'NO_ERROR_REPORT' => 'Хорошие новости. Ошибок не обнаружено.',
            'RELOAD'          => 'Обновить',
            'DELETE_LOGFILE'  => 'Удалить лог-файл',
            'PLAIN_VIEW'      => 'Простой вид',
            'COLOR_VIEW'      => 'Цветное отображение',
            'TABLE_VIEW'      => 'Табличный вид',

            'TAB_LOG'            => 'Error-Log',
            'TAB_SETTINGS'       => 'Настройки',
            'SEARCH_PLACEHOLDER' => 'Поиск в логе …',
            'NO_RESULTS'         => 'Ни одна строка лога не соответствует запросу.',
        ],
        'MSG' => [
            'WB_DEBUG_DEPRECATED'      => '<b>Примечание:</b> <code>WB_DEBUG</code>, по всей видимости, установлена в <code>config.php</code>. Эта константа устарела (deprecated) начиная с WBCE 1.7.0 — удалите её из <code>config.php</code>. Используйте вместо неё <code>WBCE_DEBUG</code> (см. переключатель ниже). После удаления <code>WB_DEBUG</code> переключатель <code>WBCE_DEBUG</code> станет доступен здесь.',
        ]
    ],
];
