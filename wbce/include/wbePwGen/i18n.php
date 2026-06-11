<?php
/**
 * wbePwGen — Localised label array
 *
 * Defines $wpg_labels, ready to pass to WbePwGen.attach() as the options object.
 * Language resolved from: WBCE LANGUAGE constant → $_GET['lang'] →
 * $_SESSION['default_language'] → EN fallback.
 *
 * Usage:
 * require_once WB_PATH . '/include/wbePwGen/i18n.php';
 * WbePwGen.attach('my_pw', 'my_wrap', <?php echo json_encode($wpg_labels); ?>);
 */

$_wpg_strings = [
    'DE_AT' => 'DE', // alias
    'DE_CH' => 'DE', // alias
];

/* ── Raw Language Data Matrix ───────────────────────────────────────────── */
$langStrings = [
    'EN' => ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong',
             'Enter at least %d characters', 'Keep going — try mixing letters & numbers',
             'Almost there — add special characters', 'Just a bit more…',
             'Contains invalid characters: %s', 'Weak — mix letters, numbers and special characters',
             'Add an uppercase letter to strengthen', 'Add a lowercase letter to strengthen', 'Add a number to strengthen',
             'Add a special character',
             'Good password', 'Strong password ✓',
             'Passwords match ✓', 'Passwords do not match', '↺', 'Generate password', 'Copy'],

    'DE' => ['Sehr schwach', 'Schwach', 'Mittel', 'Gut', 'Stark',
             'Mindestens %d Zeichen eingeben', 'Weiter so — Buchstaben und Zahlen mischen',
             'Fast geschafft — Sonderzeichen hinzufügen', 'Noch ein bisschen…',
             'Ungültige Zeichen: %s', 'Schwach — Buchstaben, Zahlen und Sonderzeichen mischen',
             'Großbuchstaben hinzufügen', 'Kleinbuchstaben hinzufügen', 'Eine Zahl hinzufügen',
             'Sonderzeichen hinzufügen',
             'Gutes Passwort', 'Starkes Passwort ✓',
             'Passwörter stimmen überein ✓', 'Passwörter stimmen nicht überein', '↺', 'Passwort generieren', 'Kopieren'],

    'NL' => ['Zeer zwak', 'Zwak', 'Matig', 'Goed', 'Sterk',
             'Voer minimaal %d tekens in', 'Ga door — combineer letters en cijfers',
             'Bijna — voeg speciale tekens toe', 'Nog even…',
             'Ongeldige tekens: %s', 'Zwak — combineer letters, cijfers en speciale tekens',
             'Voeg een hoofdletter toe', 'Voeg een kleine letter toe', 'Voeg een cijfer toe',
             'Voeg een speciaal teken toe',
             'Goed wachtwoord', 'Sterk wachtwoord ✓',
             'Wachtwoorden komen overeen ✓', 'Wachtwoorden komen niet overeen', '↺', 'Wachtwoord genereren', 'Kopiëren'],

    'BG' => ['Много слаба', 'Слаба', 'Средна', 'Добра', 'Силна',
             'Въведете поне %d символа', 'Продължете — смесете букви и цифри',
             'Почти — добавете специални знаци', 'Още малко…',
             'Невалидни символи: %s', 'Слаба — смесете букви, цифри и специални знаци',
             'Добавете главна буква', 'Добавете малка буква', 'Добавете цифра',
             'Добавете специален знак',
             'Добра парола', 'Силна парола ✓',
             'Паролите съвпадат ✓', 'Паролите не съвпадат', '↺', 'Генериране на парола', 'Копиране'],

    'CS' => ['Velmi slabé', 'Slabé', 'Průměrné', 'Dobré', 'Silné',
             'Zadejte alespoň %d znaků', 'Pokračujte — kombinujte písmena a číslice',
             'Skoro — přidejte speciální znaky', 'Ještě trochu…',
             'Neplatné znaky: %s', 'Slabé — kombinujte písmena, číslice a speciální znaky',
             'Přidejte velké písmeno', 'Přidejte malé písmeno', 'Přidejte číslici',
             'Přidejte speciální znak',
             'Dobré heslo', 'Silné heslo ✓',
             'Hesla se shodují ✓', 'Hesla se neshodují', '↺', 'Generovat heslo', 'Kopírovat'],

    'DA' => ['Meget svag', 'Svag', 'Middel', 'God', 'Stærk',
             'Indtast mindst %d tegn', 'Fortsæt — kombiner bogstaver og tal',
             'Næsten — tilføj specialtegn', 'Lidt endnu…',
             'Ugyldige tegn: %s', 'Svag — bland bogstaver, tal og specialtegn',
             'Tilføj et stort bogstav', 'Tilføj et lille bogstav', 'Tilføj et tal',
             'Tilføj et specialtegn',
             'God adgangskode', 'Stærk adgangskode ✓',
             'Adgangskoderne stemmer overens ✓', 'Adgangskoderne stemmer ikke overens', '↺', 'Generer adgangskode', 'Kopiér'],

    'ES' => ['Muy débil', 'Débil', 'Regular', 'Bueno', 'Fuerte',
             'Introduce al menos %d caracteres', 'Sigue — combina letras y números',
             'Casi — añade caracteres especiais', 'Un poco más…',
             'Caracteres no válidos: %s', 'Débil — combina letras, números y caracteres especiales',
             'Añade una letra mayúscula', 'Añade una letra minúscula', 'Añade un número',
             'Añade un carácter especial',
             'Buena contraseña', 'Contraseña fuerte ✓',
             'Las contraseñas coinciden ✓', 'Las contraseñas no coinciden', '↺', 'Generar contraseña', 'Copiar'],

    'ET' => ['Väga nõrk', 'Nõrk', 'Keskmine', 'Hea', 'Tugev',
             'Sisestage vähemalt %d sümbolit', 'Jätkake — segage tähti ja numbreid',
             'Peaaegu — lisage erimärke', 'Veel natuke…',
             'Vigased sümbolid: %s', 'Nõrk — segage tähti, numbreid ja erimärke',
             'Lisage suurtäht', 'Lisage väiketäht', 'Lisage number',
             'Lisage erimärk',
             'Hea parool', 'Tugev parool ✓',
             'Paroolid kattuvad ✓', 'Paroolid ei kattu', '↺', 'Genereeri parool', 'Kopeeri'],

    'FI' => ['Erittäin heikko', 'Heikko', 'Kohtalainen', 'Hyvä', 'Vahva',
             'Anna vähintään %d merkkiä', 'Jatka — sekoita kirjaimia ja numeroita',
             'Melkein — lisää erikoismerkkejä', 'Vielä hieman…',
             'Virheelliset merkit: %s', 'Heikko — sekoita kirjaimia, numeroita ja erikoismerkkejä',
             'Lisää iso kirjain', 'Lisää pieni kirjain', 'Lisää numero',
             'Lisää erikoismerkki',
             'Hyvä salasana', 'Vahva salasana ✓',
             'Salasanat täsmäävät ✓', 'Salasanat eivät täsmää', '↺', 'Luo salasana', 'Kopioi'],

    'FR' => ['Très faible', 'Faible', 'Moyen', 'Bon', 'Fort',
             'Saisissez au moins %d caractères', 'Continuez — mélangez lettres et chiffres',
             'Presque — ajoutez des caractères spéciaux', 'Encore un peu…',
             'Caractères invalides : %s', 'Faible — mélangez lettres, chiffres et caractères spéciaux',
             'Ajoutez une lettre majuscule', 'Ajoutez une lettre minuscule', 'Ajoutez un chiffre',
             'Ajoutez un caractère spécial',
             'Bon mot de passe', 'Mot de passe fort ✓',
             'Les mots de passe correspondent ✓', 'Les mots de passe ne correspondent pas', '↺', 'Générer un mot de passe', 'Copier'],

    'GR' => ['Πολύ ασθενές', 'Ασθενές', 'Μέτριο', 'Καλό', 'Ισχυρό',
             'Εισάγετε τουλάχιστον %d χαρακτήρες', 'Συνεχίστε — συνδυάστε γράμματα και αριθμούς',
             'Σχεδόν — προσθέστε ειδικούς χαρακτήρες', 'Λίγο ακόμα…',
             'Μη έγκυροι χαρακτήρες: %s', 'Ασθενές — συνδυάστε γράμματα, αριθμούς και ειδικούς χαρακτήρες',
             'Προσθέστε κεφαλαίο γράμμα', 'Προσθέστε πεζό γράμμα', 'Προσθέστε αριθμό',
             'Προσθέστε ειδικό χαρακτήρα',
             'Καλός κωδικός', 'Ισχυρός κωδικός ✓',
             'Οι κωδικοί ταιριάζουν ✓', 'Οι κωδικοί δεν ταιριάζουν', '↺', 'Δημιουργία κωδικού', 'Αντιγραφή'],

    'HU' => ['Nagyon gyenge', 'Gyenge', 'Közepes', 'Jó', 'Erős',
             'Adjon meg legalább %d karaktert', 'Folytassa — keverjen betűket és számokat',
             'Majdnem — adjon hozzá speciális karaktereket', 'Még egy kicsit…',
             'Érvénytelen karakterek: %s', 'Gyenge — keverjen betűket, számokat und speciális karaktereket',
             'Adjon hozzá egy nagybetűt', 'Adjon hozzá egy kisbetűt', 'Adjon hozzá egy számot',
             'Adjon hozzá speciális karaktert',
             'Jó jelszó', 'Erős jelszó ✓',
             'A jelszavak egyeznek ✓', 'A jelszavak nem egyeznek', '↺', 'Jelszó generálása', 'Másolás'],

    'IT' => ['Molto debole', 'Debole', 'Medio', 'Buono', 'Forte',
             'Inserisci almeno %d caratteri', 'Continua — combina lettere e numeri',
             'Quasi — aggiungi caratteri speciali', 'Ancora un po\'…',
             'Caratteri non validi: %s', 'Debole — combina lettere, numeri e caratteri speciali',
             'Aggiungi una lettera maiuscola', 'Aggiungi una lettera minuscule', 'Aggiungi un numero',
             'Aggiungi un carattere speciale',
             'Buona password', 'Password forte ✓',
             'Le password coincidono ✓', 'Le password non coincidono', '↺', 'Genera password', 'Copia'],

    'LV' => ['Ļoti vājš', 'Vājš', 'Vidējs', 'Labs', 'Stiprs',
             'Ievadiet vismaz %d zīmes', 'Turpiniet — jauciet burtus un ciparus',
             'Gandrīz — pievienojiet speciālās zīmes', 'Vēl mazliet…',
             'Nederīgas zīmes: %s', 'Vāja — jauciet burtus, ciparus un speciālās zīmes',
             'Pievienojiet lielo burtu', 'Pievienojiet mazo burtu', 'Pievienojiet ciparu',
             'Pievienojiet speciālo zīmi',
             'Laba parole', 'Stipra parole ✓',
             'Paroles sakrīt ✓', 'Paroles nesakrīt', '↺', 'Generēt paroli', 'Kopēt'],

    'NO' => ['Meget svak', 'Svak', 'Middels', 'God', 'Sterk',
             'Skriv inn minst %d tegn', 'Fortsett — kombiner bokstaver og tal',
             'Neste — legg til spesialtegn', 'Litt til…',
             'Ugyldige tegn: %s', 'Svag — bland bogstaver, tal og specialtegn',
             'Legg til en stor bokstav', 'Legg til en liten bokstav', 'Legg til et tel',
             'Legg til et spesialtegn',
             'Godt passord', 'Sterkt passord ✓',
             'Passordene stemmer overens ✓', 'Passordene stemmer ikke overens', '↺', 'Generer passord', 'Kopier'],

    'PL' => ['Bardzo słabe', 'Słabe', 'Średnie', 'Dobre', 'Silne',
             'Wprowadź co najmniej %d znaków', 'Dalej — mieszaj litery i cyfry',
             'Prawie — dodaj znaki specjalne', 'Jeszcze trochę…',
             'Niedozwolone znaki: %s', 'Słabe — mieszaj litery, cyfry i znaki specjalne',
             'Dodaj wielką literę', 'Dodaj małą literę', 'Dodaj cyfrę',
             'Dodaj znak specjalny',
             'Dobre hasło', 'Silne hasło ✓',
             'Hasła są zgodne ✓', 'Hasła nie są zgodne', '↺', 'Generuj hasło', 'Skopiuj'],

    'PT' => ['Muito fraca', 'Fraca', 'Regular', 'Boa', 'Forte',
             'Digite pelo menos %d caracteres', 'Continue — misture letras e números',
             'Quase lá — adicione caracteres especiais', 'Só mais um pouco…',
             'Caracteres inválidos: %s', 'Fraca — misture letras, números e caracteres especiais',
             'Adicione uma letra maiúscula', 'Adicione uma letra minúscula', 'Adicione um número',
             'Adicione um caractere especial',
             'Boa senha', 'Senha forte ✓',
             'As senhas coincidem ✓', 'As senhas não coincidem', '↺', 'Gerar senha', 'Copiar'],

    'RU' => ['Очень слабый', 'Слабый', 'Средний', 'Хороший', 'Сильный',
             'Введите не менее %d символов', 'Продолжайте — сочетайте буквы и цифры',
             'Почти готово — добавьте спецсимволы', 'Ещё чуть-чуть…',
             'Недопустимые символы: %s', 'Слабый — используйте буквы, цифры и спецсимволы',
             'Добавьте заглавную букву', 'Добавьте строчную букву', 'Добавьте цифру',
             'Добавьте спецсимвол',
             'Хороший пароль', 'Сильный пароль ✓',
             'Пароли совпадают ✓', 'Пароли не совпадают', '↺', 'Сгенерировать пароль', 'Копировать'],

    'SK' => ['Veľmi slabé', 'Slabé', 'Priemerné', 'Dobré', 'Silné',
             'Zadajte aspoň %d znakov', 'Pokračujte — kombinujte písmená a číslice',
             'Takmer — pridajte špeciálne znaky', 'Ešte trochu…',
             'Neplatné znaky: %s', 'Slabé — kombinujte písmená, číslice a špeciálne znaky',
             'Pridajte veľké písmeno', 'Pridajte malé písmeno', 'Pridajte číslicu',
             'Pridajte špeciálny znak',
             'Dobré heslo', 'Silné heslo ✓',
             'Heslá sa zhodujú ✓', 'Heslá sa nezhodujú', '↺', 'Generovať heslo', 'Kopírovať'],

    'CA' => ['Molt feble', 'Feble', 'Regular', 'Bo', 'Fort',
             'Introdueix almenys %d caràcters', 'Continua — combina lletres i números',
             'Gairebé — afegeix caràcters especials', 'Una mica més…',
             'Caràcters no vàlids: %s', 'Feble — combina lletres, números i caràcters especials',
             'Afegeix una lletra majúscula', 'Afegeix una lletra minúscula', 'Afegeix un número',
             'Afegeix un caràcter especial',
             'Bona contrasenya', 'Contrasenya forta ✓',
             'Les contrasenyes coincideixen ✓', 'Les contrasenyes no coincideixen', '↺', 'Generar contrasenya', 'Copia']
];

