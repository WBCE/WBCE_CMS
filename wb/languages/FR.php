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
 * @version         $Id: FR.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/FR.php $
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
$language_code = 'FR';
$language_name = 'Fran&ccedil;ais';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Marin Susac';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Acc&egrave;s';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Extensions';
$MENU['ADMINTOOLS'] = 'Outils d&apos;administration';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Retrouver vos identifiants de connexion';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Groupes';
$MENU['HELP'] = 'Aide';
$MENU['LANGUAGES'] = 'Langues';
$MENU['LOGIN'] = 'Connexion';
$MENU['LOGOUT'] = 'D&eacute;connexion';
$MENU['MEDIA'] = 'Media';
$MENU['MODULES'] = 'Modules';
$MENU['PAGES'] = 'Contenu';
$MENU['PREFERENCES'] = 'Pr&eacute;f&eacute;rences';
$MENU['SETTINGS'] = 'R&eacute;glages';
$MENU['START'] = 'Accueil';
$MENU['TEMPLATES'] = 'Th&egrave;mes';
$MENU['USERS'] = 'Utilisateurs';
$MENU['VIEW'] = 'Voir le site';
$TEXT['ACCOUNT_SIGNUP'] = 'Cr&eacute;er un compte';
$TEXT['ACTIONS'] = 'Actions';
$TEXT['ACTIVE'] = 'Actif';
$TEXT['ADD'] = 'Ajouter';
$TEXT['ADDON'] = 'Extension';
$TEXT['ADD_SECTION'] = 'Ajouter une rubrique';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administration';
$TEXT['ADMINISTRATION_TOOL'] = 'Outils d&apos;administration';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administrateurs';
$TEXT['ADVANCED'] = 'Avanc&eacute;';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Visiteurs autoris&eacute;s';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Autoriser la s&eacute;lection multiple';
$TEXT['ALL_WORDS'] = 'Tous les mots';
$TEXT['ANCHOR'] = 'Ancre';
$TEXT['ANONYMOUS'] = 'Anonyme';
$TEXT['ANY_WORDS'] = 'Chaque mot';
$TEXT['APP_NAME'] = 'Nom de l&apos;application';
$TEXT['ARE_YOU_SURE'] = 'Etes-vous s&ucirc;r ?';
$TEXT['AUTHOR'] = 'Auteur';
$TEXT['BACK'] = 'Retour';
$TEXT['BACKUP'] = 'Sauvegarde';
$TEXT['BACKUP_ALL_TABLES'] = 'Sauvegarder toutes les tables de la base de donn&eacute;es';
$TEXT['BACKUP_DATABASE'] = 'Sauvegarde de la base de donn&eacute;es';
$TEXT['BACKUP_MEDIA'] = 'Sauvegarde des fichiers media';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Sauvegarder uniquement les tables li&eacute;es &agrave; WebsiteBaker';
$TEXT['BASIC'] = 'Classique';
$TEXT['BLOCK'] = 'Bloc';
$TEXT['CALENDAR'] = 'Calendrier';
$TEXT['CANCEL'] = 'Annuler';
$TEXT['CAN_DELETE_HIMSELF'] = 'Ne peut pas se supprimer lui-m&ecirc;me';
$TEXT['CAPTCHA_VERIFICATION'] = 'V&eacute;rification par captcha ';
$TEXT['CAP_EDIT_CSS'] = 'Editer la feuille CSS';
$TEXT['CHANGE'] = 'Changer';
$TEXT['CHANGES'] = 'Modifications';
$TEXT['CHANGE_SETTINGS'] = 'Modifier les r&eacute;glages';
$TEXT['CHARSET'] = 'Encodage';
$TEXT['CHECKBOX_GROUP'] = 'Groupe de checkbox';
$TEXT['CLOSE'] = 'Fermer';
$TEXT['CODE'] = 'Code';
$TEXT['CODE_SNIPPET'] = 'Extrait de code';
$TEXT['COLLAPSE'] = 'Contracter';
$TEXT['COMMENT'] = 'Commentaire';
$TEXT['COMMENTING'] = 'Commentaire en cours';
$TEXT['COMMENTS'] = 'Commentaires';
$TEXT['CREATE_FOLDER'] = 'Cr&eacute;er un dossier';
$TEXT['CURRENT'] = 'Courant';
$TEXT['CURRENT_FOLDER'] = 'Dossier courant';
$TEXT['CURRENT_PAGE'] = 'Page courante';
$TEXT['CURRENT_PASSWORD'] = 'Mot de passe actuel';
$TEXT['CUSTOM'] = 'Adapter';
$TEXT['DATABASE'] = 'Base de donn&eacute;es';
$TEXT['DATE'] = 'Date';
$TEXT['DATE_FORMAT'] = 'Format de la date';
$TEXT['DEFAULT'] = 'D&eacute;faut';
$TEXT['DEFAULT_CHARSET'] = 'Encodage par d&eacute;faut';
$TEXT['DEFAULT_TEXT'] = 'Texte par d&eacute;faut';
$TEXT['DELETE'] = 'Supprimer';
$TEXT['DELETED'] = 'Supprim&eacute;';
$TEXT['DELETE_DATE'] = 'Date de suppression';
$TEXT['DELETE_ZIP'] = 'Effacer l&apos;archive zip apr&egrave;s d&eacute;compression';
$TEXT['DESCRIPTION'] = 'Description';
$TEXT['DESIGNED_FOR'] = 'Cr&eacute;&eacute; par';
$TEXT['DIRECTORIES'] = 'R&eacute;pertoires';
$TEXT['DIRECTORY_MODE'] = 'Propri&eacute;t&eacute;s des r&eacute;pertoires';
$TEXT['DISABLED'] = 'D&eacute;sactiv&eacute;';
$TEXT['DISPLAY_NAME'] = 'Afficher le nom';
$TEXT['EMAIL'] = 'Email';
$TEXT['EMAIL_ADDRESS'] = 'Adresse email';
$TEXT['EMPTY_TRASH'] = 'Vider la corbeille';
$TEXT['ENABLED'] = 'Activ&eacute;';
$TEXT['END'] = 'Fin';
$TEXT['ERROR'] = 'Erreur';
$TEXT['EXACT_MATCH'] = 'Terme exact';
$TEXT['EXECUTE'] = 'Executer';
$TEXT['EXPAND'] = 'D&eacute;ployer';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'Champ';
$TEXT['FILE'] = 'Fichier';
$TEXT['FILES'] = 'Fichiers';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Permissions des fichiers syst&egrave;me';
$TEXT['FILE_MODE'] = 'Propri&eacute;t&eacute;s des fichiers';
$TEXT['FINISH_PUBLISHING'] = 'Fin de publication';
$TEXT['FOLDER'] = 'Dossier';
$TEXT['FOLDERS'] = 'Dossiers';
$TEXT['FOOTER'] = 'Pied de page';
$TEXT['FORGOTTEN_DETAILS'] = 'Identifiants oubli&eacute;s ?';
$TEXT['FORGOT_DETAILS'] = 'Identifiants oubli&eacute;s ?';
$TEXT['FROM'] = 'de';
$TEXT['FRONTEND'] = 'Page d&apos;accueil';
$TEXT['FULL_NAME'] = 'Nom complet';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Groupe';
$TEXT['HEADER'] = 'Ent&ecirc;te';
$TEXT['HEADING'] = 'Haut de page';
$TEXT['HEADING_CSS_FILE'] = 'Feuille css actuelle : ';
$TEXT['HEIGHT'] = 'Hauteur';
$TEXT['HIDDEN'] = 'Cach&eacute;';
$TEXT['HIDE'] = 'Cacher';
$TEXT['HIDE_ADVANCED'] = 'Cacher les options avanc&eacute;es';
$TEXT['HOME'] = 'Accueil';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Redirection de la page d&apos;accueil';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'H&ocirc;te';
$TEXT['ICON'] = 'Ic&ocirc;ne';
$TEXT['IMAGE'] = 'Image';
$TEXT['INLINE'] = 'En ligne';
$TEXT['INSTALL'] = 'Installer';
$TEXT['INSTALLATION'] = 'Installation';
$TEXT['INSTALLATION_PATH'] = 'Chemin d&apos;installation';
$TEXT['INSTALLATION_URL'] = 'Adresse d&apos;installation (URL)';
$TEXT['INSTALLED'] = 'install&eacute;';
$TEXT['INTRO'] = 'Introduction';
$TEXT['INTRO_PAGE'] = 'Page d&apos;introduction';
$TEXT['INVALID_SIGNS'] = 'doit d&eacute;buter par une lettre ou comporte des caract&egrave;res invalides';
$TEXT['KEYWORDS'] = 'Mots cl&eacute;s';
$TEXT['LANGUAGE'] = 'Langue';
$TEXT['LAST_UPDATED_BY'] = 'Mis &agrave; jour par';
$TEXT['LENGTH'] = 'Longueur';
$TEXT['LEVEL'] = 'Niveau';
$TEXT['LINK'] = 'Lien';
$TEXT['LINUX_UNIX_BASED'] = 'Bas&eacute; sur linux/unix';
$TEXT['LIST_OPTIONS'] = 'Liste des options';
$TEXT['LOGGED_IN'] = 'Connect&eacute;';
$TEXT['LOGIN'] = 'Connexion';
$TEXT['LONG'] = 'Long';
$TEXT['LONG_TEXT'] = 'Texte long';
$TEXT['LOOP'] = 'Boucle';
$TEXT['MAIN'] = 'Principal';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'G&eacute;rer';
$TEXT['MANAGE_GROUPS'] = 'Gestion des groupes';
$TEXT['MANAGE_USERS'] = 'Gestion des utilisateurs';
$TEXT['MATCH'] = 'correspond';
$TEXT['MATCHING'] = 'Correspondance';
$TEXT['MAX_EXCERPT'] = 'Nombre maximum de ligne &agrave; retourner';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Maximum de soumissions par heure';
$TEXT['MEDIA_DIRECTORY'] = 'R&eacute;pertoire des fichiers media';
$TEXT['MENU'] = 'Menu';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Titre du menu';
$TEXT['MESSAGE'] = 'Message';
$TEXT['MODIFY'] = 'Modifier';
$TEXT['MODIFY_CONTENT'] = 'Modifier le contenu';
$TEXT['MODIFY_SETTINGS'] = 'Modifier les r&eacute;glages';
$TEXT['MODULE_ORDER'] = 'Ordre de recherche dans les modules';
$TEXT['MODULE_PERMISSIONS'] = 'Permissions sur les modules';
$TEXT['MORE'] = 'Plus';
$TEXT['MOVE_DOWN'] = 'Au dessous';
$TEXT['MOVE_UP'] = 'Au dessus';
$TEXT['MULTIPLE_MENUS'] = 'Menus multiples';
$TEXT['MULTISELECT'] = 'Multi-s&eacute;lection';
$TEXT['NAME'] = 'Nom';
$TEXT['NEED_CURRENT_PASSWORD'] = 'veuillez confirmer votre mot de passe';
$TEXT['NEED_TO_LOGIN'] = 'Identification obligatoire';
$TEXT['NEW_PASSWORD'] = 'Nouveau mot de passe';
$TEXT['NEW_WINDOW'] = 'Nouvelle fen&ecirc;tre';
$TEXT['NEXT'] = 'Suivant';
$TEXT['NEXT_PAGE'] = 'Page suivante';
$TEXT['NO'] = 'Non';
$TEXT['NONE'] = 'Aucun';
$TEXT['NONE_FOUND'] = 'Aucune occurence trouv&eacute;e';
$TEXT['NOT_FOUND'] = 'Non trouv&eacute;';
$TEXT['NOT_INSTALLED'] = 'non install&eacute;';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Aucun r&eacute;sultat';
$TEXT['OF'] = 'De';
$TEXT['ON'] = 'Sur';
$TEXT['OPEN'] = 'Ouvert';
$TEXT['OPTION'] = 'Option';
$TEXT['OTHERS'] = 'Autres';
$TEXT['OUT_OF'] = 'Hors de';
$TEXT['OVERWRITE_EXISTING'] = 'Ecraser les donn&eacute;es (si d&eacute;j&agrave; existantes)';
$TEXT['PAGE'] = 'Page';
$TEXT['PAGES_DIRECTORY'] = 'R&eacute;pertoire des pages';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Extension des pages';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Langues des pages';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Limite de niveau de page';
$TEXT['PAGE_SPACER'] = 'Espacement de page';
$TEXT['PAGE_TITLE'] = 'Titre de la page';
$TEXT['PAGE_TRASH'] = 'Corbeille pour les pages supprim&eacute;es';
$TEXT['PARENT'] = 'Parent';
$TEXT['PASSWORD'] = 'Mot de passe';
$TEXT['PATH'] = 'Chemin';
$TEXT['PHP_ERROR_LEVEL'] = 'Niveau d&apos;erreur PHP';
$TEXT['PLEASE_LOGIN'] = 'Merci de vous identifier';
$TEXT['PLEASE_SELECT'] = 'S&eacute;lectionnez';
$TEXT['POST'] = 'Actualit&eacute;';
$TEXT['POSTS_PER_PAGE'] = 'Nombre d&apos;actualit&eacute; par page';
$TEXT['POST_FOOTER'] = 'Pied de page de l&apos;actualit&eacute;';
$TEXT['POST_HEADER'] = 'Ent&ecirc;te de l&apos;actualit&eacute;';
$TEXT['PREVIOUS'] = 'Pr&eacute;c&eacute;dent';
$TEXT['PREVIOUS_PAGE'] = 'Page pr&eacute;c&eacute;dente';
$TEXT['PRIVATE'] = 'Priv&eacute;e';
$TEXT['PRIVATE_VIEWERS'] = 'Utilisateurs priv&eacute;s';
$TEXT['PROFILES_EDIT'] = 'Modifier le profil';
$TEXT['PUBLIC'] = 'Publique';
$TEXT['PUBL_END_DATE'] = 'Date de fin';
$TEXT['PUBL_START_DATE'] = 'Date de d&eacute;but';
$TEXT['RADIO_BUTTON_GROUP'] = 'Groupe de boutons radio';
$TEXT['READ'] = 'Lire';
$TEXT['READ_MORE'] = 'En savoir plus';
$TEXT['REDIRECT_AFTER'] = 'Redirection apr&egrave;s coup';
$TEXT['REGISTERED'] = 'Enregistr&eacute;';
$TEXT['REGISTERED_VIEWERS'] = 'Utilisateurs enregistr&eacute;s';
$TEXT['RELOAD'] = 'Actualiser';
$TEXT['REMEMBER_ME'] = 'Se souvenir de moi';
$TEXT['RENAME'] = 'Renommer';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Obligatoire';
$TEXT['REQUIREMENT'] = 'Param&egrave;tres requis';
$TEXT['RESET'] = 'R&eacute;initialiser';
$TEXT['RESIZE'] = 'Redimensionner';
$TEXT['RESIZE_IMAGE_TO'] = 'Redimensionner l&apos;image';
$TEXT['RESTORE'] = 'Restaurer';
$TEXT['RESTORE_DATABASE'] = 'Restauration de la base de donn&eacute;es';
$TEXT['RESTORE_MEDIA'] = 'Restauration des fichiers media';
$TEXT['RESULTS'] = 'R&eacute;sultats';
$TEXT['RESULTS_FOOTER'] = 'Pied de page du mod&egrave;le de recherche';
$TEXT['RESULTS_FOR'] = 'R&eacute;sultats';
$TEXT['RESULTS_HEADER'] = 'Ent&ecirc;te du mod&egrave;le de recherche';
$TEXT['RESULTS_LOOP'] = 'Mod&egrave;le d&apos;affichage de la boucle de recherche';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Saisissez &agrave; nouveau votre nouveau mot de passe';
$TEXT['RETYPE_PASSWORD'] = 'Saisissez &agrave; nouveau votre mot de passe';
$TEXT['SAME_WINDOW'] = 'Fen&ecirc;tre actuelle (this frame)';
$TEXT['SAVE'] = 'Sauvegarder';
$TEXT['SEARCH'] = 'Rechercher';
$TEXT['SEARCHING'] = 'Rechercher';
$TEXT['SECTION'] = 'Rubrique';
$TEXT['SECTION_BLOCKS'] = 'Bloc de rubrique';
$TEXT['SEC_ANCHOR'] = 'Section d&apos;ancre';
$TEXT['SELECT_BOX'] = 'S&eacute;lection des bo&icirc;tes';
$TEXT['SEND_DETAILS'] = 'Valider';
$TEXT['SEPARATE'] = 'S&eacute;parer';
$TEXT['SEPERATOR'] = 'S&eacute;parateur';
$TEXT['SERVER_EMAIL'] = 'Serveur de mail';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Syst&egrave;me d&apos;exploitation du serveur';
$TEXT['SESSION_IDENTIFIER'] = 'Identifiant de session';
$TEXT['SETTINGS'] = 'R&eacute;glages';
$TEXT['SHORT'] = 'Court';
$TEXT['SHORT_TEXT'] = 'Texte court';
$TEXT['SHOW'] = 'Montrer';
$TEXT['SHOW_ADVANCED'] = 'Afficher les options avanc&eacute;es';
$TEXT['SIGNUP'] = 'Cr&eacute;er un compte';
$TEXT['SIZE'] = 'Taille';
$TEXT['SMART_LOGIN'] = 'Identification rapide';
$TEXT['START'] = 'D&eacute;but';
$TEXT['START_PUBLISHING'] = 'D&eacute;but de publication';
$TEXT['SUBJECT'] = 'Sujet';
$TEXT['SUBMISSIONS'] = 'Soumissions';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Enregistrement des soumissions dans la base de donn&eacute;es';
$TEXT['SUBMISSION_ID'] = 'Identifiant';
$TEXT['SUBMITTED'] = 'Envoy&eacute;';
$TEXT['SUCCESS'] = 'Op&eacute;ration r&eacute;ussie';
$TEXT['SYSTEM_DEFAULT'] = 'Syst&egrave;me par d&eacute;faut';
$TEXT['SYSTEM_PERMISSIONS'] = 'Permissions syst&egrave;me';
$TEXT['TABLE_PREFIX'] = 'Pr&eacute;fixe du nom des tables';
$TEXT['TARGET'] = 'Cible';
$TEXT['TARGET_FOLDER'] = 'Dossier de destination';
$TEXT['TEMPLATE'] = 'Th&egrave;me';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Permissions sur les th&egrave;mes';
$TEXT['TEXT'] = 'Texte';
$TEXT['TEXTAREA'] = 'Zone de texte';
$TEXT['TEXTFIELD'] = 'Champ de texte';
$TEXT['THEME'] = 'Th&egrave;me graphique de l&apos;interface d&apos;administration';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Heure';
$TEXT['TIMEZONE'] = 'Fuseau horaire';
$TEXT['TIME_FORMAT'] = 'Format de l&apos;heure';
$TEXT['TIME_LIMIT'] = 'D&eacute;lai maximal de recherche par module';
$TEXT['TITLE'] = 'Titre';
$TEXT['TO'] = 'vers';
$TEXT['TOP_FRAME'] = 'Fen&ecirc;tre actuelle compl&egrave;te (top frame)';
$TEXT['TRASH_EMPTIED'] = 'Corbeille vid&eacute;e';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Editer les styles CSS dans la zone de texte ci-dessous.';
$TEXT['TYPE'] = 'Type';
$TEXT['UNDER_CONSTRUCTION'] = 'En construction';
$TEXT['UNINSTALL'] = 'D&eacute;sinstaller';
$TEXT['UNKNOWN'] = 'Inconnu';
$TEXT['UNLIMITED'] = 'Illimit&eacute;';
$TEXT['UNZIP_FILE'] = 'Uploader et d&eacute;compresser l&apos;archive zip';
$TEXT['UP'] = 'Haut';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Uploader un/des fichier(s)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Utilisateur';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'V&eacute;rification';
$TEXT['VERSION'] = 'Version';
$TEXT['VIEW'] = 'Aper&ccedil;u';
$TEXT['VIEW_DELETED_PAGES'] = 'Voir les pages supprim&eacute;es';
$TEXT['VIEW_DETAILS'] = 'Propri&eacute;t&eacute;s';
$TEXT['VISIBILITY'] = 'Visibilit&eacute;';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Adresse d&apos;exp&eacute;diteur par d&eacute;faut';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Nom d&apos;exp&eacute;diteur par d&eacute;faut';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Merci d&apos;indiquer un nom et une adresse d&apos;exp&eacute;diteur par d&eacute;faut. Il est recommand&eacute; d&apos;utiliser une adresse d&apos;exp&eacute;diteur de la forme : <strong>admin@yourdomain.com</strong>. Certains op&eacute;rateurs de mail (comme <em>mail.com</em>) peuvent rejeter les mails dont l&apos;adresse d&apos;exp&eacute;diteur est de la forme <em>name@mail.com</em> envoy&eacute;s via un relai, c&apos;est leur mani&egrave;re de lutter contre le spam.<br /><br />Les valeurs par d&eacute;faut sont uniquement utilis&eacute;es si aucune autre valeur n&apos;est sp&eacute;cifi&eacute;e par WebsiteBaker. Si votre serveur supporte <acronym title="Simple mail transfer protocol">SMTP</acronym>, vous pouvez utiliser cette option pour l&apos;envoi d&apos;emails.';
$TEXT['WBMAILER_FUNCTION'] = 'M&eacute;canisme d&apos;envoi de mail';
$TEXT['WBMAILER_NOTICE'] = '<strong>Param&egrave;tres du serveur SMTP :</strong><br />Les param&egrave;tres ci-dessous sont uniquement requis si vous souhaitez envoyer des mails via <acronym title="Simple mail transfer protocol">SMTP</acronym>. Si vous ne connaissez pas votre serveur SMTP ou si vous n&apos;&ecirc;tes pas s&ucirc;r de la valeur des param&egrave;tres requis, conservez simplement le m&eacute;canisme par d&eacute;faut : PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'Authentification SMTP';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'n&apos;utilisez l&apos;authentification que si votre seveur SMTP ne l&apos;exige';
$TEXT['WBMAILER_SMTP_HOST'] = 'H&ocirc;te SMTP';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'mot de passe SMTP';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Site internet';
$TEXT['WEBSITE_DESCRIPTION'] = 'Description du site';
$TEXT['WEBSITE_FOOTER'] = 'Pied de page du site';
$TEXT['WEBSITE_HEADER'] = 'Ent&ecirc;te du site';
$TEXT['WEBSITE_KEYWORDS'] = 'Mots cl&eacute;s du site';
$TEXT['WEBSITE_TITLE'] = 'Titre du site';
$TEXT['WELCOME_BACK'] = 'Bienvenue';
$TEXT['WIDTH'] = 'Largeur';
$TEXT['WINDOW'] = 'Fen&ecirc;tre';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Permissions d&apos;&eacute;criture sur fichier';
$TEXT['WRITE'] = 'Ecrire';
$TEXT['WYSIWYG_EDITOR'] = 'Editeur WYSIWYG';
$TEXT['WYSIWYG_STYLE'] = 'Style WYSIWYG';
$TEXT['YES'] = 'Oui';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Les param&egrave;tres requis de l&apos;extension ne sont pas v&eacute;rifi&eacute;s';
$HEADING['ADD_CHILD_PAGE'] = 'Ajouter une sous-page';
$HEADING['ADD_GROUP'] = 'Ajouter un groupe';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Ajouter un ent&ecirc;te';
$HEADING['ADD_PAGE'] = 'Ajouter une page';
$HEADING['ADD_USER'] = 'Ajouter un utilisateur';
$HEADING['ADMINISTRATION_TOOLS'] = 'Outils d&apos;administration';
$HEADING['BROWSE_MEDIA'] = 'Parcourir le dossier media';
$HEADING['CREATE_FOLDER'] = 'Cr&eacute;er un dossier';
$HEADING['DEFAULT_SETTINGS'] = 'R&eacute;glages par d&eacute;faut';
$HEADING['DELETED_PAGES'] = 'Pages effac&eacute;es';
$HEADING['FILESYSTEM_SETTINGS'] = 'R&eacute;glages des fichiers syst&egrave;mes';
$HEADING['GENERAL_SETTINGS'] = 'R&eacute;glages';
$HEADING['INSTALL_LANGUAGE'] = 'Installer une langue';
$HEADING['INSTALL_MODULE'] = 'Installer un module';
$HEADING['INSTALL_TEMPLATE'] = 'Installer un th&egrave;me';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Ex&eacute;cuter manuellement les fichiers module';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Propri&eacute;t&eacute;s de la langue';
$HEADING['MANAGE_SECTIONS'] = 'Gestion des rubriques';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Modifier les propri&eacute;t&eacute;s avanc&eacute;es de la page';
$HEADING['MODIFY_DELETE_GROUP'] = 'Modifier/Supprimer un groupe';
$HEADING['MODIFY_DELETE_PAGE'] = 'Modifier/Supprimer une page';
$HEADING['MODIFY_DELETE_USER'] = 'Modifier/Supprimer un utilisateur';
$HEADING['MODIFY_GROUP'] = 'Modifier un groupe';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Modifier la page d&apos;accueil';
$HEADING['MODIFY_PAGE'] = 'Modifier une page';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Modifier les propri&eacute;t&eacute;s de la page';
$HEADING['MODIFY_USER'] = 'Modifier un utilisateur';
$HEADING['MODULE_DETAILS'] = 'Propri&eacute;t&eacute;s du module';
$HEADING['MY_EMAIL'] = 'Mon email';
$HEADING['MY_PASSWORD'] = 'Mon mot de passe';
$HEADING['MY_SETTINGS'] = 'Mes pr&eacute;f&eacute;rences';
$HEADING['SEARCH_SETTINGS'] = 'R&eacute;glages de la recherche';
$HEADING['SERVER_SETTINGS'] = 'R&eacute;glages du serveur';
$HEADING['TEMPLATE_DETAILS'] = 'Propri&eacute;t&eacute;s du th&egrave;me';
$HEADING['UNINSTALL_LANGUAGE'] = 'D&eacute;sinstaller une langue';
$HEADING['UNINSTALL_MODULE'] = 'D&eacute;sinstaller un module';
$HEADING['UNINSTALL_TEMPLATE'] = 'D&eacute;sinstaller un th&egrave;me';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Uploader des fichiers';
$HEADING['WBMAILER_SETTINGS'] = 'R&eacute;glages de l&apos;envoi de mail';
$MESSAGE['ADDON_ERROR_RELOAD'] = 'Error while updating the Add-On information.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Languages reloaded successfully';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ATTENTION!</strong> For safety reasons uploading languages files in the folder/languages/ only by FTP and use the Upgrade function for registering or updating.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Warning: Existing module database entries will get lost. ';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'When modules are uploaded via FTP (not recommended), the module installation functions <tt>install</tt>, <tt>upgrade</tt> or <tt>uninstall</tt> will not be executed automatically. Those modules may not work correct or do not uninstall properly.<br /><br />You can execute the module functions manually for modules uploaded via FTP below.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Warning: Existing module database entries will get lost. Only use this option if you experience problems with modules uploaded via FTP.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Warning: Existing module database entries will get lost. ';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Modules reloaded successfully';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Overwrite newer Files';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Add-on installation failed. Your system does not fulfill the requirements of this Add-on. To make this Add-on working on your system, please fix the issues summarized below.';
$MESSAGE['ADDON_RELOAD'] = 'Update database with information from Add-on files (e.g. after FTP upload).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Templates reloaded successfully';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Droits insuffisants pour &ecirc;tre ici';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'D&eacute;sol&eacute;, vous ne pouvez pas modifier votre mot de passe plus d&apos;une fois par heure';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Impossible de vous renvoyer vos identifiants, merci de contacter l&apos;administrateur du site';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'L&apos;adresse email que vous avez saisi est introuvable dans la base de donn&eacute;es';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Merci de saisir votre adresse email';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'D&eacute;sol&eacute;, aucun contenu actif &agrave; afficher';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'D&eacute;sol&eacute;, vous n&apos;avez pas les droits pour visualiser cette page';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'D&eacute;j&agrave; install&eacute;';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Impossible d&apos;&eacute;crire dans le r&eacute;pertoire cible';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Impossible de d&eacute;sinstaller';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'D&eacute;sinstallation impossible : fichier en cours d&apos;utilisation';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> ne peut pas &ecirc;tre d&eacute;sinstall&eacute; car il est actuellement en cours d&apos;utilisation dans les pages {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'cette page;ces pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Impossible de d&eacute;sinstaller le mod&egrave;le <b>{{name}}</b> parce que c\'est le mod&egrave;le par d&eacute;faut !';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Impossible de d&eacute;zipper le fichier';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Impossible d&apos;uploader le fichier';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Erreur lors de l&apos;ouverture du fichier';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Les fichiers charg&eacute;s doivent avoir les extensions suivantes : ';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Les fichiers charg&eacute;s doivent &ecirc;tre aux formats suivants : ';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Merci de remplir tous les champs';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Installation r&eacute;ussie';
$MESSAGE['GENERIC_INVALID'] = 'Le fichier charg&eacute; est invalide';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Fichier d&apos;extension incorrect. V&eacute;rifiez le fichier zip .';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Fichier de langue incorrect. V&eacute;rifiez le fichier de langue.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Non install&eacte;';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Merci de patienter';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Merci de revenir plus tard';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Violation de s&eacute;curit&eacute;!! l&apos;enregistrement des donn&eacute;es a &eacute;t&eacute; refus&eacute;!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'D&eacute;sinstallation r&eacute;ussie';
$MESSAGE['GENERIC_UPGRADED'] = 'Mise &agrave; jour r&eacute;ussie';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Site en construction';
$MESSAGE['GROUPS_ADDED'] = 'Groupe ajout&eacute; avec succ&egrave;s';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Etes-vous s&ucirc;r de vouloir supprimer ce groupe (ainsi que tous les utilisateurs de ce groupe) ?';
$MESSAGE['GROUPS_DELETED'] = 'Groupe supprim&eacute; avec succ&egrave;s';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Le nom du groupe est vide';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Le nom du groupe est d&eacute;ja existant';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Groupe introuvable';
$MESSAGE['GROUPS_SAVED'] = 'Groupe sauvegard&eacute; avec succ&egrave;s';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Merci de saisir votre mot de passe';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Votre mot de passe est trop long';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Votre mot de passe est trop court';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Vous n&apos;avez pas entr&eacute; d&apos;extension';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Vous n&apos;avezpas entr&eacute; de nouveau nom';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Impossible de supprimer le dossier s&eacute;lctionn&eacute;';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Impossible de supprimer le fichier s&eacute;lectionn&eacute;';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Impossible de renommer';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Etes-vous s&ucirc;r de vouloir supprimer ce fichier/dossier ?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Dossier supprim&eacute; avec succ&egrave;s';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Fichier supprim&eacute; avec succ&egrave;s';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Le r&eacute;pertoire n&apos;existe pas';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Impossible d&apos;inclure ../ dans le nom du dossier';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Un dossier portant ce nom est d&eacute;j&agrave; existant';
$MESSAGE['MEDIA_DIR_MADE'] = 'Dossier cr&eacute;&eacute; avec succ&egrave;s';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Impossible de cr&eacute;er le dossier';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Un fichier portant ce nom est d&eacute;j&agrave; existant';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Fichier introuvable';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Impossible d&apos;inclure ../ dans le nom';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Vous ne pouvez pas utiliser index.php comme nom';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Aucun media trouv&eacute; dans ce dossier';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = 'Renommage r&eacute;ussi avec succ&egrave;s';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = 'Le fichier a &eacute;t&eacute; upload&eacute; avec succ&egrave;s';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Impossible d&apos;avoir ../ dans le nom du dossier cible';
$MESSAGE['MEDIA_UPLOADED'] = 'Les fichiers ont &eacute;t&eacute; upload&eacute;s avec succ&egrave;s';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'D&eacute;sol&eacute; mais ce formulaire est utilis&eacute; trop fr&eacute;quemment en ce moment. Afin de nous aider &agrave; lutter contre le spam, merci de r&eacute;essayer plus tard';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Le num&eacute;ro de v&eacute;rification (Captcha) que vous avez entr&eacute; est incorrect. Si vous rencontrez des probl&egrave;mes quant &agrave; la lecture de ce num&eacute;ro, merci d&apos;envoyer un email &agrave; : <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Vous devez renseigner les champs suivants';
$MESSAGE['PAGES_ADDED'] = 'Page ajout&eacute;e avec succ&egrave;s';
$MESSAGE['PAGES_ADDED_HEADING'] = 'L&apos;ent&ecirc;te de la page a &eacute;t&eacute; ajout&eacute; avec succ&egrave;s';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Entrez un titre de menu';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Entrez un titre de page';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Erreur lors de la cr&eacute;ation d&apos;un fichier dans le r&eacute;pertoire des pages (privil&egrave;ges insuffisants)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Erreur lors de la suppression d&apos;un fichier dans le r&eacute;pertoire des pages (privil&egrave;ges insuffisants)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Erreur lors du r&eacute;agencement des pages';
$MESSAGE['PAGES_DELETED'] = 'Page supprim&eacute;e avec succ&egrave;s';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Etes-vous s&ucirc;r de vouloir supprimer la page s&eacute;lectionn&eacute;e (ainsi que ses sous-rubriques)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Vous n&apos;avez pas les droits pour modifier cette pages';
$MESSAGE['PAGES_INTRO_LINK'] = 'Cliquez ici pour modifier la page d&apos;introduction';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Impossible d&apos;&eacute;crire dans la page d&apos;introduction (privil&egrave;ges insuffisants)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Page d&apos;introduction sauvegard&eacute;e avec succ&egrave;s';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Mis &agrave; jour par';
$MESSAGE['PAGES_NOT_FOUND'] = 'Page introuvable';
$MESSAGE['PAGES_NOT_SAVED'] = 'Erreur lors de la sauvegarde de la page';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Une page avec le m&ecirc;me nom existe d&eacute;j&agrave;';
$MESSAGE['PAGES_REORDERED'] = 'Page r&eacute;ordonn&eacute;e avec succ&egrave;s';
$MESSAGE['PAGES_RESTORED'] = 'Page restaur&eacute;e avec succ&egrave;s';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Retour au contenu';
$MESSAGE['PAGES_SAVED'] = 'Page sauvegard&eacute;e avec succ&egrave;s';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Param&egrave;tres de la page sauvegard&eacute;s avec succ&egrave;s';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Les propri&eacute;t&eacute;s de la rubrique ont &eacute;t&eacute; sauvegard&eacute;es avec succ&egrave;s';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Le mot de passe entr&eacute; est incorrect';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Donn&eacute;es sauvegard&eacute;es avec succ&egrave;s';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Adresse email sauvegard&eacute;e avec succ&egrave;s';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Caract&egrave;res invalides utilis&eacute;s dans le mot de passe';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Mot de passe modifi&eacute; avec succ&egrave;s';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'La mise &agrave; jour de l&apos;enregistrement a &eacute;chou&eacute;.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'L&apos;enregistrement a &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s.';
$MESSAGE['RECORD_NEW_FAILED'] = 'L&apos;ajout d&apos;un nouvel enregistrement a &eacute;chou&eacute;.';
$MESSAGE['RECORD_NEW_SAVED'] = 'Nouvel enregistrement ajout&eacute; avec succ&egrave;s.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Attention : en cliquant sur ce bouton, vous ne sauvegardez pas vos modifications';
$MESSAGE['SETTINGS_SAVED'] = 'R&eacute;glages sauvegard&eacute;s avec succ&egrave;s';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Impossible de lire le fichier de configuration';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Impossible d&apos;&eacute;crire dans le fichier de configuration';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Recommand&eacute; uniquement pour les environnement de test';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
Enregistrement d&apos;un nouvel utilisateur.

