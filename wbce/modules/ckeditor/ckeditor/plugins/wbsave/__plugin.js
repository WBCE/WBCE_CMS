/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/**
 * @fileOverview The Save plugin.
 */

(function() {
    var wbsaveCmd = {
        readOnly: 1,

        exec: function( editor ) {
            if ( editor.fire( 'save' ) ) {
        var $form = editor.element.$.form;
                if ( $form ) {
                    try {
                        $form.submit();
                    } catch ( e ) {
                        // If there's a button named "submit" then the form.submit
                        // function is masked and can't be called in IE/FF, so we
                        // call the click() method of that button.
                        if ( $form.submit.click )
                            $form.submit.click();
                    }
                }
            }
        }
    };

    var pluginName = 'wbsave';

    // Register a plugin named "wbsave".
    CKEDITOR.plugins.add( pluginName, {
        lang: 'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en,en-au,en-ca,en-gb,eo,es,et,eu,fa,fi,fo,fr,fr-ca,gl,gu,he,hi,hr,hu,id,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,th,tr,ug,uk,vi,zh,zh-cn', // %REMOVE_LINE_CORE%
        icons: 'wbsave', // %REMOVE_LINE_CORE%
        hidpi: true, // %REMOVE_LINE_CORE%
        init: function( editor ) {
            // Save plugin is for replace mode only.
            if ( editor.elementMode != CKEDITOR.ELEMENT_MODE_REPLACE )
                return;

            var command = editor.addCommand( pluginName, wbsaveCmd );
            command.modes = { wysiwyg: !!( editor.element.$.form ) };

            editor.ui.addButton && editor.ui.addButton( 'wbSave', {
                label: editor.lang.wbsave.toolbar,
                command: pluginName,
                toolbar: 'document,10'
            });
            var $form = editor.element.$.form;
            if ( $("#dummy_iframe").length == 0 ) {
            var ifrm = document.createElement('iframe');
              ifrm.setAttribute('style','display: none;');
              ifrm.setAttribute('id','dummy_iframe');
              ifrm.setAttribute('name','dummy_iframe');
              $form.parentNode.appendChild(ifrm);
            }  
          $form.target = 'dummy_iframe';        
        }
    });
})();

/**
 * Fired when the user clicks the Save button on the editor toolbar.
 * This event allows to overwrite the default Save button behavior.
 *
 * @since 4.2
 * @event save
 * @member CKEDITOR.editor
 * @param {CKEDITOR.editor} editor This editor instance.
 */
