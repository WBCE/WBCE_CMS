/*
 Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/

(function () {
    "use strict";
    
    CKEDITOR.plugins.add("wbshybutton", {
        lang: "de,en",
        icons: "wbshybutton",
        
        init: function (editor) {
            editor.addCommand("insertwbshybutton", {
                exec: function (b) {
                    b.insertHtml("&#173;");
                }
            });
            editor.ui.addButton("wbshybutton", {
                label: editor.lang.wbshybutton.insBtn,
                command: "insertwbshybutton",
                toolbar: "basicstyles,50"
            });
        }
    });
    
}());