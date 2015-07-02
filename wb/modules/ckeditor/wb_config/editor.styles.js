/**
 *  @module         ckeditor
 *  @version        see info.php of this module
 *  @authors        Michael Tenschert, Dietrich Roland Pehlke
 *  @copyright      2010-2011 Michael Tenschert, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
 */
 
 CKEDITOR.addStylesSet( 'wb',
[
    /* Block Styles */

    // These styles are defined per editor.css in your template and can be found in "Format".
    /*
    { name : 'Paragraph'        , element : 'p' },
    { name : 'Heading 1'        , element : 'h1' },
    { name : 'Heading 2'        , element : 'h2' },
    { name : 'Heading 3'        , element : 'h3' },
    { name : 'Heading 4'        , element : 'h4' },
    { name : 'Heading 5'        , element : 'h5' },
    { name : 'Heading 6'        , element : 'h6' },
    { name : 'Preformatted Text', element : 'pre' },
    { name : 'Address'            , element : 'address' },
    */

    { name : 'Blue Title'       , element : 'h3', attributes  : { 'class' : 'marker-blue' } },
    { name : 'Red Title'        , element : 'h3', attributes  : { 'class' : 'marker-red' } },

    /* Inline Styles */

    // These are core styles available as toolbar buttons. You may opt enabling
    // some of them in the Styles combo, removing them from the toolbar.
    /*
    { name : 'Strong'           , element : 'strong', overrides : 'b' },
    { name : 'Emphasis'         , element : 'em'    , overrides : 'i' },
    { name : 'Underline'        , element : 'u' },
    { name : 'Strikethrough'    , element : 'strike' },
    { name : 'Subscript'        , element : 'sub' },
    { name : 'Superscript'      , element : 'sup' },
    */

    { name : 'Marker: Yellow'   , element : 'span', attributes : { 'class' : 'Yellow' } },
    { name : 'Marker: Green'    , element : 'span', attributes : { 'class' : 'Lime' } },

    { name : 'Big'              , element : 'big' },
    { name : 'Small'            , element : 'small' },
    { name : 'Typewriter'       , element : 'tt' },

    { name : 'Computer Code'    , element : 'code' },
    { name : 'Keyboard Phrase'  , element : 'kbd' },
    { name : 'Sample Text'      , element : 'samp' },
    { name : 'Variable'         , element : 'var' },

    { name : 'Deleted Text'     , element : 'del' },
    { name : 'Inserted Text'    , element : 'ins' },

    { name : 'Cited Work'       , element : 'cite' },
    { name : 'Inline Quotation' , element : 'q' },

    { name : 'Language: RTL'    , element : 'span', attributes : { 'dir' : 'rtl' } },
    { name : 'Language: LTR'    , element : 'span', attributes : { 'dir' : 'ltr' } },

    /* Object Styles */
    //  This styles are only available when you select the defined objects. E.g. when selecting an image 
    //  you can control here with the styles dropdown the styling.
    {
        name : 'Image on Left',
        element : 'img',
        attributes :
        {
            'style' : 'padding: 5px; margin-right: 5px',
            'border' : '2',
            'align' : 'left'
        }
    },

    {
        name : 'Image on Right',
        element : 'img',
        attributes :
        {
            'style' : 'padding: 5px; margin-left: 5px',
            'border' : '2',
            'align' : 'right'
        }
    }


]);