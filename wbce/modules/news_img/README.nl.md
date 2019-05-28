** Dit document is automatisch vertaald. Let op: er kunnen enkele fouten of niet-exacte overeenkomsten zijn met de feitelijke bewoording die in de backend wordt gebruikt. **

# Nieuws met afbeeldingen: een nieuwe nieuwsmodule voor WBCE CMS
Nieuws met afbeeldingen (kort: NWI) maakt het eenvoudig om nieuwspagina's of berichten te maken.
Het is gebaseerd op de "oude" nieuwsmodule (3.5.12), maar is uitgebreid met verschillende functies:
- Plaats foto
- geïntegreerde fotogalerij (metselwerk of Fotorama)
- optioneel 2e inhoudsgebied
- Sorteer artikelen met slepen & neerzetten
- Verplaatsen / kopiëren van berichten tussen groepen en secties
- Import van onderwerpen en "Classic" Nieuws

De rudimentaire en onzekere commentaarfunctie van de oude nieuwsmodule is weggelaten; indien nodig kan deze functie worden geïntegreerd met de juiste modules (algemene opmerkingen / eenvoudige opmerkingen of beoordelingen).

## Download
De module is een kernmodule vanaf WBCE CMS 1.4 en wordt standaard geïnstalleerd. Bovendien is de download beschikbaar in de [WBCE CMS Add-on Repository] (https://addons.wbce.org).

## Licentie
NWI valt onder [GNU General Public License (GPL) v3.0] (http://www.gnu.org/licenses/gpl-3.0.html).

## Systeem vereisten
NWI vereist geen speciale systeemvereisten en werkt ook met oudere WBCE-versies en WebsiteBaker.


## installatie
1. Download indien nodig de laatste versie van [AOR] (https://addons.wbce.org)
2. Zoals elke andere WBCE-module via extensies & gt; Installeer modules

## Gebruik

### Aan de slag en schrijven
1. Maak een nieuwe pagina met "Nieuws met afbeeldingen"
2. Klik op "Bericht toevoegen"
3. Vul de kop en, indien nodig, verdere velden in, selecteer indien nodig afbeeldingen. De functie van de invoervelden is waarschijnlijk zelfverklarend.
4. Klik op "Opslaan" of "Opslaan en teruggaan"
5. Herhaal stap 1. - 4. een paar keer en bekijk het geheel in de frontend

Kort gezegd kan NWI worden gecombineerd met andere modules op een pagina of in een blok, maar dan kan het, net als met elke module die zijn eigen detailpagina's genereert, resultaten bereiken die niet aan de verwachte / gewenste verwachtingen voldoen.

### foto's in de post
Voor elk bericht kan een voorbeeldafbeelding worden geüpload, die wordt weergegeven op de overzichtspagina en indien nodig de berichtpagina. Bovendien is het mogelijk om een ??willekeurig aantal afbeeldingen aan een bericht toe te voegen, die worden weergegeven als een fotogalerij. De galerijpresentatie wordt weergegeven als een Fotorama-galerij (miniaturen, volledige breedte afbeelding) of als een maquette galerij (beeldmozaïek).

Welk galerijscript wordt gebruikt, is ingesteld voor alle berichten in de instellingen van elke sectie.

De galerijafbeeldingen worden geüpload terwijl het bericht wordt opgeslagen en kunnen vervolgens worden onderschriftt, gebruikt of verwijderd.

Bij het uploaden van bestanden met dezelfde naam als bestaande afbeeldingen worden de bestaande bestanden niet overschreven, maar de volgende bestanden worden aangevuld met opeenvolgende nummering (bild.jpg, bild_1.jpg, etc.).

Het beheer van de afbeeldingen vindt alleen plaats via de berichtpagina, niet via het WBCE-mediabeheer, aangezien NWI anders niet "weet" waar welke afbeeldingen horen / ontbreken enz.

### Groepen
Posts kunnen aan groepen worden toegewezen. Dit heeft enerzijds invloed op de volgorde (de berichten worden gesorteerd op basis van de groep en vervolgens op basis van een nader te specificeren criterium), en aan de andere kant is het mogelijk om onderwerpspecifieke overzichtspagina's te genereren. Deze kunnen dan worden benaderd via de URL van de NWI-pagina met de parameter g? = GROUP_ID, b.v. news.php? G = 2.

Een bericht kan slechts aan één groep worden toegewezen.

Enkele of meerdere berichten kunnen worden gekopieerd en tussen groepen worden verplaatst.

### importfunctie
Zolang er geen berichten in de betreffende NWI-sectie zijn geplaatst, berichten van de klassieke nieuwsmodule, kunnen andere NWI-secties evenals onderwerpen automatisch worden geïmporteerd.
De pagina-instellingen van de bronpagina worden toegepast. Bij het importeren van Onderwerpen-berichten is handmatige herbewerking echter nog steeds vereist, als de functie "Extra afbeeldingen" werd gebruikt in Onderwerpen.

### Berichten kopiëren / verplaatsen
Vanuit het postoverzicht in de backend kunnen afzonderlijke, meerdere geselecteerde of alle (gemarkeerde) berichten in een sectie worden gekopieerd of gekopieerd of verplaatst tussen verschillende secties (zelfs op verschillende pagina's). Gekopieerde berichten zijn altijd in eerste instantie niet zichtbaar in de frontend (actieve selectie: "nee").

### Berichten verwijderen
U kunt enkele, meerdere geselecteerde of alle (geselecteerde) berichten verwijderen uit het berichtoverzicht. Na bevestiging zijn de respectieve berichten onherroepelijk ** VERNIETIGD **, er is ** geen ** manier om ze te herstellen!

## configuratie
Alle aanpassingen, behalve of een tweede blok moet worden gebruikt, kunnen via de backend in de module-instellingen worden gemaakt (toegankelijk via de knop "Opties").

### overzichtspagina
- ** Bestelling op **: definitie van de volgorde van berichten (aangepast = handmatige definitie, berichten verschijnen zoals ze zijn gerangschikt in de backend, startdatum / vervaldatum / ingediend (= aanmaakdatum) / Inzending-ID: elke aflopende volgorde volgens aan het overeenkomstige criterium)
- ** Berichten per pagina **: selectie van het aantal vermeldingen (teaserafbeelding / tekst) per pagina moet worden weergegeven
- ** header, post loop, footer **: HTML-code om de uitvoer te formatteren
- ** Resize preview afbeelding tot ** Breedte / hoogte van de afbeelding in pixels. ** nee ** automatische herberekening vindt plaats als er wijzigingen worden aangebracht, dus is het logisch om van tevoren over de gewenste grootte na te denken en de waarde daarna niet opnieuw te wijzigen.

Toegestane tijdelijke aanduidingen:
#### Koptekst / voettekst
- [NEXT_PAGE_LINK] "Volgende pagina", gekoppeld aan de volgende pagina (als de overzichtspagina over meerdere pagina's is verdeeld),
- [NEXT_LINK], "Volgende", s.o.,
- [PREVIOUS_PAGE_LINK], "Vorige pagina", s.o.,
- [PREVIOUS_LINK], "Vorige", s.o.,
- [OUT_OF], [OF], "x of y",
- [DISPLAY_PREVIOUS_NEXT_LINKS] "hidden" / "visible", afhankelijk van of paginering vereist is
#### na lus
- [PAGE_TITLE] kop van de pagina,
- [GROUP_ID] ID van de groep waaraan het bericht is toegewezen, voor berichten zonder groep '0'
- [GROUP_TITLE] Titel van de groep waaraan het bericht is toegewezen, voor berichten zonder groep "",
- [GROUP_IMAGE] Afbeelding (& lt; img src ... / & gt;) van de groep waaraan het bericht is toegewezen voor berichten zonder groep "",
- [DISPLAY_GROUP] * erven * of * geen *,
- [DISPLAY_IMAGE] * erven * of * geen *,
- [TITLE] titel (kop) van het artikel,
- [IMAGE] post afbeelding (& lt; img src = ... / & gt;),
- [KORT] korte tekst,
- [LINK] Link naar de detailweergave van het artikel
- [MODI_DATE] datum van de laatste wijziging van de post,
- [MODI_TIME] Tijd (tijd) van de laatste wijziging van de post,
- [CREATED_DATE] Datum waarop het bericht is gemaakt,
- [CREATED_TIME] het tijdstip waarop het bericht is gemaakt,
- [PUBLISHED_DATE] startdatum,
- [PUBLISHED_TIME] starttijd,
- [USER_ID] ID van de maker van het bericht,
- [USERNAME] gebruikersnaam van de maker van het bericht,
- [DISPLAY_NAME] Weergavenaam van de maker van het bericht,
- [EMAIL] E-mailadres van de maker van het bericht,
- [TEXT_READ_MORE] "Details tonen",
- [SHOW_READ_MORE], * hidden * of * visible *,
- [GROUP_IMAGE_URL] URL van de groepsafbeelding

### berichtweergave
- ** Berichtkop, inhoud, voettekst, blok 2 **: HTML-code voor het indelen van het bericht

Toegestane tijdelijke aanduidingen:
#### Berichtkop, berichtvoettekst, blok 2
- [PAGE_TITLE] kop van de pagina,
- [GROUP_ID] ID van de groep waaraan het bericht is toegewezen, voor berichten zonder groep '0'
- [GROUP_TITLE] Titel van de groep waaraan het bericht is toegewezen, voor berichten zonder groep "",
- [GROUP_IMAGE] Afbeelding (& lt; img src ... / & gt;) van de groep waaraan het bericht is toegewezen voor berichten zonder groep "",
- [DISPLAY_GROUP] * erven * of * geen *,
- [DISPLAY_IMAGE] * erven * of * geen *,
- [TITLE] titel (kop) van het artikel,
- [IMAGE] post afbeelding (& lt; img src = ... / & gt;),
- [KORT] korte tekst,
- [MODI_DATE] datum van de laatste wijziging van de post,
- [MODI_TIME] Tijd (tijd) van de laatste wijziging van de post,
- [CREATED_DATE] Datum waarop het bericht is gemaakt,
- [CREATED_TIME] het tijdstip waarop het bericht is gemaakt,
- [PUBLISHED_DATE] startdatum,
- [PUBLISHED_TIME] starttijd,
- [USER_ID] ID van de maker van het bericht,
- [USERNAME] gebruikersnaam van de maker van het bericht,
- [DISPLAY_NAME] Weergavenaam van de maker van het bericht,
- [EMAIL] E-mailadres van de maker van het bericht

#### nieuwsinhoud
- [CONTENT] Inhoud plaatsen (HTML)
- [IMAGES] Afbeeldingen / Galerij HTML

### Galerij / Beeldinstellingen
- ** Afbeeldingengalerij **: selectie van het te gebruiken galerij-script. Houd er rekening mee dat eventuele aanpassingen aan de galerijcode in het veld Berichtinhoud verloren gaan in het geval van een wijziging.
- ** Afbeelding herhalen **: HTML-code voor de weergave van één afbeelding moet overeenkomen met het respectieve galerij-script
- ** Max. Afbeeldingsgrootte in bytes **: bestandsgrootte per afbeeldingsbestand, waarom dit nu moet worden opgegeven in bytes en niet in leesbare KB of MB, ik weet het gewoon niet
- ** Het formaat van galerijafbeeldingen aanpassen aan / Grootte miniatuurbreedte x hoogte **: exact hetzelfde. ** nee ** automatische herberekening vindt plaats als er wijzigingen worden aangebracht, dus is het logisch om van tevoren over de gewenste grootte na te denken en de waarde daarna niet opnieuw te wijzigen.
- ** Uitsnijden **: zie de uitleg op de pagina.

### 2e blok
Optioneel kan een tweede blok worden weergegeven als de sjabloon dit ondersteunt.
- Gebruik blok 2 (standaard): geen invoer of item * define ('NWI_USE_SECOND_BLOCK', true); * in de config.php in de root
- gebruik geen block 2: entry * define ('NWI_USE_SECOND_BLOCK', false); * in de config.php in de root