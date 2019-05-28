** Questo documento è stato tradotto automaticamente. Si prega di notare che potrebbero esserci alcuni errori o corrispondenze non esatte con la dicitura utilizzata nel back-end. **

# Notizie con immagini: un nuovo modulo di notizie per WBCE CMS
Le notizie con le immagini (abbreviazione: NWI) facilitano la creazione di pagine di notizie o post.
Si basa sul modulo di notizie "vecchio" (3.5.12), ma è stato esteso da diverse funzioni:
- Invia foto
- Galleria di immagini integrate (Masonry o Fotorama)
- 2 ° area di contenuto opzionale
- Ordina gli articoli con il drag & drop
- Spostamento / copia di messaggi tra gruppi e sezioni
- Importazione di argomenti e notizie "classiche"

La funzione di commento rudimentale e insicura del vecchio modulo di notizie è stata abbandonata; se necessario, questa funzione può essere integrata con moduli appropriati (Commenti globali / Commenti facili o Recensioni).

## Scaricare
Il modulo è un modulo base di WBCE CMS 1.4 e installato di default. Inoltre, il download è disponibile nel [WBCE CMS Add-On Repository] (https://addons.wbce.org).

## Licenza
NWI è sotto [GNU General Public License (GPL) v3.0] (http://www.gnu.org/licenses/gpl-3.0.html).

## Requisiti di sistema
NWI non richiede requisiti di sistema speciali e funziona anche con versioni WBCE precedenti e WebsiteBaker.


## installazione
1. Se necessario, scaricare l'ultima versione da [AOR] (https://addons.wbce.org)
2. Come qualsiasi altro modulo WBCE tramite estensioni & gt; Installa i moduli

## Uso

### Iniziare e scrivere
1. Crea una nuova pagina con "Notizie con immagini"
2. Fai clic su "Aggiungi post"
3. Compilare l'intestazione e, se necessario, ulteriori campi, se necessario selezionare le immagini. La funzione dei campi di input è probabilmente auto-esplicativa.
4. Fai clic su "Salva" o "Salva e torna indietro"
5. Ripeti i passaggi da 1 a 4. alcune volte e guarda l'intero nel frontend

Fondamentalmente, NWI può essere combinato con altri moduli su una pagina o in un blocco, ma poi può, come con qualsiasi modulo che genera le proprie pagine di dettaglio, arrivare a risultati che non soddisfano il previsto / desiderabile.

### immagini nel post
Per ogni post è possibile caricare un'immagine di anteprima, che viene visualizzata nella pagina di riepilogo e, se necessario, nella pagina di post. Inoltre, è possibile aggiungere qualsiasi numero di immagini a un post, che vengono visualizzate come una galleria di immagini. La presentazione della galleria è mostrata come una galleria di Fotorama (miniature, immagine a tutta larghezza) o come galleria in muratura (mosaico di immagini).

Quale script di galleria viene utilizzato è impostato per tutti i post nelle impostazioni di ciascuna sezione.

Le immagini della galleria vengono caricate quando il post viene salvato e possono quindi essere didascalie, ricorsi o eliminati.

Quando si caricano file con lo stesso nome di immagini già esistenti, i file esistenti non vengono sovrascritti, ma i seguenti file sono integrati con numerazione progressiva (bild.jpg, bild_1.jpg, ecc.).

La gestione delle immagini avviene solo sulla pagina di posta, non sull'amministrazione dei media WBCE, dal momento che NWI non "sa" diversamente, dove le immagini appartengono / mancano ecc.

### Gruppi
I post possono essere assegnati ai gruppi. Da un lato, ciò ha un'influenza sull'ordine (i post sono ordinati in base al gruppo e quindi secondo un ulteriore criterio da specificare), e dall'altro lato, è possibile generare pagine di panoramica specifiche dell'argomento. A questi si può accedere tramite l'URL della pagina NWI con il parametro g? = GROUP_ID, ad es. news.php? g = 2.

Un post può essere assegnato a un solo gruppo.

I post singoli o multipli possono essere copiati e spostati tra i gruppi.

### funzione di importazione
Finché non è stato creato alcun post nella rispettiva sezione NWI, i post del modulo news classico, altre sezioni NWI e argomenti possono essere importati automaticamente.
Vengono applicate le impostazioni della pagina della pagina di origine. Quando si importano i post degli argomenti, tuttavia, è ancora necessaria la rielaborazione manuale, se la funzione "Immagini aggiuntive" è stata utilizzata in Argomenti.

### Copia / sposta post
Dalla panoramica dei post nel back-end, i singoli, i post selezionati o tutti (contrassegnati) all'interno di una sezione possono essere copiati o copiati o spostati tra diverse sezioni (anche su pagine diverse). I post copiati non sono sempre inizialmente visibili nel frontend (selezione attiva: "no").

### Elimina post
Puoi eliminare singoli, più selezionati o tutti i post (selezionati) dalla panoramica dei post. Dopo aver confermato, i rispettivi post sono irrevocabili ** DESTROYED **, c'è ** no ** modo di ripristinarli!

## configuration
Tutte le regolazioni, tranne che per l'utilizzo di un secondo blocco, possono essere effettuate tramite il back-end nelle impostazioni del modulo (accessibile tramite il pulsante "Opzioni").

### pagina panoramica
- ** Ordina per **: definizione dell'ordine dei post (personalizzato = definizione manuale, i post appaiono come sono disposti nel back-end, data di inizio / data di scadenza / inviata (= data di creazione) / ID di presentazione: ogni ordine decrescente secondo al criterio corrispondente)
- ** Post per pagina **: selezione del numero di voci (immagine teaser / testo) per pagina da visualizzare
- ** header, post loop, footer **: codice HTML per formattare l'output
- ** Resize anteprima immagine a ** Larghezza / altezza dell'immagine in pixel. ** no ** il ricalcolo automatico avverrà se vengono apportate modifiche, quindi è logico pensare in anticipo alla dimensione desiderata e quindi non cambiare di nuovo il valore.

Segnaposti consentiti:
#### Intestazione / piè di pagina
- [NEXT_PAGE_LINK] "Pagina successiva", collegata alla pagina successiva (se la pagina panoramica è suddivisa su più pagine),
- [NEXT_LINK], "Avanti", ad es.
- [PREVIOUS_PAGE_LINK], "Pagina precedente", ad es.
- [PREVIOUS_LINK], "Precedente", s.o.,
- [OUT_OF], [OF], "x of y",
- [DISPLAY_PREVIOUS_NEXT_LINKS] "nascosto" / "visibile", a seconda se è necessaria l'impaginazione
#### post loop
- [PAGE_TITLE] titolo della pagina,
- [GROUP_ID] ID del gruppo a cui è assegnato il post, per i post senza gruppo "0"
- [GROUP_TITLE] Titolo del gruppo a cui è assegnato il post, per i post senza gruppo "",
- [GROUP_IMAGE] Immagine (& lt; img src ... / & gt;) del gruppo a cui è assegnato il post per i post senza gruppo "",
- [DISPLAY_GROUP] * inherit * o * none *,
- [DISPLAY_IMAGE] * inherit * o * none *,
- titolo [TITOLO] (titolo) dell'articolo,
- Immagine [IMAGE] post (& lt; img src = ... / & gt;),
- Breve testo [CORTO],
- [LINK] Link alla vista dettagli dell'articolo,
- [MODI_DATE] data dell'ultima modifica del post,
- [MODI_TIME] Ora (ora) dell'ultima modifica del post,
- [CREATED_DATE] Data in cui il post è stato creato,
- [CREATED_TIME] momento in cui è stato creato il post,
- [PUBLISHED_DATE] data di inizio,
- Ora di inizio [PUBLISHED_TIME],
- [USER_ID] ID del creatore del post,
- Nome utente [USERNAME] del creatore del post,
- [DISPLAY_NAME] Visualizza il nome del creatore del post,
- [EMAIL] Indirizzo email del creatore del post,
- [TEXT_READ_MORE] "Mostra dettagli",
- [SHOW_READ_MORE], * nascosto * o * visibile *,
- [GROUP_IMAGE_URL] URL dell'immagine del gruppo

### visualizzazione post
- ** Intestazione messaggio, contenuto, piè di pagina, blocco 2 **: codice HTML per la formattazione del messaggio

Segnaposti consentiti:
#### Intestazione del messaggio, Piè di pagina del messaggio, Blocco 2
- [PAGE_TITLE] titolo della pagina,
- [GROUP_ID] ID del gruppo a cui è assegnato il post, per i post senza gruppo "0"
- [GROUP_TITLE] Titolo del gruppo a cui è assegnato il post, per i post senza gruppo "",
- [GROUP_IMAGE] Immagine (& lt; img src ... / & gt;) del gruppo a cui è assegnato il post per i post senza gruppo "",
- [DISPLAY_GROUP] * inherit * o * none *,
- [DISPLAY_IMAGE] * inherit * o * none *,
- titolo [TITOLO] (titolo) dell'articolo,
- Immagine [IMAGE] post (& lt; img src = ... / & gt;),
- Breve testo [CORTO],
- [MODI_DATE] data dell'ultima modifica del post,
- [MODI_TIME] Ora (ora) dell'ultima modifica del post,
- [CREATED_DATE] Data in cui il post è stato creato,
- [CREATED_TIME] momento in cui è stato creato il post,
- [PUBLISHED_DATE] data di inizio,
- Ora di inizio [PUBLISHED_TIME],
- [USER_ID] ID del creatore del post,
- Nome utente [USERNAME] del creatore del post,
- [DISPLAY_NAME] Visualizza il nome del creatore del post,
- [EMAIL] Indirizzo email del creatore del post

#### contenuto di notizie
- [CONTENUTO] Post contenuti (HTML)
- [IMMAGINI] Immagini / Galleria HTML

### Galleria / Impostazioni immagine
- ** Galleria immagini **: selezione dello script della galleria da utilizzare. Si noti che eventuali personalizzazioni apportate al codice galleria nel campo Contenuto messaggio andranno perse in caso di modifica.
- ** Loop immagine **: il codice HTML per la rappresentazione di una singola immagine deve corrispondere al rispettivo script della galleria
- ** Max. Dimensione immagine in byte **: dimensione file per file immagine, perché ora deve essere specificata in byte e non in KB o MB leggibile, semplicemente non lo so
- ** Ridimensiona le immagini della galleria in / Dimensioni miniatura larghezza x altezza **: esattamente uguale. ** no ** il ricalcolo automatico avverrà se vengono apportate modifiche, quindi è logico pensare in anticipo alla dimensione desiderata e quindi non cambiare di nuovo il valore.
- ** Ritaglia **: vedere la spiegazione sulla pagina.

### 2 ° blocco
Facoltativamente, un secondo blocco può essere visualizzato se il modello lo supporta.
- Usa blocco 2 (predefinito): Nessuna voce o voce * define ('NWI_USE_SECOND_BLOCK', true); * nel config.php nella radice
- non utilizzare il blocco 2: entry * define ('NWI_USE_SECOND_BLOCK', false); * nel config.php nella radice