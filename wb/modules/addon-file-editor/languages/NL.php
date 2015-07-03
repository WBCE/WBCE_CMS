<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the Dutch text outputs of the module.
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @translation Dutch translation by forum members Luckyluck, pcwacht, hans
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
*/

// Dutch module description
$module_description = 'De cwsoft-addon-file-editor kan je bestanden zoals modules, templates en taal bestanden "wijzigen", "wissen", "creëren", "uploaden" of "kopiëren" via de WebsiteBaker website-beheer. Meer informatie over <a href="https://github.com/cwsoft/wb-cwsoft-addon-file-editor#readme">GitHub</a>.';

// initialize global $LANG variable as array if needed
global $LANG;
if (! isset($LANG) || (isset($LANG) && ! is_array($LANG))) {
      $LANG = array();
}

// Text outputs overview page (templates/addons_overview.htt)
$LANG['ADDON_FILE_EDITOR'][1] = array(
      'TXT_DESCRIPTION'             => 'Deze lijst toont alle door PHP leesbare Add-ons. Door te klikken op de Add-on naam kan je ' . 
                                       'deze wijzigen. Met het download icoon wordt onmiddellijk een installeerbare backup ' .
                                       'van de Add-on gemaakt.',
      'TXT_FTP_NOTICE'              => 'Add-ons/bestanden in rood zijn niet beschrijfbaar door PHP. Dit kan het geval zijn indien je ' .
                                       'Add-ons installeerd door middel van FTP. Je zal <a class="link" target="_blank" href="{URL_ASSISTANT}">' .
                                       'FTP support</a> moeten gebruiken om deze Add-on bestanden te wijzigen.',
      'TXT_HEADING'                 => 'Geïnstalleerde Add-ons (Modules, Templates en Taal bestanden)',
      'TXT_HELP'                    => 'Help',

      'TXT_RELOAD'                  => 'Herlees',
      'TXT_ACTION_EDIT'             => 'Wijzigen',
      'TXT_ACTION_DELETE'           => 'Wissen',
      'TXT_FTP_SUPPORT'             => ' (schrijftoegang via FTP nodig om te wijzigen)',

      'TXT_MODULES'                 => 'Modules',
      'TXT_LIST_OF_MODULES'         => 'Lijst van geïnstalleerde modules',
      'TXT_EDIT_MODULE_FILES'       => 'Wijzig module bestanden',
      'TXT_ZIP_MODULE_FILES'        => 'Backup en download module bestanden',

      'TXT_TEMPLATES'               => 'Templates',
      'TXT_LIST_OF_TEMPLATES'       => 'Lijst van geïnstalleerde templates',
      'TXT_EDIT_TEMPLATE_FILES'     => 'Wijzig template bestanden',
      'TXT_ZIP_TEMPLATE_FILES'      => 'Backup en download template bestanden',

      'TXT_LANGUAGES'               => 'Taal bestanden',
      'TXT_LIST_OF_LANGUAGES'       => 'Lijst van geïnstalleerde WB taal bestanden',
      'TXT_EDIT_LANGUAGE_FILES'     => 'Wijzig taal bestanden',
      'TXT_ZIP_LANGUAGE_FILES'      => 'Download taal bestanden',
);

// Text outputs filemanager page (templates/filemanager.htt)
$LANG['ADDON_FILE_EDITOR'][2] = array(
      'TXT_EDIT_DESCRIPTION'        => 'Met deze bestandsbeheer kan je bestanden wijzigen, hernoemen, creëren, wissen en uploaden. Bij het klikken op een ' .
                                       'bestandsnaam opent het bestand om te wijzigen of te bekijken.',
      'TXT_BACK_TO_OVERVIEW'        => 'Terug naar het overzicht met alle Add-ons',

      'TXT_MODULE'                  => 'Module',
      'TXT_TEMPLATE'                => 'Template',
      'TXT_LANGUAGE'                => 'WB Taal bestand',
      'TXT_FTP_SUPPORT'             => ' (schrijftoegang via FTP nodig om te wijzigen)',

      'TXT_RELOAD'                  => 'Herlees',
      'TXT_CREATE_FILE_FOLDER'      => 'Creëer bestand/map',
      'TXT_UPLOAD_FILE'             => 'Upload bestand',
      'TXT_VIEW'                    => 'View',
      'TXT_EDIT'                    => 'Wijzigen',
      'TXT_UNZIP'                   => 'Uitpakken',
      'TXT_RENAME'                  => 'Hernoem',
      'TXT_DELETE'                  => 'Wis',

      'TXT_FILE_INFOS'              => 'Bestandsinformatie',
      'TXT_FILE_INFOS'              => 'Acties',
      'TXT_FILE_TYPE_TEXTFILE'      => 'Tekst bestand',
      'TXT_FILE_TYPE_FOLDER'        => 'map',
      'TXT_FILE_TYPE_IMAGE'         => 'Grafisch bestand',
      'TXT_FILE_TYPE_ARCHIVE'       => 'Archief bestand',
      'TXT_FILE_TYPE_OTHER'         => 'Onbekend',

      'DATE_FORMAT'                 => 'd/m/y - h:m',
);

