/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config )
{  

    // Thia Plugin set the standard color of CKEditor. Can be changed in any hexadecimal color you like. For example:
    //config.uiColor = '#bcd5eb';

    /* Protect PHP code tags (<?...?>) so CKEditor will not break them when switching from Source to WYSIWYG.
    *  Uncommenting this line doesn't mean the user will not be able to type PHP code in the source.
    *  This kind of prevention must be done in the server side, so just leave this line as is. */ 
    config.protectedSource.push(/<\?[\s\S]*?\?>/g); // PHP Code

    //disable ckes Advanced Content Filter (ACF) to avoid wblinks to be filtered?
    config.allowedContent = true;
};

CKEDITOR.on('instanceReady', function (ev) {
ev.editor.dataProcessor.htmlFilter.addRules( {
        elements: {
            $: function (element) {
                // Remove width and height style rules from images
                if (element.name == 'img') {
                    var style = element.attributes.style;
                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];
                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];
                        if (width) { element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, ''); }
                        if (height) { element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, ''); }
                    }
                }
                if (!element.attributes.style)
                    delete element.attributes.style;
                return element;
            }
        }
    });
});	

CKEDITOR.on( 'dialogDefinition', function(ev) {
// Take the dialog name and its definition from the event data.
var dialogName = ev.data.name;
var dialogDefinition = ev.data.definition;
// Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
if ( dialogName == 'image' ) {
    // Get a reference to the "Link Info" tab.
    var linkTab = dialogDefinition.getContents('Link');
    //alert(linkTab);
    // Set the default value for the URL field.
    var urlField = linkTab.get( 'Klasse' );
    //urlField['default'] = 'www.example.com';
    //alert(urlField);
}
// Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
if ( dialogName == 'wblink' ) {
    // Get a reference to the "Link Info" tab.
    var infoTab = dialogDefinition.getContents( 'info' );
    // Set the default value for the URL field.
    var urlField = infoTab.get( 'url' );
    urlField['default'] = 'www.example.com';
    }
});