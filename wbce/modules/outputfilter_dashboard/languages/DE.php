<?php

/*
languages/DE.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.4
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2017 Martin Hecht (mrbaseman)
 * @link            https://github.com/WebsiteBaker-modules/outpufilter_dashboard
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
  GERMAN LANGUAGE FILE FOR THE  MODULE: Outputfilter Dashboard
 -----------------------------------------------------------------------------------------
*/

// include English file so that all strings have at least a default value
include((dirname(__FILE__))."/EN.php");

// Deutsche Modulbeschreibung
$module_description = "Admin-Tool um OutputFilter Dashboard zu verwalten";

$LANG['MOD_OPF']['TXT_MODULE_TYPE_LAST']='Ein <em>"Modul-Typ&quot;</em> oder <em>&quot;Modul-Typ (Ende)&quot;</em> Filter wird auf alle Abschnitte angewendet, die in der Modultyp Baumansicht unten ausgew&auml;hlt sind.';

$LANG['MOD_OPF']['TXT_MODULE_TREE']='W&auml;len Sie im Modul-Baum unten die Module aus, auf die der Filter angewendet werden soll';

$LANG['MOD_OPF']['TXT_TYPE_LAST']='Ein <em>Page-Type</em> oder <em>Page-Type (last)</em> Filter wird auf jede angezeigte Seite als ganzes angewendet (inclusive den output von z.B. snippets oder des Templates an sich).';

$LANG['MOD_OPF']['TXT_SUB_PAGES']='W&auml;hlen Sie unten (Unter-)Seiten aus.<br />Der Filter wird auf alle ausgew&auml;hlten Seiten, die die entsprechenden ausgew&auml;hlten Module aus dem obigen &quot;Modul-Baum&quot; enthalten, angewendet.';

$LANG['MOD_OPF']['TXT_FILTER_FUNC'] = 'Geben Sie die Filter-Funktion hier ein.';

$LANG['MOD_OPF']['TXT_FILTER_CONF'] = 'Filter Konfiguration';

$LANG['MOD_OPF']['TXT_FILTER_DESC'] = 'Filter Beschreibung';

$LANG['MOD_OPF']['TXT_FILTER_NAME'] = 'Name des Filters';

$LANG['MOD_OPF']['TXT_ACTIVE'] = 'aktiv';

$LANG['MOD_OPF']['TXT_INACTIVE'] = 'inaktiv';

$LANG['MOD_OPF']['TXT_DESCRIPTION'] = 'Beschreibung';

$LANG['MOD_OPF']['TXT_FILTER_HELP'] = 'Hilfe zu diesem Filter';

$LANG['MOD_OPF']['TXT_HELP'] = 'Hilfe';

$LANG['MOD_OPF']['TXT_FILTER_OUTPUT_SETTINGS'] = 'Filter Ausgabeeinstellungen (Seiten/Module)';

$LANG['MOD_OPF']['TXT_TYPE'] = 'Typ';

$LANG['MOD_OPF']['TXT_FILTER_MODULES'] = 'Filter auf diese Module anwenden';

$LANG['MOD_OPF']['TXT_FILTER_PAGES'] = 'Filter auf diese Seiten anwenden';

$LANG['MOD_OPF']['TXT_FUNCTION'] = 'Filter-Funktion';

$LANG['MOD_OPF']['TXT_FUNC_NAME'] = 'Name der Funktion';

$LANG['MOD_OPF']['TXT_FILTER_FILE'] = 'Filter-Datei';

$LANG['MOD_OPF']['TXT_SAVE'] = 'Speichern';

$LANG['MOD_OPF']['TXT_SAVE_AND_CLOSE'] = 'Speichern und schlie&szlig;en';

$LANG['MOD_OPF']['TXT_EXIT'] = 'Verlassen';

$LANG['MOD_OPF']['TXT_CANCEL'] = 'Abbrechen';

$LANG['MOD_OPF']['TXT_OUTPUTFILTER'] = 'OutputFilter - CSS-Editor';

$LANG['MOD_OPF']['TXT_EDITCSS'] = 'CSS bearbeiten';

$LANG['MOD_OPF']['TXT_EXPORT_FAILED'] = 'Export fehlgeschlagen';

$LANG['MOD_OPF']['TXT_EXPORT_SUCCESS'] = 'Export erfolgreich';

$LANG['MOD_OPF']['TXT_CONVERT_FAILED'] = 'Konvertierung fehlgeschlagen';

