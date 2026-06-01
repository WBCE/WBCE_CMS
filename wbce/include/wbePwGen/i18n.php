<?php
/**
 * wbe_pw_gen — Localised label array
 *
 * Returns $wpg_labels, ready to pass to WbePwGen.attach() as the options object.
 * Language is resolved from (in order): WBCE's LANGUAGE constant → $_GET['lang'] →
 * $_SESSION['default_language'] → EN fallback.
 * This makes the file work both inside WBCE (LANGUAGE is defined) and in the
 * standalone installer (which uses $_GET / $_SESSION instead).
 *
 * Usage (installer or any module):
 *
 *   require_once WB_PATH . '/include/wbePwGen/wbe_pw_gen_labels.php';
 *   // $wpg_labels is now defined
 *
 *   // In a <script> block:
 *   // WbePwGen.attach('my_input', 'my_container', <?php echo json_encode($wpg_labels); ?>);
 */

$_wpg_strings = [
    'EN' => [
        'levels'       => ['Very Weak',    'Weak',    'Fair',   'Good',   'Strong'],
        'hint_length'  => 'At least {n} characters',
        'hint_case'    => 'Upper + lowercase letters',
        'hint_number'  => 'Contains a number',
        'hint_special' => 'Special char (_-!#*+@$&:)',
        'warn_invalid' => 'Invalid characters:',
        'warn_allowed' => 'Allowed:',
    ],
    'DE' => [
        'levels'       => ['Sehr schwach', 'Schwach', 'Mittel', 'Gut',    'Stark'],
        'hint_length'  => 'Mindestens {n} Zeichen',
        'hint_case'    => 'Groß- und Kleinbuchstaben',
        'hint_number'  => 'Enthält eine Zahl',
        'hint_special' => 'Sonderzeichen (_-!#*+@$&:)',
        'warn_invalid' => 'Ungültige Zeichen:',
        'warn_allowed' => 'Erlaubt:',
    ],
    'NL' => [
        'levels'       => ['Zeer zwak',    'Zwak',    'Matig',  'Goed',   'Sterk'],
        'hint_length'  => 'Minimaal {n} tekens',
        'hint_case'    => 'Hoofd- en kleine letters',
        'hint_number'  => 'Bevat een cijfer',
        'hint_special' => 'Speciaal teken (_-!#*+@$&:)',
        'warn_invalid' => 'Ongeldige tekens:',
        'warn_allowed' => 'Toegestaan:',
    ],
    'BG' => [
        'levels'       => ['Много слаба',  'Слаба',   'Средна', 'Добра',  'Силна'],
        'hint_length'  => 'Поне {n} символа',
        'hint_case'    => 'Главни + малки букви',
        'hint_number'  => 'Съдържа цифра',
        'hint_special' => 'Специален знак (_-!#*+@$&:)',
        'warn_invalid' => 'Невалидни символи:',
        'warn_allowed' => 'Разрешени:',
    ],
    'CA' => [
        'levels'       => ['Molto feble',  'Feble',   'Regular','Bo',     'Fort'],
        'hint_length'  => 'Almenys {n} caràcters',
        'hint_case'    => 'Majúscules + minúscules',
        'hint_number'  => 'Conté un número',
        'hint_special' => 'Caràcter especial (_-!#*+@$&:)',
        'warn_invalid' => 'Caràcters no vàlids:',
        'warn_allowed' => 'Permesos:',
    ],
    'CS' => [
        'levels'       => ['Velmi slabé',  'Slabé',   'Průměrné','Dobré',  'Silné'],
        'hint_length'  => 'Nejméně {n} znaků',
        'hint_case'    => 'Velká + malá písmena',
        'hint_number'  => 'Obsahuje číslo',
        'hint_special' => 'Speciální znak (_-!#*+@$&:)',
        'warn_invalid' => 'Neplatné znaky:',
        'warn_allowed' => 'Povolené:',
    ],
    'DA' => [
        'levels'       => ['Meget svag',   'Svag',    'Middel', 'God',    'Stærk'],
        'hint_length'  => 'Mindst {n} tegn',
        'hint_case'    => 'Store + små bogstaver',
        'hint_number'  => 'Indeholder et tal',
        'hint_special' => 'Specialtegn (_-!#*+@$&:)',
        'warn_invalid' => 'Ugyldige tegn:',
        'warn_allowed' => 'Tilladte:',
    ],
    'ES' => [
        'levels'       => ['Muy débil',    'Débil',   'Regular','Bueno',  'Fuerte'],
        'hint_length'  => 'Al menos {n} caracteres',
        'hint_case'    => 'Mayúsculas + minúsculas',
        'hint_number'  => 'Contiene un número',
        'hint_special' => 'Carácter especial (_-!#*+@$&:)',
        'warn_invalid' => 'Caracteres no válidos:',
        'warn_allowed' => 'Permitidos:',
    ],
    'ET' => [
        'levels'       => ['Väga nõrk',    'Nõrk',    'Keskmine','Hea',   'Tugev'],
        'hint_length'  => 'Vähemalt {n} sümbolit',
        'hint_case'    => 'Suured + väikesed tähed',
        'hint_number'  => 'Sisaldab numbrit',
        'hint_special' => 'Erimärk (_-!#*+@$&:)',
        'warn_invalid' => 'Vigased sümbolid:',
        'warn_allowed' => 'Lubatud:',
    ],
    'FI' => [
        'levels'       => ['Erittäin heikko','Heikko', 'Kohtalainen','Hyvä','Vahva'],
        'hint_length'  => 'Vähintään {n} merkkiä',
        'hint_case'    => 'Suuret + pienet kirjaimet',
        'hint_number'  => 'Sisältää numeron',
        'hint_special' => 'Erikoismerkki (_-!#*+@$&:)',
        'warn_invalid' => 'Virheelliset merkit:',
        'warn_allowed' => 'Sallitut:',
    ],
    'FR' => [
        'levels'       => ['Très faible',  'Faible',  'Moyen',  'Bon',    'Fort'],
        'hint_length'  => 'Au moins {n} caractères',
        'hint_case'    => 'Majuscules + minuscules',
        'hint_number'  => 'Contient un chiffre',
        'hint_special' => 'Caractère spécial (_-!#*+@$&:)',
        'warn_invalid' => 'Caractères invalides :',
        'warn_allowed' => 'Autorisés :',
    ],
    'GR' => [
        'levels'       => ['Πολύ ασθενές', 'Ασθενές', 'Μέτριο', 'Καλό',   'Ισχυρό'],
        'hint_length'  => 'Τουλάχιστον {n} χαρακτήρες',
        'hint_case'    => 'Κεφαλαία + πεζά γράμματα',
        'hint_number'  => 'Περιέχει αριθμό',
        'hint_special' => 'Ειδικός χαρακτήρας (_-!#*+@$&:)',
        'warn_invalid' => 'Μη έγκυροι χαρακτήρες:',
        'warn_allowed' => 'Επιτρεπόμενοι:',
    ],
    'HU' => [
        'levels'       => ['Nagyon gyenge', 'Gyenge',  'Közepes','Jó',    'Erős'],
        'hint_length'  => 'Legalább {n} karakter',
        'hint_case'    => 'Kis- és nagybetűk',
        'hint_number'  => 'Tartalmaz számot',
        'hint_special' => 'Speciális karakter (_-!#*+@$&:)',
        'warn_invalid' => 'Érvénytelen karakterek:',
        'warn_allowed' => 'Engedélyezett:',
    ],
    'IT' => [
        'levels'       => ['Molto debole', 'Debole',  'Medio',  'Buono',  'Forte'],
        'hint_length'  => 'Almeno {n} caratteri',
        'hint_case'    => 'Maiuscole + minuscole',
        'hint_number'  => 'Contiene un numero',
        'hint_special' => 'Carattere speciale (_-!#*+@$&:)',
        'warn_invalid' => 'Caratteri non validi:',
        'warn_allowed' => 'Consentiti:',
    ],
    'LV' => [
        'levels'       => ['Ļoti vājš',    'Vājš',    'Vidējs', 'Labs',   'Stiprs'],
        'hint_length'  => 'Vismaz {n} zīmes',
        'hint_case'    => 'Lielie + mazie burti',
        'hint_number'  => 'Satur skaitli',
        'hint_special' => 'Speciālā zīme (_-!#*+@$&:)',
        'warn_invalid' => 'Nederīgas zīmes:',
        'warn_allowed' => 'Atļautās:',
    ],
    'NO' => [
        'levels'       => ['Meget svak',   'Svak',    'Middels','God',    'Sterk'],
        'hint_length'  => 'Minst {n} tegn',
        'hint_case'    => 'Store + små bokstaver',
        'hint_number'  => 'Inneholder et tall',
        'hint_special' => 'Spesialtegn (_-!#*+@$&:)',
        'warn_invalid' => 'Ugyldige tegn:',
        'warn_allowed' => 'Tillatte:',
    ],
    'PL' => [
        'levels'       => ['Bardzo słabe', 'Słabe',   'Średnie','Dobre',  'Silne'],
        'hint_length'  => 'Co najmniej {n} znaków',
        'hint_case'    => 'Wielkie + małe litery',
        'hint_number'  => 'Zawiera cyfrę',
        'hint_special' => 'Znak specjalny (_-!#*+@$&:)',
        'warn_invalid' => 'Nieprawidłowe znaki:',
        'warn_allowed' => 'Dozwolone:',
    ],
    'PT' => [
        'levels'       => ['Muito fraca',  'Fraca',   'Regular','Boa',    'Forte'],
        'hint_length'  => 'Pelo menos {n} caracteres',
        'hint_case'    => 'Maiúsculas + minúsculas',
        'hint_number'  => 'Contém um número',
        'hint_special' => 'Carácter especial (_-!#*+@$&:)',
        'warn_invalid' => 'Caracteres inválidos:',
        'warn_allowed' => 'Permitidos:',
    ],
    'RU' => [
        'levels'       => ['Очень слабый', 'Слабый',  'Средний','Хороший','Сильный'],
        'hint_length'  => 'Не менее {n} символов',
        'hint_case'    => 'Заглавные + строчные буквы',
        'hint_number'  => 'Содержит цифру',
        'hint_special' => 'Спецсимвол (_-!#*+@$&:)',
        'warn_invalid' => 'Недопустимые символы:',
        'warn_allowed' => 'Допустимые:',
    ],
    'SK' => [
        'levels'       => ['Veľmi slabé',  'Slabé',   'Priemerné','Dobré', 'Silné'],
        'hint_length'  => 'Najmenej {n} znakov',
        'hint_case'    => 'Veľké + malé písmená',
        'hint_number'  => 'Obsahuje číslo',
        'hint_special' => 'Špeciálny znak (_-!#*+@$&:)',
        'warn_invalid' => 'Neplatné znaky:',
        'warn_allowed' => 'Povolené:',
    ],
    'SV' => [
        'levels'       => ['Mycket svagt', 'Svagt',   'Godtagbart','Bra',  'Starkt'],
        'hint_length'  => 'Minst {n} tecken',
        'hint_case'    => 'Stora + små bokstäver',
        'hint_number'  => 'Innehåller en siffra',
        'hint_special' => 'Specialtecken (_-!#*+@$&:)',
        'warn_invalid' => 'Ogiltiga tecken:',
        'warn_allowed' => 'Tillåtna:',
    ],
    'TR' => [
        'levels'       => ['Çok zayıf',    'Zayıf',   'Orta',   'İyi',    'Güçlü'],
        'hint_length'  => 'En az {n} karakter',
        'hint_case'    => 'Büyük + küçük harfler',
        'hint_number'  => 'Rakam içerir',
        'hint_special' => 'Özel karakter (_-!#*+@$&:)',
        'warn_invalid' => 'Geçersiz karakterler:',
        'warn_allowed' => 'İzin verilen:',
    ],
];

