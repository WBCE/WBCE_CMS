/*
 * jQuery Furl (Friendly URL) Plugin 1.0.0
 *
 * Author : Vedat Taylan
 *
 * Year : 2018
 * Usage : $('#InputID').furl({id:'ReplaceInputID', seperate (optional) : '_' });
 */
(function ($, undefined) {
    function Furl() {
        this.defaults = {
            seperate: '-'
        };
    }
    Furl.prototype = {
        init: function (target, options) {
            var $this = this;
            options = $.extend({}, $this.defaults, options);

            $(target).keyup(function () {
                var url = urlReplace($(this).val(), options);
                var $element = $('#' + options.id);

                if ($element.length > 0) {
                    var tagName = $element.get(0).tagName;
                    switch (tagName) {
                        case 'INPUT':
                            $element.val(url);
                            break;
                        default:
                            $element.text(url);
                    }
                }
            });
        }
    };
    function urlReplace(url, options) {
        return url.toLowerCase()
            .replace(/ğ/g, 'g')
            .replace(/ü/g, 'u')
            .replace(/ş/g, 's')
            .replace(/ı/g, 'i')
            .replace(/ö/g, 'o')
            .replace(/ç/g, 'c')
            .replace(/Ğ/g, 'g')
            .replace(/Ü/g, 'u')
            .replace(/Ş/g, 's')
            .replace(/I/g, 'i')
            .replace(/İ/g, 'i')
            .replace(/Ö/g, 'o')
            .replace(/Ç/g, 'c')

            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/[\s-]+/g, " ")
            .replace(/^\s+|\s+$/g, "")
            .replace(/\s/g, "-")

            .replace(/^\s+|\s+$/g, "")
            .replace(/[_|\s]+/g, "-")
            .replace(/[^a-z\u0400-\u04FF0-9-]+/g, "")
            .replace(/[-]+/g, "-")
            .replace(/^-+|-+$/g, "")
            .replace(/[-]+/g, options.seperate);
    }

    $.furl = new Furl();
    $.furl.version = "1.0.0";

    $.fn.furl = function (options) {
        return this.each(function () {
            $.furl.init(this, options);
        });
    };
})(jQuery);
