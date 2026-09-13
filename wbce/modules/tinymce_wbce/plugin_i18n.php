<?php
/**
 * tinymce_wbce — plugin_i18n.php
 * Builds the translation map for the editor plugins (wblink, wbdroplets,
 * wbcodemirror). The map is injected as window.TINYMCE_WBCE_I18N by both
 * include.php (real editors on backend pages) and tool.php (preview editors
 * in the toolbar configurator), so PHP stays the single source of truth.
 *
 * Keys resolve through the module language files in languages/ (EN base,
 * active language on top) via the core i18n registry (framework/i18n).
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) { return; }

if (!function_exists('tinymce_wbce_plugin_i18n')) {

    function tinymce_wbce_plugin_i18n(): array
    {
        Lang::loadLanguage(__DIR__); // idempotent

        global $MENU;

        return [
            // shared dialog buttons
            'insert'         => L_('TXT:INSERT'),
            'cancel'         => L_('TXT:CANCEL'),
            // wblink — internal page link dialog
            'wblinkTitle'    => L_('TXT:WBLINK_TITLE'),
            'wblinkSearch'   => L_('TXT:WBLINK_SEARCH'),
            'wblinkText'     => L_('TXT:WBLINK_TEXT'),
            'wblinkOpenIn'   => L_('TXT:WBLINK_OPEN_IN'),
            'wblinkSameTab'  => L_('TXT:WBLINK_SAME_TAB'),
            'wblinkNewTab'   => L_('TXT:WBLINK_NEW_TAB'),
            'wblinkNoPages'  => L_('TXT:WBLINK_NO_PAGES'),
            'wblinkNoResults'=> L_('TXT:WBLINK_NO_RESULTS'),
            // wbdroplets
            'dropletTitle'   => L_('TXT:DROPLET_TITLE'),
            'dropletNone'    => L_('TXT:DROPLET_NONE'),
            // wbcodemirror
            'cmTooltip'      => L_('TXT:CM_TOOLTIP'),
            // wbce_shy
            'shyTooltip'     => L_('TXT:SHY_TOOLTIP'),
            // image size badge (js/image-size-badge.js)
            'imgBadgeReset'  => L_('TXT:IMG_BADGE_RESET'),
            // image alt reminder (js/image-alt-badge.js)
            'imgAltMissing'  => L_('TXT:IMG_ALT_MISSING'),
            // wbce_codesample
            'codeSample'     => [
                'tooltip' => L_('TXT:CODESAMPLE_TITLE'),
            ],
            // wbce_history
            'history'        => [
                'title'    => L_('TXT:HISTORY_TITLE'),
                'choose'   => L_('TXT:HISTORY_CHOOSE'),
                'restore'  => L_('TXT:HISTORY_RESTORE'),
                'restored' => L_('TXT:HISTORY_RESTORED'),
                'none'     => L_('TXT:HISTORY_NONE'),
                'close'    => L_('TXT:HISTORY_CLOSE'),
                'current'  => L_('TXT:HISTORY_CURRENT'),
                'delete'        => L_('TXT:HISTORY_DELETE'),
                'confirmDelete' => L_('TEXT:ARE_YOU_SURE'),
                'cancel'   => L_('TXT:CANCEL'),
            ],
            // wbce_casechange — "Aa" dropdown
            'caseChange'     => [
                'tooltip'   => L_('TXT:CASE_TOOLTIP'),
                'lower'     => L_('TXT:CASE_LOWER'),
                'upper'     => L_('TXT:CASE_UPPER'),
                'title'     => L_('TXT:CASE_TITLE'),
                'smallcaps' => L_('TXT:CASE_SMALLCAPS'),
            ],
            // fa_picker — Font Awesome icon dialog
            'faPicker'       => [
                'title'     => L_('TXT:FA_TITLE'),
                'search'    => L_('TXT:FA_SEARCH'),
                'size'      => L_('TXT:FA_SIZE'),
                'color'     => L_('TXT:FA_COLOR'),
                'inherit'   => L_('TXT:FA_INHERIT'),
                'noResults' => L_('TXT:FA_NO_RESULTS'),
                'insert'    => L_('TXT:INSERT'),
                'cancel'    => L_('TXT:CANCEL'),
            ],
            // link — merged link dialog (plugins/link/plugin.min.js).
            // Keys mirror the plugin's internal T label object.
            'linkDialog'     => [
                'link'         => L_('TXT:LINK'),
                'internalLink' => L_('TXT:INTERNAL_LINK'),
                'email'        => L_('TXT:EMAIL'),
                'phone'        => L_('TXT:PHONE'),
                'url'          => L_('TXT:URL'),
                'search'       => L_('TXT:WBLINK_SEARCH'),
                'pageItem'     => L_('TXT:LINK_PAGE_ITEM'),
                'text'         => L_('TXT:TEXT_TO_DISPLAY'),
                'title'        => L_('TXT:TITLE'),
                'target'       => L_('TXT:TARGET'),
                'cssClass'     => L_('TXT:CSS_CLASS'),
                'rel'          => L_('TXT:REL'),
                'self'         => L_('TXT:SAME_WINDOW'),
                'blank'        => L_('TXT:NEW_WINDOW'),
                'top'          => L_('TXT:BREAK_FRAMESET'),
                'subject'      => L_('TXT:SUBJECT'),
                'message'      => L_('TXT:MESSAGE'),
                // Tab label: the picker opens the Media library regardless of file
                // type (PDF, Markdown, image, …), so it borrows the core admin's
                // own "Media" string instead of a module-specific "Image" one.
                'image'        => $MENU['MEDIA'] ?? L_('TXT:LINKIMG_TAB'),
                'imageUrl'     => L_('TXT:LINKIMG_URL'),
                'chooseImage'  => L_('TXT:LINKIMG_BROWSE'),
                'style'        => L_('TXT:LINK_STYLE'),
                'insert'       => L_('TXT:INSERT'),
                'cancel'       => L_('TXT:CANCEL'),
                'dialogTitle'  => L_('TXT:LINK_DIALOG_TITLE'),
            ],
        ];
    }

    /**
     * Convenience: the ready-to-echo <script> line defining the global object.
     */
    function tinymce_wbce_plugin_i18n_js(): string
    {
        return 'window.TINYMCE_WBCE_I18N = '
            . json_encode(tinymce_wbce_plugin_i18n(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . ';';
    }
}
