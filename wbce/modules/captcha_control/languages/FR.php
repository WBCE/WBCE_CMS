<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// dule Description
$module_description = 'Outil d&apos;administration de la v&eacute;rification par Captcha et du ASP';

// Headings and text outputs
$MOD_CAPTCHA_CONTROL['HEADING'] = 'Contr&ocirc;le du Captcha et de l&apos;ASP';
$MOD_CAPTCHA_CONTROL['HOWTO'] = 'Ici vous pouvez modifier le comportement du "CAPTCHA" et de l&apos;ASP (Advanced Spam Protection). Pour qu&apos;un module puisse utiliser l&apos;ASP, il doit &ecirc;tre sp&eacute;cifiquement adapt&eacute;.';

// Text and captions of form elements
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF'] = 'Configuration du CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE'] = 'Type de CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP'] = 'les r&eacute;glagles des CAPTCHA pour les modules sont d&eacute;finis dans leurs pr&eacute;f&eacute;rences respectives';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA'] = 'CAPTCHA avant validation';
$MOD_CAPTCHA_CONTROL['ENABLED'] = 'Activ&eacute;';
$MOD_CAPTCHA_CONTROL['DISABLED'] = 'D&eacute;sactiv&eacute;';
$MOD_CAPTCHA_CONTROL['ASP_CONF'] = 'Configuration de l&apos;ASP (Advanced Spam Protection)';
$MOD_CAPTCHA_CONTROL['ASP_TEXT'] = 'Activer ASP (si disponible)';
$MOD_CAPTCHA_CONTROL['ASP_EXP'] = 'ASP tente de d&eacute;terminer si un formulaire &agrave; &eacute;t&eacute; post&eacute; par un humain ou un spam-bot.';
$MOD_CAPTCHA_CONTROL['CALC_TEXT'] = 'Calcul sous forme de texte';
$MOD_CAPTCHA_CONTROL['CALC_IMAGE'] = 'Calcul sous forme d&apos;image';
$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE'] = 'Calcul sous forme d&apos;image avec polices et fonds al&eacute;atoires';
$MOD_CAPTCHA_CONTROL['TTF_IMAGE'] = 'Image avec polices et fonds al&eacute;atoires';
$MOD_CAPTCHA_CONTROL['OLD_IMAGE'] = 'Ancienne m&eacute;thode (non recommand&eacute;)';
$MOD_CAPTCHA_CONTROL['TEXT'] = 'CAPTCHA texte';
$MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT'] = 'Questions et R&eacute;ponses';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'] = 'Effacez ceci et remplacez le par votre propre texte'."\n".'sinon vos changements ne seront pas enregistr&eacute;s'."\n".'### exemple ###'."\n".'Saisissez ici vos questions et r&eacute;ponses.'."\n".'Utilisation:'."\n".'?Quel est le <b>pr&eacute;nom</b> de <b>Claudia</b> Schiffer ?'."\n".'!Claudia'."\n".'?Question 2'."\n".'!R&eacute;ponse 2'."\n".''."\n".'si le langage n&apos;a pas d&apos;importance.'."\n".' ... '."\n".'Ou, si vous utilisez la localisation:'."\n".'?FR:Quel est le <b>pr&eacute;nom</b> de <b>Claudia</b> Schiffer ?'."\n".'!Claudia'."\n".' ... '."\n".'?EN:What&apos;s <b>Claudia</b> Schiffer&apos;s <b>first name</b>?'."\n".'!Claudia'."\n".'?EN:Question 2'."\n".'!Answer 2'."\n".'### exemple ###'."\n".'';

$MOD_CAPTCHA['VERIFICATION'] = 'V&eacute;rification';
$MOD_CAPTCHA['ADDITION'] = 'ajout&eacute; &agrave; (+)';
$MOD_CAPTCHA['SUBTRAKTION'] = 'moins (-)';
$MOD_CAPTCHA['MULTIPLIKATION'] = 'multipli&eacute; par (x)';
$MOD_CAPTCHA['VERIFICATION_INFO_RES'] = 'Indiquez le r&eacute;sultat';
$MOD_CAPTCHA['VERIFICATION_INFO_TEXT'] = 'Remplissez le texte';
$MOD_CAPTCHA['VERIFICATION_INFO_QUEST'] = 'R&eacute;pondez &agrave; la question';
$MOD_CAPTCHA['INCORRECT_VERIFICATION'] = 'Erreur de v&eacute;rification';
