/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.dialog.add('wbembedDialog', function (editor) {
    return {
        title: editor.lang.wbembed.title,
        minWidth: 400,
        minHeight: 80,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'html',
                        html: '<p>' + editor.lang.wbembed.onlytxt + '</p>'
                    },
                    {
                        type: 'text',
                        id: 'url_video',
                        label: 'URL (ex: https://www.youtube.com/watch?v=EOIvnRUa3ik)',
                        validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.wbembed.validatetxt)
                    },
                    {
                        type: 'text',
                        id: 'css_class',
                        label: editor.lang.wbembed.input_css
                    },
                    {
                        type: 'select',
                        id: 'resizeType',
                        label: editor.lang.wbembed.resizeType,
                        'default': 'responsive',
                        items: [
                            [editor.lang.wbembed.responsive, 'responsive'],
                            [editor.lang.wbembed.noresize, 'noresize'],
                            [editor.lang.wbembed.custom, 'custom']
                        ],
                        onChange: function(e) {
                            /* console.log(e.data.value); */
                        }
                    }
                ]
            }
        ],
        onOk: function () {
            var
            dialog = this,
            div_container = new CKEDITOR.dom.element('div'),
            css = 'embeddedContent';
            // Set custom css class name
            if (dialog.getValueOf('tab-basic', 'css_class').length > 0) {
                css = dialog.getValueOf('tab-basic', 'css_class');
            }
            div_container.setAttribute('class', css);

            // Auto-detect if youtube, vimeo or dailymotion url
            var url = detect(dialog.getValueOf('tab-basic', 'url_video'));
            // Create iframe with specific url
            if (url.length > 1) {
                var resizetype = dialog.getValueOf('tab-basic', 'resizeType');
                     if (resizetype == "custom") {
                        var iframe = new CKEDITOR.dom.element.createFromHtml('<iframe src="' + url + '" allowfullscreen></iframe>');
                        div_container.append(iframe);
                        editor.insertElement(div_container);
                    } else if (resizetype == 'noresize') {
                        var iframe = new CKEDITOR.dom.element.createFromHtml('<iframe frameborder="0" width="560" height="349" src="' + url + '" allowfullscreen></iframe>');
                        div_container.append(iframe);
                        editor.insertElement(div_container);
                    } else {
                        var iframe = new CKEDITOR.dom.element.createFromHtml('<iframe src="' + url + '" style="border: 0; top: 0; left: 0; width: 100%; height: 100%; position: absolute;" allowfullscreen></iframe>');
                        div_container.setAttribute("style", "left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.2493%;");
                        div_container.append(iframe);
                        editor.insertElement(div_container);
                    }
            }
        }
    };
});

// Detect platform and return video ID
function detect(url) {
    var embed_url = '';
    // full youtube url
    if (url.indexOf('youtube') > 0) {
        id = getId(url, "?v=", 3);
        if (id.indexOf('&') > 0) {
            realID = id.split('&');
            id = realID[0];
        }
        return embed_url = 'https://www.youtube.com/embed/' + id;
    }
    // tiny youtube url
    if (url.indexOf('youtu.be') > 0) {
        id = getId(url);
        return embed_url = 'https://www.youtube.com/embed/' + id;
    }
    // full vimeo url
    if (url.indexOf('vimeo') > 0) {
        id = getId(url);
        return embed_url = 'https://player.vimeo.com/video/' + id + '?badge=0';
    }
    // full dailymotion url
    if (url.indexOf('dailymotion') > 0) {
        // if this is a playlist (jukebox)
        if (url.indexOf('/playlist/') > 0) {
           id = url.substring(url.lastIndexOf('/playlist/') + 10, url.indexOf("/1#video="));
           console.log(id);
            return embed_url = 'https://www.dailymotion.com/widget/jukebox?list[]=%2Fplaylist%2F' + id + '%2F1&&autoplay=0&mute=0';
        } else {
            id = getId(url);
        }
        return embed_url = 'https://www.dailymotion.com/embed/video/' + id;
    }
    // tiny dailymotion url
    if (url.indexOf('dai.ly') > 0) {
        id = getId(url);
        return embed_url = 'https://www.dailymotion.com/embed/video/' + id;
    }
    return embed_url;
}

// Return video ID from URL
function getId(url, string = "/", index = 1) {
    return url.substring(url.lastIndexOf(string) + index, url.length);
}