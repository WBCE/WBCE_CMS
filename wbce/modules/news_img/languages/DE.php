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

//Modul Description
$module_description = 'Modul zum Erstellen von Newsbeitr&auml;gen mit optionalem Beitragsbild und Beitrags-Bildergalerie. Siehe README.md für weitere Informationen';

//Variables for the backend
$MOD_NEWS_IMG['ADD_GROUP'] = 'Gruppe anlegen';
$MOD_NEWS_IMG['ADD_POST'] = 'Beitrag verfassen';
$MOD_NEWS_IMG['NEW_POST'] = 'Neuen Beitrag verfassen';
$MOD_NEWS_IMG['COPY_POST'] = 'Beitrag kopieren';
$MOD_NEWS_IMG['DELETEIMAGE'] ='Bild l&ouml;schen?';
$MOD_NEWS_IMG['GALLERY_SETTINGS'] ='Galerie-/Bild-Einstellungen';
$MOD_NEWS_IMG['GALLERYIMAGES'] = 'Galeriebilder';
$MOD_NEWS_IMG['GENERIC_IMAGE_ERROR'] ='Problem mit Vorschau- und/oder Galeriebild(ern). Bitte Dateinamen,-formate und -gr&ouml;&szlig;en &uuml;berpr&uuml;fen!';
$MOD_NEWS_IMG['GOBACK'] = 'Zur&uuml;ck';
$MOD_NEWS_IMG['IMAGE_FILENAME_ERROR'] ='Dateiname ist zu lang (erlaubt: max. 256 Zeichen)';
$MOD_NEWS_IMG['IMAGE_INVALID_TYPE'] = 'Nicht unterst&uuml;tzter Bildtyp';
$MOD_NEWS_IMG['IMAGE_LARGER_THAN'] ='Bild ist zu gro&szlig, max. erlaubt: ';
$MOD_NEWS_IMG['IMAGE_TOO_SMALL'] = 'Bild ist zu klein';
$MOD_NEWS_IMG['IMAGEUPLOAD'] = 'Bilder hochladen';
$MOD_NEWS_IMG['OPTIONS'] ='Einstellungen';
$MOD_NEWS_IMG['ORDERBY']  = 'Sortierung';
$MOD_NEWS_IMG['ORDER_CUSTOM_INFO'] = 'Die Einstellung &quot;benutzerdefiniert&quot; erlaubt das manuelle Sortieren der Beitr&auml;ge &uuml;ber auf-/ab-Pfeile.';
$MOD_NEWS_IMG['OVERVIEW_SETTINGS'] ='&Uuml;bersichtsseite';
$MOD_NEWS_IMG['POST_CONTENT'] = 'Nachrichten-Inhalt';
$MOD_NEWS_IMG['POST_SETTINGS'] = 'Beitragsansicht';
$MOD_NEWS_IMG['PREVIEWIMAGE'] = 'Beitragsbild';
$MOD_NEWS_IMG['SAVEGOBACK'] = 'Speichern und Zur&uuml;ck';
$MOD_NEWS_IMG['SETTINGS'] = 'News Einstellungen';
$MOD_NEWS_IMG['LINK'] = 'Link';
$MOD_NEWS_IMG['EXPIRED_NOTE'] = 'Der Beitrag wird im Frontend nicht mehr angezeigt, da das Ablaufdatum überschritten ist.';
$MOD_NEWS_IMG['ACTION'] = "Markierte Beiträge";
$MOD_NEWS_IMG['COPY'] = "kopieren";
$MOD_NEWS_IMG['MOVE'] = "verschieben";
$MOD_NEWS_IMG['DELETE'] = "l&ouml;schen";
$MOD_NEWS_IMG['TO'] = "nach";
$MOD_NEWS_IMG['CONTINUE'] = "Weiter";
$MOD_NEWS_IMG['IMPORT_OPTIONS'] = "Einstellungen importieren";
$MOD_NEWS_IMG['LOAD_VALUES'] = "Werte laden";
$MOD_NEWS_IMG['ALL'] = "Alle";
$MOD_NEWS_IMG['ACTIVATE_POST'] = "Beitrag aktivieren";
$MOD_NEWS_IMG['DEACTIVATE_POST'] = "Beitrag deaktivieren";
$MOD_NEWS_IMG['MANAGE_POSTS'] = "Beitr&auml;ge verwalten";
$MOD_NEWS_IMG['ACTIVATE'] = "aktivieren";
$MOD_NEWS_IMG['DEACTIVATE'] = "deaktivieren";
$MOD_NEWS_IMG['NONE'] = "keine";

