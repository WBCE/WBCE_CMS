<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

// Descrizione modulo
$module_description = "Modulo per la creazione di notizie con immagine dell'articolo e galleria di articoli (opzionale).";

// Variabili per il back-end
$MOD_NEWS_IMG['ACTION'] = "Post selezionate";
$MOD_NEWS_IMG['ACTIVATE'] = "activate";
$MOD_NEWS_IMG['ACTIVATE_POST'] = "attiva post";
$MOD_NEWS_IMG['ADD_GROUP'] = 'Aggiungi gruppo';
$MOD_NEWS_IMG['ADD_POST'] = 'Aggiungi post';
$MOD_NEWS_IMG['ADD_TAG'] = 'Add tag'; //missing
$MOD_NEWS_IMG['ADVANCED_SETTINGS'] = 'Advanced settings'; //missing
$MOD_NEWS_IMG['ALL'] = "Tutto";
$MOD_NEWS_IMG['ASSIGN_GROUP'] = "Assign group"; //missing
$MOD_NEWS_IMG['ASSIGN_TAGS'] = "Assign tags"; //missing
$MOD_NEWS_IMG['CONTINUE'] = "Successivo";
$MOD_NEWS_IMG['COPY'] = "copia";
$MOD_NEWS_IMG['COPY_WITH_TAGS'] = 'copy (incl. tags)'; //missing
$MOD_NEWS_IMG['COPY_POST'] = 'Copia post';
$MOD_NEWS_IMG['CURRENT_SECTION'] = 'Current section'; //missing
$MOD_NEWS_IMG['DEACTIVATE'] = "disattiva";
$MOD_NEWS_IMG['DEACTIVATE_POST'] = "disattiva post";
$MOD_NEWS_IMG['DELETE'] = "elimina";
$MOD_NEWS_IMG['DELETEIMAGE'] = 'Elimina questa immagine?';
$MOD_NEWS_IMG['DESCENDING'] = 'absteigend'; //missing
$MOD_NEWS_IMG['EXPERT_MODE'] = 'expert mode'; //missing
$MOD_NEWS_IMG['EXPIRED_NOTE'] = 'Il post non viene pi&ugrave; visualizzato nel frontend perch&eacute; &egrave; scaduta la data di scadenza.';
$MOD_NEWS_IMG['FIRST_EXPIRING_LAST'] = 'first expiring last'; //missing
$MOD_NEWS_IMG['GALLERY_SETTINGS'] = 'Galleria / impostazioni immagine';
$MOD_NEWS_IMG['GALLERYIMAGES'] = 'Immagini della galleria';
$MOD_NEWS_IMG['GENERIC_IMAGE_ERROR'] = 'Problemi con immagini post e / o galleria. Controlla il nome del file, il tipo di file e la dimensione del file.';
$MOD_NEWS_IMG['GLOBAL'] = 'Global tag'; //missing
$MOD_NEWS_IMG['GOBACK'] = 'Indietro';
$MOD_NEWS_IMG['GROUP'] = 'Group'; //missing
$MOD_NEWS_IMG['GROUPS'] = 'Groups'; //missing
$MOD_NEWS_IMG['IMAGE_FILENAME_ERROR'] = 'Il nome del file &egrave; troppo lungo (massimo 256 caratteri consentiti)';
$MOD_NEWS_IMG['IMAGE_INVALID_TYPE'] = 'Tipo di immagine non supportato';
$MOD_NEWS_IMG['IMAGE_LARGER_THAN'] = "L'immagine &egrave; troppo grande, max. taglia: ";
$MOD_NEWS_IMG['IMAGE_TOO_SMALL'] = "L'immagine &egrave troppo piccola";
$MOD_NEWS_IMG['IMAGEUPLOAD'] = 'Carica immagini';
$MOD_NEWS_IMG['IMPORT_OPTIONS'] = "Importa le impostazioni";
$MOD_NEWS_IMG['INFO_GLOBAL'] = "Global tags can be used in all news with images sections."; //missing
$MOD_NEWS_IMG['INFO_RELOAD_PAGE'] = "This will reload the page; all unsaved data will be lost!"; //missing
$MOD_NEWS_IMG['LINK'] = 'Link';
$MOD_NEWS_IMG['LOAD_VALUES'] = "Carica valori";
$MOD_NEWS_IMG['MANAGE_POSTS'] = "gestici i post";
$MOD_NEWS_IMG['MOVE'] = "sposta";
$MOD_NEWS_IMG['MOVE_WITH_TAGS'] = 'move (incl. tags)'; //missing
$MOD_NEWS_IMG['NEW_POST'] = 'Crea nuovo post';
$MOD_NEWS_IMG['NEWEST_FIRST'] = 'most recent on top'; //missing
$MOD_NEWS_IMG['NONE'] = "No";
$MOD_NEWS_IMG['OPTIONS'] = 'Opzioni';
$MOD_NEWS_IMG['OR'] = 'or'; //missing
$MOD_NEWS_IMG['ORDER_CUSTOM_INFO'] = "L'impostazione &quot;personalizzata&quot; consente la selezione manuale degli articoli tramite frecce su / gi&ugrve;. ";
$MOD_NEWS_IMG['ORDERBY'] = 'Ordina per';
$MOD_NEWS_IMG['OVERVIEW_SETTINGS'] = 'Impostazioni pagina panoramica';
$MOD_NEWS_IMG['POST_ACTIVE'] = 'Post is visible'; //missing
$MOD_NEWS_IMG['POST_CONTENT'] = 'Pubblica contenuto';
$MOD_NEWS_IMG['POST_INACTIVE'] = 'Post is not visible'; //missing
$MOD_NEWS_IMG['POST_SETTINGS'] = 'Impostazioni post';
$MOD_NEWS_IMG['POSTED_BY'] = 'Posted by'; //missing
$MOD_NEWS_IMG['POSTS'] = 'Posts'; //missing
$MOD_NEWS_IMG['PREVIEWIMAGE'] = 'Anteprima immagine';
$MOD_NEWS_IMG['SAVEGOBACK'] = 'Salva e torna indietro';
$MOD_NEWS_IMG['SETTINGS'] = 'Impostazioni Notizie';
$MOD_NEWS_IMG['TAG'] = 'Tag'; //missing
$MOD_NEWS_IMG['TAG_COLOR'] = 'Tag color'; //missing
$MOD_NEWS_IMG['TAG_EXISTS'] = 'Tag exists'; //missing
$MOD_NEWS_IMG['TAGS'] = 'Tags'; //missing
$MOD_NEWS_IMG['TAGS_INFO'] = 'To use tags, edit a post and select the desired posts there.'; //missing
$MOD_NEWS_IMG['TO'] = "a";
$MOD_NEWS_IMG['UPLOAD'] = 'Upload'; //missing
$MOD_NEWS_IMG['USE_SECOND_BLOCK'] = 'Use second block'; //missing
$MOD_NEWS_IMG['USE_SECOND_BLOCK_HINT'] = 'Must be supported by the template'; //missing
$MOD_NEWS_IMG['VIEW'] = 'Presentation / View'; //missing
$MOD_NEWS_IMG['VIEW_INFO'] = 'After changing the setting, hit save; the markups for post loop and post details view will be adjusted automatically.'; //missing

