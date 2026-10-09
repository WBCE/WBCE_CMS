/**
 * tinymce_wbce — tinymce_init.js
 * Initializes TinyMCE on all .tinymce_wbce_editor textareas.
 * Toolbar presets: minimal | standard | full
 * @author  SC-Peet
 * @license GNU GPL2
 */

(function () {
    'use strict';

    var TOOLBARS = {
        minimal: [
            'bold italic underline | link | bullist numlist | undo redo | code'
        ],
        standard: [
            'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor |',
            'alignleft aligncenter alignright alignjustify |',
            'bullist numlist outdent indent | blockquote |',
            'removeformat | code | fullscreen'
        ],
        full: [
            'undo redo | formatselect fontselect fontsizeselect |',
            'bold italic underline strikethrough | forecolor backcolor |',
            'alignleft aligncenter alignright alignjustify |',
            'bullist numlist outdent indent | blockquote |',
            'wbdroplets wblink |',
            'subscript superscript | charmap |',
            'removeformat | code | fullscreen'
        ]
    };

    // Collect all editor textareas
    var textareas = document.querySelectorAll('.tinymce_wbce_editor');
    if (!textareas.length) { return; }

    // Build per-instance config from data attributes
    textareas.forEach(function (ta) {
        var preset  = ta.dataset.toolbar || 'standard';
        var toolbar = TOOLBARS[preset] || TOOLBARS.standard;
        var height  = parseInt(ta.dataset.height, 10) || 400;

        tinymce.init({
            target:          ta,
            license_key:     'gpl',
            base_url:        WB_URL + '/modules/tinymce_wbce/tinymce',
            suffix:          '.min',
            height:          height,
            menubar:         false,
            statusbar:       true,
            branding:        false,
            promotion:       false,

            // No entity conversion — store raw UTF-8 (ä ö ü stay as-is)
            entity_encoding: 'raw',

            // Disable URL conversion (CKE pain point)
            convert_urls:    false,
            relative_urls:   false,

            // Template CSS — resolved by include.php, injected before this script
            content_css:     (typeof TINYMCE_EDITOR_CSS !== 'undefined')
                                 ? TINYMCE_EDITOR_CSS
                                 : [],

            plugins: [
                'lists', 'link', 'image', 'table', 'code',
                'fullscreen', 'charmap', 'media',
                'autoresize'
            ],

            toolbar: toolbar.join(' '),

            // Paste: strip Word/Office formatting
            paste_as_text:              false,
            paste_remove_styles_if_webkit: true,

            setup: function (editor) {
                // Sync back to textarea on change (required for form submit)
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    });

}());