if (defined('LANGUAGE')) {
    $_wpg_lang = strtoupper(LANGUAGE);
} elseif (isset($_GET['lang']) && preg_match('/^[A-Z]{2}$/i', $_GET['lang'])) {
    $_wpg_lang = strtoupper($_GET['lang']);
} elseif (isset($_SESSION['default_language']) && preg_match('/^[A-Z]{2}$/i', $_SESSION['default_language'])) {
    $_wpg_lang = strtoupper($_SESSION['default_language']);
} else {
    $_wpg_lang = 'EN';
}
$_wpg      = $_wpg_strings[$_wpg_lang] ?? $_wpg_strings['EN'];

$wpg_labels = [
    'minLength'     => 12,
    'levels'        => $_wpg['levels'],
    'hints'         => [
        'length'    => $_wpg['hint_length'],
        'case'      => $_wpg['hint_case'],
        'number'    => $_wpg['hint_number'],
        'special'   => $_wpg['hint_special'],
    ],
    'warn'          => [
        'invalid'   => $_wpg['warn_invalid'],
        'allowed'   => $_wpg['warn_allowed'],
    ],
    'allowedDisplay' => 'a–z   A–Z   0–9   _ - ! # * + @ $ & :',
];

unset($_wpg_strings, $_wpg_lang, $_wpg);