//image settings
$MOD_NEWS_IMG['CROP'] = 'Beschneiden';
$MOD_NEWS_IMG['GALLERY'] = 'Bildergalerie';
$MOD_NEWS_IMG['GALLERY_INFO'] = 'Nach dem &Auml;ndern der Galerie-Einstellung speichern; danach wird automatisch das Markup angepasst.';
$MOD_NEWS_IMG['GALLERY_WARNING'] = 'Sind Sie sicher? Beachten Sie, dass die Anpassungen f&uuml;r das Markup der Image Schleife verloren gehen.';
$MOD_NEWS_IMG['IMAGE_MAX_SIZE'] = 'Max. Bildgr&ouml;&szlig;e in Kilobytes';
$MOD_NEWS_IMG['RESIZE_GALLERY_IMAGES_TO'] = 'Galeriebilder Gr&ouml;&szlig;e &auml;ndern auf';
$MOD_NEWS_IMG['RESIZE_PREVIEW_IMAGE_TO'] = 'Vorschaubild Gr&ouml;&szlig;e &auml;ndern auf';
$MOD_NEWS_IMG['TEXT_CROP'] = 'Wenn das Seitenverh&auml;ltnis des Originals nicht zum eingestellten Seitenverh&auml;ltnis passt, wird der &Uuml;berstand der l&auml;ngeren Seite abgeschnitten.';
$MOD_NEWS_IMG['TEXT_DEFAULTS'] = 'Vorschl&auml;ge';
$MOD_NEWS_IMG['TEXT_DEFAULTS_CLICK'] = 'Anklicken um Gr&ouml;&szlig;e zu w&auml;hlen';
$MOD_NEWS_IMG['THUMB_SIZE'] = 'Gr&ouml;&szlig;e der Thumbnails';
$MOD_NEWS_IMG['MISSING_GD'] = 'WICHTIGER HINWEIS: Die GD-Bibliothek fehlt, es wird nicht möglich sein, die Größe hochgeladener Bilder automatisch anzupassen!';

// Uploader
$MOD_NEWS_IMG['DRAG_N_DROP_HERE'] = 'Dateien hier mit Drag &amp; Drop ablegen';
$MOD_NEWS_IMG['CLICK_TO_ADD'] = 'Klicken, um Dateien hinzuzuf&uuml;gen';
$MOD_NEWS_IMG['NO_FILES_UPLOADED'] = 'Keine Dateien hochgeladen.';
$MOD_NEWS_IMG['COMPLETE_MESSAGE'] = 'Speichern Sie Ihre &Auml;nderungen, um den Upload in der Galerie anzuzeigen';

//Variables for the frontend
$MOD_NEWS_IMG['TEXT_READ_MORE'] = 'Details anzeigen';
$MOD_NEWS_IMG['TEXT_POSTED_BY'] = 'Ver&ouml;ffentlicht von';
$MOD_NEWS_IMG['TEXT_ON'] = 'am';
$MOD_NEWS_IMG['TEXT_LAST_CHANGED'] = 'Zuletzt ge&auml;ndert am';
$MOD_NEWS_IMG['TEXT_AT'] = 'um';
$MOD_NEWS_IMG['TEXT_BACK'] = 'Zur&uuml;ck zur &Uuml;bersicht';
$MOD_NEWS_IMG['TEXT_BY'] = 'von';
$MOD_NEWS_IMG['PAGE_NOT_FOUND'] = 'Seite nicht gefunden';
$MOD_NEWS_IMG['IMPORT'] = 'importieren';

