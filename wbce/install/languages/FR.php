<?php 
/**
 * @file    FR.php
 * @brief   French language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'FR';
$INFO['language_name'] = 'Français';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Assistant d\'installation WBCE CMS';
$TXT['welcome_heading']      = 'Assistant d\'installation';
$TXT['welcome_sub']          = 'Effectue toutes les étapes ci-dessous pour terminer l\'installation';

$TXT['step1_heading']        = 'Étape 1 — Configuration requise';
$TXT['step1_desc']           = 'Vérification que ton serveur remplit toutes les conditions';
$TXT['step2_heading']        = 'Étape 2 — Paramètres du site';
$TXT['step2_desc']           = 'Configure les paramètres de base du site et la locale';
$TXT['step3_heading']        = 'Étape 3 — Base de données';
$TXT['step3_desc']           = 'Saisis tes informations de connexion MySQL / MariaDB';
$TXT['step4_heading']        = 'Étape 4 — Compte administrateur';
$TXT['step4_desc']           = 'Crée tes identifiants de connexion à l\'administration';
$TXT['step5_heading']        = 'Étape 5 — Installer WBCE CMS';
$TXT['step5_desc']           = 'Vérifie la licence et lance l\'installation';

$TXT['req_php_version']      = 'Version PHP >=';
$TXT['req_php_sessions']     = 'Support des sessions PHP';
$TXT['req_server_charset']   = 'Jeu de caractères par défaut du serveur';
$TXT['req_safe_mode']        = 'Mode sans échec PHP';
$TXT['files_and_dirs_perms'] = 'Permissions des fichiers &amp; répertoires';
$TXT['file_perm_descr']      = 'Les chemins suivants doivent être accessibles en écriture par le serveur web';

$TXT['lbl_website_title']    = 'Titre du site';
$TXT['lbl_absolute_url']     = 'URL absolue';
$TXT['lbl_timezone']         = 'Fuseau horaire par défaut';
$TXT['lbl_language']         = 'Langue par défaut';
$TXT['lbl_server_os']        = 'Système d\'exploitation du serveur';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Permissions d\'écriture pour tous (777)';

$TXT['lbl_db_host']          = 'Nom d\'hôte';
$TXT['lbl_db_name']          = 'Nom de la base de données';
$TXT['lbl_db_prefix']        = 'Préfixe des tables';
$TXT['lbl_db_user']          = 'Nom d\'utilisateur';
$TXT['lbl_db_pass']          = 'Mot de passe';
$TXT['btn_test_db']          = 'Tester la connexion';
$TXT['db_testing']           = 'Connexion en cours…';
$TXT['db_retest']            = 'Tester à nouveau';

$TXT['lbl_admin_login']      = 'Nom de connexion';
$TXT['lbl_admin_email']      = 'Adresse e-mail';
$TXT['lbl_admin_pass']       = 'Mot de passe';
$TXT['lbl_admin_repass']     = 'Répéter le mot de passe';
$TXT['btn_gen_password']     = '⚙ Générer';
$TXT['pw_copy_hint']         = 'Copier le mot de passe';

$TXT['btn_install']          = '▶  Installer WBCE CMS';
$TXT['btn_check_settings']   = 'Vérifie tes paramètres à l\'étape 1 et actualise la page avec F5';

$TXT['error_prefix']         = 'Erreur';
$TXT['version_prefix']       = 'Version WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Le support des sessions PHP peut sembler désactivé si ton navigateur n\'accepte pas les cookies.';

$MSG['charset_warning'] =
    'Ton serveur web est configuré pour ne délivrer que le jeu de caractères <b>%1$s</b>. '
    . 'Pour afficher correctement les caractères spéciaux, désactive cette présélection '
    . '(ou demande à ton hébergeur). Tu peux aussi sélectionner <b>%1$s</b> dans les paramètres WBCE, '
    . 'mais cela peut affecter l\'affichage de certains modules.';

$MSG['world_writeable_warning'] =
    'Recommandé uniquement pour les environnements de test. '
    . 'Tu pourras modifier ce paramètre plus tard dans l\'administration.';

$MSG['license_notice'] =
    'WBCE CMS est publié sous la licence <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. En cliquant sur le bouton d\'installation ci-dessous, tu confirmes '
    . 'que tu as lu et accepté les termes de la licence.';

// JS validation messages
$MSG['val_required']       = 'Ce champ est obligatoire.';
$MSG['val_url']            = 'Saisis une URL valide (commençant par http:// ou https://).';
$MSG['val_email']          = 'Saisis une adresse e-mail valide.';
$MSG['val_pw_mismatch']    = 'Les mots de passe ne correspondent pas.';
$MSG['val_pw_short']       = 'Le mot de passe doit contenir au moins 12 caractères.';
$MSG['val_db_untested']    = 'Teste d\'abord avec succès la connexion à la base de données avant d\'installer.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Remplis d\'abord le nom d\'hôte, le nom de la base de données et le nom d\'utilisateur.';
$MSG['db_pdo_missing']        = 'L\'extension PDO n\'est pas disponible sur ce serveur.';
$MSG['db_success']            = 'Connexion réussie : MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Accès refusé. Vérifie le nom d\'utilisateur et le mot de passe.';
$MSG['db_unknown_db']         = 'La base de données n\'existe pas. Crée-la d\'abord ou vérifie le nom.';
$MSG['db_connection_refused'] = 'Impossible de se connecter à l\'hôte. Vérifie le nom d\'hôte et le port.';
$MSG['db_connection_failed']  = 'Échec de la connexion : %s';