<?php
/**
 * captcha_control — PT.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Prote&ccedil;&atilde;o anti-spam';
$module_description = 'Ferramenta de administra&ccedil;&atilde;o para configurar o captcha ALTCHA';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Prote&ccedil;&atilde;o anti-spam';
$CAPTCHA['HOWTO']              = 'Configure o captcha ALTCHA proof-of-work e o filtro anti-spam Honeypot. Ambos protegem formul&aacute;rios em todo o site sem qualquer atrito para o utilizador.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Tipo de captcha';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA &eacute; um captcha proof-of-work auto-hospedado e respeitador da privacidade. N&atilde;o requer servi&ccedil;os de terceiros.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Ativar captcha para registos';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Prote&ccedil;&atilde;o avan&ccedil;ada anti-spam (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Um campo oculto deteta bots que preenchem automaticamente todos os campos de entrada. N&atilde;o requer a&ccedil;&atilde;o do utilizador. Funciona independentemente do captcha acima.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>IMPORTANTE:</b> M&oacute;dulos individuais como <i>MiniForm</i>, <i>Guestbook</i>, etc. t&ecirc;m suas pr&oacute;prias configura&ccedil;&otilde;es para o uso do Captcha no formul&aacute;rio do m&oacute;dulo. <br><b>Verifique as configura&ccedil;&otilde;es dos respectivos m&oacute;dulos</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Personaliza&ccedil;&atilde;o do widget';
$CAPTCHA['AUTO_LABEL']         = 'Modo de in&iacute;cio';
$CAPTCHA['AUTO_OFF']           = 'Manual (clique)';
$CAPTCHA['AUTO_ONLOAD']        = 'Autom&aacute;tico';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Ao enviar o formul&aacute;rio';
$CAPTCHA['DELAY_LABEL']        = 'Atraso';
$CAPTCHA['DELAY_HINT']         = 'ms de pausa antes do PoW iniciar — dificulta ataques automatizados';
$CAPTCHA['HIDEFOOTER']         = 'Ocultar rodap&eacute; "Powered by ALTCHA"';
$CAPTCHA['HIDELOGO']           = 'Ocultar log&oacute;tipo ALTCHA';
$CAPTCHA['COLOR_BRAND']        = 'Cor de destaque';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; borda';
$CAPTCHA['COLOR_SUCCESS']      = 'Cor de valida&ccedil;&atilde;o';
$CAPTCHA['COLOR_BASE']         = 'Fundo do widget';
$CAPTCHA['COLOR_CHECKBOX']     = 'Fundo da caixa de verifica&ccedil;&atilde;o';
$CAPTCHA['COLOR_TEXT']         = 'Cor do texto';
$CAPTCHA['BORDER_RADIUS']      = 'Raio dos cantos';
$CAPTCHA['COLOR_DEFAULT']      = 'Padr&atilde;o';
$CAPTCHA['CORNER_SQUARE']      = 'Quadrado';
$CAPTCHA['CORNER_LIGHT']       = 'Leve';
$CAPTCHA['CORNER_ROUND']       = 'Redondo';
$CAPTCHA['PREVIEW']            = 'Pr&eacute;-visualiza&ccedil;&atilde;o';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Protegido por ALTCHA';
