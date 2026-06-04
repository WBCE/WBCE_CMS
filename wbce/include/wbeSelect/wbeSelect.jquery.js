/*!
 * wbeSelect — jQuery Select Plugin
 * Version 1.0.4
 *
 * Inspired by and derived from Selectator by QODIO
 * https://github.com/QODIO/selectator
 *
 */

 /*
 * MIT License
 * Copyright (c) 2025-2026 Christian M. Stefan
 * Copyright (c) 2013 QODIO
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *  var defaults = {
            height: 'auto',
            useSearch: true,
            disable_search_threshold: 7,
            showAllOptionsOnFocus: false,
            selectFirstOptionOnSearch: true,
            valueField: 'value',
            textField: 'text',
            searchFields: ['value', 'text'],
            placeholder: '',
            customSelector: '',    // ← NEW: allows for cusom css
            treeView: false,       // ← NEW: enables prefix wrapping when true
 */
(function ($) {
    'use strict';

    $.wbeSelect = function (_element, _options) {

        var defaults = {
            customSelector: '',
            height: 'auto',
            useSearch: true,
            disable_search_threshold: 7,
            showAllOptionsOnFocus: true,
            selectFirstOptionOnSearch: true,
            valueField: 'value',
            textField: 'text',
            searchFields: ['value', 'text'],
            placeholder: '',
            treeView: false,

            render: {
                selected_item: function (_item, escape) {
                    var html = '';
                    if (typeof _item.left !== 'undefined')
                        html += '<div class="ws-tag-l"><img src="' + escape(_item.left) + '" alt=""></div>';
                    if (typeof _item.right !== 'undefined')
                        html += '<div class="ws-tag-r">' + escape(_item.right) + '</div>';
                    html += '<div class="ws-tag-ttl">' + (typeof _item.text !== 'undefined' ? escape(_item.text) : '') + '</div>';
                    if (typeof _item.subtitle !== 'undefined')
                        html += '<div class="ws-tag-sub">' + escape(_item.subtitle) + '</div>';
                    html += '<div class="ws-tag-rm">✖</div>';
                    return html;
                },
                option: function (_item, escape) {
                    var html = '';
                    if (typeof _item.left !== 'undefined')
                        html += '<div class="ws-opt-l"><img src="' + escape(_item.left) + '" alt=""></div>';
                    if (typeof _item.right !== 'undefined')
                        html += '<div class="ws-opt-r">' + escape(_item.right) + '</div>';
                    html += '<div class="ws-opt-ttl">' + (typeof _item.text !== 'undefined' ? escape(_item.text) : '') + '</div>';
                    if (typeof _item.subtitle !== 'undefined')
                        html += '<div class="ws-opt-sub">' + escape(_item.subtitle) + '</div>';
                    return html;
                }
            },
            labels: {
                search: 'Search...'
            }
        };

        var self = this;
        self.options = {};
        self.$source_element = $(_element);
        self.$container_element = null;
        self.$selecteditems_element = null;
        self.$input_element = null;
        self.$textlength_element = null;
        self.$options_element = null;
        var is_single = self.$source_element.attr('multiple') === undefined;
        var is_multiple = !is_single;
        var has_visible_options = true;


        // ── INITIALIZE ───────────────────────────────────────────────────────────

        self.init = function () {
            self.options = $.extend(true, {}, defaults, _options);

            $.each(self.$source_element.data(), function (_key, _value) {
                if (_key.substring(0, 9) === 'wbeSelect') {
                    self.options[_key.substring(9, 10).toLowerCase() + _key.substring(10)] = _value;
                }
            });

            self.options.searchFields = typeof self.options.searchFields === 'string'
                ? self.options.searchFields.split(' ')
                : self.options.searchFields;

            self.$source_element.find('option').each(function () {
                $(this).data('value', this.value);
                $(this).data('text', this.text);
            });

            var searchLabel = self.$source_element.attr('data-search-label') || self.options.labels.search;

            self.$source_element.addClass('wbeSelect');
            if (self.$source_element.attr('placeholder')) {
                self.options.placeholder = self.$source_element.attr('placeholder');
            }

            // ── Container ────────────────────────────────────────────────────────
            self.$container_element = $(document.createElement('div'));
            if (self.$source_element.attr('id') !== undefined) {
                self.$container_element.attr('id', 'ws-el_' + self.$source_element.attr('id'));
            }
            self.$container_element.addClass(
                (self.options.customSelector ? self.options.customSelector + ' ' : '') +
                'ws-el ' +
                (is_multiple ? 'ws--multiple' : 'ws--single') +
                ' ws--hidden'
            );
            if (!self.options.useSearch) {
                self.$container_element.addClass('ws--no-search');
            }
            self.$container_element.css({
                width:       self.$source_element.css('width'),
                minHeight:   self.$source_element.css('height'),
                padding:     self.$source_element.css('padding'),
                'flex-grow': self.$source_element.css('flex-grow'),
                position:    'relative'
            });
            if (self.options.height === 'element') {
                self.$container_element.css({ height: self.$source_element.outerHeight() + 'px' });
            }

            // ── Text-length span ─────────────────────────────────────────────────
            self.$textlength_element = $(document.createElement('span'));
            self.$textlength_element.addClass('ws-txl');
            self.$textlength_element.css({ position: 'absolute', visibility: 'hidden' });
            self.$container_element.append(self.$textlength_element);

            // ── Selected items wrapper ───────────────────────────────────────────
            self.$selecteditems_element = $(document.createElement('div'));
            self.$selecteditems_element.addClass('ws-tags');
            self.$container_element.append(self.$selecteditems_element);

            // ── Search input ─────────────────────────────────────────────────────
            self.$input_element = $(document.createElement('input'));
            self.$input_element.addClass('ws-inp');
            self.$input_element.attr('tabindex', self.$source_element.attr('tabindex'));
            self.$input_element.attr('autocomplete', 'false');

            if (!self.options.useSearch) {
                self.$input_element.attr('readonly', true);
                self.$input_element.css({
                    'width': '0px', 'height': '0px', 'overflow': 'hidden',
                    'border': 0, 'padding': 0, 'position': 'absolute'
                });
            } else {
                if (is_single) {
                    self.$input_element.attr('placeholder', searchLabel);
                } else {
                    if (self.options.placeholder !== '') {
                        self.$input_element.attr('placeholder', self.options.placeholder);
                    }
                    self.$input_element.width(20);
                }
            }
            self.$container_element.append(self.$input_element);

            // ── Options list ─────────────────────────────────────────────────────
            self.$options_element = $(document.createElement('ul'));
            self.$options_element.addClass('ws-opts');
            self.$container_element.append(self.$options_element);

            self.$source_element.after(self.$container_element);
            self.$source_element.hide();


            // ── EVENTS ───────────────────────────────────────────────────────────

            self.$source_element.change(function () {
                renderSelectedItems();
            });

            self.$container_element.on('mousedown', function (_e) {
                _e.preventDefault();
                if (self.$container_element.hasClass('ws--open')) {
                    /* stopPropagation() on .ws-opt mousedown does NOT prevent
                     * this handler from firing (same element, different handler).
                     * Only close the dropdown when clicking OUTSIDE the options list. */
                    if ($(_e.target).closest('.ws-opts').length) return;
                    _e.stopPropagation();
                    hideDropdown();
                } else {
                    self.$input_element.focus();
                    self.$input_element.trigger('focus');
                    if (self.$input_element[0].setSelectionRange) {
                        self.$input_element[0].setSelectionRange(
                            self.$input_element.val().length,
                            self.$input_element.val().length
                        );
                    }
                }
            });

            self.$container_element.on('click', function () {
                if (self.$container_element.hasClass('ws--hidden')) {
                    hideDropdown();
                } else {
                    self.$input_element.focus();
                    self.$input_element.trigger('focus');
                }
            });

            self.$container_element.on('dblclick', function () {
                self.$input_element.select();
                self.$input_element.trigger('focus');
            });

            // ── Keyboard: up/down navigation, enter to select, escape, backspace ──
            self.$input_element.on('keydown', function (_e) {
                var keyCode = _e.keyCode || _e.which;
                var $active, $visible, idx;

                // Up arrow
                if (keyCode === 38) {
                    _e.preventDefault();
                    showDropdown();
                    $visible = self.$options_element.find('.ws-opt:visible');
                    $active  = self.$options_element.find('.ws--active');
                    $active.removeClass('ws--active');
                    if ($active.length) {
                        idx = $visible.index($active);
                        $visible.eq(idx - 1 >= 0 ? idx - 1 : 0).addClass('ws--active');
                    } else {
                        $visible.last().addClass('ws--active');
                    }
                    scrollToActive();
                    return;
                }

                // Down arrow
                if (keyCode === 40) {
                    _e.preventDefault();
                    showDropdown();
                    $visible = self.$options_element.find('.ws-opt:visible');
                    $active  = self.$options_element.find('.ws--active');
                    $active.removeClass('ws--active');
                    if ($active.length) {
                        idx = $visible.index($active);
                        $visible.eq(idx + 1 < $visible.length ? idx + 1 : $visible.length - 1).addClass('ws--active');
                    } else {
                        $visible.first().addClass('ws--active');
                    }
                    scrollToActive();
                    return;
                }

                // Enter: commit highlighted option, then blur to prevent
                // the focus handler from immediately reopening the dropdown
                if (keyCode === 13) {
                    _e.preventDefault();
                    if (self.$options_element.find('.ws--active').length) {
                        selectOption();
                        self.$input_element.blur();
                    }
                    resizeSearchInput();
                    return;
                }

                // Escape: preventDefault here, hideDropdown in keyup
                if (keyCode === 27) {
                    _e.preventDefault();
                    return;
                }

                if (!self.options.useSearch) {
                    _e.preventDefault();
                    return;
                }

                // Backspace: remove last tag in multiple mode
                if (keyCode === 8) {
                    if (self.$input_element.val() === '' && is_multiple && self.$source_element.find('option:selected').length) {
                        var lastSelectedItem = self.$source_element.find('option:selected').last()[0];
                        lastSelectedItem.removeAttribute('selected');
                        lastSelectedItem.selected = false;
                        self.$source_element.trigger('change');
                        renderSelectedItems();
                    }
                }
                resizeSearchInput();
            });

            // ── Keyup: filter results, close on Escape ────────────────────────────
            self.$input_element.on('keyup', function (_e) {
                _e.preventDefault();
                _e.stopPropagation();
                var keyCode = _e.which;
                switch (keyCode) {
                    case 27: // Escape
                        hideDropdown();
                        break;
                    case 37: case 38: case 39: case 40: // arrows
                    case 9:  // tab
                    case 16: // shift
                    case 13: // enter (already handled in keydown)
                        break;
                    default:
                        filterResults();
                        break;
                }
                resizeSearchInput();
            });

            self.$input_element.on('focus', function (e) {
                self.$container_element.addClass('ws--focused');
                if (is_single || self.options.showAllOptionsOnFocus || !self.options.useSearch) {
                    e.stopPropagation();
                    showDropdown();
                }
            });

            self.$input_element.on('blur', function (e) {
                self.$container_element.removeClass('ws--focused');
                e.stopPropagation();
                hideDropdown();
            });

            // Remove button on selected tag
            self.$container_element.on('mousedown', '.ws-tag-rm', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $tag = $(this).closest('.ws-tag');
                self.removeSelection($tag);
            });

            // Option hover
            self.$container_element.on('mouseover', '.ws-opt', function () {
                self.$options_element.find('.ws--active').removeClass('ws--active');
                $(this).addClass('ws--active');
            });

            // Option mousedown: prevent blur
            self.$container_element.on('mousedown', '.ws-opt', function (_e) {
                _e.preventDefault();
                _e.stopPropagation();
            });

            // Option mouseup: commit selection
            self.$container_element.on('mouseup', '.ws-opt', function () {
                selectOption();
            });

            // Option click: highlight
            self.$container_element.on('click', '.ws-opt', function (_e) {
                _e.stopPropagation();
                self.$options_element.find('.ws--active').removeClass('ws--active');
                $(this).addClass('ws--active');
            });

            // Expose elements via options for external access
            self.options.$source_element        = self.$source_element;
            self.options.$container_element     = self.$container_element;
            self.options.$selecteditems_element = self.$selecteditems_element;
            self.options.$input_element         = self.$input_element;
            self.options.$textlength_element    = self.$textlength_element;
            self.options.$options_element       = self.$options_element;

            renderOptions();
            renderSelectedItems();
            resizeSearchInput();

            if (self.options.treeView) {
                enhanceTreePrefixes();
            }
        };


        // ── TREE VIEW ────────────────────────────────────────────────────────────

        function enhanceTreePrefixes() {
            if (!self.options.treeView) return;

            function normalize(str) {
                return (str || '').replace(/&nbsp;/gi, '\u00A0').replace(/\s+/g, '\u00A0').trim();
            }

            var DOT = '●';

            function cleanOldPrefix(text) {
                return (text || '')
                    .replace(/^[│├└ \u00A0·•◦●\-\—─\|]+/, '')
                    .trimStart();
            }

            // Dropdown options: wrap full line-based prefix in a span
            self.$options_element.find('.ws-opt-ttl').each(function (i) {
                var $ttl = $(this);

                if ($ttl.find('.tree-prefix').length > 0) return;

                var $opt         = $ttl.closest('.ws-opt');
                var prefixEntity = $opt.attr('data-prefix') || '';
                var prefix       = normalize(prefixEntity);

                if (!prefix.trim()) return;

                var currentText   = $ttl.text();
                var currentHtml   = $ttl.html() || '';
                var matchLiteral  = currentText.indexOf(prefix) === 0;
                var escapedPrefix = prefixEntity.replace(/\u00A0/g, '&nbsp;');
                var matchEscaped  = currentHtml.indexOf(escapedPrefix) === 0;

                if (matchLiteral || matchEscaped) {
                    var sliceFrom = matchEscaped ? escapedPrefix.length : prefix.length;
                    var titlePart = currentHtml.substring(sliceFrom);
                    $ttl.html('<span class="tree-prefix">' + escape(prefixEntity) + '</span>' + titlePart);
                }
            });

            // Selected item: show depth dots based on data-level
            if (is_single) {
                self.$selecteditems_element.find('.ws-tag-ttl').each(function () {
                    var $ttl     = $(this);
                    var $tag     = $ttl.closest('.ws-tag');
                    var levelStr = $tag.attr('data-level');
                    var level    = levelStr ? parseInt(levelStr, 10) : 0;
                    var cleanText = cleanOldPrefix($ttl.text() || '');

                    if (level <= 0) {
                        $ttl.html(cleanText);
                        return;
                    }

                    var dots = DOT.repeat(level);
                    $ttl.html('<span class="tree-level-dots">' + escape(dots) + '</span>' + cleanText);
                });
            }
        }


        // ── RESIZE INPUT ─────────────────────────────────────────────────────────

        var resizeSearchInput = function () {
            if (is_multiple) {
                self.$textlength_element.text(
                    self.$input_element.val() === '' && self.options.placeholder !== ''
                        ? self.options.placeholder
                        : self.$input_element.val()
                );
                var width = self.$textlength_element.width() > (self.$container_element.width() - 30)
                    ? (self.$container_element.width() - 30)
                    : (self.$textlength_element.width() + 30);
                self.$input_element.css({ width: width + 'px' });
            }
        };


        // ── RENDER SELECTED ITEMS ────────────────────────────────────────────────

        var renderSelectedItems = function () {
            self.$selecteditems_element.empty();
            self.$source_element.find('option').each(function () {
                var $option = $(this);
                if (!this.selected) return;

                var $item = $(document.createElement('div'));
                $item.data('source_item_element', this);
                $item.addClass('ws-tag ws-val_' + $option.val().replace(/\W/g, ''));

                var data = {
                    value: this.value,
                    text:  this.text,
                    icon:  $option.data('icon'),
                    class: $option.data('class')
                };
                $.each(this.attributes, function () {
                    if (this.specified) {
                        var name = this.name;
                        if (name.startsWith('data-')) {
                            var key = name.replace('data-', '');
                            data[key] = this.value;
                            // Copy level and prefix to selected tag for treeView
                            if (key === 'level' || key === 'prefix') {
                                $item.attr('data-' + key, this.value);
                            }
                        } else {
                            data[name] = this.value;
                        }
                    }
                });
                $.extend(data, $option.data('item_data'));

                // Copy data-class (e.g. type-public) so CSS can target it on the tag
                if (data.class) {
                    $item.addClass(data.class);
                }

                $item.append(self.options.render.selected_item(data, escape));

                // Single: hide remove button when there is no empty-value fallback
                if (is_single && (
                    data[self.options.valueField] === '' ||
                    typeof data[self.options.valueField] === 'undefined' ||
                    self.$source_element.find('[value=""]').length === 0
                )) {
                    $item.find('.ws-tag-rm').remove();
                }

                self.$selecteditems_element.append($item);
            });

            if (is_single) {
                var val = self.$source_element.val();
                if (self.options.placeholder !== '' && (val === '' || val === null)) {
                    self.$selecteditems_element.empty();
                    self.$selecteditems_element.append(
                        '<div class="ws-ph">' + self.options.placeholder + '</div>'
                    );
                } else {
                    self.$selecteditems_element.find('.ws-ph').remove();
                }
            }

            if (self.options.treeView) {
                setTimeout(enhanceTreePrefixes, 50);
            }
        };


        // ── CREATE OPTION ────────────────────────────────────────────────────────

        var createOption = function (_isGroupOption) {
            var $option = $(document.createElement('li'));
            $option.data('source_option_element', this);
            $option.attr('onclick', 'void(0)');
            $option.addClass('ws-opt ws-val_' + $(this).val().replace(/\W/g, ''));

            if (_isGroupOption)  $option.addClass('ws-grp-opt');
            if (this.selected)   $option.addClass('ws--active');
            if (this.disabled)   $option.addClass('ws--opt-disabled');
            if ($(this).attr('class') !== undefined) $option.addClass($(this).attr('class'));

            var data = {
                value: this.value,
                text:  this.text,
                icon:  $(this).data('icon'),
                class: $(this).data('class')
            };
            $.each(this.attributes, function () {
                if (this.specified) {
                    var name = this.name;
                    if (name.startsWith('data-')) {
                        var key = name.replace('data-', '');
                        data[key] = this.value;
                        if (key === 'prefix' || key === 'level') {
                            $option.attr('data-' + key, this.value);
                        }
                    } else {
                        data[name] = this.value;
                    }
                }
            });
            $.extend(data, $(this).data('item_data'));

            if (is_multiple && this.selected) $option.hide();
            if (data.icon)  $option.append('<i class="' + data.icon + '"></i>');
            if (data.class) $option.addClass(data.class);

            $option.append(self.options.render.option(data, escape));
            return $option;
        };


        // ── RENDER OPTIONS ───────────────────────────────────────────────────────

        var renderOptions = function () {
            var optionCount = self.$source_element.find('option').length;
            if (optionCount <= self.options.disable_search_threshold) {
                self.$container_element.addClass('ws--no-search');
                self.options.useSearch = false;
            } else {
                self.$container_element.removeClass('ws--no-search');
            }

            self.$options_element.empty();
            var optionsArray = [];

            self.$source_element.children().each(function () {
                if ($(this).prop('tagName').toLowerCase() === 'optgroup') {
                    var $group = $(this);
                    if ($group.children('option').length !== 0) {
                        var groupOptionsArray = [];
                        $group.children('option').each(function () {
                            groupOptionsArray.push({ type: 'option', text: $(this).html(), element: this });
                        });
                        optionsArray.push({
                            type: 'group', text: $group.attr('label'),
                            options: groupOptionsArray, element: $group
                        });
                    }
                } else {
                    optionsArray.push({ type: 'option', text: $(this).html(), element: this });
                }
            });

            $(optionsArray).each(function () {
                if (this.type === 'group') {
                    var $group_element = $(document.createElement('li'));
                    $group_element.addClass('ws-grp');
                    if ($(this.element).attr('class') !== undefined) {
                        $group_element.addClass($(this.element).attr('class'));
                    }
                    $group_element.html($(this.element).attr('label'));
                    self.$options_element.append($group_element);
                    $(this.options).each(function () {
                        self.$options_element.append(createOption.call(this.element, true));
                    });
                } else {
                    self.$options_element.append(createOption.call(this.element, false));
                }
            });

            filterResults();

            if (self.options.treeView) {
                setTimeout(enhanceTreePrefixes, 50);
            }
        };


        // ── SCROLL TO ACTIVE OPTION ──────────────────────────────────────────────

        var scrollToActive = function () {
            var $active = self.$options_element.find('.ws-opt.ws--active');
            if ($active.length) {
                self.$options_element.scrollTop(
                    self.$options_element.scrollTop() +
                    $active.position().top -
                    self.$options_element.height() / 2 +
                    $active.outerHeight() / 2
                );
            }
        };


        // ── FILTER RESULTS ───────────────────────────────────────────────────────

        var filterResults = function () {
            has_visible_options = false;
            var searchFor = self.$input_element.val().toLowerCase();

            self.$options_element.find('.ws-opt').each(function () {
                var $this = $(this);
                var source_option_element  = $this.data('source_option_element');
                var $source_option_element = $(source_option_element);

                var match_found = false;
                $.each(self.options.searchFields, function (key, value) {
                    if (
                        typeof $source_option_element.data(value) !== 'undefined' &&
                        $source_option_element.data(value).toString().toLowerCase().indexOf(searchFor) !== -1
                    ) {
                        match_found = true;
                        return false;
                    }
                });

                var shouldShow = (!source_option_element.selected || is_single) &&
                    (!self.options.useSearch || match_found || $source_option_element.val() === '');

                if (shouldShow) {
                    $this.show();
                    has_visible_options = true;
                } else {
                    $this.hide();
                }
            });

            // Hide optgroup headers with no visible children
            self.$options_element.find('.ws-grp').each(function () {
                var $this = $(this);
                var groupHasVisible = false;
                $this.nextUntil('.ws-grp').each(function () {
                    if ($(this).css('display') !== 'none') {
                        groupHasVisible = true;
                        return false;
                    }
                });
                groupHasVisible ? $this.show() : $this.hide();
            });

            showDropdown();

            if (is_multiple) {
                self.$options_element.find('.ws--active').removeClass('ws--active');
                if (self.options.selectFirstOptionOnSearch && searchFor !== '') {
                    self.$options_element.find('.ws-opt:visible').first().addClass('ws--active');
                }
            }

            if (self.options.treeView) {
                setTimeout(enhanceTreePrefixes, 50);
            }
        };


        // ── SHOW DROPDOWN ────────────────────────────────────────────────────────

        var showDropdown = function () {
            if (
                self.$input_element.is(':focus') &&
                (has_visible_options || is_single) &&
                !(self.$options_element.is(':empty') && !self.options.useSearch)
            ) {
                self.$container_element.removeClass('ws--hidden').addClass('ws--open');
                setTimeout(function () {
                    self.$options_element.css('top',
                        (self.$container_element.outerHeight() +
                         (is_multiple ? 0 : self.$input_element.outerHeight()) - 1) + 'px'
                    );
                }, 1);
            } else {
                hideDropdown();
            }
        };


        // ── HIDE DROPDOWN ────────────────────────────────────────────────────────

        var hideDropdown = function () {
            self.$container_element.removeClass('ws--open').addClass('ws--hidden');
        };


        // ── SELECT ACTIVE OPTION ─────────────────────────────────────────────────

        var selectOption = function () {
            if (is_single) {
                var last = self.$source_element.find('option:selected').last()[0];
                last.removeAttribute('selected');
                last.selected = false;
            }
            var $active = self.$options_element.find('.ws--active');
            // Bail out if the highlighted option is disabled
            if ($active.hasClass('ws--opt-disabled')) return;
            $active.data('source_option_element').setAttribute('selected', '');
            $active.data('source_option_element').selected = true;
            self.$source_element.trigger('change');
            self.$input_element.val('');
            filterResults();
            renderSelectedItems();
            resizeSearchInput();
            hideDropdown();

            if (self.options.treeView) {
                setTimeout(enhanceTreePrefixes, 50);
            }
        };


        // ── ESCAPE ───────────────────────────────────────────────────────────────

        var escape = function (str) {
            return (str + '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        };


        // ── PUBLIC API ───────────────────────────────────────────────────────────

        /** Re-render selected item tags after programmatic <option> changes */
        self.refresh = function () {
            renderSelectedItems();
        };

        /** Close the dropdown programmatically */
        self.hideDropdown = function () {
            hideDropdown();
        };

        /**
         * Remove a specific selected item.
         * Pass the .ws-tag jQuery element, or omit to remove the first (single select).
         */
        self.removeSelection = function ($tagEl) {
            var source_item_element;
            if ($tagEl && $tagEl.length) {
                source_item_element = $tagEl.data('source_item_element');
            } else {
                source_item_element = self.$container_element.find('.ws-tag').first().data('source_item_element');
            }
            if (!source_item_element) return;

            source_item_element.removeAttribute('selected');
            source_item_element.selected = false;

            if (is_single && self.$source_element.find('[value=""]').length) {
                var noValueOption = self.$source_element.find('[value=""]')[0];
                noValueOption.setAttribute('selected', '');
                noValueOption.selected = true;
            }

            self.$source_element.trigger('change');
            filterResults();
            renderOptions();
            renderSelectedItems();
            hideDropdown();

            if (self.options.treeView) {
                setTimeout(enhanceTreePrefixes, 50);
            }
        };

        /** Remove the plugin and restore the original <select> */
        self.destroy = function () {
            self.$container_element.remove();
            $.removeData(_element, 'wbeSelect');
            self.$source_element.show();
        };

        // Initialise
        self.init();
    };


    // ── jQuery Plugin Initializer ────────────────────────────────────────────────

    $.fn.wbeSelect = function (_options) {
        var options = _options !== undefined ? _options : {};
        return this.each(function () {
            var $this = $(this);
            if (typeof options === 'object') {
                if ($this.data('wbeSelect') === undefined) {
                    var self = new $.wbeSelect(this, options);
                    $this.data('wbeSelect', self);
                }
            } else if ($this.data('wbeSelect') && $this.data('wbeSelect')[options]) {
                $this.data('wbeSelect')[options].apply(this, Array.prototype.slice.call(arguments, 1));
            } else {
                $.error('Method ' + options + ' does not exist in $.fn.wbeSelect');
            }
        });
    };

}(jQuery));


// ── Auto-init via markup ─────────────────────────────────────────────────────

$(function () {
    'use strict';
    $('select.wbeSelect').each(function () {
        var $this = $(this);
        var options = {};
        $.each($this.data(), function (_key, _value) {
            if (_key.substring(0, 9) === 'wbeSelect') {
                options[_key.substring(9, 10).toLowerCase() + _key.substring(10)] = _value;
            }
        });
        $this.wbeSelect(options);
    });
});