// General text outputs for the file handler templates
$LANG['ADDON_FILE_EDITOR'][3] = array(
      'ERR_WRONG_PARAMETER'         => 'De opgegeven parameters zijn fout of niet compleet.',
      'TXT_MODULE'                  => 'Module',
      'TXT_TEMPLATE'                => 'Template',
      'TXT_LANGUAGE'                => 'WB Taal bestand',
      'TXT_ACTION'                  => 'Actie',
      'TXT_ACTUAL_FILE'             => 'Huidig bestand',
      'TXT_SUBMIT_CANCEL'           => 'Annuleer',
);      

// Text outputs file handler (templates/action_handler_edit_textfile.htt)
$LANG['ADDON_FILE_EDITOR'][4] = array(
      'TXT_ACTION_EDIT_TEXTFILE'    => 'Wijzig tekst bestand',
      'TXT_SUBMIT_SAVE'             => 'Bewaar',
      'TXT_SUBMIT_SAVE_BACK'        => 'Bewaar &amp; Terug',
      'TXT_ACTUAL_FILE'             => 'Huidig bestand',
      'TXT_SAVE_SUCCESS'            => 'Wijzigingen aan het bestand werden bewaard.',
      'TXT_SAVE_ERROR'              => 'Onmogelijk om het bestand te bewaren. Bekijk de permissies.',
);

// Text outputs file handler (templates/action_handler_rename_file_folder.htt)
$LANG['ADDON_FILE_EDITOR'][5] = array(
      'TXT_ACTION_RENAME_FILE'      => 'Hernoem bestand/map',
      'TXT_OLD_FILE_NAME'           => 'Bestand/map (oud)',
      'TXT_NEW_FILE_NAME'           => 'Bestand/map (nieuw)',
      'TXT_SUBMIT_RENAME'           => 'hernoem',
      'TXT_RENAME_SUCCESS'          => 'Hernoemen van het bestand of map is in orde.',
      'TXT_RENAME_ERROR'            => 'Onmogelijk om het bestand of de map te hernoemen. Bekijk de permissies.',
      'TXT_ALLOWED_FILE_CHARS'      => '[a-zA-Z0-9.-_]',
);

// Text outputs file handler (templates/action_handler_delete_file_folder.htt)
$LANG['ADDON_FILE_EDITOR'][6] = array(
      'TXT_ACTION_DELETE_FILE'      => 'Wis bestand/map',
      'TXT_SUBMIT_DELETE'           => 'Wis',
      'TXT_ACTUAL_FOLDER'           => 'Huidige map',
      'TXT_DELETE_WARNING'          => '<strong>Aandacht: </strong>wissen van bestanden of mappen kan niet ongedaan maken. Hou er ook rekening mee ' .
                                       'dat als je een map wist, ook alle bestanden in die map mee gewist worden.',
      'TXT_DELETE_SUCCESS'          => 'Bestand/map werd gewist.',
      'TXT_DELETE_ERROR'            => 'Onmogelijk om het bestand of de map te wissen. Bekijk de permissies. <br /><em>Aandacht: om een map te wissen ' .
                                       'met FTP, zorg ervoor dat de map geen andere map of bestanden bevat.</em>'
);

// Text outputs file handler (templates/action_handler_create_file_folder.htt)
$LANG['ADDON_FILE_EDITOR'][7] = array(
      'TXT_ACTION_CREATE_FILE'      => 'Cre&euml;er bestand/map',
      'TXT_CREATE'                  => 'Cre&euml;er',
      'TXT_FILE'                    => 'Bestand',
      'TXT_FOLDER'                  => 'Map',
      'TXT_FILE_NAME'               => 'Bestandsnaam',
      'TXT_ALLOWED_FILE_CHARS'      => '[a-zA-Z0-9.-_]',
      'TXT_SUBMIT_CREATE'           => 'Cre&euml;er',
      'TXT_TARGET_FOLDER'           => 'Doel map',
      'TXT_CREATE_SUCCESS'          => 'Bestand/map met succes aangemaakt.',
      'TXT_CREATE_ERROR'            => 'Onmogelijk om het bestand of de map te cre&euml;ren. Bekijk de permissies en bestandsnaam.',
      'TXT_INVALID_FILENAME'        => 'Opgegeven bestandsnaam of directory is ongeldig.',
);