Loginname: {LOGIN_NAME}
Code utilisateur: {LOGIN_ID}
Adresse email: {LOGIN_EMAIL}
Adresse IP: {LOGIN_IP}
Date d&apos;enregistrement: {SIGNUP_DATE}
----------------------------------------
Ce message &agrave; &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Bonjour {LOGIN_DISPLAY_NAME},

Vous avez re&ccedil;u ce message car vous avez utilis&eacute; la fonction \'Retrouver vos identifiants de connexion\' depuis votre compte.

Voici vos nouveaux param&egrave;tres de connexion pour \'{LOGIN_WEBSITE_TITLE}\':

Loginname: {LOGIN_NAME}
Mot de Passe: {LOGIN_PASSWORD}

Nous vous avons attribu&eacute; le mot de passe ci-dessus.
Cela signifie que vous ne pouvez plus vous servir de votre ancien mot de passe!
Si vous avez des probl&egrave;mes ou des questions concernant vos nouveaux param&egrave;tres de connexion
veuillez contacter l&apos;administrateur du site ou l&apos;&eacute;quipe de \'{LOGIN_WEBSITE_TITLE}\'.
Pensez &agrave; vider le cache de votre navigateur avant de vous reconnecter pour &eacute;viter toute probl&egrave;me &eacute;ventuel.

