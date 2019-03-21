<?php

/*
languages/IT.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.8
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2019 Martin Hecht (mrbaseman)
 * @link            https://github.com/WebsiteBaker-modules/outputfilter_dashboard
 * @link            http://forum.websitebaker.org/index.php/topic,28926.0.html
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @link            http://addons.wbce.org/pages/addons.php?do=item&item=53
 * @license         GNU General Public License, Version 3
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.4 and higher
 *
 * This file is part of OutputFilter-Dashboard, a module for Website Baker CMS.
 *
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 *
 **/

/*
 -----------------------------------------------------------------------------------------
  ITALIAN LANGUAGE FILE FOR THE  MODULE: Outputfilter Dashboard
 -----------------------------------------------------------------------------------------
*/

// include English file so that all strings have at least a default value
include((dirname(__FILE__))."/EN.php");

// Descrizione italiano
$module_description = "Admin-Tool per gestire OutputFilter Dashboard";


$LANG['MOD_OPF']['TXT_MODULE_TYPE_LAST'] = 'Un filtro di <em>&quot; tipo modulo (primo)&quot;</ em> o <em>&quot;tipo modulo&quot;</ em> o <em>&quot; tipo modulo (ultimo)&quot;</ em> verr&agrave; applicato ad ogni visualizzata sezioni dei moduli selezionati nel arbero moduli ci sotto.';

$LANG['MOD_OPF']['TXT_MODULE_TREE'] = 'Nel albero modulo ci sotto, selezionare i moduli a cui deve essere applicato il filtro';

$LANG['MOD_OPF']['TXT_TYPE_LAST'] = 'Un filtro di <em>&quot;tipo pagina (primo)&quot;</ em> o <em>&quot;tipo pagina&quot;</ em> o <em>&quot;tipo pagina (ultimo)&quot;</ em>o <em>&quot;tipo pagina (finale)&quot;</ em> verr&agrave; applicato ad ogni pagina visualizzata (tra cui ad esempio, il resultato  dei &quot;Code-Snippet&quot;  e il modello stesso) intera.';

$LANG['MOD_OPF']['TXT_SUB_PAGES'] = 'Selezionare o deselezionare le pagine / sottopagine sotto <br /> Il filtro sar&agrave; applicato a tutte le pagine selezionate insieme a tutti i moduli selezionati nel sopra &quot;albero moduli&quot;' ;

$LANG['MOD_OPF']['TXT_FILTER_FUNC'] = 'Inserire il funzione filtro qui sotto.';

$LANG['MOD_OPF']['TXT_FILTER_CONF'] = 'Configurazione filtro';

$LANG['MOD_OPF']['TXT_FILTER_DESC'] = 'Descrizione filtro';

$LANG['MOD_OPF']['TXT_FILTER_NAME'] = 'Nome del filtro';

$LANG['MOD_OPF']['TXT_ACTIVE'] = 'attivo';

$LANG['MOD_OPF']['TXT_INACTIVE'] = 'inattivo';

$LANG['MOD_OPF']['TXT_DESCRIPTION'] = 'Descrizione';

$LANG['MOD_OPF']['TXT_FILTER_HELP'] = 'Aiuto per questo filtro';

$LANG['MOD_OPF']['TXT_HELP'] = 'Aiuto';

$LANG['MOD_OPF']['TXT_FILTER_OUTPUT_SETTINGS'] = 'Impostazioni di filtro (Pagine / Modules)';

$LANG['MOD_OPF']['TXT_TYPE'] = 'Tipo';

$LANG['MOD_OPF']['TXT_FILTER_MODULES'] = 'Applicare il filtro a questi moduli';

$LANG['MOD_OPF']['TXT_FILTER_PAGES'] = 'Applicare il filtro a queste pagine';

$LANG['MOD_OPF']['TXT_FUNCTION'] = 'funzione filtro';

$LANG['MOD_OPF']['TXT_FUNC_NAME'] = 'Nome della funzione';

$LANG['MOD_OPF']['TXT_FILTER_FILE'] = 'Filtro-file';

$LANG['MOD_OPF']['TXT_SAVE'] = 'Salva';

$LANG['MOD_OPF']['TXT_SAVE_AND_CLOSE'] = 'Salva e chiudi';

$LANG['MOD_OPF']['TXT_EXIT'] = 'Uscita';

$LANG['MOD_OPF']['TXT_CANCEL'] = 'Annulla';

$LANG['MOD_OPF']['TXT_OUTPUTFILTER'] = 'filtro di output - CSS-Editor';

$LANG['MOD_OPF']['TXT_EDITCSS'] = 'Edit CSS';

