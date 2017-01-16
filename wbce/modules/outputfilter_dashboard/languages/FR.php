<?php

/*
languages/FR.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.1
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
  FRENCH LANGUAGE FILE FOR THE  MODULE: Outputfilter Dashboard
 -----------------------------------------------------------------------------------------
*/

// include English file so that all strings have at least a default value
include((dirname(__FILE__))."/EN.php");

// description francais
$module_description = "Admin-Tool pour g&eacute;rir OutputFilter Dashboard";


$LANG['MOD_OPF']['TXT_MODULE_TYPE_LAST'] = 'Un filtre de <em>&quot;type module&quot; </ em> ou <em> &quot;type module(dernier)&quot;</ em> sera appliqu&eacute; &agrave; chaque affich&eacute; sections des modules s&eacute;lectionn&eacute;s dans le module arbre ci-dessous.';

$LANG['MOD_OPF']['TXT_MODULE_TREE'] = 'Dans le module arbre ci-dessous, s&eacute;lectionnez les modules &agrave; laquelle le filtre doit &ecirc;tre appliqu&eacute;';

$LANG['MOD_OPF']['TXT_TYPE_LAST'] = 'Un filtre de <em>type page</ em> ou <em>type page (dernier)</ em> sera appliqu&eacute; &agrave; chaque page affich&eacute;e (y compris par exemple le resultat des extraits de code et le mod&egrave;le lui-m&ecirc;me) dans son ensemble.';

$LANG['MOD_OPF']['TXT_SUB_PAGES'] = 'Cochez ou d&eacute;cochez pages / subpages ci-dessous <br /> Le filtre sera appliqu&eacute; &agrave; toutes les pages s&eacute;lectionn&eacute;es ainsi que tous les modules s&eacute;lectionn&eacute;s dans le &quot;module arbre&quot; ci-dessus.' ;

$LANG['MOD_OPF']['TXT_FILTER_FUNC'] = 'Entrez le fonction de filtrage ci-dessous.';

$LANG['MOD_OPF']['TXT_FILTER_CONF'] = 'configuration du filtre';

$LANG['MOD_OPF']['TXT_FILTER_DESC'] = 'Description du filtre';

$LANG['MOD_OPF']['TXT_FILTER_NAME'] = 'Nom du filtre';

$LANG['MOD_OPF']['TXT_ACTIVE'] = 'active';

$LANG['MOD_OPF']['TXT_INACTIVE'] = 'inactive';

$LANG['MOD_OPF']['TXT_DESCRIPTION'] = 'Description';

$LANG['MOD_OPF']['TXT_FILTER_HELP'] = 'Aide pour ce filtre';

$LANG['MOD_OPF']['TXT_HELP'] = 'Aide';

$LANG['MOD_OPF']['TXT_FILTER_OUTPUT_SETTINGS'] = 'R&eacute;glages du filtre (Pages / Modules)';

$LANG['MOD_OPF']['TXT_TYPE'] = 'Type';

$LANG['MOD_OPF']['TXT_FILTER_MODULES'] = 'Appliquer un filtre &agrave; ces modules';

$LANG['MOD_OPF']['TXT_FILTER_PAGES'] = 'Appliquer un filtre &agrave; ces pages';

$LANG['MOD_OPF']['TXT_FUNCTION'] = 'Function du filtre';

$LANG['MOD_OPF']['TXT_FUNC_NAME'] = 'Nom de la fonction';

$LANG['MOD_OPF']['TXT_FILTER_FILE'] = 'fichier de filtre';

$LANG['MOD_OPF']['TXT_SAVE'] = 'Enregistrer';

$LANG['MOD_OPF']['TXT_SAVE_AND_CLOSE'] = 'Enregistrer et fermer';

$LANG['MOD_OPF']['TXT_EXIT'] = 'Exit';

$LANG['MOD_OPF']['TXT_CANCEL'] = 'Annuler';

$LANG['MOD_OPF']['TXT_OUTPUTFILTER'] = 'filtre de sortie - editer la feuille CSS';

$LANG['MOD_OPF']['TXT_EDITCSS'] = 'Modifier CSS';

$LANG['MOD_OPF']['TXT_EXPORT_FAILED'] = 'Echec de l&apos;export';

$LANG['MOD_OPF']['TXT_EXPORT_SUCCESS'] = 'Exporter avec succe&egrave;s';

$LANG['MOD_OPF']['TXT_CONVERT_FAILED'] = 'Echec de la transformation';

$LANG['MOD_OPF']['TXT_CONVERT_SUCCESS'] = 'Transformation avec succe&egrave;s';

$LANG['MOD_OPF']['TXT_OK'] = 'OK';

$LANG['MOD_OPF']['TXT_DOWNLOAD'] = 'T&eacute;l&eacute;charger';

