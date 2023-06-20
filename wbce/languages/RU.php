<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 *
 * Made whith help of Automated Language File tool Copyright heimsath.org
 */

//no direct file access
if (count(get_included_files()) ==1) {
    $z="HTTP/1.0 404 Not Found";
    header($z);
    die($z);
}

// Set the language information
$language_code = 'RU';
$language_name = 'Russian'; // русский
$language_version = '2.9';
$language_platform = '1.4.x';
$language_author = 'Kirill Karakulko (kirill@nadosoft.com), Leo Klee';
$language_license = 'GNU General Public License';


$MENU['ACCESS'] = 'Управление пользователями';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Дополнительные функции';
$MENU['ADMINTOOLS'] = 'Админ-панель';
$MENU['BREADCRUMB'] = 'Вы находитесь здесь: ';
$MENU['FORGOT'] = 'Забыли пароль?';
$MENU['GROUP'] = 'Группа';
$MENU['GROUPS'] = 'Группы';
$MENU['HELP'] = 'Помощь';
$MENU['LANGUAGES'] = 'Языки';
$MENU['LOGIN'] = 'Вход';
$MENU['LOGOUT'] = 'Выход';
$MENU['MEDIA'] = 'Файлы';
$MENU['MODULES'] = 'Модули';
$MENU['PAGES'] = 'Страницы';
$MENU['PREFERENCES'] = 'Свойства';
$MENU['SETTINGS'] = 'Установки';
$MENU['START'] = 'Старт';
$MENU['TEMPLATES'] = 'Шаблоны';
$MENU['USERS'] = 'Пользователи';
$MENU['VIEW'] = 'Просмотреть';