$LANG['MOD_OPF']['TXT_EXPORT_FAILED'] = 'esportazione non riuscita';

$LANG['MOD_OPF']['TXT_EXPORT_SUCCESS'] = 'Export con successo';

$LANG['MOD_OPF']['TXT_CONVERT_FAILED'] = 'Trasformazione non riuscita';

$LANG['MOD_OPF']['TXT_CONVERT_SUCCESS'] = 'Trasformazione con successo';

$LANG['MOD_OPF']['TXT_OK'] = 'OK';

$LANG['MOD_OPF']['TXT_DOWNLOAD'] = 'Download';

$LANG['MOD_OPF']['TXT_DOWNLOAD_FAILED'] = 'download non riuscito';

$LANG['MOD_OPF']['TXT_UPLOAD_SUCCESS'] = 'Carica di successo';

$LANG['MOD_OPF']['TXT_OUTPUTFILTER_DASHBOARD'] = 'Output Filter - Dashboard';

$LANG['MOD_OPF']['TXT_PATCH_COREFILES'] = '&Egrave; necessario correggere due file core per fare funzionando questo AdminTool. <a href = "%s"> Si prega di leggere la documentazione</a> ';

$LANG['MOD_OPF']['TXT_ACTIVATE_JS'] = 'Nota: Per profitare di tutti i comfort funzionalit&agrave; offerte da questo modulo necessario abilitare JavaScript.';

$LANG['MOD_OPF']['TXT_ADD_FILTER'] = 'Aggiungi un filtro';

$LANG['MOD_OPF']['TXT_NEW_INLINEFILTER'] = 'Aggiungi nuovo filtro in linea';

$LANG['MOD_OPF']['TXT_UPLOAD_FILTER'] = 'Carica filtro';

$LANG['MOD_OPF']['TXT_UPLOAD_PLUGIN_FILTER'] = 'filtro upload (plugin)';

$LANG['MOD_OPF']['TXT_HELP_BROWSER'] = 'Aiuto browser';

$LANG['MOD_OPF']['TXT_ABOUT'] = 'Chi';

$LANG['MOD_OPF']['TXT_UPLOAD_FILTER_PLUGIN'] = 'Carica plugin filtro';

$LANG['MOD_OPF']['TXT_INSTALL_PLUGIN_FILTER'] = 'installare il filtro plugin';

$LANG['MOD_OPF']['TXT_UPLOAD'] = 'Carica';

$LANG['MOD_OPF']['TXT_ACTIONS'] = 'Azioni';

$LANG['MOD_OPF']['TXT_FILTER_ACTIVE'] = 'filtro attivo (disattivare il filtro al clic)';

$LANG['MOD_OPF']['TXT_FILTER_INACTIVE'] = 'filtro innativo (attivare il filtro al clic)';

$LANG['MOD_OPF']['TXT_EDIT_FILTER'] = 'Modifica filtro';

$LANG['MOD_OPF']['TXT_PLUGIN_FILTER'] = 'filtro plugin';

$LANG['MOD_OPF']['TXT_INLINE_FILTER'] = 'filtro in linea';

$LANG['MOD_OPF']['TXT_MODULE_EXTENSION_FILTER'] = 'modulo filtro estensione';

$LANG['MOD_OPF']['TXT_CONFIG_FILTER'] = 'Configurazione filtro';

$LANG['MOD_OPF']['TXT_MOVE_UP'] = 'Sposta su';

$LANG['MOD_OPF']['TXT_MOVE_DOWN'] = 'Sposta gi&ugrave;';

$LANG['MOD_OPF']['TXT_DELETE_FILTER'] = 'Elimina filtro';

$LANG['MOD_OPF']['TXT_SURE_TO_DELETE'] = 'Sei sicuro di voler eliminare il filtro <br /> <b> %s </ b>?';

$LANG['MOD_OPF']['TXT_CONVERT_FILTER'] = 'trasformare il filtro in plugin';

$LANG['MOD_OPF']['TXT_SURE_TO_CONVERT'] = 'Sei sicuro di voler trasformare il filtro <br /><b>%s</b><br />in un plugin?';

$LANG['MOD_OPF']['TXT_CONVERT_PLUGIN'] = 'trasformare il plugin in un filtro inline';

$LANG['MOD_OPF']['TXT_SURE_TO_INLINE'] = 'Sei sicuro di voler trasformare il plugin <br /><b>%s</b><br />in un  filtro inline?';

$LANG['MOD_OPF']['TXT_EXPORT_FILTER'] = 'Filtro di esportazione';