$LANG['MOD_OPF']['TXT_CONVERT_SUCCESS'] = 'Konvertierung erfolgreich';

$LANG['MOD_OPF']['TXT_OK'] = 'OK';

$LANG['MOD_OPF']['TXT_DOWNLOAD'] = 'Download';

$LANG['MOD_OPF']['TXT_DOWNLOAD_FAILED'] = 'Download fehlgeschlagen';

$LANG['MOD_OPF']['TXT_UPLOAD_SUCCESS']  = 'Upload erfolgreich';

$LANG['MOD_OPF']['TXT_OUTPUTFILTER_DASHBOARD'] = 'OutputFilter - Dashboard';

$LANG['MOD_OPF']['TXT_PATCH_COREFILES'] = 'Damit dieses AdminTool funktioniert m&uuml;ssen noch zwei Dateien ver&auml;ndert werden. <a href="%s">Bitte lesen Sie die Dokumentation</a>';

$LANG['MOD_OPF']['TXT_ACTIVATE_JS']  = 'Hinweis: Zur vollt&auml;ndigen Nutzung aller Komfort-Features ben&ouml;tigt dieses Modul Javascript.';

$LANG['MOD_OPF']['TXT_ADD_FILTER']  = 'Filter hinzuf&uuml;gen';

$LANG['MOD_OPF']['TXT_NEW_INLINEFILTER']  = 'Neuen Inline-Filter hinzuf&uuml;gen';

$LANG['MOD_OPF']['TXT_UPLOAD_FILTER'] = 'Filter hochladen';

$LANG['MOD_OPF']['TXT_UPLOAD_PLUGIN_FILTER']  = 'Plugin-Filter hochladen';

$LANG['MOD_OPF']['TXT_HELP_BROWSER']  = '&Ouml;ffne Hilfe-Browser';

$LANG['MOD_OPF']['TXT_ABOUT']  = '&Uuml;ber';

$LANG['MOD_OPF']['TXT_UPLOAD_FILTER_PLUGIN'] = 'Plugin-Filter hochladen';

$LANG['MOD_OPF']['TXT_INSTALL_PLUGIN_FILTER']  = 'Plugin-Filter installieren';

$LANG['MOD_OPF']['TXT_UPLOAD'] = 'Upload';

$LANG['MOD_OPF']['TXT_ACTIONS'] = 'Aktionen';

$LANG['MOD_OPF']['TXT_FILTER_ACTIVE']  = 'Filter AKTIVIERT (deaktiviere Filter durch klicken)';

$LANG['MOD_OPF']['TXT_FILTER_INACTIVE'] = 'Filter DEAKTIVIERT (aktiviere Filter durch klicken)';

$LANG['MOD_OPF']['TXT_EDIT_FILTER'] = 'Filter Bearbeiten';

$LANG['MOD_OPF']['TXT_PLUGIN_FILTER'] = 'Plugin-Filter';

$LANG['MOD_OPF']['TXT_INLINE_FILTER']  = 'Inline-Filter';

$LANG['MOD_OPF']['TXT_MODULE_EXTENSION_FILTER']  = 'Modul-Filter';

$LANG['MOD_OPF']['TXT_CONFIG_FILTER']  = 'Filter konfigurieren';

$LANG['MOD_OPF']['TXT_MOVE_UP'] = 'Aufw&auml;rts';

$LANG['MOD_OPF']['TXT_MOVE_DOWN'] = 'Abw&auml;rts';

$LANG['MOD_OPF']['TXT_DELETE_FILTER'] = 'Filter l&ouml;schen';

$LANG['MOD_OPF']['TXT_SURE_TO_DELETE'] = 'Wollen Sie den folgenden Filter wirklich l&ouml;schen<br /><b>%s</b>?';

$LANG['MOD_OPF']['TXT_CONVERT_FILTER'] = 'Filter in Plugin konvertieren';

$LANG['MOD_OPF']['TXT_SURE_TO_CONVERT'] = 'Wollen Sie den folgenden Filter wirklich in ein Plugin konvertieren<br /><b>%s</b>?';

$LANG['MOD_OPF']['TXT_CONVERT_PLUGIN'] = 'In Inline-Filter konvertieren';

$LANG['MOD_OPF']['TXT_SURE_TO_INLINE'] = 'Wollen Sie den folgendes Plugin wirklich in einen Inline Filter konvertieren<br /><b>%s</b>?';

$LANG['MOD_OPF']['TXT_EXPORT_FILTER']  = 'Filter exportieren';