$TEXT['ACCOUNT_SIGNUP'] = 'Регистрация';
$TEXT['ACTIONS'] = 'Действия';
$TEXT['ACTIVE'] = 'Активная';
$TEXT['ADD'] = 'Добавить';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Добавить секцию';
$TEXT['ADMIN'] = 'Администратор';
$TEXT['ADMINISTRATION'] = 'Администрирование';
$TEXT['ADMINISTRATION_TOOL'] = 'Средства администрирования';
$TEXT['ADMINISTRATOR'] = 'Администратор';
$TEXT['ADMINISTRATORS'] = 'Администраторы';
$TEXT['ADVANCED'] = 'Расширенные';
$TEXT['ADVANCED_SEARCH'] = 'Расширенный поиск';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Разрешенные для загрузки типы файлов';
$TEXT['ALLOWED_VIEWERS'] = 'Разрешенные пользователи';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Разрешить мульти-выбор';
$TEXT['ALL_WORDS'] = 'Все слова';
$TEXT['ANCHOR'] = 'Якорь';
$TEXT['ANONYMOUS'] = 'Анонимный';
$TEXT['ANY_WORDS'] = 'Любое слово';
$TEXT['APP_NAME'] = 'Имя приложения';
$TEXT['ARE_YOU_SURE'] = 'Вы уверены?';
$TEXT['AUTHOR'] = 'Автор';
$TEXT['BACK'] = 'Назад';
$TEXT['BACKEND'] = 'Backend';
$TEXT['BACKUP'] = 'Резервное копирование';
$TEXT['BACKUP_ALL_TABLES'] = 'Резервное копирование всех таблиц базы';
$TEXT['BACKUP_DATABASE'] = 'Резервное копирование базы данных';
$TEXT['BACKUP_MEDIA'] = 'Резервное копирование файлов';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Резервное копирование только таблиц CMS';
$TEXT['BASIC'] = 'Основные';
$TEXT['BLOCK'] = 'Блокировать';
$TEXT['BUTTON_SEND_TESTMAIL'] = 'Проверить настройки e-mail';
$TEXT['CALENDAR'] = 'Календарь';
$TEXT['CANCEL'] = 'Отменить';
$TEXT['CAN_DELETE_HIMSELF'] = 'Может быть удален самостоятельно';
$TEXT['CAPTCHA_VERIFICATION'] = 'Графическая верификация';
$TEXT['CAP_EDIT_CSS'] = 'Редактировать CSS';
$TEXT['CHANGE'] = 'Изменить';
$TEXT['CHANGES'] = 'Изменения';
$TEXT['CHANGE_SETTINGS'] = 'Изменение свойств';
$TEXT['CHARACTERS'] = 'Символы';
$TEXT['CHARSET'] = 'Кодировка';
$TEXT['CHECKBOX_GROUP'] = 'Checkbox группа';
$TEXT['CLOSE'] = 'Закрыть';
$TEXT['CODE'] = 'Код';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Свернуть';
$TEXT['COMMENT'] = 'Комментарий';
$TEXT['COMMENTING'] = 'Комментировать';
$TEXT['COMMENTS'] = 'Комментарии';
$TEXT['CREATE_FOLDER'] = 'Создать папку';
$TEXT['CURRENT'] = 'Текущая';
$TEXT['CURRENT_FOLDER'] = 'Текущая папка';
$TEXT['CURRENT_PAGE'] = 'Текущая страница';
$TEXT['CURRENT_PASSWORD'] = 'Текущий пароль';
$TEXT['CUSTOM'] = 'Задать e-mail';
$TEXT['DATABASE'] = 'База данных';
$TEXT['DATE'] = 'Дата';
$TEXT['DATE_FORMAT'] = 'Формат даты';
$TEXT['DEFAULT'] = 'По умолчанию';
$TEXT['DEFAULT_CHARSET'] = 'Кодировка по умолчанию';
$TEXT['DEFAULT_TEXT'] = 'Текст по умолчанию';
$TEXT['DELETE'] = 'Удалить';
$TEXT['DELETED'] = 'Удаленная';
$TEXT['DELETE_DATE'] = 'Удалить дату';
$TEXT['DELETE_ZIP'] = 'Удалить zip архив после распаковки';
$TEXT['DESCRIPTION'] = 'Описание';
$TEXT['DESIGNED_FOR'] = 'Создано для';
$TEXT['DIRECTORIES'] = 'Папки';
$TEXT['DIRECTORY_MODE'] = 'Режим папки';
$TEXT['DISABLED'] = 'Отсутствует';
$TEXT['DISPLAY_NAME'] = 'Вывести Название';
$TEXT['EMAIL'] = 'Email';
$TEXT['EMAIL_ADDRESS'] = 'Email адрес';
$TEXT['EMPTY_TRASH'] = 'Очистить корзину';
$TEXT['ENABLED'] = 'Присутствует';
$TEXT['END'] = 'Закончить';
$TEXT['ERROR'] = 'Ошибка';
$TEXT['ERR_USE_SYSTEM_DEFAULT'] = 'Используемые системные настройки (php.ini)';
$TEXT['ERR_HIDE_ERRORS_NOTICES'] = 'Скрыть ошибки и предупреждения (WWW)';
$TEXT['ERR_SHOW_ERRORS_NOTICES'] = 'Показать ошибки и предупреждения (разработка)';
$TEXT['ERR_SHOW_ERRORS_HIDE_NOTICES'] = 'Отображать только ошибки';
$TEXT['EXACT_MATCH'] = 'Точное совпадение';
$TEXT['EXECUTE'] = 'Запуск';
$TEXT['EXPAND'] = 'Раскрыть';
$TEXT['EXTENSION'] = 'Расширение';
$TEXT['FIELD'] = 'Поле';
$TEXT['FILE'] = 'Файл';
$TEXT['FILENAME'] = 'Имя файла';
$TEXT['FILES'] = 'Файлы';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Права доступа к файлам и папкам';
$TEXT['FILE_MODE'] = 'Файловый режим';
$TEXT['FINISH_PUBLISHING'] = 'Закончить публикацию';
$TEXT['FOLDER'] = 'Папка';
$TEXT['FOLDERS'] = 'Папки';
$TEXT['FOOTER'] = 'Нижняя часть';
$TEXT['FORGOTTEN_DETAILS'] = 'Забыли ваши данные?';
$TEXT['FORGOT_DETAILS'] = 'Забыли данные?';
$TEXT['FROM'] = 'из';
$TEXT['FRONTEND'] = 'Внешний интерфейс';
$TEXT['FULL_NAME'] = 'Полное имя';
$TEXT['FUNCTION'] = 'Функция';
$TEXT['GROUP'] = 'Группа';
$TEXT['HEADER'] = 'Заголовок';
$TEXT['HEADING'] = 'Заголовок';
$TEXT['HEADING_ADD_USER'] = 'Добавить пользователя';
$TEXT['HEADING_MODIFY_USER'] = 'Редактировать пользователя';
$TEXT['HEADING_CSS_FILE'] = 'Имя файла: ';
$TEXT['HEIGHT'] = 'Высота';
$TEXT['HIDDEN'] = 'Скрытый(ая)';
$TEXT['HIDE'] = 'Спрятать';
$TEXT['HIDE_ADVANCED'] = 'Скрыть расширенные опции';
$TEXT['HOME'] = 'Главная';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Перенаправление на домашнюю страницу';
$TEXT['HOME_FOLDER'] = 'Личная папка';
$TEXT['HOME_FOLDERS'] = 'Личные папки';
$TEXT['HOST'] = 'Хост';
$TEXT['ICON'] = 'Иконка';
$TEXT['IMAGE'] = 'Картинку';
$TEXT['INLINE'] = 'Встроенная';
$TEXT['INSTALL'] = 'Добавить';
$TEXT['INSTALLATION'] = 'Установка';
$TEXT['INSTALLATION_PATH'] = 'Путь установки';
$TEXT['INSTALLATION_URL'] = 'URL установки';
$TEXT['INSTALLED'] = 'инсталлирован';
$TEXT['INTRO'] = 'Заставка';
$TEXT['INTRO_PAGE'] = 'Страница-заставка';
$TEXT['INVALID_SIGNS'] = 'должно начинаться с буквы или имеет недопустимые символы';
$TEXT['KEYWORDS'] = 'Ключевые слова';
$TEXT['LANGUAGE'] = 'Язык';
$TEXT['LAST_UPDATED_BY'] = 'Последнее обновление: ';
$TEXT['LENGTH'] = 'Длина';
$TEXT['LEVEL'] = 'Уровень';
$TEXT['LICENSE'] = 'Лицензия';
$TEXT['LINK'] = 'Ссылка';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Свойства списка';
$TEXT['LOGGED_IN'] = 'Вход осуществлен';
$TEXT['LOGIN'] = 'Вход';
$TEXT['LONG'] = 'Полностью';
$TEXT['LONG_TEXT'] = 'Текст полностью';
$TEXT['LOOP'] = 'основная часть';
$TEXT['MAIN'] = 'Главная';
$TEXT['MAINTENANCE_ON'] = 'Техническое обслуживание Вкл.';
$TEXT['MAINTENANCE_OFF'] = 'Техническое обслуживание Выкл.';
$TEXT['MANAGE'] = 'Управление:';
$TEXT['MANAGE_GROUPS'] = 'Управление группами';
$TEXT['MANAGE_USERS'] = 'Управление пользователями';
$TEXT['MATCH'] = 'Совпадения';
$TEXT['MATCHING'] = 'Совпадения';
$TEXT['MAX_EXCERPT'] = 'Макс. строк с результатами';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Макс. сообщений в час';
$TEXT['MEDIA_DIRECTORY'] = 'Директория файлов';
$TEXT['MENU'] = 'Меню';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon mouseover';
$TEXT['MENU_TITLE'] = 'Заголовок меню';
$TEXT['MESSAGE'] = 'Сообщение';
$TEXT['MODIFY'] = 'Изменить';
$TEXT['MODIFY_CONTENT'] = 'Изменить содержание';
$TEXT['MODIFY_SETTINGS'] = 'Изменить настройки';
$TEXT['MODULE_ORDER'] = 'Порядок модулей при поиске';
$TEXT['MODULE_PERMISSIONS'] = 'Права в модуле';
$TEXT['MORE'] = 'Больше';
$TEXT['MOVE_DOWN'] = 'Передвинуть вниз';
$TEXT['MOVE_UP'] = 'Передвинуть вверх';
$TEXT['MULTIPLE_MENUS'] = 'Мульти-меню';
$TEXT['MULTISELECT'] = 'Мульти-выбор';
$TEXT['NAME'] = 'Имя';
$TEXT['NEED_CURRENT_PASSWORD'] = 'подтвердить действующим паролем';
$TEXT['NEED_TO_LOGIN'] = 'Необходимо войти?';
$TEXT['NEW_PASSWORD'] = 'Новый пароль';
$TEXT['NEW_WINDOW'] = 'Новое окно';
$TEXT['NEXT'] = 'далее';
$TEXT['NEXT_PAGE'] = 'Следующая страница';
$TEXT['NO'] = 'Нет';
$TEXT['NONE'] = 'Нет';
$TEXT['NONE_FOUND'] = 'ничего не найдено';
$TEXT['NOT_FOUND'] = 'Не найдено';
$TEXT['NOT_INSTALLED'] = 'не инсталлировано';
$TEXT['NO_IMAGE_SELECTED'] = 'изображение не выбрано';
$TEXT['NO_RESULTS'] = 'Нет результатов';
$TEXT['OF'] = 'из';
$TEXT['ON'] = 'на';
$TEXT['OPEN'] = 'Открыть';
$TEXT['OPTION'] = 'Свойство';
$TEXT['OTHERS'] = 'Остальные';
$TEXT['OUT_OF'] = 'свыше';
$TEXT['OVERWRITE_EXISTING'] = 'Перезаписать существующий(ую)';
$TEXT['PAGE'] = 'Страница';
$TEXT['PAGES_DIRECTORY'] = 'Директория страниц';
$TEXT['PAGES_PERMISSION'] = 'Права на страницу';
$TEXT['PAGES_PERMISSIONS'] = 'Права на страницы';
$TEXT['PAGE_EXTENSION'] = 'Расширение страницы';
$TEXT['PAGE_ICON'] = 'Изображение страницы';
$TEXT['PAGE_ICON_DIR'] = 'Каталог для страниц/изображений меню';
$TEXT['PAGE_LANGUAGES'] = 'Язык страницы';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Уровень вложенности страниц';
$TEXT['PAGE_SPACER'] = 'Разделитель';
$TEXT['PAGE_TITLE'] = 'Заголовок страницы';
$TEXT['PAGE_TRASH'] = 'Корзина';
$TEXT['PARENT'] = 'Родитель';
$TEXT['PASSWORD'] = 'Пароль';
$TEXT['PATH'] = 'Путь';
$TEXT['PHP_ERROR_LEVEL'] = 'Уровень вывода ошибок PHP';
$TEXT['PLEASE_LOGIN'] = 'Пожалуйста выполните вход';
$TEXT['PLEASE_SELECT'] = 'Выберите';
$TEXT['POST'] = 'Отправить';
$TEXT['POSTS_PER_PAGE'] = 'Сообщений на страницу';
$TEXT['POST_FOOTER'] = 'Нижняя часть сообщения';
$TEXT['POST_HEADER'] = 'Заголовок сообщения';
$TEXT['PREVIOUS'] = 'назад';
$TEXT['PREVIOUS_PAGE'] = 'Предыдущая страница';
$TEXT['PRIVATE'] = 'Закрытая';
$TEXT['PRIVATE_VIEWERS'] = 'Могут просматривать личные страницы';
$TEXT['PROFILES_EDIT'] = 'Изменить профиль';
$TEXT['PUBLIC'] = 'Для общего доступа';
$TEXT['PUBL_END_DATE'] = 'Дата окончания';
$TEXT['PUBL_START_DATE'] = 'Дата старта';
$TEXT['QUICK_SEARCH_STRG_F'] = 'Нажмите <b>Strg + f</b> для быстрого поиска или используйте';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radio Button группа';
$TEXT['READ'] = 'Чтение';
$TEXT['READ_MORE'] = 'Читать дальше';
$TEXT['REDIRECT_AFTER'] = 'Время отображения указаний';
$TEXT['REGISTERED'] = 'Зарегистрированные';
$TEXT['REGISTERED_VIEWERS'] = 'Зарегистрированные пользователи';
$TEXT['RELOAD'] = 'Обновить';
$TEXT['REMAINING'] = 'осталось';
$TEXT['REMEMBER_ME'] = 'Запомнить меня';
$TEXT['RENAME'] = 'Переименовать';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'Не загружать эти типы фалов';
$TEXT['REQUIRED'] = 'Необходимые';
$TEXT['REQUIREMENT'] = 'Условие';
$TEXT['RESET'] = 'Сбросить';
$TEXT['RESIZE'] = 'Изменить размер';
$TEXT['RESIZE_IMAGE_TO'] = 'Изменять размер картинки на';
$TEXT['RESTORE'] = 'Восстановление';
$TEXT['RESTORE_DATABASE'] = 'Восстановить базу данных';
$TEXT['RESTORE_MEDIA'] = 'Восстановить Media';
$TEXT['RESULTS'] = 'Результаты';
$TEXT['RESULTS_FOOTER'] = 'Нижняя часть результатов';
$TEXT['RESULTS_FOR'] = 'Результаты для';
$TEXT['RESULTS_HEADER'] = 'Заголовок результатов';
$TEXT['RESULTS_LOOP'] = 'Основной блок';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Повторить новый пароль';
$TEXT['RETYPE_PASSWORD'] = 'Повторить пароль';
$TEXT['SAME_WINDOW'] = 'Текущее окно';
$TEXT['SAVE'] = 'Сохранить';
$TEXT['SEARCH'] = 'Поиск';
$TEXT['SEARCHING'] = 'Идет поиск';
$TEXT['SECTION'] = 'Секция';
$TEXT['SECTION_BLOCKS'] = 'Блоки секций';
$TEXT['SEC_ANCHOR'] = 'Якорь текстовой секции';
$TEXT['SELECT_BOX'] = 'Список выбора';
$TEXT['SEND_DETAILS'] = 'Послать данные';
$TEXT['SEND_TESTMAIL'] = 'Чтобы проверить, корректны ли настройки e-mail, здесь может быть отправлено тестовое e-mail сообщение на выше названный адрес. Обрати внимание, что изменения в настройках сначала должны быть сохранены.';
$TEXT['SEPARATE'] = 'Разделенный';
$TEXT['SEPERATOR'] = 'Разделитель';
$TEXT['SERVER_EMAIL'] = 'Email сервера';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'OS сервера';
$TEXT['SESSION_IDENTIFIER'] = 'Идентификатор сессии';
$TEXT['SETTINGS'] = 'Установки';
$TEXT['SHORT'] = 'Вкратце';
$TEXT['SHORT_TEXT'] = 'Краткий текст';
$TEXT['SHOW'] = 'Показать';
$TEXT['SHOW_ADVANCED'] = 'Показать расширенные опции';
$TEXT['SIGNUP'] = 'Регистрация';
$TEXT['SIZE'] = 'Размер';
$TEXT['SMART_LOGIN'] = 'Умный Login';
$TEXT['START'] = 'Старт';
$TEXT['START_PUBLISHING'] = 'Начать публикацию';
$TEXT['SUBJECT'] = 'Тема';
$TEXT['SUBMISSIONS'] = 'Подписки';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Сообщений, сохраняемых в базе';
$TEXT['SUBMISSION_ID'] = 'ID подписки';
$TEXT['SUBMITTED'] = 'Отправлено';
$TEXT['SUCCESS'] = 'Успешно';
$TEXT['SYSTEM_DEFAULT'] = 'По умолчанию';
$TEXT['SYSTEM_PERMISSIONS'] = 'Системные права';
$TEXT['TABLE_PREFIX'] = 'Префикс таблиц';
$TEXT['TARGET'] = 'Открывать в';
$TEXT['TARGET_FOLDER'] = 'Текущая папка';
$TEXT['TEMPLATE'] = 'Шаблон';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Права на шаблоны';
$TEXT['TEXT'] = 'Текст';
$TEXT['TEXTAREA'] = 'Длинный текст';
$TEXT['TEXTFIELD'] = 'Короткий текст';
$TEXT['THEME'] = 'Шаблон админки';
$TEXT['THEME_COPY_CURRENT'] = 'Скопировать шаблон админки';
$TEXT['THEME_NEW_NAME'] = 'Название нового шаблона админки';
$TEXT['THEME_CURRENT'] = 'Текущий шаблон админки';
$TEXT['THEME_START_COPY'] = 'Копировать';
$TEXT['THEME_IMPORT_HTT'] = 'Импортировать файлы шаблона';
$TEXT['THEME_SELECT_HTT'] = 'Выбрать файлы шаблона';
$TEXT['THEME_NOMORE_HTT'] = 'Больше нет';
$TEXT['THEME_START_IMPORT'] = 'Импортировать';
$TEXT['TIME'] = 'Время';
$TEXT['TIMEZONE'] = 'Временная зона';
$TEXT['TIME_FORMAT'] = 'Формат времени';
$TEXT['TIME_LIMIT'] = 'Max время поиска в модуле';
$TEXT['TITLE'] = 'Заголовок';
$TEXT['TO'] = 'в';
$TEXT['TOP_FRAME'] = 'Главный фрейм';
$TEXT['TRASH_EMPTIED'] = 'Корзина очищена';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Измените CSS файл, если необходимо:';
$TEXT['TYPE'] = 'Тип';
$TEXT['UNDER_CONSTRUCTION'] = 'В стадии разработки';
$TEXT['UNINSTALL'] = 'Удалить';
$TEXT['UNKNOWN'] = 'Неизвестно';
$TEXT['UNLIMITED'] = 'Неограниченно';
$TEXT['UNZIP_FILE'] = 'загрузить и распаковать zip архив';
$TEXT['UP'] = 'Вверх';
$TEXT['UPGRADE'] = 'Обновить';
$TEXT['UPLOAD_FILES'] = 'Закачать файл(ы)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Владелец';
$TEXT['USERNAME'] = 'Логин';
$TEXT['USERS_ACTIVE'] = 'Пользователь активен';
$TEXT['USERS_CAN_SELFDELETE'] = 'Разрешить самоудаление';
$TEXT['USERS_CHANGE_SETTINGS'] = 'Пользователь может изменять собственные настройки';
$TEXT['USERS_DELETED'] = 'Пользователь отмечен как удаленный';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'Пользователь может создать расширенный профиль';
$TEXT['VERIFICATION'] = 'Изображение на картинке';
$TEXT['VERSION'] = 'Версия';
$TEXT['VIEW'] = 'Просмотреть';
$TEXT['VIEW_DELETED_PAGES'] = 'Посмотреть удаленные страницы';
$TEXT['VIEW_DETAILS'] = 'Просмотреть детали';
$TEXT['VISIBILITY'] = 'Видимость';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Письмо от';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Имя отправителя';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Введите стандартный адрес и имя отправителя. В качестве e-mail адреса отправителя рекомендуется такой формат: <strong>admin@yourdomain.com</strong>. Стандартные значения будут использованы, если не будут установлены другие значения системой WBCE CMS либо инсталлированными модулями.';
$TEXT['WBMAILER_FUNCTION'] = 'Почтовая служба';
$TEXT['WBMAILER_NOTICE'] = '<strong>Настройки SMTP:</strong><br />Данные настройки необходимы только если Вы планируете отправлять письма, используя <abbr title="Simple mail transfer protocol">SMTP</abbr>. Если вы не знаете параметры Вашего SMTP сервера или не уверены в выборе, используйте PHP MAIL в качестве почтовой службы.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP авторизация';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'отметьте, если Ваш SMTP сервер требует авторизацию';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP сервер';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP пароль';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP логин';
$TEXT['WEBSITE'] = 'Вебсайт';
$TEXT['WEBSITE_DESCRIPTION'] = 'Описание сайта';
$TEXT['WEBSITE_FOOTER'] = 'Нижняя часть страниц сайта';
$TEXT['WEBSITE_HEADER'] = 'Заголовок сайта';
$TEXT['WEBSITE_KEYWORDS'] = 'Ключевые слова сайта';
$TEXT['WEBSITE_TITLE'] = 'Заголовок страниц сайта';
$TEXT['WELCOME_BACK'] = 'Добро пожаловать';
$TEXT['WIDTH'] = 'Ширина';
$TEXT['WINDOW'] = 'Окно';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Запись разрешена всем';
$TEXT['WRITE'] = 'Запись';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG редактор';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG стиль';
$TEXT['YES'] = 'Да';