Bien cordialement
-------------------------------------
Ce message &agrave; &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Bonjour {LOGIN_DISPLAY_NAME},

Bienvenue chez \'{LOGIN_WEBSITE_TITLE}\'.

Voici vos param&egrave;tres de connexion pour \'{LOGIN_WEBSITE_TITLE}\':
Loginname: {LOGIN_NAME}
Mot de Passe: {LOGIN_PASSWORD}

Bien cordialement

Remarque:
Si vous pensez avoir re&ccedil;u ce message par erreur, veuillez l&apos;effacer et ne pas en tenir compte!
-------------------------------------
Ce message &agrave; &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement!
';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Param&egrave;tres de votre connexion ...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'L&apos;adresse email est obligatoire';
$MESSAGE['START_CURRENT_USER'] = 'Vous &ecirc;tes connect&eacute; en tant que : ';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Attention : le r&eacute;pertoire d&apos;installation existe toujours';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Bienvenue dans la zone d&apos;administration';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Pour modifier le th&egrave;me du site, vous devez vous rendre dans la rubrique R&eacute;glages';
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
$MESSAGE['USERS_ADDED'] = 'Utilisateur ajout&eacute; avec succ&egrave;s';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Action refus&eacute;e, Vous ne pouvez pas vous supprimer vous-m&ecirc;me!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Vous ne devez modifier les valeurs ci-dessus uniquement lors d&apos;une modification de mot de passe';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Etes-vous s&ucirc;r de vouloir supprimer cet utilisateur ?';
$MESSAGE['USERS_DELETED'] = 'Utilisateur supprim&eacute; avec succ&egrave;s';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'L&apos;adresse email est d&eacute;ja utilis&eacute;e';
$MESSAGE['USERS_INVALID_EMAIL'] = 'L&apos;adresse email n&apos;est pas valide';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Aucun groupe n&apos;a &eacute;t&eacute; s&eacute;lectionn&eacute;';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Le mot de passe est introuvable';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Le mot de passe est trop court';
$MESSAGE['USERS_SAVED'] = 'Utilisateur sauvegard&eacute; avec succ&egrave;s';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Acc&egrave;s aux outils d&apos;administration de WebsiteBaker...';
$OVERVIEW['GROUPS'] = 'Gestions des groupes d&apos;utilisateurs et des permissions';
$OVERVIEW['HELP'] = 'Aide et FAQ sur l&apos;utilisation du site';
$OVERVIEW['LANGUAGES'] = 'Gestion des langues du site';
$OVERVIEW['MEDIA'] = 'Gestion des fichiers media (images, t&eacute;l&eacute;chargements...)';
$OVERVIEW['MODULES'] = 'Gestion des modules du site';
$OVERVIEW['PAGES'] = 'Gestion du contenu du site';
$OVERVIEW['PREFERENCES'] = 'Gestion de vos pr&eacute;f&eacute;rences (email, mot de passe...) ';
$OVERVIEW['SETTINGS'] = 'Configuration du site';
$OVERVIEW['START'] = 'Pr&eacute;sentation de l&apos;administration';
$OVERVIEW['TEMPLATES'] = 'Gestion des th&egrave;mes et modification de l&apos;apparence du site';
$OVERVIEW['USERS'] = 'Gestion des acc&egrave;s au site';
$OVERVIEW['VIEW'] = 'Aper&ccedil;u du site dans une nouvelle fen&ecirc;tre';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
