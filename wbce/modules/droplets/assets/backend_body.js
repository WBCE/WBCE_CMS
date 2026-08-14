/**
 * Minimal click-to-sort for table#myTable — replaces jquery.tablesorter.js.
 * A <th> is sortable if it has visible text (empty <th>s, e.g. the checkbox
 * and actions columns, stay unsortable) — column-index-agnostic, so it
 * doesn't break when the Date column is toggled on/off (see show_date).
 * Sort value per cell: its data-sort-timestamp attribute if present,
 * otherwise its trimmed text content (matches the old textExtraction()).
 * Sort direction is shown with a small text icon (⇅/↑/↓) appended to the
 * header, not a background image — the img/ folder with the old
 * bg.gif/asc.gif/desc.gif icons was removed.
 */
var DROPLETS_SORT_ICON = {unsorted: '⇅', asc: '↑', desc: '↓'};

function initDropletsTableSort(table) {
    var headRow = table.tHead && table.tHead.rows[0];
    var tbody = table.tBodies[0];
    if (!headRow || !tbody) return;

    function cellSortValue(row, index) {
        var cell = row.cells[index];
        if (!cell) return '';
        var timestamp = cell.getAttribute('data-sort-timestamp');
        return timestamp !== null ? timestamp : cell.textContent.trim();
    }

    function compareValues(a, b) {
        var na = parseFloat(a), nb = parseFloat(b);
        var bothNumeric = a !== '' && b !== '' && !isNaN(na) && !isNaN(nb);
        if (bothNumeric) return na - nb;
        return a.localeCompare(b, undefined, {sensitivity: 'base'});
    }

    function setIcon(th, symbol) {
        var icon = th.querySelector('.tablesorter-sort-icon');
        if (!icon) {
            icon = document.createElement('span');
            icon.className = 'tablesorter-sort-icon';
            th.appendChild(icon);
        }
        icon.textContent = symbol;
    }

    Array.prototype.forEach.call(headRow.cells, function (th, index) {
        if (th.textContent.trim() === '') {
            th.classList.add('sorter-false');
            return;
        }
        th.classList.add('tablesorter-headerUnSorted');
        setIcon(th, DROPLETS_SORT_ICON.unsorted);

        th.addEventListener('click', function () {
            var ascending = !th.classList.contains('tablesorter-headerAsc');

            Array.prototype.forEach.call(headRow.cells, function (cell) {
                cell.classList.remove('tablesorter-headerAsc', 'tablesorter-headerDesc');
                if (!cell.classList.contains('sorter-false')) {
                    cell.classList.add('tablesorter-headerUnSorted');
                    setIcon(cell, DROPLETS_SORT_ICON.unsorted);
                }
            });
            th.classList.remove('tablesorter-headerUnSorted');
            th.classList.add(ascending ? 'tablesorter-headerAsc' : 'tablesorter-headerDesc');
            setIcon(th, ascending ? DROPLETS_SORT_ICON.asc : DROPLETS_SORT_ICON.desc);

            var rows = Array.prototype.slice.call(tbody.rows);
            rows.sort(function (rowA, rowB) {
                var result = compareValues(cellSortValue(rowA, index), cellSortValue(rowB, index));
                return ascending ? result : -result;
            });
            // appendChild() on an existing row moves it, it doesn't clone —
            // any bound click handlers (delete-item, etc.) survive the reorder.
            rows.forEach(function (row) { tbody.appendChild(row); });
        });
    });
}

/**
 * Live text filter for #filterTable — hides tbody rows of the table named in
 * its data-filter-table attribute whose text doesn't contain the search term.
 */
function initDropletsTableFilter(input) {
    var table = document.getElementById(input.getAttribute('data-filter-table') || '');
    var tbody = table && table.tBodies[0];
    if (!tbody) return;

    input.addEventListener('input', function () {
        var needle = input.value.trim().toLowerCase();
        Array.prototype.forEach.call(tbody.rows, function (row) {
            var visible = needle === '' || row.textContent.toLowerCase().indexOf(needle) > -1;
            row.style.display = visible ? '' : 'none';
        });
    });
}

if (typeof jQuery != 'undefined') {
    jQuery(document).ready(function ($) {
        var sortableTable = document.getElementById('myTable');
        if (sortableTable) {
            initDropletsTableSort(sortableTable);
        }

        var filterInput = document.getElementById('filterTable');
        if (filterInput) {
            initDropletsTableFilter(filterInput);
        }

        // Scroll to latest edited doplet in droplets overview
        if ($(".hilite")[0]){
            $('html, body').animate({
                scrollTop: $('.hilite').offset().top -250
            }, 'slow');
        }

        $('*[data-redirect-location]').on("click", function () {
            var uri = $(this).data('redirect-location');
            if ($(this).data('new-window')) {
                window.open(uri, '_blank');
            } else {
                window.location = uri;
            }
        });
    });
}
