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

// Headings and text outputs
$MOD_CAPTCHA_CONTROL['HEADING'] = 'Управление Captcha и ASP';
$MOD_CAPTCHA_CONTROL['HOWTO'] = 'Здесь вы можете управлять настройками "CAPTCHA" и "Advanced Spam Protection" (ASP). Для того чтобы ASP работал в модулях, вам необходимо настроить его поведение здесь.';

// Text and captions of form elements
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF'] = 'Настройки CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE'] = 'Тип CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP'] = 'Настройки CAPTCHA для модулей находятся в настройках соответствующего модуля';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA'] = 'Использовать CAPTCHA для формы регистрации';
$MOD_CAPTCHA_CONTROL['ASP_CONF'] = 'Настройки Advanced Spam Protection';
$MOD_CAPTCHA_CONTROL['ASP_TEXT'] = 'Использовать ASP (если возможно)';
$MOD_CAPTCHA_CONTROL['ASP_EXP'] = 'ASP пытается определить, заполнена форма человеком или spam-роботом.';
$MOD_CAPTCHA_CONTROL['CALC_TEXT'] = 'Вычисления (текст)';
$MOD_CAPTCHA_CONTROL['CALC_IMAGE'] = 'Вычисления (картинка)';
$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE'] = 'Вычисления (картинка с различными шрифтами и фоном)';
$MOD_CAPTCHA_CONTROL['TTF_IMAGE'] = 'Картинка с различными шрифтами и фоном';
$MOD_CAPTCHA_CONTROL['OLD_IMAGE'] = 'Старый стиль (не рекомендуется)';
$MOD_CAPTCHA_CONTROL['TEXT'] = 'Текстовая CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT'] = 'Вопросы и Ответы';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'] = 'Delete this all to add your own entries'."\n".'or your changes won\'t be saved!'."\n".'### example ###'."\n".'Here you can enter Questions and Answers.'."\n".'Use:'."\n".'?What\'s <b>Claudia</b> Schiffer\'s <b>first name</b>?'."\n".'!Claudia'."\n".'?Question 2'."\n".'!Answer 2'."\n".' ... '."\n".'if language doesn\'t matter.'."\n".''."\n".'Or, if language do matter, use:'."\n".'?EN:What\'s <b>Claudia</b> Schiffer\'s <b>first name</b>?'."\n".'!Claudia'."\n".'?EN:Question 2'."\n".'!Answer 2'."\n".'?DE:Wie ist der <b>Vorname</b> von <b>Claudia</b> Schiffer?'."\n".'!Claudia'."\n".' ... '."\n".'### example ###'."\n".'';

$MOD_CAPTCHA['VERIFICATION'] = 'Проверка';
$MOD_CAPTCHA['ADDITION'] = 'плюс';
$MOD_CAPTCHA['SUBTRAKTION'] = 'минус';
$MOD_CAPTCHA['MULTIPLIKATION'] = 'умножить на';
$MOD_CAPTCHA['VERIFICATION_INFO_RES'] = 'Укажите результат';
$MOD_CAPTCHA['VERIFICATION_INFO_TEXT'] = 'Укажите текст';
$MOD_CAPTCHA['VERIFICATION_INFO_QUEST'] = 'Ответьте на вопрос';
$MOD_CAPTCHA['INCORRECT_VERIFICATION'] = 'Ошибка верификации';

// ── CAPTCHA namespace ─────────────────────────────────────────────────────────
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>ВАЖНО:</b> Отдельные модули, такие как <i>MiniForm</i>, <i>Guestbook</i> и др., имеют собственные настройки для использования Captcha в форме модуля. <br><b>Пожалуйста, проверьте настройки соответствующих модулей</b>.';