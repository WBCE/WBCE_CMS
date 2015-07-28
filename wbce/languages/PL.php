<?php
/**
 *
 * @category        framework
 * @package         language
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: PL.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/PL.php $
 * @lastmodified    $Date: 2012-03-09 15:30:29 +0100 (Fr, 09. Mrz 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Define that this file is loaded
if(!defined('LANGUAGE_LOADED')) {
define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_code = 'PL';
$language_name = 'Polski';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Marek Stepien;';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Dostep';
$MENU['ADDON'] = 'Dodatek';
$MENU['ADDONS'] = 'Dodatki';
$MENU['ADMINTOOLS'] = 'Narzedzia admina';
$MENU['BREADCRUMB'] = 'Jestes tu: ';
$MENU['FORGOT'] = 'Odzyskaj dane logowania';
$MENU['GROUP'] = 'Groupa';
$MENU['GROUPS'] = 'Grupy';
$MENU['HELP'] = 'Pomoc';
$MENU['LANGUAGES'] = 'Jezyki';
$MENU['LOGIN'] = 'Zaloguj sie';
$MENU['LOGOUT'] = 'Wyloguj';
$MENU['MEDIA'] = 'Media';
$MENU['MODULES'] = 'Moduly';
$MENU['PAGES'] = 'Strony';
$MENU['PREFERENCES'] = 'Preferencje';
$MENU['SETTINGS'] = 'Ustawienia';
$MENU['START'] = 'Poczatek';
$MENU['TEMPLATES'] = 'Szablony';
$MENU['USERS'] = 'Uzytkownicy';
$MENU['VIEW'] = 'Podglad';
$TEXT['ACCOUNT_SIGNUP'] = 'Nowe konto';
$TEXT['ACTIONS'] = 'Czynnosci';
$TEXT['ACTIVE'] = 'Aktywne';
$TEXT['ADD'] = 'Dodaj';
$TEXT['ADDON'] = 'Dodatek';
$TEXT['ADD_SECTION'] = 'Dodaj sekcji;';
$TEXT['ADMIN'] = 'Administrator';
$TEXT['ADMINISTRATION'] = 'Administracja';
$TEXT['ADMINISTRATION_TOOL'] = 'Narzedzie administracyjne';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administratorzy';
$TEXT['ADVANCED'] = 'Zaawansowane';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Dozwolone pliki do uploadu';
$TEXT['ALLOWED_VIEWERS'] = 'Dozwoleni obserwatorzy';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Pozwól na wielokrotne wybory';
$TEXT['ALL_WORDS'] = 'Wszystkie slowa';
$TEXT['ANCHOR'] = 'Kotwica';
$TEXT['ANONYMOUS'] = 'Anonimowy';
$TEXT['ANY_WORDS'] = 'Dowolne ze slów';
$TEXT['APP_NAME'] = 'Nazwa aplikacji';
$TEXT['ARE_YOU_SURE'] = 'Czy aby na pewno?';
$TEXT['AUTHOR'] = 'Autor';
$TEXT['BACK'] = 'Wstecz';
$TEXT['BACKUP'] = 'Kopia zapasowa';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup all tables in database';
$TEXT['BACKUP_DATABASE'] = 'Kopia zapasowa bazy danych';
$TEXT['BACKUP_MEDIA'] = 'Kopia zapasowa mediów';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Kopia zapasowa tylko tabel WB_';
$TEXT['BASIC'] = 'Podstawowe';
$TEXT['BLOCK'] = 'Blokuj';
$TEXT['CALENDAR'] = 'Calentarz';
$TEXT['CANCEL'] = 'Anuluj';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Weryfikacja Captcha';
$TEXT['CAP_EDIT_CSS'] = 'Edytuj CSS';
$TEXT['CHANGE'] = 'Zmien;';
$TEXT['CHANGES'] = 'Zmiany';
$TEXT['CHANGE_SETTINGS'] = 'Zmien ustawienia';
$TEXT['CHARSET'] = 'Kodowanie znaków';
$TEXT['CHECKBOX_GROUP'] = 'Grupa pól zaznaczanych';
$TEXT['CLOSE'] = 'Zamknij';
$TEXT['CODE'] = 'Kod';
$TEXT['CODE_SNIPPET'] = 'Kod snippeta';
$TEXT['COLLAPSE'] = 'Zwin;';
$TEXT['COMMENT'] = 'Komentarz';
$TEXT['COMMENTING'] = 'Komentowanie';
$TEXT['COMMENTS'] = 'Komentarze';
$TEXT['CREATE_FOLDER'] = 'Utwórz folder';
$TEXT['CURRENT'] = 'Biezacy';
$TEXT['CURRENT_FOLDER'] = 'Biezacy folder';
$TEXT['CURRENT_PAGE'] = 'Biezaca strona';
$TEXT['CURRENT_PASSWORD'] = 'Biezace haslo';
$TEXT['CUSTOM'] = 'Wlasny';
$TEXT['DATABASE'] = 'Baza danych';
$TEXT['DATE'] = 'Data';
$TEXT['DATE_FORMAT'] = 'Format daty';
$TEXT['DEFAULT'] = 'Domyslne';
$TEXT['DEFAULT_CHARSET'] = 'Domyslne kodowanie znaków';
$TEXT['DEFAULT_TEXT'] = 'Domyslny tekst';
$TEXT['DELETE'] = 'Usun;';
$TEXT['DELETED'] = 'Usuniete';
$TEXT['DELETE_DATE'] = 'Usun date';
$TEXT['DELETE_ZIP'] = 'Usun archiwum zip po rozpakowaniu';
$TEXT['DESCRIPTION'] = 'Opis';
$TEXT['DESIGNED_FOR'] = 'Zaprojektowane dla';
$TEXT['DIRECTORIES'] = 'Katalogi';
$TEXT['DIRECTORY_MODE'] = 'Tryb katalogów';
$TEXT['DISABLED'] = 'Wylaczone';
$TEXT['DISPLAY_NAME'] = 'Nazwa';
$TEXT['EMAIL'] = 'E-mail';
$TEXT['EMAIL_ADDRESS'] = 'Adres e-mail';
$TEXT['EMPTY_TRASH'] = 'Opróznij smietnik';
$TEXT['ENABLED'] = 'Wlaczone';
$TEXT['END'] = 'Koniec';
$TEXT['ERROR'] = 'Blad';
$TEXT['EXACT_MATCH'] = 'Dopasowanie dokladne';
$TEXT['EXECUTE'] = 'Wykonaj';
$TEXT['EXPAND'] = 'Rozwin';
$TEXT['EXTENSION'] = 'Rozszerzenie';
$TEXT['FIELD'] = 'Pole';
$TEXT['FILE'] = 'plik';
$TEXT['FILES'] = 'pliki';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Uprawnienia systemu plików';
$TEXT['FILE_MODE'] = 'Tryb plikw';
$TEXT['FINISH_PUBLISHING'] = 'Zakoncz publikowanie';
$TEXT['FOLDER'] = 'folder';
$TEXT['FOLDERS'] = 'foldery';
$TEXT['FOOTER'] = 'Stopka';
$TEXT['FORGOTTEN_DETAILS'] = 'Zapomniales(-as) hasla?';
$TEXT['FORGOT_DETAILS'] = 'Zapomniales(-as) szczególów?';
$TEXT['FROM'] = 'Od';
$TEXT['FRONTEND'] = 'Panel przedni';
$TEXT['FULL_NAME'] = 'Imie i nazwisko';
$TEXT['FUNCTION'] = 'Funkcja';
$TEXT['GROUP'] = 'Grupa';
$TEXT['HEADER'] = 'Naglówek';
$TEXT['HEADING'] = 'Naglówek';
$TEXT['HEADING_CSS_FILE'] = 'Aktualny plik modulu: ';
$TEXT['HEIGHT'] = 'Wysokosc;';
$TEXT['HIDDEN'] = 'Ukryty';
$TEXT['HIDE'] = 'Schowaj';
$TEXT['HIDE_ADVANCED'] = 'Schowaj opcje zaawansowane';
$TEXT['HOME'] = 'Strona domowa';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Przekierowanie strony domowej';
$TEXT['HOME_FOLDER'] = 'Osobisty Folder';
$TEXT['HOME_FOLDERS'] = 'Osobiste Foldery';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Ikona';
$TEXT['IMAGE'] = 'Obrazek';
$TEXT['INLINE'] = 'Inline';
$TEXT['INSTALL'] = 'Zainstaluj';
$TEXT['INSTALLATION'] = 'Instalacja';
$TEXT['INSTALLATION_PATH'] = 'Sciezka instalacji';
$TEXT['INSTALLATION_URL'] = 'URL instalacji';
$TEXT['INSTALLED'] = 'zainstalowano';
$TEXT['INTRO'] = 'Intro';
$TEXT['INTRO_PAGE'] = 'Strona wprowadzajaca';
$TEXT['INVALID_SIGNS'] = 'musi zaczynac sie od litery badz niedozwolonych znaków';
$TEXT['KEYWORDS'] = 'slow kluczowe';
$TEXT['LANGUAGE'] = 'Jezyk';
$TEXT['LAST_UPDATED_BY'] = 'Ostatnio zmienione przez';
$TEXT['LENGTH'] = 'Dlugosc';
$TEXT['LEVEL'] = 'Poziom';
$TEXT['LINK'] = 'Odnosnik';
$TEXT['LINUX_UNIX_BASED'] = 'oparty na Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Listuj opcje';
$TEXT['LOGGED_IN'] = 'Zalogowany';
$TEXT['LOGIN'] = 'Zaloguj';
$TEXT['LONG'] = 'Dlugi';
$TEXT['LONG_TEXT'] = 'Dlugi tekst';
$TEXT['LOOP'] = 'Petla';
$TEXT['MAIN'] = 'Glówny';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Zarzadzaj';
$TEXT['MANAGE_GROUPS'] = 'Zarzadzaj grupami';
$TEXT['MANAGE_USERS'] = 'Zarzadzaj uzytkownikami';
$TEXT['MATCH'] = 'Dopasuj';
$TEXT['MATCHING'] = 'Pasujace';
$TEXT['MAX_EXCERPT'] = 'Maksymalny fragment linii';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Maks. zgloszen na godzine';
$TEXT['MEDIA_DIRECTORY'] = 'Katalog mediów';
$TEXT['MENU'] = 'Menu';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Tytul menu';
$TEXT['MESSAGE'] = 'Wiadomosc';
$TEXT['MODIFY'] = 'Zmien';
$TEXT['MODIFY_CONTENT'] = 'Zmien zawartosc';
$TEXT['MODIFY_SETTINGS'] = 'Zmien ustawienia';
$TEXT['MODULE_ORDER'] = 'Modul- kolejnosc wyszukiwania';
$TEXT['MODULE_PERMISSIONS'] = 'Uprawnienia do modulów';
$TEXT['MORE'] = 'Wiecej';
$TEXT['MOVE_DOWN'] = 'W dól';
$TEXT['MOVE_UP'] = 'Do góry';
$TEXT['MULTIPLE_MENUS'] = 'Wielokrotne menu';
$TEXT['MULTISELECT'] = 'Wybór wielokrotny';
$TEXT['NAME'] = 'Nazwa';
$TEXT['NEED_CURRENT_PASSWORD'] = 'potwierdz obecne haslo';
$TEXT['NEED_TO_LOGIN'] = 'Potrzebujesz sie zalogowac?';
$TEXT['NEW_PASSWORD'] = 'Nowe haslo';
$TEXT['NEW_WINDOW'] = 'Nowe okno';
$TEXT['NEXT'] = 'Nastepny';
$TEXT['NEXT_PAGE'] = 'Nastepna strona';
$TEXT['NO'] = 'Nie';
$TEXT['NONE'] = 'Brak';
$TEXT['NONE_FOUND'] = 'Nie odnaleziono';
$TEXT['NOT_FOUND'] = 'Nie odnaleziono';
$TEXT['NOT_INSTALLED'] = 'nie zainstalowano';
$TEXT['NO_IMAGE_SELECTED'] = 'nie wybrano obrazu';
$TEXT['NO_RESULTS'] = 'Brak wyników';
$TEXT['OF'] = 'z';
$TEXT['ON'] = 'dnia';
$TEXT['OPEN'] = 'Otwórz';
$TEXT['OPTION'] = 'Opcja';
$TEXT['OTHERS'] = 'Inni';
$TEXT['OUT_OF'] = 'z poza';
$TEXT['OVERWRITE_EXISTING'] = 'Nadpisz istniejacy(-e)';
$TEXT['PAGE'] = 'Strona';
$TEXT['PAGES_DIRECTORY'] = 'Katalog stron';
$TEXT['PAGES_PERMISSION'] = 'Prawa do strony';
$TEXT['PAGES_PERMISSIONS'] = 'Prawa do stron';
$TEXT['PAGE_EXTENSION'] = 'Rozszerzenie strony';
$TEXT['PAGE_ICON'] = 'Obrazek strony';
$TEXT['PAGE_ICON_DIR'] = 'Sciezka stron/obrazki menu';
$TEXT['PAGE_LANGUAGES'] = 'Jezyki stron';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Limit poziomów stron';
$TEXT['PAGE_SPACER'] = 'Odstep strony';
$TEXT['PAGE_TITLE'] = 'Tytul strony';
$TEXT['PAGE_TRASH'] = 'Smietnik stron';
$TEXT['PARENT'] = 'Nadrzedny';
$TEXT['PASSWORD'] = 'Haslo';
$TEXT['PATH'] = 'Sciezka';
$TEXT['PHP_ERROR_LEVEL'] = 'Poziom raportowania bledów PHP';
$TEXT['PLEASE_LOGIN'] = 'Podaj login';
$TEXT['PLEASE_SELECT'] = 'Prosze wybrac';
$TEXT['POST'] = 'Wiadomosc';
$TEXT['POSTS_PER_PAGE'] = 'Wiadomosci na strone';
$TEXT['POST_FOOTER'] = 'Stopka wiadomosci';
$TEXT['POST_HEADER'] = 'Naglówek wiadomosci';
$TEXT['PREVIOUS'] = 'Poprzedni';
$TEXT['PREVIOUS_PAGE'] = 'Poprzednia strona';
$TEXT['PRIVATE'] = 'Prywatna';
$TEXT['PRIVATE_VIEWERS'] = 'Prywatni obserwatorzy';
$TEXT['PROFILES_EDIT'] = 'Zmien profil';
$TEXT['PUBLIC'] = 'Publiczna';
$TEXT['PUBL_END_DATE'] = 'Data zakonczenia';
$TEXT['PUBL_START_DATE'] = 'Data rozpoczecia';
$TEXT['RADIO_BUTTON_GROUP'] = 'Grupa pól przelaczanych';
$TEXT['READ'] = 'Odczytaj';
$TEXT['READ_MORE'] = 'Czytaj dalej';
$TEXT['REDIRECT_AFTER'] = 'Przekierowanie po';
$TEXT['REGISTERED'] = 'Zarejestrowany';
$TEXT['REGISTERED_VIEWERS'] = 'Zarejestrowani obserwatorzy';
$TEXT['RELOAD'] = 'Przeladuj, odswiez';
$TEXT['REMEMBER_ME'] = 'Zapamietaj mnie';
$TEXT['RENAME'] = 'Zmien nazwe';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Wymagany';
$TEXT['REQUIREMENT'] = 'Wymagania';
$TEXT['RESET'] = 'Resetuj';
$TEXT['RESIZE'] = 'Zmien rozmiar';
$TEXT['RESIZE_IMAGE_TO'] = 'Zmien rozmiar obrazków na';
$TEXT['RESTORE'] = 'Przywróc';
$TEXT['RESTORE_DATABASE'] = 'Przywróc baze danych';
$TEXT['RESTORE_MEDIA'] = 'Przywróc media';
$TEXT['RESULTS'] = 'Wyniki';
$TEXT['RESULTS_FOOTER'] = 'Stopka wyników';
$TEXT['RESULTS_FOR'] = 'Wyniki dla';
$TEXT['RESULTS_HEADER'] = 'Naglówek wyników';
$TEXT['RESULTS_LOOP'] = 'Petla wyników';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Powtórz nowe haslo';
$TEXT['RETYPE_PASSWORD'] = 'Powtórz haslo';
$TEXT['SAME_WINDOW'] = 'To samo okno';
$TEXT['SAVE'] = 'Zapisz';
$TEXT['SEARCH'] = 'Szukaj';
$TEXT['SEARCHING'] = 'Wyszukiwanie';
$TEXT['SECTION'] = 'Sekcja';
$TEXT['SECTION_BLOCKS'] = 'Bloki sekcji';
$TEXT['SEC_ANCHOR'] = 'Przedrostek tabeli (prefix)';
$TEXT['SELECT_BOX'] = 'Pole wyboru';
$TEXT['SEND_DETAILS'] = 'Wyslij szczególy';
$TEXT['SEPARATE'] = 'Osobno';
$TEXT['SEPERATOR'] = 'Separator';
$TEXT['SERVER_EMAIL'] = 'E-mail serwera';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'System operacyjny serwera';
$TEXT['SESSION_IDENTIFIER'] = 'Identyfikator sesji';
$TEXT['SETTINGS'] = 'Ustawienia';
$TEXT['SHORT'] = 'Krótki';
$TEXT['SHORT_TEXT'] = 'Krótki tekst';
$TEXT['SHOW'] = 'Wyswietl';
$TEXT['SHOW_ADVANCED'] = 'Wyswietl opcje zaawansowane';
$TEXT['SIGNUP'] = 'Zapisz sie';
$TEXT['SIZE'] = 'Rozmiar';
$TEXT['SMART_LOGIN'] = 'Inteligentne logowanie';
$TEXT['START'] = 'Start';
$TEXT['START_PUBLISHING'] = 'Rozpocznij publikowanie';
$TEXT['SUBJECT'] = 'Temat';
$TEXT['SUBMISSIONS'] = 'Zgloszenia';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Zgloszenia przechowywane w bazie danych';
$TEXT['SUBMISSION_ID'] = 'ID zgloszenia';
$TEXT['SUBMITTED'] = 'Zgloszone';
$TEXT['SUCCESS'] = 'Sukces';
$TEXT['SYSTEM_DEFAULT'] = 'Domyslne ustawienia systemu';
$TEXT['SYSTEM_PERMISSIONS'] = 'Uprawnienia systemowe';
$TEXT['TABLE_PREFIX'] = 'Przedrostek tabeli';
$TEXT['TARGET'] = 'Cel';
$TEXT['TARGET_FOLDER'] = 'Folder docelowy';
$TEXT['TEMPLATE'] = 'Szablon';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Uprawnienia szablonów';
$TEXT['TEXT'] = 'Tekst';
$TEXT['TEXTAREA'] = 'Obszar tekstowy';
$TEXT['TEXTFIELD'] = 'Pole tekstowe';
$TEXT['THEME'] = 'Szablon panelu administracji';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Czas';
$TEXT['TIMEZONE'] = 'Strefa czasowa';
$TEXT['TIME_FORMAT'] = 'Format czasu';
$TEXT['TIME_LIMIT'] = 'Maksymalny czas potrzebny na fragment modulu';
$TEXT['TITLE'] = 'Tytul';
$TEXT['TO'] = 'Do';
$TEXT['TOP_FRAME'] = 'Glówna ramka';
$TEXT['TRASH_EMPTIED'] = 'Smietnik opróniony';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edycja CSS w polu tekstowym ponizej.';
$TEXT['TYPE'] = 'Rodzaj';
$TEXT['UNDER_CONSTRUCTION'] = 'W trakcie tworzenia';
$TEXT['UNINSTALL'] = 'Odinstaluj';
$TEXT['UNKNOWN'] = 'Nieznany';
$TEXT['UNLIMITED'] = 'Nieograniczony';
$TEXT['UNZIP_FILE'] = 'Wrzuc i rozpakuj archiwum';
$TEXT['UP'] = 'Góra';
$TEXT['UPGRADE'] = 'Aktualizuj';
$TEXT['UPLOAD_FILES'] = 'Zaladuj plik(i)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Uzytkownik';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'Aktywni uzytkownicy';
$TEXT['USERS_CAN_SELFDELETE'] = 'Uzytkownik moze usunac sie sam';
$TEXT['USERS_CHANGE_SETTINGS'] = 'Uzytkownik moze zmienic swoje ustawienia';
$TEXT['USERS_DELETED'] = 'Uzytkownicy usunieci';
$TEXT['USERS_FLAGS'] = 'Flagi uzytkowników';
$TEXT['USERS_PROFILE_ALLOWED'] = 'Uzytkownik moze tworzyc profil rozszerzony';
$TEXT['VERIFICATION'] = 'Weryfikacja';
$TEXT['VERSION'] = 'Wersja';
$TEXT['VIEW'] = 'Widok';
$TEXT['VIEW_DELETED_PAGES'] = 'Wyswietl usuniete strony';
$TEXT['VIEW_DETAILS'] = 'Pokaz szczególy';
$TEXT['VISIBILITY'] = 'Widocznosc';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Domyslny mail nadawcy';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Domyslna nazwa nadawcy';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Okresl domyslny adres odbiorcy "FROM" i nadawcy "SENDER". Zaleca sie stosowanie ODBIORCY tak jak na przykladzie: <strong>admin@yourdomain.com</strong>. Niektórzy dostawcy maili (np. <em>mail.com</em>) moga odrzucic maile od ODBIORCY adresu takiego jak np <em>name@mail.com</em> ze wzgledu na potraktowanie tego jako spam.<br /><br /> Wartosci domyslne sa uzywane tylko wtedy inne wartosci sa okreslone przez WebsiteBakera. Jesli twój serwer obsluguje <acronym title="Prosty protokól przesylania poczty">SMTP</acronym>, mozesz skorzystac z tej funkcji.';
$TEXT['WBMAILER_FUNCTION'] = 'Funkcja maila';
$TEXT['WBMAILER_NOTICE'] = '<strong>Ustawienia poczty SMTP:</strong><br />The settings below are only required if you want to send mails via <acronym title="Simple mail transfer protocol">SMTP</acronym>. If you do not know your SMTP host or you are not sure about the required settings, simply stay with the default mail routine: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'mail PHP';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'Weryfikacja SMTP';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'Aktywuj tylko jesli serwer wymaga uwierzytelnienia SMTP';
$TEXT['WBMAILER_SMTP_HOST'] = ' Host SMTP';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'Haslo poczty SMTP';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Witryna';
$TEXT['WEBSITE_DESCRIPTION'] = 'Opis witryny';
$TEXT['WEBSITE_FOOTER'] = 'Stopka witryny';
$TEXT['WEBSITE_HEADER'] = 'Naglówek witryny';
$TEXT['WEBSITE_KEYWORDS'] = 'Slowa kluczowe witryny';
$TEXT['WEBSITE_TITLE'] = 'Tytul witryny';
$TEXT['WELCOME_BACK'] = 'Witamy ponownie';
$TEXT['WIDTH'] = 'Szerokosc';
$TEXT['WINDOW'] = 'Okno';
$TEXT['WINDOWS'] = 'Okna';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Uprawnienia zapisywania plików przez wszystkich';
$TEXT['WRITE'] = 'Zapisz';
$TEXT['WYSIWYG_EDITOR'] = 'Edytor WYSIWYG';
$TEXT['WYSIWYG_STYLE'] = 'Styl WYSIWYG';
$TEXT['YES'] = 'Tak';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Wymagania dodatku nie zostaly spelnione';
$HEADING['ADD_CHILD_PAGE'] = 'Dodaj strone dziecko"';
$HEADING['ADD_GROUP'] = 'Dodaj grupe';
$HEADING['ADD_GROUPS'] = 'Dodak grupy';
$HEADING['ADD_HEADING'] = 'Dodaj naglówek';
$HEADING['ADD_PAGE'] = 'Dodaj strone';
$HEADING['ADD_USER'] = 'Dodaj uzytkownika';
$HEADING['ADMINISTRATION_TOOLS'] = 'Narzedzia administracyjne';
$HEADING['BROWSE_MEDIA'] = 'Przegladaj media';
$HEADING['CREATE_FOLDER'] = 'Utwórz folder';
$HEADING['DEFAULT_SETTINGS'] = 'Ustawienia domyslne';
$HEADING['DELETED_PAGES'] = 'Usuniete strony';
$HEADING['FILESYSTEM_SETTINGS'] = 'Ustawienia systemu plików';
$HEADING['GENERAL_SETTINGS'] = 'Ustawienia ogólne';
$HEADING['INSTALL_LANGUAGE'] = 'Zainstaluj jezyk';
$HEADING['INSTALL_MODULE'] = 'Zainstaluj moduól';
$HEADING['INSTALL_TEMPLATE'] = 'Zainstaluj szablon';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Uaktywnij pliki jezykowe recznie';
$HEADING['INVOKE_MODULE_FILES'] = 'Uaktywnij pliki modulów recznie';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Uaktywnij pliki szablonów recznie';
$HEADING['LANGUAGE_DETAILS'] = 'Szczególy jezyka';
$HEADING['MANAGE_SECTIONS'] = 'Zarzadzaj sekcjami';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Zmien zaawansowane ustawienia strony';
$HEADING['MODIFY_DELETE_GROUP'] = 'Zmien/usun grupe';
$HEADING['MODIFY_DELETE_PAGE'] = 'Zmien/Usun strone';
$HEADING['MODIFY_DELETE_USER'] = 'Zmien/usun uzytkownika';
$HEADING['MODIFY_GROUP'] = 'Zmien grupe';
$HEADING['MODIFY_GROUPS'] = 'Zmien grupy';
$HEADING['MODIFY_INTRO_PAGE'] = 'Zmien strone poczatkowa';
$HEADING['MODIFY_PAGE'] = 'Zmien strone';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Zmien ustawienia strony';
$HEADING['MODIFY_USER'] = 'Zmien uzytkownika';
$HEADING['MODULE_DETAILS'] = 'Szczególy modulu';
$HEADING['MY_EMAIL'] = 'Mój e-mail';
$HEADING['MY_PASSWORD'] = 'Moje haslo';
$HEADING['MY_SETTINGS'] = 'Moje ustawienia';
$HEADING['SEARCH_SETTINGS'] = 'Ustawienia wyszukiwania';
$HEADING['SERVER_SETTINGS'] = 'Ustawienia serwera';
$HEADING['TEMPLATE_DETAILS'] = 'Szczególy szablonu';
$HEADING['UNINSTALL_LANGUAGE'] = 'Odinstaluj jezyk';
$HEADING['UNINSTALL_MODULE'] = 'Odinstaluj modul';
$HEADING['UNINSTALL_TEMPLATE'] = 'Odinstaluj szablon';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Zaladuj plik(i)';
$HEADING['WBMAILER_SETTINGS'] = 'Ustawienia rozsylania maili';
$MESSAGE['ADDON_ERROR_RELOAD'] = 'Blad podczas aktualizacji dodatku.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Pomyslnie zainstalowano ponownie pliki jezykowe';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>UWAGA!</strong> Ze wzgledów bezpieczenstwa przeslanie plików jezykowych do folderu /languages/ powinno odbyc sie tylko przez FTP.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Uwaga istniejace wpisy modulu moga zostac utracone w bazie danych.';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'Jesli moduly sa przesylane za pomoca protokolu FTP (nie polecane), to funkcje takie jak <tt>instalacja</tt>, <tt>aktualizacja</tt> lub <tt>odinstalowanie</tt> moga nie dzialac prawidlowo. <br /><br />';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Uwaga istniejace wpisy modulu moga zostac utracone w bazie danych. Uzyj tej opcji tylko wtedy gdy masz problemy z przeslaniem przez FTP.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Uwaga istniejace wpisy modulu moga zostac utracone w bazie danych.';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Pomyslnie zainstalowano ponownie moduly';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Zastap nowsze pliki';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Instalacja dodatku. Twój system nie spelnia wymogów niniejszego dodatku. Aby system pracowal z tym dodatkiem nalezy rozwiazac kwestie przedstawione ponizej.';
$MESSAGE['ADDON_RELOAD'] = 'Aktualizacja bazy danych z informacjami dodatków (np. po FTP).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Szablony zostaly pomyslnie zaladowane ponownie';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Niewystarczajace uprawnienia do ogladania tej strony.';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Haslo mozna resetowac tylko raz na godzine';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Nie udalo sie wyslac hasla, prosze sie skontaktowac z administratorem';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Wprowadzonego adresu e-mail nie ma w bazie danych';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Prosze wprowadzic ponizej swój adres e-mail';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Niestety, nie ma aktywnej zawartosci do wyswietlenia';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Niestety, nie masz uprawnien do ogladania tej strony.';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Juz zainstalowany';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Nie moza zapisac do katalogu docelowego';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Prosimy o cierpliwosc';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Nie mozna odinstalowac';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Nie mozna odinstalowac: wybrany plik jest obecnie uzywany';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> nie moze byc odinstalowany, poniewaz jest uzywany przez {{pages}}:<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'nastepujaca strone;nastepujace strony';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Szablon <b>{{name}}</b> nie moze byc odinstalowany, poniewaz jest ustawiony jako szablon domyslny!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Nie mozna odinstalowac szablonu <b>{{name}}</b>, poniewaz jest ustawiony jako domyslny!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Nie mozna rozpakowac pliku';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Nie mozna zaladowac pliku';
$MESSAGE['GENERIC_COMPARE'] = ' pomyslnie';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Blad podczas otwierania pliku.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' niepomyslnie';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Prosze zwrócic uwage, ze ladowany plik musi byc w formacie:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Prosze zwrócic uwage, ze ladowany plik musi byc w jednym z formatów:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Prosze sie cofnac i wypelnic wszystkie pola';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'Nie dokonano zadnego wyboru!';
$MESSAGE['GENERIC_INSTALLED'] = 'Zainstalowano pomyslnie';
$MESSAGE['GENERIC_INVALID'] = 'Zaladowany plik jest nieprawidlowy';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Nieprawidlowy plik instalacyjny Websidebakera. Sprawdz format *.zip.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Nieprawidlowy plik jezykowy Websidebakera. Prosze sprawdzic w pliku tekstowym.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Nieprawidlowy plik modulu Websidebakera. Prosze sprawdzic w pliku tekstowym.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Nieprawidlowy plik szablonu Websidebakera. Prosze sprawdzic w pliku tekstowym.';
$MESSAGE['GENERIC_IN_USE'] = ' moze byc uzyte w ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Brak archiwum pliku!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'Modul nie jest poprawnie zainstalowany! Bledna wersja!';
$MESSAGE['GENERIC_NOT_COMPARE'] = 'nie jest mozliwe';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Niezainstalowano';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Aktualizacja nie moze nastapic';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Prosimy o cierpliwosc, to moze troche potrwac.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Zapraszamy wkrótce...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Naruszenie bezpieczenstwa!! Odmowa dostepu!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Naruszenia bezpieczenstwa! Przechowywanie danych zostalo odrzucone!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Odinstalowano pomyslnie';
$MESSAGE['GENERIC_UPGRADED'] = 'Zaktualizowano pomyslnie';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Porównyanie wersji';
$MESSAGE['GENERIC_VERSION_GT'] = 'Wymagana aktualizacja!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Aktualizacja do nizszej wersji';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'Ta strona jest chwilowo niedostepna';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Witryna w trakcie tworzenia';
$MESSAGE['GROUPS_ADDED'] = 'Grupa zostala dodana';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Czy aby na pewno usunac wybrana grupe (i wszystkich uzytkowników, którzy do niej naleza)?';
$MESSAGE['GROUPS_DELETED'] = 'Grupa zostala usunieta';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Nazwa grupy jest pusta';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Grupa o takiej nazwie juz istnieje';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Nie odnaleziono zadnych grup';
$MESSAGE['GROUPS_SAVED'] = 'Grupa zostala zapisana';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Prosze wprowadzic haslo';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Wprowadzone haslo jest zbyt krótkie';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Wprowadzone haslo jest zbyt krótkie';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Nie wprowadzono rozszerzenia pliku';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Nie wprowadzono nazwy uzytkownika';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Nie mozna usunac wybranego folderu';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Nie mozna usunac wybranego pliku';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Nie udalo sie zmienic nazwy';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Czy aby na pewno usunac nastepujace pliki lub foldery?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Folder zostal usuniety';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Plik zostal usuniety';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Okreslony katalog nie istnieje lub nie jest dozwolony.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Katalog nie istnieje';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Nazwa folderu nie moze zawierac ../';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Folder pasujacy do wprowadzonej nazwy juz istnieje';
$MESSAGE['MEDIA_DIR_MADE'] = 'Folder zostal utworzony';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Nie udalo sie utworzyc folderu';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Plik pasujacy do wprowadzonej nazwy juz istnieje';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Plik nieodnaleziony';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Nazwa nie moze zawierac ../';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Nie mozna uzyc index.php jako nazwy';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Nie odnaleziono zadnych mediów w biezacym folderze';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'Nie przyjeto pliku';
$MESSAGE['MEDIA_RENAMED'] = 'Nazwa zostala zmieniona';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' plik zostal pomyslnie zaladowany';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Folder docelowy nie moze zawierac ../';
$MESSAGE['MEDIA_UPLOADED'] = ' pliki zostaly pomyslnie zaladowane';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Niestety, ten formularz zostal wyslany zbyt wiele razy w ciagu tej godziny. Prosimy spróbowac ponownie za godzine.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Wprowadzony numer weryfikacyjny (tzw. Captcha) jest nieprawidlowy. Jesli masz problemy z odczytaniem Captcha, napisz do: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Nalezy wprowadzic szczególy dla nastepujacych pól';
$MESSAGE['PAGES_ADDED'] = 'Strona zostala dodana';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Naglówek strony zostal dodany';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Prosze wprowadzic tytul menu';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Prosze wprowadzic tytul strony';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Blad podczas tworzenia pliku dostepowego w katalogu /pages (niewystarczajace uprawnienia)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Blad podczas usuwania pliku dostepowego w katalogu /pages (niewystarczajace uprawnienia)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Blad podczas zmieniania kolejnosci stron';
$MESSAGE['PAGES_DELETED'] = 'Strona zostala usunieta';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Czy aby na pewno usunac wybrana strone (i wszystkie jej podstrony)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Nie masz uprawnien do modyfikowania tej strony';
$MESSAGE['PAGES_INTRO_LINK'] = 'Kliknij TUTAJ by zmienic strone wprowadzajaca';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Nie mozna zapisac pliku /pages/intro.php (niewystarczajace uprawnienia)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Strona wprowadzajaca zostala zapisana';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Ostatnio zmodyfikowane przez';
$MESSAGE['PAGES_NOT_FOUND'] = 'Strona nie znaleziona';
$MESSAGE['PAGES_NOT_SAVED'] = 'Blad podczas zapisywania strony';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Strona o tym lub podobnym tytule juz istnieje';
$MESSAGE['PAGES_REORDERED'] = 'Zmieniono kolejnosc stron';
$MESSAGE['PAGES_RESTORED'] = 'Strona zostala przywrócona';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Powrót do stron';
$MESSAGE['PAGES_SAVED'] = 'Strona zostala zapisana';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Ustawienia strony zostaly zapisane';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Wlasciwosci sekcji zostaly zapisane';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = '(Biezace) haslo jest nieprawidlowe';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Szczególy zostaly zapisane';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'E-mail zostal zaktualizowany';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Blad. Haslo zawiera nieprawidlowe znaki';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Haslo zostalo zmienione';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'Zmiana tego rekordu nie powiodla sie';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'Zmiana rekordu zostala zaktualizowana pomyslnie.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Dodanie nowego rekordu sie nie powiodlo.';
$MESSAGE['RECORD_NEW_SAVED'] = 'Nowy rekord zostal dodany pomyslnie.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Uwaga: nacisniecie tego przycisku resetuje wszystkie niezapisane zmiany';
$MESSAGE['SETTINGS_SAVED'] = 'Ustawienia zostaly zapisane';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Nie mozna otworzyc pliku konfiguracyjnego';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Nie mozna zapisac pliku konfiguracyjnego';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Uwaga: zalecane wylacznie w srodowiskach testowych';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
Nowe konto uzytkownika zostalo utworzone.

Loginname: {LOGIN_NAME}
ID uzytkownika: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
Adres IP: {LOGIN_IP}
Data rejestracji: {SIGNUP_DATE}
----------------------------------------
Ta wiadomosc zostala wygenerowana automatycznie.

';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Witaj {LOGIN_DISPLAY_NAME},

Ten mail zostal wyslany poniewaz\'zapomiano hasla\' funkcja odzyskania twojego konta zostala uruchomiona.

Szczególy twojego nowego konta \'{LOGIN_WEBSITE_TITLE}\' ponizej:

Loginname: {LOGIN_NAME}
Haslo: {LOGIN_PASSWORD}

Powyzej zostalo podane twoje haslo.
Oznacza to, ze stare haslo nie bedzie juz dzialac!
Jesli masz pytania badz problemy z nowym loginem lub haslem skontaktuj sie z administratorem \'{LOGIN_WEBSITE_TITLE}\'.
Aby uniknac nieoczekiwanych awarii prosze pamietac o czyszczeniu pamieci podrecznej cache przegladarki

Pozdrawiamy
------------------------------------
Ta wiadomosc zostala wygenerowana automatycznie.

';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Hello {LOGIN_DISPLAY_NAME},

Witamy \'{LOGIN_WEBSITE_TITLE}\'.

Szczególy konta \'{LOGIN_WEBSITE_TITLE}\' ponizej:
Loginname: {LOGIN_NAME}
Haslo: {LOGIN_PASSWORD}

Pozdrawiamy

Prosba:
Jesli otrzymales te wiadomosc przez pomylke, usun ja niezwlocznie!
-------------------------------------
Ta wiadomosc zostala wygenerowana automatycznie!
';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Twoje dane logowania...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Nalezy wprowadzic adres e-mail';
$MESSAGE['START_CURRENT_USER'] = 'Jestes obecnie zalogowany(-a) jako:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Uwaga: katalog instalacyjny wciaz istnieje!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Witamy w panelu administracyjnym WebsiteBakera';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Uwaga: aby zmienic szablon, nalezy przejsc do sekcji Ustawienia';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'This new theme descriptor already exists.';
$MESSAGE['THEME_COPY_CURRENT'] = 'Copy the current active theme and save it with a new name.';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'No rights to create new theme directory!';
$MESSAGE['THEME_IMPORT_HTT'] = 'Import additional templates into the current active theme.<br />Use these templates to overwrite the corresponding default template.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Invalid descriptor for the new theme given!';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unknown upload error';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Failed to write file to disk';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'File upload stopped by extension';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'No file was uploaded';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Missing a temporary folder';
$MESSAGE['UPLOAD_ERR_OK'] = 'File were successful uploaded';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'The uploaded file was only partially uploaded';
$MESSAGE['USERS_ADDED'] = 'Uzytkownik zostal dodany';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Zadanie odrzucone, Nie mozesz usunac sam siebie!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Uwaga: Powyzsze pola nalezy wypelnic tylko, jesli chce sie zmienic haslo tego uzytkownika';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Czy aby na pewno usunac wybranego uzytkownika?';
$MESSAGE['USERS_DELETED'] = 'Uzytkownik zostal usuniety';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Wprowadzony adres e-mail jest juz uzywany';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Wprowadzony adres e-mail jest nieprawidlowy';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Nie wybrano grupy';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Wprowadzone hasla nie pasuja';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Wprowadzone haslo bylo za krótkie';
$MESSAGE['USERS_SAVED'] = 'Uzytkownik zostal zapisany';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Narzedzia administracji WebsiteBakera...';
$OVERVIEW['GROUPS'] = 'Zarzadzaj grupami uzytkowników i ich uprawnieniami systemowymi...';
$OVERVIEW['HELP'] = 'Masz pytania? Znajdz odpowiedzi...';
$OVERVIEW['LANGUAGES'] = 'Zarzadzaj jezykami WebsiteBakera...';
$OVERVIEW['MEDIA'] = 'Zarzadzaj plikami przechowywanymi w folderze mediów...';
$OVERVIEW['MODULES'] = 'Zarzadzaj modulami WebsiteBakera...';
$OVERVIEW['PAGES'] = 'Zarzadzaj stronami...';
$OVERVIEW['PREFERENCES'] = 'Zmien preferencje, takie jak adres e-mail, haslo itp... ';
$OVERVIEW['SETTINGS'] = 'Zmien ustawienia WebsiteBakera...';
$OVERVIEW['START'] = 'Panel administracyjny';
$OVERVIEW['TEMPLATES'] = 'Zmien wyglad swojej strony za pomoca szablonów...';
$OVERVIEW['USERS'] = 'Zarzadzaj uzytkownikami mogacymi logowac sie do WebsiteBakera...';
$OVERVIEW['VIEW'] = 'Podglad witryny w nowym oknie...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