$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On не отвечает требованиям';
$HEADING['ADD_CHILD_PAGE'] = 'Добавить дочернюю страницу';
$HEADING['ADD_GROUP'] = 'Добавить группу';
$HEADING['ADD_GROUPS'] = 'Добавить группы';
$HEADING['ADD_HEADING'] = 'Добавить заголовок';
$HEADING['ADD_PAGE'] = 'Добавить страницу';
$HEADING['ADD_USER'] = 'Добавить пользователя';
$HEADING['ADMINISTRATION_TOOLS'] = 'Средства администрирования';
$HEADING['BROWSE_MEDIA'] = 'Просмотреть файлы';
$HEADING['CREATE_FOLDER'] = 'Создать папку';
$HEADING['DEFAULT_SETTINGS'] = 'Настройки по умолчанию';
$HEADING['DELETED_PAGES'] = 'Удалить страницы';
$HEADING['FILESYSTEM_SETTINGS'] = 'Настройки файловой системы';
$HEADING['GENERAL_SETTINGS'] = 'Общие установки';
$HEADING['INSTALL_LANGUAGE'] = 'Установить язык';
$HEADING['INSTALL_MODULE'] = 'Установить модуль';
$HEADING['INSTALL_TEMPLATE'] = 'Установить шаблон';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Запустить языковые файлы вручную';
$HEADING['INVOKE_MODULE_FILES'] = 'Запустить файлы модуля вручную';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Запустить файлы шаблона вручную';
$HEADING['LANGUAGE_DETAILS'] = 'Свойства языка';
$HEADING['MANAGE_SECTIONS'] = 'Управление секциями';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Изменить расширенные настройки страницы';
$HEADING['MODIFY_DELETE_GROUP'] = 'Изменить/Удалить группу';
$HEADING['MODIFY_DELETE_PAGE'] = 'Изменить/Удалить страницу';
$HEADING['MODIFY_DELETE_USER'] = 'Изменить/Удалить пользователя';
$HEADING['MODIFY_GROUP'] = 'Изменить группу';
$HEADING['MODIFY_GROUPS'] = 'Изменить группы';
$HEADING['MODIFY_INTRO_PAGE'] = 'Изменить первую страницу';
$HEADING['MODIFY_PAGE'] = 'Изменить страницу';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Изменить настройки страницы';
$HEADING['MODIFY_USER'] = 'Изменить установки пользователя';
$HEADING['MODULE_DETAILS'] = 'Свойства модуля';
$HEADING['MY_EMAIL'] = 'Мой адрес e-mail';
$HEADING['MY_PASSWORD'] = 'Мой пароль';
$HEADING['MY_SETTINGS'] = 'Мои установки';
$HEADING['SEARCH_SETTINGS'] = 'Настройки поиска';
$HEADING['SERVER_SETTINGS'] = 'Настройки сервера';
$HEADING['TEMPLATE_DETAILS'] = 'Свойства шаблона';
$HEADING['UNINSTALL_LANGUAGE'] = 'Удалить язык';
$HEADING['UNINSTALL_MODULE'] = 'Удалить модуль';
$HEADING['UNINSTALL_TEMPLATE'] = 'Удалить шаблон';
$HEADING['UPGRADE_LANGUAGE'] = 'Зарегистрировать/обновить язык';
$HEADING['UPLOAD_FILES'] = 'Закачать файл(ы)';
$HEADING['WBMAILER_SETTINGS'] = 'Настройки почтовой программы';
$HEADING['WBMAILER_CFG_OVERRIDE_HINT'] = '<b>ОБРАТИТЕ ВНИМАНИЕ:</b> приведенные ниже '.$HEADING['WBMAILER_SETTINGS'].' будут перезаписаны настройками из файла  <code>[WB_PATH]/include/PHPMailer/config_mail.php</code> überschrieben.<br />'
                                        . 'Чтобы использовать приведенные ЗДЕСЬ НИЖЕ '.$HEADING['WBMAILER_SETTINGS'].' необходимо, чтобы настройки в указанном файле были удалены.';


