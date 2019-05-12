/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    /* Protect PHP code tags (<?...?>) so CKEditor will not break them when switching from Source to WYSIWYG.
     * Uncommenting this line doesn't mean the user will not be able to type PHP code in the source.
     * This kind of prevention must be done in the server side, so just leave this line as is. */
    config.protectedSource.push(/<\?[\s\S]*?\?>/g); // PHP Code
    
    //disable ckes Advanced Content Filter (ACF) to avoid wblinks to be filtered?
    config.allowedContent = true;
    
    // All content will be pasted as plain text.
    config.forcePasteAsPlainText = true;
    
    // Only Microsoft Word content formatting will be preserved.
    config.forcePasteAsPlainText = 'allow-word';
};

CKEDITOR.on('instanceReady', function (ev) {
    ev.editor.dataProcessor.htmlFilter.addRules( {
        elements: {
            $: function (element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;
                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];
                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];
                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }
                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                    }
                }
                if (!element.attributes.style)
                    delete element.attributes.style;
                return element;
            }
        }
    });
});