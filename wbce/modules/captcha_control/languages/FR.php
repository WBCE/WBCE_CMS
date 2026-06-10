<?php
/**
 * captcha_control — FR.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Protection anti-spam';
$module_description = 'Outil d\'administration pour configurer le captcha ALTCHA';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Protection anti-spam';
$CAPTCHA['HOWTO']              = 'Configurez le captcha ALTCHA proof-of-work et le filtre anti-spam Honeypot. Les deux prot&egrave;gent les formulaires sur l\'ensemble du site sans aucune friction pour l\'utilisateur.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Type de captcha';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA est un captcha proof-of-work auto-h&eacute;berg&eacute; et respectueux de la vie priv&eacute;e. Aucun service tiers requis.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Activer le captcha pour les inscriptions';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Protection avanc&eacute;e anti-spam (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Un champ cach&eacute; d&eacute;tecte les bots qui remplissent automatiquement tous les champs. Aucune action requise de l\'utilisateur. Fonctionne ind&eacute;pendamment du captcha ci-dessus.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>IMPORTANT&nbsp;:</b> Les modules individuels tels que <i>MiniForm</i>, <i>Guestbook</i>, etc. poss&egrave;dent leurs propres param&egrave;tres concernant l&apos;utilisation du Captcha dans le formulaire du module. <br><b>Veuillez v&eacute;rifier les param&egrave;tres des modules concern&eacute;s</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Personnalisation du widget';
$CAPTCHA['AUTO_LABEL']         = 'Mode de d&eacute;marrage';
$CAPTCHA['AUTO_OFF']           = 'Manuel (clic)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automatique';
$CAPTCHA['AUTO_ONSUBMIT']      = '&Agrave; la soumission du formulaire';
$CAPTCHA['DELAY_LABEL']        = 'D&eacute;lai';
$CAPTCHA['DELAY_HINT']         = 'ms de pause avant le d&eacute;marrage du PoW — rend les attaques automatis&eacute;es plus difficiles';
$CAPTCHA['HIDEFOOTER']         = 'Masquer le pied de page &laquo;&nbsp;Powered by ALTCHA&nbsp;&raquo;';
$CAPTCHA['HIDELOGO']           = 'Masquer le logo ALTCHA';
$CAPTCHA['COLOR_BRAND']        = 'Couleur d\'accentuation';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; bordure';
$CAPTCHA['COLOR_SUCCESS']      = 'Couleur de validation';
$CAPTCHA['COLOR_BASE']         = 'Arri&egrave;re-plan du widget';
$CAPTCHA['COLOR_CHECKBOX']     = 'Arri&egrave;re-plan de la case &agrave; cocher';
$CAPTCHA['COLOR_TEXT']         = 'Couleur du texte';
$CAPTCHA['BORDER_RADIUS']      = 'Arrondi des coins';
$CAPTCHA['COLOR_DEFAULT']      = 'Par d&eacute;faut';
$CAPTCHA['CORNER_SQUARE']      = 'Carr&eacute;';
$CAPTCHA['CORNER_LIGHT']       = 'L&eacute;ger';
$CAPTCHA['CORNER_ROUND']       = 'Rond';
$CAPTCHA['PREVIEW']            = 'Aper&ccedil;u';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Prot&eacute;g&eacute; par ALTCHA';
