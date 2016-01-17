/**
 *  @module         ckeditor
 *  @version        see info.php of this module
 *  @authors        Michael Tenschert, Dietrich Roland Pehlke, Dietmar Wöllbrink, Marmot
 *  @copyright      Michael Tenschert, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
 */

/*
* WARNING: Clear the cache of your browser cache after you modify this file!
* If you don't do this, you may notice that your browser is ignoring all your changes.
*
* --------------------------------------------------
*
* Note: Some CKEditor configs are set in _yourwb_/modules/ckeditor/include.php
*
* Example: "$ckeditor->config['toolbar']" is PHP code in include.php. The very same here in the 
* wb_ckconfig.js would be: "config.toolbar" inside CKEDITOR.editorConfig = function( config ). 
*
* Please read "readme-faq.txt" in the wb_config folder for more information about customizing.
* 
*/

CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

  // Different Toolbars. Remove, add or move 'SomeButton', with the quotes and following comma 
    config.toolbar_Full =
    [
        { name: 'document',  items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',   items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'forms',     items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
        '/',
        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'tools',  items : [ 'Maximize', 'ShowBlocks','-','About' ] }
    ];
  config.toolbar_WB_Basic = [
            ['Source','Preview'],['Cut','Copy','Paste','PasteText','PasteFromWord'],['Image','Flash','Table','HorizontalRule'],['Wbdroplets','Wblink','Unlink','Anchor'],['Undo','Redo','-','SelectAll','RemoveFormat'],['Maximize','ShowBlocks','-','Code','About'],'/',
            ['Styles','Format','Font','FontSize'],['TextColor','BGColor'],['Bold','Italic','Underline','Strike'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv']];

    // see http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
    config.toolbar_WB_Full =
    [
        { name: 'document', items : [ 'Source','-','Save','Print','-','DocProps','Preview','NewPage','-','Templates' ] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'tools', items : [ 'Maximize', 'ShowBlocks','Code','-','About' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','Shy','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
         { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton','HiddenField' ] },
        '/',
        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor','Wbdroplets','Wblink' ] },
        { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] }

    ];

    config.toolbar_WB_Default =
    [
        { name: 'mode', items : [ 'Source','autoFormat','CommentSelectedRange','UncommentSelectedRange' ] },
        { name: 'document', items : [ 'Save','wbSave','Print','-','Preview','NewPage','-','Templates' ] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo','Backup' ] },
        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','Shy','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links', items : [ 'Wbdroplets','Wblink','Unlink','Anchor' ] },
        { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','Iframe','Youtube','oembed', 'slideshow' ] },
        '/',
        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'tools', items : [ 'Maximize', 'ShowBlocks','Syntaxhighlight','-','About' ] }

    ];

    config.toolbar_Basic = [['Bold','Italic','-','NumberedList','BulletedList','-','Link','Unlink','-','Code','About']];
    config.toolbar_WB_Simple = [['Bold','Italic','-','NumberedList','BulletedList','-','Wbdroplets','Wblink','Unlink','-','Scayt','-','Code','About']];

    // The default toolbar. Default: WB_Default
  config.toolbar = 'WB_Default';

  // The standard color of CKEditor. Can be changed in any hexadecimal color you like. Use the     
  // UIColor Plugin in your CKEditor to pick the right color.
    config.uiColor = '#BDD5EA';

    config.browserContextMenuOnCtrl = true;

    config.ModulVersiom  = '';

    config.fullPage = false;

    config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address;div';

    config.resize_dir = 'resize_dir';

    config.autoParagraph = true;

    /* The skin to load. It may be the name of the skin folder inside the editor installation path,
    * or the name and the path separated by a comma. 
    * Available skins: moono, moonocolor*/
    config.skin = 'moonocolor';

  // Define all extra CKEditor plugins in _yourwb_/modules/ckeditor/ckeditor/plugins here
  //config.extraPlugins = 'timestamp';
    config.extraPlugins  = 'justify,find,flash,colorbutton,colordialog,dialogadvtab,'
                         + 'div,font,forms,iframe,indentblock,language,bidi,liststyle,pagebreak,save,'
                         + 'selectall,showblocks,smiley,templates,codemirror,'
                         + 'wblink,wbdroplets,youtube,oembed,backup,wbsave';

    config.removePlugins = 'wsc,link,save,newpage,print,shybutton,syntaxhighlight,preview';

    config.browserContextMenuOnCtrl = true;

    config.entities = false;

    config.scayt_autoStartup = false;

    // The standard height and width of CKEditor in pixels.
    config.height           = '250';
    config.width            = '900';
    config.toolbarLocation  = 'top';

    // Define possibilities of automatic resizing in pixels. Set config.resize_enabled to false to 
    // deactivate resizing.
    config.resize_enabled   = true;
    config.resize_minWidth  = 500;
    config.resize_maxWidth  = 1500;
    config.resize_minHeight = 200;
    config.resize_maxHeight = 1200;
    config.resize_dir = 'vertical';

  config.docType           = '<!DOCTYPE html>';

  config.image_previewText = 'WebsiteBaker helps you to create the website you want: A free, easy and secure, flexible and extensible open source content management system (CMS). Create new templates within minutes - powered by (X)HTML, CSS and jQuery. With WebsiteBaker it\'s quite natural your site is W3C-valid, SEO-friendly and accessible - there are no limitations at all. Use droplets - the new and revolutionary way of inserting PHP code - everywhere you want. In addition to that, WebsiteBaker and the community are offering lots of extensions: Just download, install with two clicks and use them. That is not enough? You want more? No problem, build your own modules! The WebsiteBaker API gives many opportunities you can rely on.';

  // Both options are for XHTML 1.0 strict compatibility
  // config.indentClasses = [ 'indent1', 'indent2', 'indent3', 'indent4' ];
  // [ Left, Center, Right, Justified ]
  // config.justifyClasses = [ 'left', 'center', 'right', 'justify' ];

  config.templates_replaceContent =   false;

  config.syntaxhighlight_lang = 'js', 'jscript', 'javascript', 'perl', 'Perl', 'pl', 'php', 'text', 'plain', 'sass', 'scss', 'scala', 'sql', 'xml', 'xhtml', 'xslt', 'html';

  // Explanation: _P: new <p> paragraphs are created; _BR: lines are broken with <br> elements;
  //              _DIV: new <div> blocks are created.
  // Sets the behavior for the ENTER key. Default is _P allowed tags: _P | _BR | _DIV
  config.enterMode = CKEDITOR.ENTER_P;

  // Sets the behavior for the Shift + ENTER keys. allowed tags: _P | _BR | _DIV
  config.shiftEnterMode = CKEDITOR.ENTER_BR;

  /* Allows to force CKEditor not to localize the editor to the user language. 
  * Default: Empty (''); Example: ('fr') for French. 
  * Note: Language configuration is based on the backend language of WebsiteBaker. 
  * It's defined in include.php
  * config.language         = ''; */
  // The language to be used if config.language is empty and it's not possible to localize the editor to the user language.
  config.defaultLanguage   = 'en';

    /* Protect PHP code tags (<?...?>) so CKEditor will not break them when switching from Source to WYSIWYG.
    *  Uncommenting this line doesn't mean the user will not be able to type PHP code in the source.
    *  This kind of prevention must be done in the server side, so just leave this line as is. */ 
    config.protectedSource.push(/<\?[\s\S]*?\?>/g); // PHP Code

    //disable ckes Advanced Content Filter (ACF) to avoid wblinks to be filtered?
    config.allowedContent = true;

    // Simplify the dialog windows.
//    config.removeDialogTabs = 'image:advanced;link:advanced';

};

CKEDITOR.on( 'instanceReady', function( ev )
{
    var writer = ev.editor.dataProcessor.writer;
    // The character sequence to use for every indentation step.
    writer.indentationChars = '\t';
    // The way to close self closing tags, like <br />.
    writer.selfClosingEnd   = ' />';
    // The character sequence to be used for line breaks.
    writer.lineBreakChars   = '\n';
    // Setting rules for several HTML tags.
    
    var dtd = CKEDITOR.dtd;
    for (var e in CKEDITOR.tools.extend( {}, dtd.$block ))
    {
        writer.setRules( e,
        {
            // Indicates that this tag causes indentation on line breaks inside of it.
            indent : false,
            // Insert a line break before the <h1> tag.
            breakBeforeOpen : true,
            // Insert a line break after the <h1> tag.
            breakAfterOpen : false,
            // Insert a line break before the </h1> closing tag.
            breakBeforeClose : false,
            // Insert a line break after the </h1> closing tag.
            breakAfterClose : true
        });
    };
    writer.setRules( 'p',
    {
        // Indicates that this tag causes indentation on line breaks inside of it.
        indent : false,
        // Insert a line break before the <p> tag.
        breakBeforeOpen : true,
        // Insert a line break after the <p> tag.
        breakAfterOpen : false,
        // Insert a line break before the </p> closing tag.
        breakBeforeClose : false,
        // Insert a line break after the </p> closing tag.
        breakAfterClose : true
    });
    writer.setRules( 'br',
    {
        // Indicates that this tag causes indentation on line breaks inside of it.
        indent : false,
        // Insert a line break before the <br /> tag.
        breakBeforeOpen : false,
        // Insert a line break after the <br /> tag.
        breakAfterOpen : true
    });
    writer.setRules( 'a',
    {
        // Indicates that this tag causes indentation on line breaks inside of it.
        indent : false,
        // Insert a line break before the <a> tag.
        breakBeforeOpen : true,
        // Insert a line break after the <a> tag.
        breakAfterOpen : false,
        // Insert a line break before the </a> closing tag.
        breakBeforeClose : false,
        // Insert a line break after the </a> closing tag.
        breakAfterClose : false
    });
    writer.setRules( 'div',
    {
        // Indicates that this tag causes indentation on line breaks inside of it.
        indent : false,
        // Insert a line break before the <div> tag.
        breakBeforeOpen : true,
        // Insert a line break after the <div> tag.
        breakAfterOpen : false,
        // Insert a line break before the </div> closing tag.
        breakBeforeClose : true,
        // Insert a line break after the </div> closing tag.
        breakAfterClose : false
    });
    writer.setRules( 'img',
    {
        // Indicates that this tag causes indentation on line breaks inside of it.
        indent : false,
        // Insert a line break before the <img> tag.
        breakBeforeOpen : true,
        // Insert a line break after the <img> tag.
        breakAfterOpen : false,
        // Insert a line break before the </img>> closing tag.
        breakBeforeClose : false,
        // Insert a line break after the </img> closing tag.
        breakAfterClose : false
    });

    ev.editor.dataProcessor.htmlFilter.addRules(
    {
        elements:
        {
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
/**
 * 
                if (!element.attributes.style)
                    delete element.attributes.style;
 */

                return element;
            }
        }
    });

});

CKEDITOR.on( 'dialogDefinition', function( ev )
    {
        // Take the dialog name and its definition from the event data.
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;

        // Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
        if ( dialogName == 'image' )
        {
            // Get a reference to the "Link Info" tab.
            var linkTab = dialogDefinition.getContents('Link');
        }
        // Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
        if ( dialogName == 'wblink' )
        {
            // Get a reference to the "Link Info" tab.
            var infoTab = dialogDefinition.getContents( 'info' );
            // Set the default value for the URL field.
            var urlField = infoTab.get( 'url' );
            urlField['default'] = 'www.example.com';
        }

    // Ends self closing tags the HTML4 way, like <br>.


    });