$LANG['MOD_OPF']['TXT_VISIT_PROJECTS_WEBSITE'] = 'Besuchen Sie die Homepage des Projektes';

$LANG['MOD_OPF']['TXT_INSERT_NAME'] = 'Hier Namen eingeben';

$LANG['MOD_OPF']['TXT_INSERT_DESCRIPTION'] = 'Hier Beschreibung eingeben';

$LANG['MOD_OPF']['TXT_MODULE'] = 'Modul';

$LANG['MOD_OPF']['TXT_MODULE_LAST'] = 'Modul (zuletzt)';

$LANG['MOD_OPF']['TXT_PAGE'] = 'Seite';

$LANG['MOD_OPF']['TXT_PAGE_LAST'] = 'Seite (zuletzt)';

$LANG['MOD_OPF']['TXT_ALL_MODULES'] = 'Alle Module';

$LANG['MOD_OPF']['TXT_SINGLE_PAGE'] = 'einzelne Seite';

$LANG['MOD_OPF']['TXT_PAGE_HIERARCHY'] = 'Seitenhierarchie';

$LANG['MOD_OPF']['TXT_SEARCH_RESULTS'] = 'Suchresultate';

$LANG['MOD_OPF']['TXT_BACKEND'] = 'Backend';

$LANG['MOD_OPF']['TXT_ALL_PAGES'] = 'Alle Seiten';

$LANG['MOD_OPF']['TXT_EXPORT_FAILED_PLUGIN'] = 'Export fehlgeschlagen: %s';

$LANG['MOD_OPF']['TXT_CONVERT_FAILED_PLUGIN'] = 'Konvertierung fehlgeschlagen: %s';

$LANG['MOD_OPF']['TXT_WRITE_DENIED'] = 'Kann nicht ins Verzeichnis %s schreiben';

$LANG['MOD_OPF']['TXT_NO_FILTER'] = 'Filter existiert nicht';

$LANG['MOD_OPF']['TXT_NO_EXPORT'] = 'Ein Modul-Filter kann nicht exportiert werden';

$LANG['MOD_OPF']['TXT_NO_CONVERT'] = 'Ein Modul-Filter kann nicht konvertiert werden';

$LANG['MOD_OPF']['TXT_NO_SUCH_DIR'] = 'Verzeichnis existiert nicht';

$LANG['MOD_OPF']['TXT_WRITE_FAILED'] = 'Konnte Datei %s nicht schreiben';

$LANG['MOD_OPF']['TXT_PLUGIN_EXPORTED'] = 'Plugin erfolgreich exportiert';

$LANG['MOD_OPF']['TXT_FAILED_TO_UPLOAD'] = 'Upload des Plugins fehlgeschlagen: %s';

$LANG['MOD_OPF']['TXT_DIR_WRITE_FAILED'] = 'Kann nicht ins Plugin-Verzeichnis schreiben';

$LANG['MOD_OPF']['TXT_UPLOAD_FAILED'] = 'Upload fehlgeschlagen';

$LANG['MOD_OPF']['TXT_NOT_A_FILTER'] = 'Hochgeladene Datei ist kein OutputFilter Plugin';

$LANG['MOD_OPF']['TXT_LOOKS_LIKE_MODULE'] = ' - es scheint ein Modulfilter zu sein. Installieren Sie es unter <a href=%s>Module</a>.';

$LANG['MOD_OPF']['TXT_ALREADY_INSTALLED'] = 'Eine neuere Version ist bereits installiert';

$LANG['MOD_OPF']['TXT_UNZIP_FAILED'] = 'Entpacken der Datei fehlgeschlagen';

$LANG['MOD_OPF']['TXT_PLUGIN_UPLOAD_SUCCESS'] = 'Plugin erfolgreich hochgeladen';

$LANG['MOD_OPF']['TXT_ERR_NO_UPLOAD'] = 'Keine Datei hochgeladen';

$LANG['MOD_OPF']['TXT_ERR_PHP_ERR'] = 'PHP Fehler code: %d';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_SIZE'] = 'Hochgeladene Datei sprengt das Gr&ouml;&szlig;enlimit %d';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_EXT'] = 'Hochgeladene Datei erf&uuml;llt nicht die Erweiterung %s';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_TYPE'] = 'Hochgeladene Datei hat falschen Typ %s';

$LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'] = 'Abgebrochen: %s Sicherheitsausnahme!';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_FAILED'] = 'Hochgeladene Datei konnte nicht gespeichert werden';
