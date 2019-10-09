/*
 Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/

(function () {
    "use strict";
    
    CKEDITOR.plugins.add('wbembed', {
        lang: 'de,en',
        icons: 'wbembed',
        hidpi: true,
        
        init: function (editor) {
            // Command
            editor.addCommand('wbembed', new CKEDITOR.dialogCommand('wbembedDialog'));
            // Toolbar button
            editor.ui.addButton('wbembed', {
                label: editor.lang.wbembed.button,
                command: 'wbembed',
                toolbar: 'insert'
            });
            // Dialog window
            CKEDITOR.dialog.add('wbembedDialog', this.path + 'dialogs/wbembedDialog.js');
        }
    });
    
})();