<?php 
/**
 * @file    BG.php
 * @brief   Bulgarian language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'BG';
$INFO['language_name'] = 'Български';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Инсталационен помощник';
$TXT['welcome_heading']      = 'Инсталационен помощник';
$TXT['welcome_sub']          = 'Изпълни всички стъпки по-долу, за да завършиш инсталацията';

$TXT['step1_heading']        = 'Стъпка 1 — Системни изисквания';
$TXT['step1_desc']           = 'Проверка дали твоят сървър отговаря на всички изисквания';
$TXT['step2_heading']        = 'Стъпка 2 — Настройки на уебсайта';
$TXT['step2_desc']           = 'Конфигурирай основните параметри на сайта и локала';
$TXT['step3_heading']        = 'Стъпка 3 — База данни';
$TXT['step3_desc']           = 'Въведи данните си за връзка с MySQL / MariaDB';
$TXT['step4_heading']        = 'Стъпка 4 — Администраторски акаунт';
$TXT['step4_desc']           = 'Създай своите данни за вход в администрацията';
$TXT['step5_heading']        = 'Стъпка 5 — Инсталиране на WBCE CMS';
$TXT['step5_desc']           = 'Прегледай лиценза и стартирай инсталацията';

$TXT['req_php_version']      = 'PHP версия >=';
$TXT['req_php_sessions']     = 'Поддръжка на PHP сесии';
$TXT['req_server_charset']   = 'Кодировка по подразбиране на сървъра';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Права на файлове и папки';
$TXT['file_perm_descr']      = 'Следните пътища трябва да са записваеми от уеб сървъра';

$TXT['lbl_website_title']    = 'Заглавие на сайта';
$TXT['lbl_absolute_url']     = 'Абсолютен URL';
$TXT['lbl_timezone']         = 'Часова зона по подразбиране';
$TXT['lbl_language']         = 'Език по подразбиране';
$TXT['lbl_server_os']        = 'Операционна система на сървъра';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Права за запис от всички (777)';

$TXT['lbl_db_host']          = 'Име на хоста';
$TXT['lbl_db_name']          = 'Име на базата данни';
$TXT['lbl_db_prefix']        = 'Префикс на таблиците';
$TXT['lbl_db_user']          = 'Потребителско име';
$TXT['lbl_db_pass']          = 'Парола';
$TXT['btn_test_db']          = 'Тествай връзката';
$TXT['db_testing']           = 'Свързване…';
$TXT['db_retest']            = 'Тествай отново';

$TXT['lbl_admin_login']      = 'Потребителско име';
$TXT['lbl_admin_email']      = 'E-mail адрес';
$TXT['lbl_admin_pass']       = 'Парола';
$TXT['lbl_admin_repass']     = 'Повтори паролата';
$TXT['btn_gen_password']     = '⚙ Генерирай';
$TXT['pw_copy_hint']         = 'Копирай паролата';

$TXT['btn_install']          = '▶  Инсталирай WBCE CMS';
$TXT['btn_check_settings']   = 'Провери настройките си в Стъпка 1 и презареди страницата с F5';

$TXT['error_prefix']         = 'Грешка';
$TXT['version_prefix']       = 'WBCE версия';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Поддръжката на PHP сесии може да изглежда изключена, ако браузърът ти не поддържа бисквитки.';

$MSG['charset_warning'] =
    'Твоят уеб сървър е конфигуриран да изпраща само кодировката <b>%1$s</b>. '
    . 'За да се показват правилно националните специални символи, моля изключи тази предварителна настройка '
    . '(или се обърни към твоя хостинг доставчик). Можеш също да избереш <b>%1$s</b> в настройките на WBCE, '
    . 'но това може да повлияе на изхода на някои модули.';

$MSG['world_writeable_warning'] =
    'Препоръчително само за тестови среди. '
    . 'Можеш да промениш тази настройка по-късно в администрацията.';

$MSG['license_notice'] =
    'WBCE CMS се разпространява под лиценза <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. С натискането на бутона за инсталиране по-долу, потвърждаваш, '
    . 'че си прочел и приемаш условията на лиценза.';

// JS validation messages
$MSG['val_required']       = 'Това поле е задължително.';
$MSG['val_url']            = 'Моля, въведи валиден URL (започващ с http:// или https://).';
$MSG['val_email']          = 'Моля, въведи валиден e-mail адрес.';
$MSG['val_pw_mismatch']    = 'Паролите не съвпадат.';
$MSG['val_pw_short']       = 'Паролата трябва да съдържа поне 12 символа.';
$MSG['val_db_untested']    = 'Моля, първо успешно тествай връзката с базата данни преди инсталация.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Моля, първо попълни хоста, името на базата данни и потребителското име.';
$MSG['db_pdo_missing']        = 'PDO разширението не е налично на този сървър.';
$MSG['db_success']            = 'Връзката е успешна: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Достъпът е отказан. Провери потребителското име и паролата.';
$MSG['db_unknown_db']         = 'Базата данни не съществува. Създай я първо или провери името.';
$MSG['db_connection_refused'] = 'Не може да се свърже с хоста. Провери името на хоста и порта.';
$MSG['db_connection_failed']  = 'Връзката неуспешна: %s';