$LANG['MOD_OPF']['TXT_DOWNLOAD_FAILED'] = '&Eacute;chec du t&eacute;l&eacute;chargement';

$LANG['MOD_OPF']['TXT_UPLOAD_SUCCESS'] = 'Ajouter r&eacute;ussie';

$LANG['MOD_OPF']['TXT_OUTPUTFILTER_DASHBOARD'] = 'OutputFilter Dashboard';

$LANG['MOD_OPF']['TXT_PATCH_COREFILES'] = 'Vous devez patcher deux fichiers core pour faire ce travail de AdminTool. <a href = "%s"> S&apos;il vous pla&icirc;t lire la documentation </a> ';

$LANG['MOD_OPF']['TXT_ACTIVATE_JS'] = 'Note: Pour profiter de tout le confort fonctionnalit&eacute;s offertes par ce module s&apos;il vous pla&icirc;t activer javascript.';

$LANG['MOD_OPF']['TXT_ADD_FILTER'] = 'Ajouter un filtre';

$LANG['MOD_OPF']['TXT_NEW_INLINEFILTER'] = 'Ajouter nouveau filtre inline';

$LANG['MOD_OPF']['TXT_UPLOAD_FILTER'] = 'Upload filtre';

$LANG['MOD_OPF']['TXT_UPLOAD_PLUGIN_FILTER'] = 'filtre de t&eacute;l&eacute;chargement (plugin)';

$LANG['MOD_OPF']['TXT_HELP_BROWSER'] = 'Ouvrir l&apos;aide-navigateur';

$LANG['MOD_OPF']['TXT_ABOUT'] = 'A propos';

$LANG['MOD_OPF']['TXT_UPLOAD_FILTER_PLUGIN'] = 'Ajouter un plugin filtre';

$LANG['MOD_OPF']['TXT_INSTALL_PLUGIN_FILTER'] = 'Installer un filtre plugin';

$LANG['MOD_OPF']['TXT_UPLOAD'] = 'Ajouter';

$LANG['MOD_OPF']['TXT_ACTIONS'] = 'Actions';

$LANG['MOD_OPF']['TXT_FILTER_ACTIVE'] = 'Filtre ACTIVE (d&eacute;sactiver filtre sur clic)';

$LANG['MOD_OPF']['TXT_FILTER_INACTIVE'] = 'Filtre INACTIVE (activer filtre sur clic)';

$LANG['MOD_OPF']['TXT_EDIT_FILTER'] = 'Modifier le filtre';

$LANG['MOD_OPF']['TXT_PLUGIN_FILTER'] = 'filtre plugin';

$LANG['MOD_OPF']['TXT_INLINE_FILTER'] = 'filtre inline';

$LANG['MOD_OPF']['TXT_MODULE_EXTENSION_FILTER'] = 'le module de filtre d&apos;extension';

$LANG['MOD_OPF']['TXT_CONFIG_FILTER'] = 'Config Filter';

$LANG['MOD_OPF']['TXT_MOVE_UP'] = 'D&eacute;placer vers le haut';

$LANG['MOD_OPF']['TXT_MOVE_DOWN'] = 'D&eacute;placer vers le bas';

$LANG['MOD_OPF']['TXT_DELETE_FILTER'] = 'Supprimer filtre';

$LANG['MOD_OPF']['TXT_SURE_TO_DELETE'] = '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer le filtre <br /> <b> %s </ b>?';

$LANG['MOD_OPF']['TXT_CONVERT_FILTER'] = 'transformer filtre en plugin';

$LANG['MOD_OPF']['TXT_SURE_TO_CONVERT'] = '&Ecirc;tes-vous s&ucirc;r de vouloir transformer le filtre <br /><b>%s</b><br />en plugin?';

$LANG['MOD_OPF']['TXT_CONVERT_PLUGIN'] = 'transformer plugin en filtre inline';
'Convert filter to inline';

$LANG['MOD_OPF']['TXT_SURE_TO_INLINE'] = '&Ecirc;tes-vous s&ucirc;r de vouloir transformer le plugin <br /><b>%s</b><br />en inline filtre?';

$LANG['MOD_OPF']['TXT_CONVERT_FILTER'] = 'transformer en plugin';

$LANG['MOD_OPF']['TXT_SURE_TO_CONVERT'] = '&Ecirc;tes-vous s&ucirc;r de vouloir transformer le filtre <br /><b>%s</b>?';

$LANG['MOD_OPF']['TXT_EXPORT_FILTER'] = 'Export du filtre';

$LANG['MOD_OPF']['TXT_VISIT_PROJECTS_WEBSITE'] = 'Visiter projets site';

$LANG['MOD_OPF']['TXT_INSERT_NAME'] = 'Ins&eacute;rer un nom ici';

