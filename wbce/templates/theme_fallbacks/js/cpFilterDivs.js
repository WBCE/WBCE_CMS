/**
 * cpFilterDivs is a jQuery plugin that filters a set of elements based on the text entered in an input field.
 * The plugin takes an options object as an argument. The options object can contain the following properties:
 * - inputField: The selector for the input field used for filtering. Defaults to #filter-input.
 * - filterSelector: The selector for the elements to be filtered. Defaults to .subpage.
 * - textSelector: The selector for the element containing the text to filter by. Defaults to .subpage-title a.
 */
(function ( $ ) {
    $.fn.cpFilterDivs = function( options ) {
        var settings = $.extend({
            inputField: "#filter-input",
            filterSelector: ".link-card",
            textSelector: ".link-card__title, .link-card__meta",
            highlightClass: "search-hilite",
            // New settings
            container: "#link_cards",
            txtNoResults: "No results found for your search."
        }, options );
        
        var $input = $(settings.inputField);
        var $container = $(settings.container);
        var noResultsId = "no-filter-results-msg";
        
        // 1. Initial Setup: Store original HTML
        $(settings.filterSelector).each(function() {
            var $item = $(this);
            var $targets = settings.textSelector ? $item.find(settings.textSelector) : $item;
            $targets.each(function() {
                var $el = $(this);
                if (!$el.data('original-html')) {
                    $el.data('original-html', $el.html());
                }
            });
        });

        $input.on("keyup", function() {
            var value = $(this).val().trim();
            var lowerValue = value.toLowerCase();
            var visibleCount = 0; // Global counter for this search
            
            var escapedValue = value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            var regex = new RegExp('(' + escapedValue + ')', 'gi');

            $(settings.filterSelector).each(function() {
                var $item = $(this);
                var $targets = settings.textSelector ? $item.find(settings.textSelector) : $item;
                var matchFound = false;

                $targets.each(function() {
                    var $el = $(this);
                    var originalHtml = $el.data('original-html');
                    var plainText = $el.text().toLowerCase();

                    if (lowerValue === "") {
                        $el.html(originalHtml);
                        matchFound = true;
                        return;
                    }

                    if (plainText.indexOf(lowerValue) > -1) {
                        matchFound = true;
                        var newHtml = originalHtml.replace(regex, '<span class="' + settings.highlightClass + '">$1</span>');
                        $el.html(newHtml);
                    } else {
                        $el.html(originalHtml);
                    }
                });

                var isVisible = matchFound || lowerValue === "";
                $item.toggle(isVisible);
                if (isVisible) visibleCount++;
            });

            // Handle "No Results" Message
            $('#' + noResultsId).remove(); // Always remove first
            if (visibleCount === 0 && lowerValue !== "") {
                $('<div id="' + noResultsId + '" class="no-filter-results">' + settings.txtNoResults + '</div>')
                    .insertBefore($container);
            }
        });
        
        return this;
    };
}( jQuery ));