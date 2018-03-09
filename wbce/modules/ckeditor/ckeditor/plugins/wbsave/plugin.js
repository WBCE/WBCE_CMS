/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

(function () {
    "use strict";
    
    var wbsaveCmd = {
        readOnly: 1,
        
        exec: function (editor) {
            if (editor.fire("save")) {
                
                var $form = editor.element.$.form,
                    ifrm = document.createElement("iframe");
                
                ifrm.setAttribute("style", "display: none;");
                ifrm.setAttribute("id", "dummy_iframe");
                ifrm.setAttribute("name", "dummy_iframe");
                $form.parentNode.appendChild(ifrm);
                $("#dummy_iframe").load(function () {
                    var reply = $("#dummy_iframe").contents().find(".content").html();
                    reply = reply.replace(/<input.*>/ig, "");
                    iBox.show(reply);
                    $("#dummy_iframe").remove();
                    $form.target = "";
                });
                $form.target = "dummy_iframe";
                
                if ($form) {
                    try {
                        $form.submit();
                    } catch (e) {
                        // If there's a button named "submit" then the form.submit
                        // function is masked and can't be called in IE/FF, so we
                        // call the click() method of that button.
                        if ($form.submit.click) {
                            $form.submit.click();
                        }
                    }
                }
            }
        }
    };
    
    CKEDITOR.plugins.add("wbsave", {
        lang: "de,en",
        icons: "wbsave",
        hidpi: true,
        
        init: function (editor) {
            if (editor.elementMode !== CKEDITOR.ELEMENT_MODE_REPLACE) {
                return;
            }
            var command = editor.addCommand("wbsave", wbsaveCmd);
            command.modes = {
                wysiwyg: !!(editor.element.$.form)
            };
            editor.ui.addButton("wbSave", {
                label: editor.lang.wbsave.insBtn,
                command: "wbsave",
                toolbar: "document,10"
            });
            CKEDITOR.scriptLoader.load(CKEDITOR.plugins.getPath("wbsave") + "ibox.js");
        }
    });
}());

/**
 * Fired when the user clicks the Save button on the editor toolbar.
 * This event allows to overwrite the default Save button behavior.
 *
 * @since 4.2
 * @event save
 * @member CKEDITOR.editor
 * @param {CKEDITOR.editor} editor This editor instance.
 */