/* ── Hydrate $_wpg_strings associative arrays ───────────────────────────── */
foreach ($langStrings as $_lc => $_v) {
    $_wpg_strings[$_lc] = [
        'levels'         => array_slice($_v, 0, 5),
        'nudge_0'        => $_v[5],
        'nudge_1'        => $_v[6],
        'nudge_2'        => $_v[7],
        'nudge_3'        => $_v[8],
        'msg_invalid'    => $_v[9],
        'msg_same'       => $_v[10],
        'msg_no_upper'   => $_v[11],
        'msg_no_lower'   => $_v[12],
        'msg_no_number'  => $_v[13],
        'msg_no_special' => $_v[14],
        'msg_good'       => $_v[15],
        'msg_strong'     => $_v[16],
        'cfm_match'      => $_v[17],
        'cfm_nomatch'    => $_v[18],
        'gen_label'      => $_v[19],
        'gen_hint'       => $_v[20],
        'copy_title'     => $_v[21],
        'empty_hint'     => ($_lc === 'DE' ? 'Passwort eingeben oder {generate}' : ($_lc === 'NL' ? 'Wachtwoord genereren' : 'Type your password or {generate}')),
        'invalid_msg'    => ($_lc === 'DE' ? 'Ungültiges Zeichen: %s' : 'Invalid character: %s'),
        'invalid_plural' => ($_lc === 'DE' ? 'Ungültige Zeichen: %s' : 'Invalid characters: %s'),
        'invalid_space'  => ($_lc === 'DE' ? 'Leerzeichen' : 'space'),
    ];
}