// Text outputs file handler (templates/action_handler_upload_file.htt)
$LANG['ADDON_FILE_EDITOR'][8] = array(
      'TXT_ACTION_UPLOAD_FILE'      => 'Upload bestand',
      'TXT_SUBMIT_UPLOAD'           => 'Upload',

      'TXT_FILE'                    => 'bestand',
      'TXT_TARGET_FOLDER'           => 'Doel map',

      'TXT_UPLOAD_SUCCESS'          => 'Uploaden van het bestand compleet.',
      'TXT_UPLOAD_ERROR'            => 'Onmogelijk om het bestand te uploaden. Bekijk de permissies.',
);

// Text outputs for the download handler
$LANG['ADDON_FILE_EDITOR'][9] = array(
      'ERR_TEMP_PERMISSION'         => 'PHP heeft geen schrijf permissies voor de tijdelijke WB map (/temp).',
      'ERR_ZIP_CREATION'            => 'Onmogelijk om een archiefbestand te creëren.',
      'ERR_ZIP_DOWNLOAD'            => 'Fout tijdens het downloaden van het archief bestand.<br /><a href="{URL}">Download</a> bestand manueel.',
);

// Text outputs for the FTP checking (templates/ftp_connection_check.htt)
$LANG['ADDON_FILE_EDITOR'][10] = array(
      'TXT_FTP_HEADING'             => 'FTP Configuratie-Assistent',
      'TXT_FTP_DESCRIPTION'         => 'De FTP assistent helpt je tijdens de instellingen en test FTP support voor de Addon File Editor.',

      'TXT_FTP_SETTINGS'            => 'Huidige FTP instellingen',
      'TXT_FTP_SUPPORT'             => 'FTP Support',
      'TXT_ENABLE'                  => 'Aan',
      'TXT_DISABLE'                 => 'Uit',
      'TXT_FTP_SERVER'              => 'Server',
      'TXT_FTP_USER'                => 'Gebruiker',
      'TXT_FTP_PASSWORD'            => 'Paswoord',
      'TXT_FTP_PORT'                => 'Poort',
      'TXT_FTP_START_DIR'           => 'Start map',

      'TXT_FTP_CONNECTION_TEST'     => 'Test FTP Connectie',
      'TXT_CHECK_FTP_CONNECTION'    => 'Druk op onderstaande knop om de connectie te testen van de FTP server.',
      'TXT_FTP_CHECK_NOTE'          => '<strong>Aandacht: </strong>Deze test kan wel 15 seconds duren.',
      'TXT_SUBMIT_CHECK'            => 'Connect',
      'TXT_FTP_LOGIN_OK'            => 'Connectie naar de FTP server is succesvol. FTP support is aanwezig.',
      'TXT_FTP_LOGIN_FAILED'        => 'Connectie naar de FTP server is mislukt. Bekijk je FTP instellingen. ' .
                                       '<br /><strong>Aandacht: </strong>De start map moet wijzen naar je WB installatie.',
);

// Text outputs file handler (templates/action_handler_unzip_file.htt)
$LANG['ADDON_FILE_EDITOR'][11] = array(
      'TXT_ACTION_UNZIP_ARCHIVE'    => 'Zip-bestand uitpakken',
      'TXT_FILE_TO_UNZIP'           => 'Uit te pakken bestand',
      'TXT_TARGET_FOLDER'           => 'Doelfolder',
      'TXT_SUBMIT_UNZIP'            => 'Uitpakken',
      'TXT_UNZIP_WARNING'           => '<strong>Belangrijk: </strong>Het uitpakken van het Zip bestand kan eventuele bestaande bestanden in de doelfolder overschrijven. Dat kan niet ongedaan worden gemaakt.',
      'TXT_UNZIP_SUCCESS'           => 'Het bestand is uitgepakt.',
      'TXT_UNZIP_ERROR'             => 'Het uitpakken is niet gelukt. Check of het zipbestand geldig en niet beschadigd is en of de doelfolder schrijfrechten heeft.',
);