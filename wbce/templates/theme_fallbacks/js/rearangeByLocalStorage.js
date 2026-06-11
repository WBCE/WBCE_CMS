(function($) {
    /**
     * Rearrange elements on page load based on click counts.
     * Clicks only update the counter without triggering a re-sort immediately.
     */
    $.fn.rearangeByLocalStorage = function(options) {
        var settings = $.extend({
            storageKey: 'AdminTools',
            parentClass: 'link-cards-grid' // Updated to match your Twig
        }, options);

        var $elements = this;

        // 1. UPDATE STORAGE ON CLICK
        this.on('click', function() {
            var recId = $(this).attr('id');
            var recordArray = getRecordsFromStorage() || [];
            var record = recordArray.find(r => r.id === recId);

            if (record) {
                record.clickCount = (record.clickCount || 0) + 1;
            } else {
                recordArray.push({ id: recId, clickCount: 1 });
            }

            localStorage.setItem(settings.storageKey, JSON.stringify(recordArray));
            
            // NOTE: No rearrangeHTML() here to prevent lag before navigation
        });

        // 2. REARRANGE ON INITIAL LOAD
        function rearrangeHTML() {
            var recordArray = getRecordsFromStorage();
            if (!recordArray || recordArray.length === 0) return;

            var container = $('.' + settings.parentClass);
            if (container.length === 0) return;

            // Create a lookup map for faster sorting performance
            var counts = {};
            recordArray.forEach(function(r) {
                counts[r.id] = r.clickCount;
            });

            var sorted = $elements.get().sort(function(a, b) {
                var countA = counts[$(a).attr('id')] || 0;
                var countB = counts[$(b).attr('id')] || 0;
                return countB - countA;
            });

            // Efficiently move elements in the DOM
            $(sorted).detach().appendTo(container);
        }

        function getRecordsFromStorage() {
            try {
                var s = localStorage.getItem(settings.storageKey);
                return s ? JSON.parse(s) : null;
            } catch (e) {
                return null;
            }
        }

        // Run once on load
        rearrangeHTML();

        return this;
    };
})(jQuery);