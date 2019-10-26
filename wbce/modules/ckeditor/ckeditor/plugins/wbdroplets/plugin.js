/*
 Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/

(function () {
    "use strict";
    
    CKEDITOR.plugins.add('wbdroplets', {
        requires: 'dialog,fakeobjects',
        lang: 'de,en',
        icons: 'wbdroplets',
        
        init: function (editor) {
            // Add the link and unlink buttons.
            editor.addCommand('wbdroplets', new CKEDITOR.dialogCommand('wbdroplets'));
            
            if (editor.ui.addButton) {
                editor.ui.addButton('Wbdroplets', {
                    label: editor.lang.wbdroplets.wbdroplets.insBtn,
                    command: 'wbdroplets',
                    toolbar: 'links,40'
                });
            }
            
            CKEDITOR.dialog.add('wbdroplets', this.path + 'dialogs/wbdroplets.js');
            
            editor.on('doubleclick', function (evt) {
                var dropletText = CKEDITOR.plugins.wbdroplets.getSelectedDroplet(editor);
                if (dropletText) {
                    evt.data.dialog = 'wbdroplets';
                }
            });
            
            // If the "menu" plugin is loaded, register the menu items.
            if (editor.addMenuItems) {
                editor.addMenuGroup('wbdroplets');
                editor.addMenuItems({
                    wbdroplets: {
                        label: editor.lang.wbdroplets.wbdroplets.menu,
                        command: 'wbdroplets',
                        group: 'wbdroplets',
                        order: 1
                    }
                });
            }
            
            // If the "contextmenu" plugin is loaded, register the listeners.
            if (editor.contextMenu) {
                editor.contextMenu.addListener(function (element, selection) {
                
                var dropletText = CKEDITOR.plugins.wbdroplets.getSelectedDroplet(editor);
                if (!dropletText) {
                    return null;
                }
                    
                return { wbdroplets : CKEDITOR.TRISTATE_OFF};
                });
            }
        }
    });
    
    /**
     * Set of wblink plugin's helpers.
     *
     * @class
     * @singleton
     */
    CKEDITOR.plugins.wbdroplets = {
        /**
         * Get the surrounding link element of current selection.
         *
         */
        getSelectedDroplet: function (editor) {
            var selection = editor.getSelection(),
                range = selection.getRanges()[0];
                    var content="";
            
            if (range) {
                range.shrink(CKEDITOR.SHRINK_TEXT);
                content = editor.elementPath(range.getCommonAncestor()).elements[0].$.innerHTML;
                content = content.match(/\[\[([^\]]*)\]\]/);
                if (content === null) {
                   return null 
                } else {
                    return content[1];
                }
            }
            return null;
        }
    };
    
    CKEDITOR.scriptLoader.load(CKEDITOR.plugins.getPath('wbdroplets')+'pages.php');
    
})();