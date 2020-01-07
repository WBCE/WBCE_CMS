/*!
 * InsertLoader 1.0
 *
 * Copyright (c) 2016 Jonathan Nessier (https://www.neoflow.ch)
 * Released under The MIT license (MIT)
 */
var InsertLoader = (function () {

    this.load = function (urls, path) {
        if (!path || typeof path === 'undefined' || (typeof path === 'string' && path.length)) {
            if ($.isArray(urls)) {
                return loadResources(urls, path);
            } else if (typeof urls === 'string') {
                return loadResource(urls, path);
            }
            throw new Error('Multiple URLs have to be in an array or only one URL has to be a string');
        }
        throw new Error('Path has to be a string or empty');
    };

    var loadResource = function (url, path) {
        if (typeof url === 'string' && url.length) {

            var extension = url.substring(url.lastIndexOf('.') + 1, url.length),
                    finalUrl = (path || '') + url + '?_=' + new Date().getTime(),
                    $head = $('head');

            switch (extension) {
                case 'php':
                case 'js':
                    $head.append($('<script>', {
                        src: finalUrl
                    }));
                    break;
                case 'css':
                    $head.append($('<link>', {
                        href: finalUrl,
                        rel: 'stylesheet',
                        type: 'test/css'
                    }));
                    break;
                default:
                    console.warn('URL is not linking to a CSS or JS file: ' + url);
                    return false;
            }
            return true;
        }
        throw new Error('URL has to be a string');
    };

    var loadResources = function (urls, path) {
        if ($.isArray(urls)) {
            var result = true;
            $.each(urls, function (i, url) {
                if (!loadResource(url, path)) {
                    result = false;
                }
            });
            return result;
        }
        throw new Error('Multiple URLs have to be in an array');
    };

    return {
        load: load
    };
})();

$.insert = function (urls, path) {
    return InsertLoader.load(urls, path);
};