// Impostazioni dell'immagine
$MOD_NEWS_IMG['CROP'] = 'Ritaglia';
$MOD_NEWS_IMG['GALLERY'] = 'Galleria immagini';
$MOD_NEWS_IMG['GALLERY_INFO'] = "Dopo aver modificato l'impostazione della galleria, premi save; il markup per il loop dell'immagine verr&agrave;  regolato automaticamente. ";
$MOD_NEWS_IMG['GALLERY_WARNING'] = "E sicuro? Nota che le impostazioni personalizzate per il markup del loop dell'immagine andranno perse.";
$MOD_NEWS_IMG['IMAGE_MAX_SIZE'] = "Max. dimensione dell'immagine in kilobyte ";
$MOD_NEWS_IMG['RESIZE_PREVIEW_IMAGE_TO'] = "Ridimensiona l'immagine di anteprima a";
$MOD_NEWS_IMG['RESIZE_GALLERY_IMAGES_TO'] = "Ridimensiona le immagini della galleria su";
$MOD_NEWS_IMG['TEXT_CROP'] = "Se lo aspect ratio dell'originale non corrisponde allo aspect ratio specificato, la sovrapposizione del bordo pi&ugrave; lungo verr&agrave; troncata.";
$MOD_NEWS_IMG['TEXT_DEFAULTS'] = 'Dimensioni predefinite';
$MOD_NEWS_IMG['TEXT_DEFAULTS_CLICK'] = 'Fai clic per scegliere tra i valori predefiniti';
$MOD_NEWS_IMG['THUMB_SIZE'] = 'Dimensione anteprima';

// Uploader
$MOD_NEWS_IMG['DRAG_N_DROP_HERE'] = 'Trascina &amp; rilasciare i file qui';
$MOD_NEWS_IMG['CLICK_TO_ADD'] = 'Clicca per aggiungere file';
$MOD_NEWS_IMG['NO_FILES_UPLOADED'] = 'Nessun file caricato.';
$MOD_NEWS_IMG['COMPLETE_MESSAGE'] = 'Salva le modifiche per mostrare il caricamento nella galleria';

// Variabili per il frontend
$MOD_NEWS_IMG['PAGE_NOT_FOUND'] = 'Pagina non trovata';
$MOD_NEWS_IMG['TEXT_AT'] = 'il';
$MOD_NEWS_IMG['TEXT_BACK'] = 'Indietro';
$MOD_NEWS_IMG['TEXT_BY'] = 'per';
$MOD_NEWS_IMG['TEXT_LAST_CHANGED'] = 'Ultima modifica';
$MOD_NEWS_IMG['TEXT_NEXT_POST'] = 'Prossimo post';
$MOD_NEWS_IMG['TEXT_O_CLOCK'] = 'Prossimo post';
$MOD_NEWS_IMG['TEXT_ON'] = 'il';
$MOD_NEWS_IMG['TEXT_POSTED_BY'] = 'Inserito da';
$MOD_NEWS_IMG['TEXT_PREV_POST'] = 'Messaggio post';
$MOD_NEWS_IMG['TEXT_READ_MORE'] = 'Leggi altro';
$MOD_NEWS_IMG['TEXT_RESET'] = 'Ripristina';
$MOD_NEWS_IMG['TO'] = "per";
$MOD_NEWS_IMG['IMPORT'] = 'importa';