$LANG['MOD_OPF']['TXT_INSERT_DESCRIPTION'] = 'Ins&eacute;rer une description ici';

$LANG['MOD_OPF']['TXT_MODULE'] = 'Module';

$LANG['MOD_OPF']['TXT_MODULE_LAST'] = 'Module (dernier)';

$LANG['MOD_OPF']['TXT_PAGE'] = 'Page';

$LANG['MOD_OPF']['TXT_PAGE_LAST'] = 'Page (derni&egrave;re)';

$LANG['MOD_OPF']['TXT_ALL_MODULES'] = 'Tous les modules';

$LANG['MOD_OPF']['TXT_SINGLE_PAGE'] = 'Une seule page';

$LANG['MOD_OPF']['TXT_PAGE_HIERARCHY'] = 'hi&eacute;rarchie des pages';

$LANG['MOD_OPF']['TXT_SEARCH_RESULTS'] = 'R&eacute;sultats de la r&eacute;cherche';

$LANG['MOD_OPF']['TXT_BACKEND'] = 'Backend';

$LANG['MOD_OPF']['TXT_ALL_PAGES'] = 'Toutes les pages';

$LANG['MOD_OPF']['TXT_EXPORT_FAILED_PLUGIN'] = '&Eacute;chec de l&apos;exportation plugin: %s';

$LANG['MOD_OPF']['TXT_CONVERT_FAILED_PLUGIN'] = '&Eacute;chec de la transformation plugin: %s';

$LANG['MOD_OPF']['TXT_WRITE_DENIED'] = 'ne peut pas &eacute;crire dans le r&eacute;pertoire %s';

$LANG['MOD_OPF']['TXT_NO_FILTER'] = 'Filtre n&apos;existe pas';

$LANG['MOD_OPF']['TXT_NO_EXPORT'] = 'On ne peut pas exporter un module de filtre';

$LANG['MOD_OPF']['TXT_NO_CONVERT'] = 'On ne peut pas transformer un module de filtre';

$LANG['MOD_OPF']['TXT_NO_SUCH_DIR'] = 'r&eacute;pertoire n&apos;existe pas';

$LANG['MOD_OPF']['TXT_WRITE_FAILED'] = 'n&apos;a pas r&eacute;ussi &agrave; &eacute;crire le fichier %s';

$LANG['MOD_OPF']['TXT_PLUGIN_EXPORTED'] = 'Plugin export&eacute; avec succ&egrave;s';

$LANG['MOD_OPF']['TXT_FAILED_TO_UPLOAD'] = 'Impossible de t&eacute;l&eacute;charger le plugin: %s';

$LANG['MOD_OPF']['TXT_DIR_WRITE_FAILED'] = 'ne peut pas &eacute;crire plugin-r&eacute;pertoire';

$LANG['MOD_OPF']['TXT_UPLOAD_FAILED'] = 't&eacute;l&eacute;chargement a &eacute;chou&eacute;';

$LANG['MOD_OPF']['TXT_NOT_A_FILTER'] = 'fichier t&eacute;l&eacute;charg&eacute; est pas un plugin filtre de sortie';

$LANG['MOD_OPF']['TXT_LOOKS_LIKE_MODULE'] = ' - il semble d&apos;&ecirc;tre un filtre de module. Installez-l parmi les <a href=%s>Modules</a>.';

$LANG['MOD_OPF']['TXT_ALREADY_INSTALLED'] = 'une version plus r&eacute;cente est d&eacute;j&agrave; install&eacute;e';

$LANG['MOD_OPF']['TXT_UNZIP_FAILED'] = 'n&apos;a pas r&eacute;ussi &agrave; d&eacute;compresser les archives';

$LANG['MOD_OPF']['TXT_PLUGIN_UPLOAD_SUCCESS'] = 'Plugin t&eacute;l&eacute;charg&eacute; avec succ&egrave;s';

$LANG['MOD_OPF']['TXT_ERR_NO_UPLOAD'] = 'Aucun fichier t&eacute;l&eacute;charg&eacute;';

$LANG['MOD_OPF']['TXT_ERR_PHP_ERR'] = 'code d&apos;erreur de PHP: %d';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_SIZE'] = 'Le fichier transf&eacute;r&eacute; d&eacute;passe la limite de taille %d';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_EXT'] = 'Le fichier transf&eacute;r&eacute; ne correspond pas &agrave; l&apos;extension %s';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_TYPE'] = 'Le fichier transf&eacute;r&eacute; ne correspond pas au type %s';

$LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'] = 'Terminated: S&eacute;curit&eacute; de la violation de %s !';

$LANG['MOD_OPF']['TXT_ERR_UPLOAD_FAILED'] = 'Impossible de sauvegarder le fichier t&eacute;l&eacute;charg&eacute;';
