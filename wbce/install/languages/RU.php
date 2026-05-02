<?php 
/**
 * @file    RU.php
 * @brief   Russian language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'RU';
$INFO['language_name'] = 'Русский';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Мастер установки WBCE CMS';
$TXT['welcome_heading']      = 'Мастер установки';
$TXT['welcome_sub']          = 'Выполни все шаги ниже, чтобы завершить установку';

$TXT['step1_heading']        = 'Шаг 1 — Системные требования';
$TXT['step1_desc']           = 'Проверка, соответствует ли твой сервер всем требованиям';
$TXT['step2_heading']        = 'Шаг 2 — Настройки сайта';
$TXT['step2_desc']           = 'Настрой основные параметры сайта и локали';
$TXT['step3_heading']        = 'Шаг 3 — База данных';
$TXT['step3_desc']           = 'Введи данные для подключения к MySQL / MariaDB';
$TXT['step4_heading']        = 'Шаг 4 — Учётная запись администратора';
$TXT['step4_desc']           = 'Создай данные для входа в административную панель';
$TXT['step5_heading']        = 'Шаг 5 — Установка WBCE CMS';
$TXT['step5_desc']           = 'Проверь лицензию и запусти установку';

$TXT['req_php_version']      = 'Версия PHP >=';
$TXT['req_php_sessions']     = 'Поддержка сессий PHP';
$TXT['req_server_charset']   = 'Кодировка сервера по умолчанию';
$TXT['req_safe_mode']        = 'Безопасный режим PHP';
$TXT['files_and_dirs_perms'] = 'Права на файлы и папки';
$TXT['file_perm_descr']      = 'Следующие пути должны быть доступны для записи веб-сервером';

$TXT['lbl_website_title']    = 'Название сайта';
$TXT['lbl_absolute_url']     = 'Абсолютный URL';
$TXT['lbl_timezone']         = 'Часовой пояс по умолчанию';
$TXT['lbl_language']         = 'Язык по умолчанию';
$TXT['lbl_server_os']        = 'Операционная система сервера';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Права на запись для всех (777)';

$TXT['lbl_db_host']          = 'Имя хоста';
$TXT['lbl_db_name']          = 'Имя базы данных';
$TXT['lbl_db_prefix']        = 'Префикс таблиц';
$TXT['lbl_db_user']          = 'Имя пользователя';
$TXT['lbl_db_pass']          = 'Пароль';
$TXT['btn_test_db']          = 'Проверить подключение';
$TXT['db_testing']           = 'Подключение…';
$TXT['db_retest']            = 'Проверить снова';

$TXT['lbl_admin_login']      = 'Логин';
$TXT['lbl_admin_email']      = 'Адрес электронной почты';
$TXT['lbl_admin_pass']       = 'Пароль';
$TXT['lbl_admin_repass']     = 'Повторить пароль';
$TXT['btn_gen_password']     = '⚙ Сгенерировать';
$TXT['pw_copy_hint']         = 'Скопировать пароль';

$TXT['btn_install']          = '▶  Установить WBCE CMS';
$TXT['btn_check_settings']   = 'Проверь свои настройки на шаге 1 и обнови страницу клавишей F5';

$TXT['error_prefix']         = 'Ошибка';
$TXT['version_prefix']       = 'Версия WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Поддержка сессий PHP может отображаться как отключённая, если твой браузер не принимает cookies.';

$MSG['charset_warning'] =
    'Твой веб-сервер настроен на выдачу только кодировки <b>%1$s</b>. '
    . 'Чтобы корректно отображать специальные символы, отключи эту предустановку '
    . '(или обратись к своему хостинг-провайдеру). Ты также можешь выбрать <b>%1$s</b> '
    . 'в настройках WBCE, однако это может повлиять на вывод некоторых модулей.';

$MSG['world_writeable_warning'] =
    'Рекомендуется только для тестовых окружений. '
    . 'Ты сможешь изменить эту настройку позже в административной панели.';

$MSG['license_notice'] =
    'WBCE CMS распространяется под лицензией <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Нажимая кнопку установки ниже, ты подтверждаешь, '
    . 'что прочитал и принимаешь условия лицензии.';

// JS validation messages
$MSG['val_required']       = 'Это поле обязательно для заполнения.';
$MSG['val_url']            = 'Пожалуйста, введи корректный URL (начиная с http:// или https://).';
$MSG['val_email']          = 'Пожалуйста, введи корректный адрес электронной почты.';
$MSG['val_pw_mismatch']    = 'Пароли не совпадают.';
$MSG['val_pw_short']       = 'Пароль должен содержать не менее 12 символов.';
$MSG['val_db_untested']    = 'Пожалуйста, успешно проверь подключение к базе данных перед установкой.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Сначала заполни поля хоста, имени базы данных и имени пользователя.';
$MSG['db_pdo_missing']        = 'Расширение PDO недоступно на этом сервере.';
$MSG['db_success']            = 'Подключение успешно: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Доступ запрещён. Проверь имя пользователя и пароль.';
$MSG['db_unknown_db']         = 'База данных не существует. Создай её сначала или проверь название.';
$MSG['db_connection_refused'] = 'Не удалось подключиться к хосту. Проверь имя хоста и порт.';
$MSG['db_connection_failed']  = 'Ошибка подключения: %s';
