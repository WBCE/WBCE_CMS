<?php
/**
 * captcha_control — RU.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Защита от спама';
$module_description = 'Инструмент администратора для настройки ALTCHA captcha';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Защита от спама';
$CAPTCHA['HOWTO']              = 'Настройте ALTCHA proof-of-work captcha и фильтр спама Honeypot. Оба защищают формы на всём сайте без каких-либо неудобств для пользователей.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Тип captcha';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA — это самостоятельно размещаемая, дружественная к приватности captcha proof-of-work. Сторонние сервисы не требуются.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Включить captcha для регистраций';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Расширенная защита от спама (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Скрытое поле обнаруживает ботов, автоматически заполняющих все поля ввода. Не требует действий от пользователя. Работает независимо от captcha выше.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>ВАЖНО:</b> Отдельные модули, такие как <i>MiniForm</i>, <i>Guestbook</i> и др., имеют собственные настройки для использования Captcha в форме модуля. <br><b>Пожалуйста, проверьте настройки соответствующих модулей</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Настройка виджета';
$CAPTCHA['AUTO_LABEL']         = 'Режим запуска';
$CAPTCHA['AUTO_OFF']           = 'Вручную (клик)';
$CAPTCHA['AUTO_ONLOAD']        = 'Автоматически';
$CAPTCHA['AUTO_ONSUBMIT']      = 'При отправке формы';
$CAPTCHA['DELAY_LABEL']        = 'Задержка';
$CAPTCHA['DELAY_HINT']         = 'мс паузы перед запуском PoW — затрудняет автоматизированные атаки';
$CAPTCHA['HIDEFOOTER']         = 'Скрыть подвал "Powered by ALTCHA"';
$CAPTCHA['HIDELOGO']           = 'Скрыть логотип ALTCHA';
$CAPTCHA['COLOR_BRAND']        = 'Акцентный цвет';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; рамка';
$CAPTCHA['COLOR_SUCCESS']      = 'Цвет отметки';
$CAPTCHA['COLOR_BASE']         = 'Фон виджета';
$CAPTCHA['COLOR_CHECKBOX']     = 'Фон флажка';
$CAPTCHA['COLOR_TEXT']         = 'Цвет текста';
$CAPTCHA['BORDER_RADIUS']      = 'Скругление углов';
$CAPTCHA['COLOR_DEFAULT']      = 'По умолчанию';
$CAPTCHA['CORNER_SQUARE']      = 'Квадратный';
$CAPTCHA['CORNER_LIGHT']       = 'Лёгкое';
$CAPTCHA['CORNER_ROUND']       = 'Круглый';
$CAPTCHA['PREVIEW']            = 'Предпросмотр';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Защищено ALTCHA';