$LANG['MOD_OPF']['TXT_VISIT_PROJECTS_WEBSITE'] = 'visitare i progetti sito web';

$LANG['MOD_OPF']['TXT_INSERT_NAME'] = 'Inserisci un nome qui';

$LANG['MOD_OPF']['TXT_INSERT_DESCRIPTION'] = 'Inserire una descrizione qui';

$LANG['MOD_OPF']['TXT_MODULE_FIRST'] = 'Modulo (primo)';

$LANG['MOD_OPF']['TXT_MODULE'] = 'Modulo';

$LANG['MOD_OPF']['TXT_MODULE_LAST'] = 'Modulo (ultimo)';

$LANG['MOD_OPF']['TXT_PAGE_FIRST'] = 'Pagina (primo)';

$LANG['MOD_OPF']['TXT_PAGE'] = 'Pagina';

$LANG['MOD_OPF']['TXT_PAGE_LAST'] = 'Pagina (ultimo)';

$LANG['MOD_OPF']['TXT_PAGE_FINAL'] = 'Pagina (finale)';

$LANG['MOD_OPF']['TXT_ALL_MODULES'] = 'Tutti i moduli';

$LANG['MOD_OPF']['TXT_SINGLE_PAGE'] = 'Pagina singola';

$LANG['MOD_OPF']['TXT_PAGE_HIERARCHY'] = 'gerarchia delle pagine';

$LANG['MOD_OPF']['TXT_SEARCH_RESULTS'] = 'resultati di richerca';

$LANG['MOD_OPF']['TXT_BACKEND'] = 'Backend';

$LANG['MOD_OPF']['TXT_ALL_PAGES'] = 'Tutte le pagine';

$LANG['MOD_OPF']['TXT_EXPORT_FAILED_PLUGIN'] = 'Impossibile esportare il filtro: %s';

$LANG['MOD_OPF']['TXT_CONVERT_FAILED_PLUGIN'] = 'Impossibile trasformare il filtro: %s';

$LANG['MOD_OPF']['TXT_WRITE_DENIED'] = 'non pu&oacute; scrivere nella directory %s';

$LANG['MOD_OPF']['TXT_NO_FILTER'] = 'filtro non esiste';

$LANG['MOD_OPF']['TXT_NO_EXPORT'] = 'Non e possibile esportare un modulo filtro';

$LANG['MOD_OPF']['TXT_NO_EXPORT'] = 'Non e possibile trasformare un modulo filtro';

$LANG['MOD_OPF']['TXT_NO_SUCH_DIR'] = 'directory non esiste';

$LANG['MOD_OPF']['TXT_WRITE_FAILED'] = 'non &egrave; riuscito a scrivere il file %s';

$LANG['MOD_OPF']['TXT_PLUGIN_EXPORTED'] = 'Plugin esportato con successo';

$LANG['MOD_OPF']['TXT_FAILED_TO_UPLOAD'] = 'Impossibile caricare plugin: %s';

$LANG['MOD_OPF']['TXT_DIR_WRITE_FAILED'] = 'Non &egrave; possibile scrivere al plugin-directory';

$LANG['MOD_OPF']['TXT_UPLOAD_FAILED'] = 'caricamento non &egrave; riuscito';

$LANG['MOD_OPF']['TXT_NOT_A_FILTER'] = 'file caricato non &egrave; un plugin filtro di output';

$LANG['MOD_OPF']['TXT_LOOKS_LIKE_MODULE'] = ' - sembra d&apos;essere un filtro di modulo. Installarlo tra le <a href=%s>Moduli</a>.';

$LANG['MOD_OPF']['TXT_ALREADY_INSTALLED'] = 'una versione pi&ugrave; recente &egrave; gi&agrave; installata';

$LANG['MOD_OPF']['TXT_UNZIP_FAILED'] = 'non &egrave; riuscito a decomprimere archivio';

$LANG['MOD_OPF']['TXT_PLUGIN_UPLOAD_SUCCESS'] = 'Plugin caricato con successo';

$LANG['MOD_OPF']['TXT_ERR_NO_UPLOAD'] = 'Nessun file caricato';

$LANG['MOD_OPF']['TXT_ERR_PHP_ERR'] = 'codice di errore PHP: %d';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_SIZE'] = 'Il file caricato supera il limite di dimensione %d';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_EXT'] = 'Il file caricato non corrisponde estensione %s';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_TYPE'] = 'Il file caricato non corrisponde il tipo %s';

$LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'] = 'Terminato: violazione di sicurezza %s!';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_FAILED'] = 'Impossibile salvare file caricato';