unset($langStrings); // Free up raw list pointer memory

// Resolve DE_AT / DE_CH aliases after structural array hydration
foreach ($_wpg_strings as $_lc => $_v) {
    if (is_string($_v)) $_wpg_strings[$_lc] = $_wpg_strings[$_v];
}

/* ── Language resolution ────────────────────────────────────────────────── */

if (defined('LANGUAGE')) {
    $_wpg_lang = strtoupper(LANGUAGE);
} elseif (isset($_GET['lang']) && preg_match('/^[A-Z]{2}(_[A-Z]{2})?$/i', $_GET['lang'])) {
    $_wpg_lang = strtoupper($_GET['lang']);
} elseif (isset($_SESSION['default_language']) && preg_match('/^[A-Z]{2}$/i', $_SESSION['default_language'])) {
    $_wpg_lang = strtoupper($_SESSION['default_language']);
} else {
    $_wpg_lang = 'EN';
}

$_wpg = $_wpg_strings[$_wpg_lang] ?? $_wpg_strings['EN'];

/* ── Output array (matches WbePwGen JS options shape) ───────────────────── */

$wpg_labels = [
    'minLength'          => 12,
    'genLength'          => 12,
    'levels'             => $_wpg['levels'],
    'nudges'             => [
        $_wpg['nudge_0'],
        $_wpg['nudge_1'],
        $_wpg['nudge_2'],
        $_wpg['nudge_3'],
    ],
    'messages'           => [
        'allSameClass' => $_wpg['msg_same'],
        'noUpper'      => $_wpg['msg_no_upper'],
        'noLower'      => $_wpg['msg_no_lower'],
        'noNumber'     => $_wpg['msg_no_number'],
        'noSpecial'    => $_wpg['msg_no_special'],
        'good'         => $_wpg['msg_good'],
        'strong'       => $_wpg['msg_strong'],
    ],
    /* Empty-state hint: {generate} is replaced with the clickable link */
    'emptyHint'          => $_wpg['empty_hint'],
    /* Label for the inline generate link */
    'generateLabel'      => $_wpg['gen_label'],
    /* Title / aria-label for the generate button */
    'generateTitle'      => $_wpg['gen_hint'],
    /* Copy-to-clipboard button */
    'copyTitle'          => $_wpg['copy_title'],
    /* Invalid character messages */
    'invalidMsg'         => $_wpg['invalid_msg'],
    'invalidMsgPlural'   => $_wpg['invalid_plural'],
    'invalidSpace'       => $_wpg['invalid_space'],
    'confirmMatch'       => $_wpg['cfm_match'],
    'confirmNoMatch'     => $_wpg['cfm_nomatch'],
];

unset($_wpg_strings, $_wpg_lang, $_wpg, $_lc, $_v);