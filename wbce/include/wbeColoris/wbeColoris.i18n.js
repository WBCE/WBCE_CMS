/*!
 * WbeColoris i18n v1.0.0
 *
 * Built-in translations for WbeColoris — 20 languages.
 * Language coverage mirrors wbePwGen (all languages shipped with WBCE).
 *
 * Auto-applies from window.LANGUAGE (set by WBCE in every admin <head>).
 * Manual override : WbeColoris.setLang('DE')
 * Per-call override: WbeColoris({ okLabel: '…', a11y: { reset: '…' } })
 */
(function (global) {
    'use strict';

    if (typeof global.WbeColoris === 'undefined') { return; }

    /* ── String matrix ───────────────────────────────────────────────────────
       Column order:
         0  clearLabel          1  closeLabel         2  defaultColorLabel
         3  a11y.open           4  a11y.close         5  a11y.clear
         6  a11y.marker         7  a11y.hueSlider     8  a11y.alphaSlider
         9  a11y.input         10  a11y.format        11  a11y.swatch
        12  a11y.instruction   13  a11y.reset         14  a11y.ok
        15  a11y.clearField
       ─────────────────────────────────────────────────────────────────────── */
    var _m = {

        EN: ['Clear','Close','Default',
             'Open color picker','Close color picker','Clear the selected color',
             'Saturation: {s}. Brightness: {v}.','Hue slider','Opacity slider',
             'Color value field','Color format','Color swatch',
             'Saturation and brightness selector. Use arrow keys to select.',
             'Reset to default','Confirm color selection','Clear field value'],

        DE: ['Löschen','Schließen','Vorgabewert',
             'Farbwähler öffnen','Farbwähler schließen','Ausgewählte Farbe löschen',
             'Sättigung: {s}. Helligkeit: {v}.','Farbton-Regler','Transparenz-Regler',
             'Farbwert-Eingabe','Farbformat','Farbfeld',
             'Sättigung und Helligkeit. Mit Pfeiltasten navigieren.',
             'Auf Vorgabewert zurücksetzen','Farbauswahl bestätigen','Farbwert leeren'],

        NL: ['Wissen','Sluiten','Standaard',
             'Kleurenkiezer openen','Kleurenkiezer sluiten','Geselecteerde kleur wissen',
             'Verzadiging: {s}. Helderheid: {v}.','Tintregelaar','Transparantieregelaar',
             'Kleurwaardeveld','Kleurformaat','Kleurstaal',
             'Verzadiging en helderheid. Gebruik pijltoetsen.',
             'Terugzetten naar standaard','Kleurkeuse bevestigen','Veldwaarde wissen'],

        FR: ['Effacer','Fermer','Par défaut',
             'Ouvrir le sélecteur de couleur','Fermer le sélecteur de couleur',
             'Effacer la couleur sélectionnée',
             'Saturation : {s}. Luminosité : {v}.','Curseur de teinte',
             'Curseur d\'opacité','Champ de valeur de couleur','Format de couleur',
             'Échantillon de couleur',
             'Sélecteur de saturation et luminosité. Utilisez les touches fléchées.',
             'Rétablir la valeur par défaut','Confirmer la sélection de couleur',
             'Vider le champ'],

        DA: ['Ryd','Luk','Standard',
             'Åbn farvevælger','Luk farvevælger','Ryd den valgte farve',
             'Mætning: {s}. Lysstyrke: {v}.','Farvetone-skyder','Gennemsigtigheds-skyder',
             'Farveværdifelt','Farveformat','Farveprøve',
             'Mætning og lysstyrke. Brug piletaster.',
             'Nulstil til standard','Bekræft farvevalg','Ryd feltværdi'],

        NO: ['Tøm','Lukk','Standard',
             'Åpne fargevelger','Lukk fargevelger','Tøm valgt farge',
             'Metning: {s}. Lysstyrke: {v}.','Fargetone-glider','Gjennomsiktighet-glider',
             'Fargeverdi-felt','Fargeformat','Fargeprøve',
             'Metning og lysstyrke. Bruk piltaster.',
             'Tilbakestill til standard','Bekreft fargevalg','Tøm feltverdi'],

        PL: ['Wyczyść','Zamknij','Domyślny',
             'Otwórz selektor kolorów','Zamknij selektor kolorów','Wyczyść wybrany kolor',
             'Nasycenie: {s}. Jasność: {v}.','Suwak odcienia','Suwak przezroczystości',
             'Pole wartości koloru','Format koloru','Próbka koloru',
             'Nasycenie i jasność. Użyj klawiszy strzałek.',
             'Przywróć wartość domyślną','Potwierdź wybór koloru','Wyczyść wartość pola'],

        PT: ['Limpar','Fechar','Padrão',
             'Abrir seletor de cor','Fechar seletor de cor','Limpar a cor selecionada',
             'Saturação: {s}. Brilho: {v}.','Controle de matiz','Controle de opacidade',
             'Campo de valor de cor','Formato de cor','Amostra de cor',
             'Seletor de saturação e brilho. Use as teclas de seta.',
             'Redefinir para o padrão','Confirmar seleção de cor','Limpar valor do campo'],

        RU: ['Очистить','Закрыть','По умолчанию',
             'Открыть выбор цвета','Закрыть выбор цвета','Очистить выбранный цвет',
             'Насыщенность: {s}. Яркость: {v}.','Ползунок оттенка',
             'Ползунок прозрачности','Поле значения цвета','Формат цвета',
             'Образец цвета',
             'Выбор насыщенности и яркости. Используйте клавиши со стрелками.',
             'Сбросить до значения по умолчанию','Подтвердить выбор цвета',
             'Очистить значение поля'],

        SK: ['Vymazať','Zavrieť','Predvolená',
             'Otvoriť výber farieb','Zavrieť výber farieb','Vymazať vybranú farbu',
             'Sýtosť: {s}. Jas: {v}.','Posúvač odtieňa','Posúvač priehľadnosti',
             'Pole hodnoty farby','Formát farby','Vzorka farby',
             'Výber sýtosti a jasu. Použite klávesy so šípkami.',
             'Obnoviť predvolenú hodnotu','Potvrdiť výber farby','Vymazať hodnotu poľa'],

        BG: ['Изчисти','Затвори','По подразбиране',
             'Отвори избор на цвят','Затвори избор на цвят','Изчисти избрания цвят',
             'Наситеност: {s}. Яркост: {v}.','Плъзгач за нюанс',
             'Плъзгач за прозрачност','Поле за стойност на цвят','Формат на цвят',
             'Мостра на цвят',
             'Избор на наситеност и яркост. Използвайте стрелките.',
             'Върни към стойност по подразбиране','Потвърди избора на цвят',
             'Изчисти стойността на полето'],

        CS: ['Vymazat','Zavřít','Výchozí',
             'Otevřít výběr barev','Zavřít výběr barev','Vymazat vybranou barvu',
             'Sytost: {s}. Jas: {v}.','Posuvník odstínu','Posuvník průhlednosti',
             'Pole hodnoty barvy','Formát barvy','Vzorek barvy',
             'Výběr sytosti a jasu. Použijte klávesy se šipkami.',
             'Obnovit výchozí hodnotu','Potvrdit výběr barvy','Vymazat hodnotu pole'],

        ES: ['Borrar','Cerrar','Predeterminado',
             'Abrir selector de color','Cerrar selector de color',
             'Borrar el color seleccionado',
             'Saturación: {s}. Brillo: {v}.','Control de tono','Control de opacidad',
             'Campo de valor de color','Formato de color','Muestra de color',
             'Selector de saturación y brillo. Use las teclas de flecha.',
             'Restablecer al valor predeterminado','Confirmar selección de color',
             'Borrar valor del campo'],

        ET: ['Kustuta','Sulge','Vaikimisi',
             'Ava värvivalija','Sulge värvivalija','Kustuta valitud värv',
             'Küllastus: {s}. Heledus: {v}.','Tooni liugur','Läbipaistvuse liugur',
             'Värviväärtuse väli','Värvivorming','Värvikataloog',
             'Küllastuse ja heleduse valik. Kasuta nooleklahve.',
             'Lähtesta vaikimisi väärtusele','Kinnita värvivalik',
             'Tühjenda välja väärtus'],

        FI: ['Tyhjennä','Sulje','Oletus',
             'Avaa värinvalitsin','Sulje värinvalitsin','Tyhjennä valittu väri',
             'Kylläisyys: {s}. Kirkkaus: {v}.','Sävyn liukusäädin',
             'Läpinäkyvyyden liukusäädin','Väriarvokenttä','Värimuoto','Värinäyte',
             'Kylläisyyden ja kirkkauden valitsin. Käytä nuolinäppäimiä.',
             'Palauta oletusarvo','Vahvista värivalinta','Tyhjennä kentän arvo'],

        GR: ['Καθαρισμός','Κλείσιμο','Προεπιλογή',
             'Άνοιγμα επιλογέα χρώματος','Κλείσιμο επιλογέα χρώματος',
             'Εκκαθάριση επιλεγμένου χρώματος',
             'Κορεσμός: {s}. Φωτεινότητα: {v}.','Ρυθμιστικό απόχρωσης',
             'Ρυθμιστικό αδιαφάνειας','Πεδίο τιμής χρώματος','Μορφή χρώματος',
             'Δείγμα χρώματος',
             'Επιλογέας κορεσμού και φωτεινότητας. Χρησιμοποιήστε τα βέλη.',
             'Επαναφορά στην προεπιλογή','Επιβεβαίωση επιλογής χρώματος',
             'Εκκαθάριση τιμής πεδίου'],

        HU: ['Törlés','Bezárás','Alapértelmezett',
             'Színválasztó megnyitása','Színválasztó bezárása',
             'A kiválasztott szín törlése',
             'Telítettség: {s}. Fényerő: {v}.','Árnyalat csúszka',
             'Átlátszóság csúszka','Színérték mező','Színformátum','Színminta',
             'Telítettség és fényerő választó. Nyílbillentyűkkel navigáljon.',
             'Visszaállítás alapértelmezettre','Színválasztás megerősítése',
             'Mezőérték törlése'],

        IT: ['Cancella','Chiudi','Predefinito',
             'Apri selettore colore','Chiudi selettore colore',
             'Cancella il colore selezionato',
             'Saturazione: {s}. Luminosità: {v}.','Cursore tonalità','Cursore opacità',
             'Campo valore colore','Formato colore','Campione colore',
             'Selettore di saturazione e luminosità. Usa i tasti freccia.',
             'Ripristina il valore predefinito','Conferma selezione colore',
             'Cancella il valore del campo'],

        LV: ['Notīrīt','Aizvērt','Noklusējums',
             'Atvērt krāsu atlasītāju','Aizvērt krāsu atlasītāju',
             'Notīrīt izvēlēto krāsu',
             'Piesātinājums: {s}. Spilgtums: {v}.','Nokrāsas slīdnis',
             'Caurspīdīguma slīdnis','Krāsas vērtības lauks','Krāsas formāts',
             'Krāsas paraugs',
             'Piesātinājuma un spilgtuma atlasītājs. Izmantojiet bulttaustiņus.',
             'Atjaunot noklusēto vērtību','Apstiprināt krāsas izvēli',
             'Notīrīt lauka vērtību'],

        CA: ['Esborra','Tanca','Per defecte',
             'Obre el selector de color','Tanca el selector de color',
             'Esborra el color seleccionat',
             'Saturació: {s}. Brillantor: {v}.','Control de to',
             'Control d\'opacitat','Camp de valor de color','Format de color',
             'Mostra de color',
             'Selector de saturació i brillantor. Usa les tecles de fletxa.',
             'Restablir al valor per defecte','Confirmar la selecció de color',
             'Buidar el valor del camp']
    };

    /* ── Hydrate matrix → WbeColoris options objects ─────────────────────── */
    var _keys  = ['clearLabel', 'closeLabel', 'defaultColorLabel'];
    var _a11yk = ['open','close','clear','marker','hueSlider','alphaSlider',
                  'input','format','swatch','instruction','reset','ok','clearField'];
    var langs  = {};

    for (var lc in _m) {
        var v   = _m[lc];
        var obj = {
            okLabel:         '✓ OK',  // universal
            resetLabel:      '↩',     // universal
            clearFieldLabel: '×',     // universal
            a11y: {}
        };
        for (var i = 0; i < _keys.length;  i++) { obj[_keys[i]]        = v[i];     }
        for (var j = 0; j < _a11yk.length; j++) { obj.a11y[_a11yk[j]] = v[3 + j]; }
        langs[lc] = obj;
    }

    /* ── Aliases ─────────────────────────────────────────────────────────── */
    langs.DE_AT = langs.DE;
    langs.DE_CH = langs.DE;

    /* ── Public API ──────────────────────────────────────────────────────── */
    WbeColoris.langs = langs;

    /**
     * Apply a language by code.
     * @param {string} lang  e.g. 'DE', 'de', 'DE_AT'
     */
    WbeColoris.setLang = function (lang) {
        var code   = (lang || 'EN').toUpperCase();
        var labels = langs[code] || langs[code.substring(0, 2)] || langs.EN;
        WbeColoris(labels);
    };

    /* ── Auto-apply: WBCE sets  var LANGUAGE = "de";  in every admin <head> */
    WbeColoris.ready(function () {
        WbeColoris.setLang(global.LANGUAGE || 'EN');
    });

    /* ── Free raw matrix memory ──────────────────────────────────────────── */
    _m = _keys = _a11yk = null;

})(window);
