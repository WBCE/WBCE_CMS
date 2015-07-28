<?php
/**
 *
 * @category        framework
 * @package         language
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: NL.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/NL.php $
 * @lastmodified    $Date: 2012-03-09 15:30:29 +0100 (Fr, 09. Mrz 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Define that this file is loaded
if(!defined('LANGUAGE_LOADED')) {
define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_code = 'NL';
$language_name = 'Nederlands';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Bramus, CodeALot, Luckyluke, Argos';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Toegang';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Extra&rsquo;s';
$MENU['ADMINTOOLS'] = 'Beheerfuncties';
$MENU['BREADCRUMB'] = 'U bent hier: ';
$MENU['FORGOT'] = 'Inloggegevens opnieuw aanvragen';
$MENU['GROUP'] = 'Groep';
$MENU['GROUPS'] = 'Groepen';
$MENU['HELP'] = 'Help';
$MENU['LANGUAGES'] = 'Talen';
$MENU['LOGIN'] = 'Inloggen';
$MENU['LOGOUT'] = 'Uitloggen';
$MENU['MEDIA'] = 'Media';
$MENU['MODULES'] = 'Modules';
$MENU['PAGES'] = 'Pagina&rsquo;s';
$MENU['PREFERENCES'] = 'Profiel';
$MENU['SETTINGS'] = 'Instellingen';
$MENU['START'] = 'Naar het hoofdmenu';
$MENU['TEMPLATES'] = 'Templates';
$MENU['USERS'] = 'Gebruikers';
$MENU['VIEW'] = 'Website';
$TEXT['ACCOUNT_SIGNUP'] = 'Aanmelden als gebruiker';
$TEXT['ACTIONS'] = 'Acties';
$TEXT['ACTIVE'] = 'Actief';
$TEXT['ADD'] = 'Toevoegen';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Toevoegen sectie';
$TEXT['ADMIN'] = 'Beheer';
$TEXT['ADMINISTRATION'] = 'Beheer';
$TEXT['ADMINISTRATION_TOOL'] = 'Beheeropties';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Beheerders';
$TEXT['ADVANCED'] = 'Geavanceerd';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Toegestane upload-bestanden';
$TEXT['ALLOWED_VIEWERS'] = 'Toegestane kijkers';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Meerdere selecties toestaan';
$TEXT['ALL_WORDS'] = 'Term of deel van term';
$TEXT['ANCHOR'] = 'Anker';
$TEXT['ANONYMOUS'] = 'Anoniem';
$TEXT['ANY_WORDS'] = 'E&eacute;n van de termen';
$TEXT['APP_NAME'] = 'Applicatienaam';
$TEXT['ARE_YOU_SURE'] = 'Weet u het zeker?';
$TEXT['AUTHOR'] = 'Auteur';
$TEXT['BACK'] = 'Terug';
$TEXT['BACKUP'] = 'Backup maken';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup van alle tabellen in de database';
$TEXT['BACKUP_DATABASE'] = 'Backup van de database maken';
$TEXT['BACKUP_MEDIA'] = 'Backup van de Media-map maken';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backup van alleen WB-gerelateerde tabellen';
$TEXT['BASIC'] = 'Basis';
$TEXT['BLOCK'] = 'Blok';
$TEXT['CALENDAR'] = 'Kalender';
$TEXT['CANCEL'] = 'Annuleren';
$TEXT['CAN_DELETE_HIMSELF'] = 'Kan zichzelf verwijderen';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha-verificatie';
$TEXT['CAP_EDIT_CSS'] = 'Wijzig CSS';
$TEXT['CHANGE'] = 'Verander';
$TEXT['CHANGES'] = 'Veranderingen';
$TEXT['CHANGE_SETTINGS'] = 'Wijzig instellingen';
$TEXT['CHARSET'] = 'Tekenset';
$TEXT['CHECKBOX_GROUP'] = 'Aankruisvakjes';
$TEXT['CLOSE'] = 'Sluiten';
$TEXT['CODE'] = 'Code';
$TEXT['CODE_SNIPPET'] = 'Code snippet';
$TEXT['COLLAPSE'] = 'Inklappen';
$TEXT['COMMENT'] = 'Reageren';
$TEXT['COMMENTING'] = 'Reactie-opties';
$TEXT['COMMENTS'] = 'Reacties';
$TEXT['CREATE_FOLDER'] = 'Cre&euml;er map';
$TEXT['CURRENT'] = 'Huidig(e)';
$TEXT['CURRENT_FOLDER'] = 'Huidige map';
$TEXT['CURRENT_PAGE'] = 'Huidige pagina';
$TEXT['CURRENT_PASSWORD'] = 'Huidig wachtwoord';
$TEXT['CUSTOM'] = 'Handmatige invoer';
$TEXT['DATABASE'] = 'Database';
$TEXT['DATE'] = 'Datum';
$TEXT['DATE_FORMAT'] = 'Datumweergave';
$TEXT['DEFAULT'] = 'Standaard';
$TEXT['DEFAULT_CHARSET'] = 'Standaard tekenset';
$TEXT['DEFAULT_TEXT'] = 'Standaardtekst';
$TEXT['DELETE'] = 'Verwijderen';
$TEXT['DELETED'] = 'Verwijderd';
$TEXT['DELETE_DATE'] = 'Wis datum';
$TEXT['DELETE_ZIP'] = 'Verwijder ZIP van server na uitpakken';
$TEXT['DESCRIPTION'] = 'Metatag "Description"';
$TEXT['DESIGNED_FOR'] = 'Ontworpen voor';
$TEXT['DIRECTORIES'] = 'Mappen';
$TEXT['DIRECTORY_MODE'] = 'Directory-modus';
$TEXT['DISABLED'] = 'Uit';
$TEXT['DISPLAY_NAME'] = 'Naamweergave';
$TEXT['EMAIL'] = 'E-mail';
$TEXT['EMAIL_ADDRESS'] = 'E-mailadres';
$TEXT['EMPTY_TRASH'] = 'Prullenbak legen';
$TEXT['ENABLED'] = 'Aan';
$TEXT['END'] = 'Einde';
$TEXT['ERROR'] = 'Fout';
$TEXT['EXACT_MATCH'] = 'Exacte term';
$TEXT['EXECUTE'] = 'Uitvoeren';
$TEXT['EXPAND'] = 'Uitklappen';
$TEXT['EXTENSION'] = 'Extensie';
$TEXT['FIELD'] = 'Veld';
$TEXT['FILE'] = 'Bestand';
$TEXT['FILES'] = 'bestanden';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Bestandssysteembevoegdheden';
$TEXT['FILE_MODE'] = 'Bestandsmodus';
$TEXT['FINISH_PUBLISHING'] = 'Einde publicatie';
$TEXT['FOLDER'] = 'Map';
$TEXT['FOLDERS'] = 'Mappen';
$TEXT['FOOTER'] = 'Footer';
$TEXT['FORGOTTEN_DETAILS'] = 'Gegevens vergeten?';
$TEXT['FORGOT_DETAILS'] = 'Gegevens vergeten?';
$TEXT['FROM'] = 'Van';
$TEXT['FRONTEND'] = 'Website';
$TEXT['FULL_NAME'] = 'Volledige naam';
$TEXT['FUNCTION'] = 'Functie';
$TEXT['GROUP'] = 'Groep';
$TEXT['HEADER'] = 'Header';
$TEXT['HEADING'] = 'Titel';
$TEXT['HEADING_CSS_FILE'] = 'Actuele modulebestand: ';
$TEXT['HEIGHT'] = 'Hoogte';
$TEXT['HIDDEN'] = 'Verborgen';
$TEXT['HIDE'] = 'Verbergen';
$TEXT['HIDE_ADVANCED'] = 'Verberg geavanceerde opties';
$TEXT['HOME'] = 'Home';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Homepage-omleiding';
$TEXT['HOME_FOLDER'] = 'Persoonlijke folder';
$TEXT['HOME_FOLDERS'] = 'Persoonlijke folders';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Icoon';
$TEXT['IMAGE'] = 'Afbeelding';
$TEXT['INLINE'] = 'Inline';
$TEXT['INSTALL'] = 'Installeren';
$TEXT['INSTALLATION'] = 'Installatie';
$TEXT['INSTALLATION_PATH'] = 'Installatiepad';
$TEXT['INSTALLATION_URL'] = 'Installatie-URL';
$TEXT['INSTALLED'] = 'ge&iuml;nstalleerd';
$TEXT['INTRO'] = 'Introductie';
$TEXT['INTRO_PAGE'] = 'Introductiepagina';
$TEXT['INVALID_SIGNS'] = 'moet met een letter beginnen, of heeft een ongeldig teken';
$TEXT['KEYWORDS'] = 'Metatag "Keywords"';
$TEXT['LANGUAGE'] = 'Taal';
$TEXT['LAST_UPDATED_BY'] = 'Laatste wijzigingen door';
$TEXT['LENGTH'] = 'Lengte';
$TEXT['LEVEL'] = 'Niveau';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Lijstopties';
$TEXT['LOGGED_IN'] = 'Ingelogd';
$TEXT['LOGIN'] = 'Inloggen';
$TEXT['LONG'] = 'Lang';
$TEXT['LONG_TEXT'] = 'Lange tekst';
$TEXT['LOOP'] = '"Loop"';
$TEXT['MAIN'] = 'Primair(e)';
$TEXT['MAINTENANCE_OFF'] = 'Onderhoud uit';
$TEXT['MAINTENANCE_ON'] = 'Onderhoud aan';
$TEXT['MANAGE'] = 'Beheren';
$TEXT['MANAGE_GROUPS'] = 'Groepenbeheer';
$TEXT['MANAGE_USERS'] = 'Gebruikersbeheer';
$TEXT['MATCH'] = 'Gelijk aan';
$TEXT['MATCHING'] = 'Overeenkomend';
$TEXT['MAX_EXCERPT'] = 'Maximaal aantal gelijktijdige zoekacties';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Maximaal aantal inzendingen per uur';
$TEXT['MEDIA_DIRECTORY'] = 'Media-map';
$TEXT['MENU'] = 'Menu';
$TEXT['MENU_ICON_0'] = 'Menu-icoon normal';
$TEXT['MENU_ICON_1'] = 'Menu-icoon hover';
$TEXT['MENU_TITLE'] = 'Menutitel';
$TEXT['MESSAGE'] = 'Bericht';
$TEXT['MODIFY'] = 'Wijzigen';
$TEXT['MODIFY_CONTENT'] = 'Wijzig inhoud';
$TEXT['MODIFY_SETTINGS'] = 'Wijzig instellingen';
$TEXT['MODULE_ORDER'] = 'Modules doorzoeken';
$TEXT['MODULE_PERMISSIONS'] = 'Modulebevoegdheden';
$TEXT['MORE'] = 'Meer';
$TEXT['MOVE_DOWN'] = 'Naar beneden';
$TEXT['MOVE_UP'] = 'Naar boven';
$TEXT['MULTIPLE_MENUS'] = 'Meerdere menu&rsquo;s';
$TEXT['MULTISELECT'] = 'Meervoudige selectie';
$TEXT['NAME'] = 'Naam';
$TEXT['NEED_CURRENT_PASSWORD'] = 'Huidig wachtwoord';
$TEXT['NEED_TO_LOGIN'] = 'Inloggen?';
$TEXT['NEW_PASSWORD'] = 'Nieuw wachtwoord';
$TEXT['NEW_WINDOW'] = 'Nieuw scherm';
$TEXT['NEXT'] = 'Volgende';
$TEXT['NEXT_PAGE'] = 'Volgende pagina';
$TEXT['NO'] = 'Nee';
$TEXT['NONE'] = 'Geen';
$TEXT['NONE_FOUND'] = 'Niet gevonden';
$TEXT['NOT_FOUND'] = 'Niet gevonden';
$TEXT['NOT_INSTALLED'] = 'niet ge&iuml;nstalleerd';
$TEXT['NO_IMAGE_SELECTED'] = 'geen afbeelding geselecteerd';
$TEXT['NO_RESULTS'] = 'Geen resultaten';
$TEXT['OF'] = 'van de';
$TEXT['ON'] = 'Op';
$TEXT['OPEN'] = 'Openen';
$TEXT['OPTION'] = 'Optie';
$TEXT['OTHERS'] = 'Anderen';
$TEXT['OUT_OF'] = 'Buiten';
$TEXT['OVERWRITE_EXISTING'] = 'Overschrijf bestaand(e)';
$TEXT['PAGE'] = 'Pagina';
$TEXT['PAGES_DIRECTORY'] = 'Pagina&rsquo;s-map';
$TEXT['PAGES_PERMISSION'] = 'Paginapermissie';
$TEXT['PAGES_PERMISSIONS'] = 'Paginapermissies';
$TEXT['PAGE_EXTENSION'] = 'Pagina-extensie';
$TEXT['PAGE_ICON'] = 'Pagina-afbeelding';
$TEXT['PAGE_ICON_DIR'] = 'Pad naar pagina- en menu-afbeeldingen';
$TEXT['PAGE_LANGUAGES'] = 'Meerdere talen';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Paginaniveaulimiet';
$TEXT['PAGE_SPACER'] = 'Pagina-spacer';
$TEXT['PAGE_TITLE'] = 'Paginatitel';
$TEXT['PAGE_TRASH'] = 'Paginaprullenbak';
$TEXT['PARENT'] = 'Ouder';
$TEXT['PASSWORD'] = 'Wachtwoord';
$TEXT['PATH'] = 'Pad';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP-foutmeldingsniveau';
$TEXT['PLEASE_LOGIN'] = 'Inloggen aub';
$TEXT['PLEASE_SELECT'] = 'Selecteer';
$TEXT['POST'] = 'Bericht';
$TEXT['POSTS_PER_PAGE'] = 'Berichten per pagina';
$TEXT['POST_FOOTER'] = 'Bericht-footer';
$TEXT['POST_HEADER'] = 'Bericht-header';
$TEXT['PREVIOUS'] = 'Vorige';
$TEXT['PREVIOUS_PAGE'] = 'Vorige pagina';
$TEXT['PRIVATE'] = 'Aangemeld';
$TEXT['PRIVATE_VIEWERS'] = 'Aangemelde bezoekers';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Iedereen';
$TEXT['PUBL_END_DATE'] = 'Einddatum';
$TEXT['PUBL_START_DATE'] = 'Startdatum';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radio buttons';
$TEXT['READ'] = 'Lees';
$TEXT['READ_MORE'] = 'Lees verder';
$TEXT['REDIRECT_AFTER'] = 'Omleiding na (sec.)';
$TEXT['REGISTERED'] = 'Geregistreerd';
$TEXT['REGISTERED_VIEWERS'] = 'Geregistreerde bezoekers';
$TEXT['RELOAD'] = 'Vernieuwen';
$TEXT['REMEMBER_ME'] = 'Onthoud mijn gegevens.';
$TEXT['RENAME'] = 'Hernoemen';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Verplicht';
$TEXT['REQUIREMENT'] = 'Benodigd';
$TEXT['RESET'] = 'Opnieuw';
$TEXT['RESIZE'] = 'Veranderen grootte';
$TEXT['RESIZE_IMAGE_TO'] = 'Verander afbeeldingsgrootte naar';
$TEXT['RESTORE'] = 'Backup terugzetten';
$TEXT['RESTORE_DATABASE'] = 'Backup van de database terugzetten';
$TEXT['RESTORE_MEDIA'] = 'Backup van de Media-map terugzetten';
$TEXT['RESULTS'] = 'Resultaten';
$TEXT['RESULTS_FOOTER'] = 'Zoekresultaten-footer';
$TEXT['RESULTS_FOR'] = 'Resultaten voor';
$TEXT['RESULTS_HEADER'] = 'Resultaten-header';
$TEXT['RESULTS_LOOP'] = 'Zoekresultaten';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Herhaal nieuw wachtwoord';
$TEXT['RETYPE_PASSWORD'] = 'Herhaal wachtwoord';
$TEXT['SAME_WINDOW'] = 'Zelfde scherm';
$TEXT['SAVE'] = 'Opslaan';
$TEXT['SEARCH'] = 'Zoeken';
$TEXT['SEARCHING'] = 'Zoekfunctie';
$TEXT['SECTION'] = 'Sectie';
$TEXT['SECTION_BLOCKS'] = 'Sectieblokken';
$TEXT['SEC_ANCHOR'] = 'Sessie-voorvoegsel';
$TEXT['SELECT_BOX'] = 'Selectiemenu';
$TEXT['SEND_DETAILS'] = 'Stuur gegevens';
$TEXT['SEPARATE'] = 'Gescheiden';
$TEXT['SEPERATOR'] = 'Scheidingsteken tussen opties (HTML toegestaan)';
$TEXT['SERVER_EMAIL'] = 'Server e-mail';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Serverbesturingssysteem';
$TEXT['SESSION_IDENTIFIER'] = 'Sessie-identificatie';
$TEXT['SETTINGS'] = 'Instellingen';
$TEXT['SHORT'] = 'Kort';
$TEXT['SHORT_TEXT'] = 'Korte tekst';
$TEXT['SHOW'] = 'Tonen';
$TEXT['SHOW_ADVANCED'] = 'Bekijk geavanceerde opties';
$TEXT['SIGNUP'] = 'Primaire aanmeldgroep';
$TEXT['SIZE'] = 'Grootte';
$TEXT['SMART_LOGIN'] = 'Snel inloggen';
$TEXT['START'] = 'Aanvang';
$TEXT['START_PUBLISHING'] = 'Aanvang publicatie';
$TEXT['SUBJECT'] = 'Onderwerp';
$TEXT['SUBMISSIONS'] = 'Inzendingen';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Maximaal aantal te bewaren inzendingen';
$TEXT['SUBMISSION_ID'] = 'Ingezonden bericht';
$TEXT['SUBMITTED'] = 'Ingezonden';
$TEXT['SUCCESS'] = 'Succes';
$TEXT['SYSTEM_DEFAULT'] = 'Standaardinstellingen';
$TEXT['SYSTEM_PERMISSIONS'] = 'Systeembevoegdheden';
$TEXT['TABLE_PREFIX'] = 'Tabelvoorvoegsel';
$TEXT['TARGET'] = 'Doel';
$TEXT['TARGET_FOLDER'] = 'Doelmap';
$TEXT['TEMPLATE'] = 'Template';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Templatebevoegdheden';
$TEXT['TEXT'] = 'Tekst';
$TEXT['TEXTAREA'] = 'Tekstveld';
$TEXT['TEXTFIELD'] = 'Tekstregel';
$TEXT['THEME'] = 'Thema Website-beheer';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Tijd';
$TEXT['TIMEZONE'] = 'Tijdzone';
$TEXT['TIME_FORMAT'] = 'Tijdweergave';
$TEXT['TIME_LIMIT'] = 'Maximale zoektijd per module';
$TEXT['TITLE'] = 'Titel';
$TEXT['TO'] = 'Aan';
$TEXT['TOP_FRAME'] = 'Bovenste frame';
$TEXT['TRASH_EMPTIED'] = 'Prullenbak geleegd';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Wijzig de CSS-definities in het tekstveld hieronder.';
$TEXT['TYPE'] = 'Type';
$TEXT['UNDER_CONSTRUCTION'] = 'In bewerking';
$TEXT['UNINSTALL'] = 'Verwijderen';
$TEXT['UNKNOWN'] = 'Onbekend(e)';
$TEXT['UNLIMITED'] = 'Ongelimiteerd';
$TEXT['UNZIP_FILE'] = 'Uploaden en uitpakken van ZIP-bestand';
$TEXT['UP'] = 'Omhoog';
$TEXT['UPGRADE'] = 'Upgraden';
$TEXT['UPLOAD_FILES'] = 'Upload bestand(en)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Gebruiker';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'gebruiker is op actief gezet';
$TEXT['USERS_CAN_SELFDELETE'] = 'gebruiker kan zijn eigen account verwijderen';
$TEXT['USERS_CHANGE_SETTINGS'] = 'Gebruiker kan de eigen instellingen aanpassen';
$TEXT['USERS_DELETED'] = 'Gebruiker is gemarkeerd als verwijderd';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'Gebruiker kan uitgebreid profiel aanmaken';
$TEXT['VERIFICATION'] = 'Verificatie';
$TEXT['VERSION'] = 'Versie';
$TEXT['VIEW'] = 'Bekijken';
$TEXT['VIEW_DELETED_PAGES'] = 'Bekijk verwijderde pagina&rsquo;s';
$TEXT['VIEW_DETAILS'] = 'Gegevens bekijken';
$TEXT['VISIBILITY'] = 'Zichtbaarheid';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Standaard afzendermailadres';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Standaard afzendernaam';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Specificeer hieronder een standaard afzenderadres en afzendernaam. Het is aanbevolen om een afzenderadres als: <strong>admin@uwdomein.nl</strong> te gebruiken. Om verspreiding van spam tegen te gaan, kunnen sommige mailproviders (bijv. <em>mail.com</em>) mails verwerpen met een afzenderadres als <em>name@mail.com</em>, die verzonden worden vanaf een relay-server. Onderstaande standaardwaarden worden enkel gebruikt indien geen andere waarden gespecifieerd worden door WebsiteBaker. Indien uw server <acronym title="Simple mail transfer protocol">SMTP</acronym> ondersteunt kunt u deze optie gebruiken voor het versturen van uitgaande mails.';
$TEXT['WBMAILER_FUNCTION'] = 'Mailafhandeling';
$TEXT['WBMAILER_NOTICE'] = '<strong>Instellingen SMTP Mailer:</strong><br />Onderstaande instellingen zijn alleen van toepassing indien u mails wilt verzenden via <acronym title="Simple mail transfer protocol">SMTP</acronym>. Indien u de naam of instellingen van de SMTP-server niet kent, selecteer dan bij de standaard mailroutine: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP-authenticatie';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'Alleen wanneer men zich dient aan te melden bij de SMTP-host';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP-host';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP-wachtwoord';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Website';
$TEXT['WEBSITE_DESCRIPTION'] = 'Metatag "Description"';
$TEXT['WEBSITE_FOOTER'] = 'Website-footer';
$TEXT['WEBSITE_HEADER'] = 'Website-header';
$TEXT['WEBSITE_KEYWORDS'] = 'Metatag "Keywords"';
$TEXT['WEBSITE_TITLE'] = 'Metatag "Title"';
$TEXT['WELCOME_BACK'] = 'Welkom terug';
$TEXT['WIDTH'] = 'Breedte';
$TEXT['WINDOW'] = 'Scherm';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'CHMOD 777 alle bestanden';
$TEXT['WRITE'] = 'Schrijf';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG-editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG-stijl';
$TEXT['YES'] = 'Ja';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Module voldoet niet aan de eisen';
$HEADING['ADD_CHILD_PAGE'] = 'Toevoegen subpagina';
$HEADING['ADD_GROUP'] = 'Toevoegen groep';
$HEADING['ADD_GROUPS'] = 'Toevoegen groepen';
$HEADING['ADD_HEADING'] = 'Toevoegen titel';
$HEADING['ADD_PAGE'] = 'Toevoegen nieuwe pagina';
$HEADING['ADD_USER'] = 'Toevoegen gebruiker';
$HEADING['ADMINISTRATION_TOOLS'] = 'Beheerfuncties';
$HEADING['BROWSE_MEDIA'] = 'Bladeren door Media-map';
$HEADING['CREATE_FOLDER'] = 'Toevoegen nieuwe map';
$HEADING['DEFAULT_SETTINGS'] = 'Standaardinstellingen';
$HEADING['DELETED_PAGES'] = 'Verwijderde pagina&rsquo;s';
$HEADING['FILESYSTEM_SETTINGS'] = 'Bestandssysteeminstellingen';
$HEADING['GENERAL_SETTINGS'] = 'Algemene instellingen';
$HEADING['INSTALL_LANGUAGE'] = 'Toevoegen taalbestand';
$HEADING['INSTALL_MODULE'] = 'Toevoegen module';
$HEADING['INSTALL_TEMPLATE'] = 'Toevoegen template';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Taal-bestanden handmatig inschakelen';
$HEADING['INVOKE_MODULE_FILES'] = 'Handmatige module-installatie';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Template-bestanden handmatig inschakelen';
$HEADING['LANGUAGE_DETAILS'] = 'Taalbestandgegevens';
$HEADING['MANAGE_SECTIONS'] = 'Sectiebeheer';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Geavanceerde pagina-instellingen';
$HEADING['MODIFY_DELETE_GROUP'] = 'Beheren groep';
$HEADING['MODIFY_DELETE_PAGE'] = 'Beheren bestaande pagina&rsquo;s';
$HEADING['MODIFY_DELETE_USER'] = 'Beheren gebruikers';
$HEADING['MODIFY_GROUP'] = 'Groepsgegevens';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Wijzigen introductiepagina';
$HEADING['MODIFY_PAGE'] = 'Aanpassen pagina';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Pagina-instellingen';
$HEADING['MODIFY_USER'] = 'Gebruikersgegevens';
$HEADING['MODULE_DETAILS'] = 'Modulegegevens';
$HEADING['MY_EMAIL'] = 'Mijn e-mailadres';
$HEADING['MY_PASSWORD'] = 'Mijn wachtwoord';
$HEADING['MY_SETTINGS'] = 'Mijn gegevens';
$HEADING['SEARCH_SETTINGS'] = 'Zoekinstellingen';
$HEADING['SERVER_SETTINGS'] = 'Serverinstellingen';
$HEADING['TEMPLATE_DETAILS'] = 'Templategegevens';
$HEADING['UNINSTALL_LANGUAGE'] = 'Verwijderen taalbestand';
$HEADING['UNINSTALL_MODULE'] = 'Verwijderen module';
$HEADING['UNINSTALL_TEMPLATE'] = 'Verwijderen template';
$HEADING['UPGRADE_LANGUAGE'] = 'Upgraden/inschakelen taalbestand';
$HEADING['UPLOAD_FILES'] = 'Uploaden bestanden';
$HEADING['WBMAILER_SETTINGS'] = 'Mailer-instellingen';
$MESSAGE['ADDON_ERROR_RELOAD'] = 'Fout tijdens het updaten van de add-onbestanden.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Taalbestanden succesvol herladen';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ATTENTIE!</strong> Om veiligheidsredenen dient u de taalbestanden in de folder /languages/ via FTP te uploaden en vervolgens de Upgrade-functie te gebruiken om ze aan het systeem toe te voegen.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Waarschuwing: bestaande module database-informatie zal verloren gaan! ';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'When modules are uploaded via FTP (not recommended), the module installation functions <tt>install</tt>, <tt>upgrade</tt> or <tt>uninstall</tt> will not be executed automatically. Those modules may not work correct or do not uninstall properly.<br /><br />You can execute the module functions manually for modules uploaded via FTP below.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Waarschuwing: de bestaande module database-informatie zal verloren gaan! Gebruik deze optie alleen indien u ervaring heeft met modules die zijn geupload via FTP.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Waarschuwing: de bestaande module database-informatie zal verloren gaan! ';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Modules succesvol herladen';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Nieuwere bestanden overschrijven';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Add-on installatie mislukt. Uw systeem voldoet niet aan de eisen van deze add-on. Om deze situatie te veranderen kunt u de informatie hieronder toepassen.';
$MESSAGE['ADDON_RELOAD'] = 'Update database met informatie uit de add-on bestanden (bijvoorbeeld na FTP-upload).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Templates succesvol herladen';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Onvoldoende rechten om hier te zijn';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Sorry, het wachtwoord kan maximaal eens per uur worden aangepast.';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Het is niet mogelijk uw wachtwoord per e-mail te versturen. Neem contact op met de beheerder';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Het door u opgegeven e-mailadres is niet gevonden in onze database';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Vult u alstublieft uw e-mailadres hieronder in';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, er is niets om af te beelden';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Sorry, u heeft geen bevoegdheden om deze pagina te bekijken';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Is al ge&iuml;nstalleerd';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Kan niet schrijven naar doelmap';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Kan niet de&iuml;nstalleren';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Kan niet de&iuml;nstalleren: het geselecteerde bestand is in gebruik';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />De {{type}} <b>{{type_name}}</b> kan niet verwijderd worden omdat het in gebruik is op {{pages}}:<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'volgende pagina;volgende pagina\'s';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'De template <b>{{name}}</b> kan niet verwijderd worden omdat het de standaard-template is.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Het is niet mogelijk de template <b>{{name}}</b> te verwijderen omdat het het standaard admin-thema is.';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Kan het bestand niet uitpakken';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Kan niet uploaden';
$MESSAGE['GENERIC_COMPARE'] = ' succesvol';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Kan bestand niet openen.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' mislukt';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Let op: het bestand moet het volgende formaat hebben:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Let op: de bestanden moeten het volgende formaat hebben:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Niet alle velden zijn ingevuld. Probeert u het nog eens';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'U heeft niets geselecteerd!';
$MESSAGE['GENERIC_INSTALLED'] = 'Installatie voltooid';
$MESSAGE['GENERIC_INVALID'] = 'Ongeldig bestand';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Ongeldig WebsiteBaker installatiebestand. Controleer het *.zip bestand.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Ongeldig WebsiteBaker taalbestand. Controleer het tekstbestand.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Ongeldig WebsiteBaker modulebestand. Controleer het tekstbestand';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Ongeldig WebsiteBaker template-bestand. Controleer het tekstbestand';
$MESSAGE['GENERIC_IN_USE'] = ' gebruikt in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Ontbrekend archiefbestand!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'De module is niet juist ge&iuml;nstalleerd!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' niet mogelijk';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Niet ge&iuml;nstalleerd';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualisatie niet mogelijk';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Even geduld aub, dit kan even duren.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Probeert u het a.u.b. binnenkort nog eens.';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Veiligheidsrisico! Toegang geweigerd!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Veiligheidsrisico! Data-opslag geweigerd!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'De&iuml;nstallatie voltooid';
$MESSAGE['GENERIC_UPGRADED'] = 'Upgrade voltooid';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Versievergelijking';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade noodzakelijk!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'Deze site is tijdelijk offline wegens onderhoud.';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Website in bewerking';
$MESSAGE['GROUPS_ADDED'] = 'Groep toegevoegd';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Weet u zeker dat u de geselecteerde groep wilt verwijderen (en alle daarbij behorende gebruikers)?';
$MESSAGE['GROUPS_DELETED'] = 'Groep verwijderd';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Groepsnaam is niet ingevuld';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Groepnaam is reeds in gebruik';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Geen groep gevonden';
$MESSAGE['GROUPS_SAVED'] = 'Groep opgeslagen';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Vul uw wachtwoord in';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Dit wachtwoord is te lang';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Dit wachtwoord is te kort';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'U heeft geen bestandsextensie opgegeven';
$MESSAGE['MEDIA_BLANK_NAME'] = 'U heeft geen nieuwe naam opgegeven';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Kan geselecteerde map niet verwijderen';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Kan geselecteerde bestand niet verwijderen';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Hernoemen niet gelukt';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Weet u zeker dat u het volgende bestand of map wilt verwijderen?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Map verwijderd';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Bestand verwijderd';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Map bestaat niet';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Gebruik van ../ in de mapnaam is niet toegestaan';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Opgegeven naam van de map bestaat al';
$MESSAGE['MEDIA_DIR_MADE'] = 'Map aangemaakt';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Aanmaken map mislukt';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Opgegeven bestandsnaam bestaat al';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Bestand niet gevonden';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Gebruik van ../ in de naam is niet toegestaan';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'index.php als naam is niet toegestaan';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Geen mediabestanden gevonden in de huidige map';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'geen bestand(en) ontvangen';
$MESSAGE['MEDIA_RENAMED'] = 'Hernoemen geslaagd';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' geupload';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Gebruik van ../ in de map is niet toegestaan';
$MESSAGE['MEDIA_UPLOADED'] = ' geupload';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Dit formulier is te vaak verstuurd binnen dit uur. Probeert u het over een uur nog eens.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Het verificatienummer (ook wel Captcha genoemd) dat u hebt ingevoerd is incorrect. Als u de Captcha niet goed kunt lezen, stuur dan een e-mail naar: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'De volgende velden zijn verplicht';
$MESSAGE['PAGES_ADDED'] = 'Pagina toegevoegd';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Paginatitel opgeslagen';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Vul a.u.b. een menutitel in';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Vul a.u.b. een paginatitel in';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Kan geen bestanden opslaan in de /pages-map (onvoldoende rechten)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Kan geen bestanden verwijderen uit de /pages-map (onvoldoende rechten)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Fout bij herordenen pagina';
$MESSAGE['PAGES_DELETED'] = 'Pagina verwijderd';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Weet u zeker dat u deze pagina wilt verwijderen (en al zijn subpagina&rsquo;s)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'U heeft niet de rechten om deze pagina aan te passen';
$MESSAGE['PAGES_INTRO_LINK'] = 'Klik hier om de introductiepagina aan te passen';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Kan instellingen niet opslaan in het bestand /pages/intro.php (onvoldoende rechten)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Introductiepagina opgeslagen';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Als laatste aangepast door';
$MESSAGE['PAGES_NOT_FOUND'] = 'Pagina niet gevonden';
$MESSAGE['PAGES_NOT_SAVED'] = 'Fout tijdens opslaan pagina';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Een pagina met dezelfde naam bestaat al';
$MESSAGE['PAGES_REORDERED'] = 'Pagina herordend';
$MESSAGE['PAGES_RESTORED'] = 'Pagina teruggehaald';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Keer terug naar pagina&rsquo;s';
$MESSAGE['PAGES_SAVED'] = 'Pagina opgeslagen';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Pagina-instellingen opgeslagen';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Sectie-instellingen opgeslagen';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Het (huidige) ingevoerde wachtwoord is niet correct';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Details opgeslagen';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'E-mail gewijzigd';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Ongeldige wachtwoordtekens gebruikt';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Wachtwoord gewijzigd';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'De aanpassing is mislukt.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'De aanpassing is opgeslagen.';
$MESSAGE['RECORD_NEW_FAILED'] = 'De toevoeging is mislukt.';
$MESSAGE['RECORD_NEW_SAVED'] = 'De toevoeging is opgeslagen';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Opgelet: sla eerst de wijzigingen op die u eventueel zojuist heeft aangebracht!';
$MESSAGE['SETTINGS_SAVED'] = 'Instellingen opgeslagen';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Het configuratiebestand kan niet worden geopend';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Het configuratiebestand kan niet worden opgeslagen';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Opgelet: dit is alleen bedoeld voor testdoeleinden!';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
Een nieuwe gebruiker heeft zich aangemeld.

Loginname: {LOGIN_NAME}
Gebruiker Id: {LOGIN_ID}
E-mail: {LOGIN_EMAIL}
IP-adres: {LOGIN_IP}
Registratiedatum: {SIGNUP_DATE}
----------------------------------------
Dit bericht is automatisch aangemaakt!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Beste {LOGIN_DISPLAY_NAME},

deze mail is aan u verzonden omdat u de \'wachtwoord vergeten\' functie heeft gebruikt.

Uw nieuwe \'{LOGIN_WEBSITE_TITLE}\' inloggegevens zijn:

Loginname: {LOGIN_NAME}
Wachtwoord: {LOGIN_PASSWORD}

Let op: dit is een automatisch aangemaakt nieuw wachtwoord. Uw oude wachtwoord is niet meer geldig!
Heeft u vragen of opmerkingen, neem dan contact op met de websitebeheerder.

Het is aan te raden om uw browser-cache te legen voordat u het nieuwe wachtwoord gebruikt.

Vriendelijke groet

------------------------------------
Dit bericht is automatisch aangemaakt!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Beste {LOGIN_DISPLAY_NAME},

Welkom bij \'{LOGIN_WEBSITE_TITLE}\'.

Uw \'{LOGIN_WEBSITE_TITLE}\' inloggegevens zijn:
Loginname: {LOGIN_NAME}
Wachtwoord: {LOGIN_PASSWORD}

Vriendelijke groet

-------------------------------------
Dit bericht is automatisch aangemaakt!
';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Uw inloggegevens...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'U moet een e-mailadres invullen';
$MESSAGE['START_CURRENT_USER'] = 'U bent ingelogd als';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Waarschuwing, de installatiemap bestaat nog steeds. U dient deze te verwijderen om veiligheidsrisico&rsquo;s te vermijden!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Welkom bij het websitebeheer';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Attentie: om de template aan te passen moet u naar de instellingensectie';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'This new theme descriptor already exists.';
$MESSAGE['THEME_COPY_CURRENT'] = 'Copy the current active theme and save it with a new name.';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'No rights to create new theme directory!';
$MESSAGE['THEME_IMPORT_HTT'] = 'Import additional templates into the current active theme.<br />Use these templates to overwrite the corresponding default template.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Invalid descriptor for the new theme given!';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unknown upload error';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Failed to write file to disk';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'File upload stopped by extension';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'No file was uploaded';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Missing a temporary folder';
$MESSAGE['UPLOAD_ERR_OK'] = 'File were successful uploaded';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'The uploaded file was only partially uploaded';
$MESSAGE['USERS_ADDED'] = 'Gebruiker toegevoegd';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Functie geweigerd. U kunt zichzelf niet verwijderen!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Attentie: vul alleen de bovenstaande velden in wanneer u het wachtwoord van de gebruiker wilt veranderen';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Weet u zeker dat u de geselecteerde gebruiker wilt verwijderen?';
$MESSAGE['USERS_DELETED'] = 'Gebruiker verwijderd';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Het ingevoerde e-mailadres is al in gebruik';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Het ingevoerde e-mailadres is niet correct';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Geen groep geselecteerd';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'De ingevoerde wachtwoorden komen niet overeen';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Het ingevoerde wachtwoord is te kort';
$MESSAGE['USERS_SAVED'] = 'Gebruiker opgeslagen';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Diverse extra beheerinstellingen.';
$OVERVIEW['GROUPS'] = 'Beheren van de gebruikersgroepen en hun rechten.';
$OVERVIEW['HELP'] = 'Uitgebreide hulp voor het gebruik van dit systeem.';
$OVERVIEW['LANGUAGES'] = 'Beheren van de aanwezige taalbestanden.';
$OVERVIEW['MEDIA'] = 'Beheren van bestanden in de Media-map.';
$OVERVIEW['MODULES'] = 'Beheren van modules die extra functies toevoegen aan uw site.';
$OVERVIEW['PAGES'] = 'Aanmaken en beheren van de sitestructuur en pagina&rsquo;s.';
$OVERVIEW['PREFERENCES'] = 'Beheren van uw persoonlijk profiel.';
$OVERVIEW['SETTINGS'] = 'Beheren van de technische website-instellingen.';
$OVERVIEW['START'] = 'Websitebeheer';
$OVERVIEW['TEMPLATES'] = 'Beheren van de templates die u kunt toepassen.';
$OVERVIEW['USERS'] = 'Beheren van de gebruikers van uw website.';
$OVERVIEW['VIEW'] = 'Bekijk uw website zoals deze voor bezoekers te zien is (in een afzonderlijk venster).';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