$MESSAGE['ADDON_ERROR_RELOAD'] = 'Ошибка при обновлении информации об Add-On.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Языки успешно загружены';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ВНИМАНИЕ!</strong> В целях безопасности загружайте языковые файлы в папку /languages/ только по FTP и используйте функцию Upgrade для регистрации или обновления.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Предупреждение: возможно существующие записи базы данных модуля будут потеряны. ';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'Когда модули загружаются через FTP (не рекомендуется), функция установки модуля <code>install</code>, <code>upgrade</code> или <code>uninstall</code> не будет выполняться автоматически. Эти модули могут работать неправильно или не удаляться должным образом.<br /><br />Вы можете выполнить функции модуля вручную для модулей, загруженных через FTP ниже.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Предупреждение: возможно существующие записи базы данных модуля будут потеряны. Пожалуйста, используйте только в случае проблем с модулями, загруженными через FTP.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Предупреждение: возможно существующие записи базы данных модуля будут потеряны.';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Модули успешно загружены';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Переписать более новые файлы';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Не удалось осуществить инсталляцию Add-on. Ваша система не отвечает всем требованиям для этого Add-on. Чтобы использовать этот Add-on должно быть проведено следующие обновления.';
$MESSAGE['ADDON_RELOAD'] = 'Обновление базы данных информацией из Add-on файлов  (напр. после загрузки по FTP).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Шаблоны успешно загружены';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Недостаточно прав для нахождения в этой секции';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Извините, но вы можете менять пароль не чаще 1 раза в час';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Невозможно выслать пароль, обратитесь к вашему администратору';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Email, который вы ввели, не найден в базе';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Пожалуйста введите ваш email';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Ваше имя пользователя и пароль отправлены Вам на Ваш email адрес';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Извините, нет активных секций';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Извините, у вас нет прав для просмотра этой страницы';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Уже установлено';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Невозможно записать в выбранную директорию';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Пожалуйста подождите.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Невозможно удалить';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_CORE_MODULES'] = 'Невозможно деинсталлировать!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Невозможно удалить: файл используется';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> не может быть деинсталлировано, потому что используется на {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'этой странице;этих страницах';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Шаблон <strong>{{name}}</strong> не может быть удален, так как он используется как шаблон по умолчанию.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Шаблон <strong>{{name}}</strong> не может быть удален, так как он используется как шаблон админки по умолчанию.';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Невозможно разархивировать файл';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Невозможно загрузить файл';
$MESSAGE['GENERIC_COMPARE'] = ' успешно';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Ошибка открытия файла.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' неудачно';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Загружаемый файл должен иметь следующий формат:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Загружаемый файл должен иметь тип:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Пожалуйста вернитесь и заполните все поля';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'Ничего не выбрано!';
$MESSAGE['GENERIC_INSTALLED'] = 'Установка прошла успешна';
$MESSAGE['GENERIC_INVALID'] = 'Загруженный файл поврежден';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Некорректный инсталляционный файл. Проверьте *.zip формат.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Некорректный языковой файл.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Некорректный файл модуля.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Некорректный файл шаблона.';
$MESSAGE['GENERIC_IN_USE'] = ' используется в ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Отсутствующий файл архива!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'Модуль некорректно инсталлирован!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' невозможно';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Не инсталлировано';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Обновление невозможно';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Пожалуйста подождите, это может занять некоторое время.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Зайдите попозже...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Нарушение безопасности! Отказано в доступе!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Нарушение безопасности! Отказано в сохранении данных!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Удалено успешно';
$MESSAGE['GENERIC_UPGRADED'] = 'Обновление прошло успешно';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Сравнение версий';
$MESSAGE['GENERIC_VERSION_GT'] = 'Необходимо обновление!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Понижение версии';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Вебсайт в разработке';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'На сайте проводятся работы, поэтому сайт временно недоступен. ';
$MESSAGE['GROUP_HAS_MEMBERS'] = 'В этой группе есть участники.';
$MESSAGE['GROUPS_ADDED'] = 'Группа добавлена успешно';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Вы уверены, что вы хотите удалить выбранную группу (и пользователей в ней)?';
$MESSAGE['GROUPS_DELETED'] = 'Группа успешно удалена';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Имя группы пустое';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Группа с таким именем уже существует';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Групп не найдено';
$MESSAGE['GROUPS_SAVED'] = 'Группа сохранена успешно';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Неверные имя пользователя или пароль';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Введите имя пользователя и пароль';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Пожалуйста введите пароль';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Указанный пароль слишком длинный';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Указанный пароль слишком короткий';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Введите имя пользователя';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Введенный логин слишком длинный';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Введенный логин слишком короткий';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Вы не ввели расширение файла';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Вы не ввели новое имя';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Невозможно удалить выбранную папку';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Невозможно удалить выбранный файл';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Невозможно переименовать';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Вы уверены, что хотите удалить данный файл или папку?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Папка успешно удалена';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Файл успешно удален';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Указанный каталог не существует или нет прав доступа к нему.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Директория не существует';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Нельзя использовать ../ в имени папки';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Папка с таким именем уже существует';
$MESSAGE['MEDIA_DIR_MADE'] = 'Папка успешно создана';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Невозможно создать папку';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Файл с таким именем уже существует';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Файл не найден';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Нельзя использовать ../ в имени';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Нельзя использовать index.php в качестве имени';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'Файл не был получен';
$MESSAGE['MEDIA_NONE_FOUND'] = 'В данной папке нет файлов';
$MESSAGE['MEDIA_RENAMED'] = 'Успешно переименовано';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' файл успешно закачан';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Нельзя использовать ../ в имени';
$MESSAGE['MEDIA_UPLOADED'] = ' файлы успешно закачаны';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Извините, слишком много сообщений за последний час. Пожалуйста попробуйте повторить отправку через некоторое время.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Код подтверждения введен неверно. Если вы не можете прочесть код, пожалуйста сообщите разработчикам: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'ВНИМАНИЕ! Вы должны заполнить поле';
$MESSAGE['PAGES_ADDED'] = 'Страница успешно добавлена';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Заголовок страницы успешно добавлен';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Введите заголовок меню';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Введите заголовок страницы';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Ошибка создания файла в папке /pages  (недостаточно прав)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Ошибка удаления файла в папке /pages  (недостаточно прав)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Ошибка изменения порядка страниц';
$MESSAGE['PAGES_DELETED'] = 'Страница успешно удалена';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Вы уверены, что хотите удалить выбранную страницу (и все её подразделы)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'У вас нет прав для изменения этой страницы';
$MESSAGE['PAGES_INTRO_LINK'] = 'Нажмите ЗДЕСЬ для изменения страницы-заставки';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Невозможно записать в /pages/intro.php (недостаточно прав)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Страница-заставка успешно сохранена';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Последнее изменение:';
$MESSAGE['PAGES_NOT_FOUND'] = 'Страница не найдена';
$MESSAGE['PAGES_NOT_SAVED'] = 'Ошибка сохранения страницы';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Страница с таким или схожим именем уже существует';
$MESSAGE['PAGES_REORDERED'] = 'Порядок страниц изменен';
$MESSAGE['PAGES_RESTORED'] = 'Страница успешно восстановлена';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'К списку страниц';
$MESSAGE['PAGES_SAVED'] = 'Страница успешно сохранена';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Свойства страницы успешно сохранены';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Свойства секции успешно сохранены';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Текущий пароль, который вы ввели, неправильный';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Данные сохранены успешно';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Email обновлен успешно';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Недопустимые символы в пароле';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Пароль изменен успешно';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'Не удалось изменить запись';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'Запись успешно изменена.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Не удалось добавить новую запись';
$MESSAGE['RECORD_NEW_SAVED'] = 'Новая запись успешно добавлена.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Имейте ввиду, что при нажатии на эту кнопку произойдет сброс не сохраненных данных';
$MESSAGE['SETTINGS_SAVED'] = 'Установки сохранены успешно';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Невозможно открыть конфигурационный файл';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Невозможна запись в конфигурационный файл';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Имейте ввиду, что это рекомендовано только для тестирования';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
Зарегистрирован новый пользователь.

Loginname: {LOGIN_NAME}
UserId: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
IP-Adress: {LOGIN_IP}
Дата входа: {SIGNUP_DATE}

----------------------------------------
Это e-mail сообщение было создано автоматически!';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Добрый день {LOGIN_DISPLAY_NAME},

Вы получили это сообщение, потому что Вы запросили новый пароль.

Ваши новые данные регистрации для {LOGIN_WEBSITE_TITLE} таковы:

Логин: {LOGIN_NAME}
Пароль: {LOGIN_PASSWORD}

Старый пароль заменен на новый.
Это означает, что старый пароль больше не действует.
Если у Вас возникнут вопросы или проблемы с новыми данными доступа
обратитесь к веб-администратору сайта  {LOGIN_WEBSITE_TITLE}.
Прежде чем использовать новый пароль очистите кэш браузера, чтобы избежать непредвиденных проблем.

------------------------------------
Это e-mail сообщение было создано автоматически!';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Добрый день {LOGIN_DISPLAY_NAME},

Добро пожаловать на сайт {LOGIN_WEBSITE_TITLE}

Ваши данные доступа для сайта {LOGIN_WEBSITE_TITLE}:
Логин: {LOGIN_NAME}
Пароль: {LOGIN_PASSWORD}

Примечание:
Если Вы получили это сообщение без Вашего участия, пожалуйста удалить его.

-------------------------------------
Это e-mail сообщение было создано автоматически!';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Ваши данные доступа...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Вы должны ввести email адрес';
$MESSAGE['START_CURRENT_USER'] = 'Вы вошли как:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Внимание, инсталляционная директория все еще не удалена!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Удалите файл "upgrade-script.php".';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Добро пожаловать в Меню Администрирования Сайта';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Внимание! Чтобы чтобы сменить шаблон перейдите в раздел "Установки"';
$MESSAGE['TESTMAIL_SUCCESS'] = "Тестовое e-mail сообщение отправлено <code>%s</code>. Проверьте почту.";
$MESSAGE['TESTMAIL_FAILURE'] = "Тестовое e-mail сообщение получателю <code>%s</code> не доставлено.<br />Проверьте настройки e-mail и попробуйте еще раз.";
$MESSAGE['THEME_COPY_CURRENT'] = 'Копировать текущий шаблон админки и сохранить его под другим именем';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'Такое имя шаблона уже существует.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Недопустимое имя шаблона';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'Каталог не может быть создан';
$MESSAGE['THEME_IMPORT_HTT'] = 'Дополнительные файлы шаблона импортировать в существующий шаблон.<br />Этот шаблон может перезаписать шаблоны по умолчанию.';
$MESSAGE['UPLOAD_ERR_OK'] = 'Файл успешно загружен';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'Размер загружаемого файла превышает лимит, установленный в php.ini';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'Размер загружаемого файла превышает максимальный размер';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'Файл загружен только частично';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'Файл не загружен';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Отсутствует временный каталог';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Нет прав на запись файла на диск';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'Недопустимое расширение файла';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Неизвестная ошибка при загрузке';
$MESSAGE['USERS_ADDED'] = 'Пользователь добавлен успешно';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Отказано, Вы не можете удалить сами себя!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Имейте ввиду, что вам следует ввести значения только в верхних полях если вы хотите изменить пароль';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Вы уверены, что хотите удалить выбранного пользователя?';
$MESSAGE['USERS_DELETED'] = 'Пользователь был успешно удален';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Email, который вы ввели, уже есть в базе';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Вы ввели неправильный email';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Для логина применяются недопустимые символы';
$MESSAGE['USERS_NO_GROUP'] = 'Не одной группы не было выбрано';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Пароли, которые вы ввели, не совпадают';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Пароль, который был введен, слишком короток';
$MESSAGE['USERS_SAVED'] = 'Данные о пользователе сохранены успешно';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'Такой логин уже существует';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'Введенный логин слишком короткий';


$OVERVIEW['ADMINTOOLS'] = 'Доступ к средствам администрирования';
$OVERVIEW['GROUPS'] = 'Управление группами пользователей и права доступа';
$OVERVIEW['HELP'] = 'Ответы на вопросы';
$OVERVIEW['LANGUAGES'] = 'Управление языковыми пакетами';
$OVERVIEW['MEDIA'] = 'Управление файлами в папке media';
$OVERVIEW['MODULES'] = 'Управлениями модулями';
$OVERVIEW['PAGES'] = 'Управление страницами сайта';
$OVERVIEW['PREFERENCES'] = 'Изменить настройки, такие как адрес e-mail, пароль...';
$OVERVIEW['SETTINGS'] = 'Управление настройками WBCE';
$OVERVIEW['START'] = 'Стартовая страница админки';
$OVERVIEW['TEMPLATES'] = 'Управление шаблонами';
$OVERVIEW['USERS'] = 'Управление пользователями';
$OVERVIEW['VIEW'] = 'Посмотреть изменения на сайте в новом окне';
