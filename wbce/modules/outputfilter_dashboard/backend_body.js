/*
backend_body.js
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.15
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2021 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            http://forum.websitebaker.org/index.php/topic,28926.0.html
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @link            http://addons.wbce.org/pages/addons.php?do=item&item=53
 * @license         GNU General Public License, Version 3
 * @platform        WebsiteBaker 2.8.x or WBCE
 * @requirements    PHP 5.4 and higher
 *
 * This file is part of OutputFilter-Dashboard, a module for WBCE and Website Baker CMS.
 *
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 *
 **/

$(document).ready(function()
{
// ---| insert jQuery Dialogue CSS & JS Files
    if($("#dashboard").length) {
       $.insert(WB_URL+"/modules/outputfilter_dashboard/dialog/jquery.dialog.css");
       $.insert(WB_URL+"/modules/outputfilter_dashboard/dialog/jquery.dialog.js");
      }
// ---| show upload area
  $("button.show-upload").click(function() {
    $("#upload-panel").slideToggle("fast");
  });

// ---| show upload area
  $("p#close-panel img").click(function() {
    $("#upload-panel").slideToggle("fast");
  });
});

// --| drag&drop

var MODULE_URL = ADMIN_URL + '/admintools/tool.php?tool=outputfilter_dashboard';
var ICONS = IMAGE_URL;
var AJAX_PLUGINS =  WB_URL + '/modules/outputfilter_dashboard/ajax';


$(function() {
    // Load external ajax_dragdrop file
    $.insert(AJAX_PLUGINS +"/ajax.js");
});

/*
    CheckTree jQuery Plugin - Version 0.22-thorn-1
    original copyright notice see below
    -- changed for use with Website Baker's outputfilter-module. - thorn. Dec, 2008
    ATTN: Special version for use with outputfilter-module only!
          The callback-code isn't updated, so it's possibly broken -- do not use callbacks.
    Added Options:
    allChildrenMarksParentChecked:
        set to "yes" for old behaviour, set to "no" to keep parent on half-checked state (use half-checked as indicator for: some or all childs are set.
    checkedMarksChildrenChecked:
        set to "yes" for old behaviour, set to "no" to keep children unchecked if parent is marked. In this case a parent with checked childs can only be checked or half-checked. Does some more things...
        One may add the class "special_all" to a single <li>-element to add a special "mark-all/unmark-all"-feature.

    Usage:
    $("ul.tree").checkTree_my({labelAction: "check", allChildrenMarksParentChecked: "no", checkedMarksChildrenChecked: "yes"});
    $("ul.tree2").checkTree_my({labelAction: "check", allChildrenMarksParentChecked: "no", checkedMarksChildrenChecked: "no"});

    tree:  check hierarchy to use (that is, the checked page and all sub-pages). Half-checked-state is used to indicate that there are checked sub-pages.
    tree2: check each page to use individually. Half-checked-state is used to indicate that there are checked sub-pages.


Original copyright notice follows:
*/
/**
    Project: CheckTree jQuery Plugin
    Version: 0.22
    Project Website: http://static.geewax.org/checktree/
    Author: JJ Geewax <jj@geewax.org>

    License:
    The CheckTree jQuery plugin is currently available for use in all personal or
    commercial projects under both MIT and GPL licenses. This means that you can choose
    the license that best suits your project, and use it accordingly.
*/

(function(jQuery) {
jQuery.fn.checkTree_my = function(settings) {

    settings = jQuery.extend({
    /* Callbacks
        The callbacks should be functions that take one argument. The checkbox tree
        will return the jQuery wrapped LI element of the item that was checked/expanded.
    */
    onExpand: null,
    onCollapse: null,
    onCheck: null,
    onUnCheck: null,
    onHalfCheck: null,
    onLabelHoverOver: null,
    onLabelHoverOut: null,

    /* Valid choices: 'expand', 'check' */
    labelAction: "expand",
    allChildrenMarksParentChecked: "yes",
    checkedMarksChildrenChecked: "yes",

    // Debug (currently does nothing)
    debug: false
    }, settings);

    var $tree = this;

    $tree.find("li")

    // Hide all checkbox inputs
    .find(":checkbox")
        .change(function() {
        // Fired when the children of this checkbox have changed.
        // Children can change the state of a parent based on what they do as a group.
        var $all = jQuery(this).siblings("ul").find(":checkbox");
        var $checked = $all.filter(":checked");

        // All children are checked
        if ($all.length == $checked.length) {
            if(settings.allChildrenMarksParentChecked=="yes") {
            if(settings.checkedMarksChildrenChecked=="yes") {
                jQuery(this).prop("checked", true).siblings(".checkbox").removeClass("half_checked").addClass("checked");
            } else {
                jQuery(this).prop("checked", true).siblings(".checkbox").removeClass("half_checked").addClass("checked");
            }
            } else {
            if(settings.checkedMarksChildrenChecked=="yes") {
                jQuery(this).siblings(".checkbox").not(".checked").addClass("half_checked");
            } else {
                jQuery(this).siblings(".checkbox").not(".checked").addClass("half_checked");
            }
            }
            // Fire parent's onCheck callback
            if (settings.onCheck) settings.onCheck(jQuery(this).parent());
        }
        // All children are unchecked
        else if($checked.length == 0) {
            if(settings.checkedMarksChildrenChecked=="yes") {
            jQuery(this).prop("checked", false).siblings(".checkbox").removeClass("checked").removeClass("half_checked");
            } else {
            jQuery(this).siblings(".checkbox").not("checked").prop("checked", false).removeClass("half_checked");
            }
            // Fire parent's onUnCheck callback
            if (settings.onUnCheck) settings.onUnCheck(jQuery(this).parent());
        }

        // Some children are checked, makes the parent in a half checked state.
        else {
            // Fire parent's onHalfCheck callback only if it's going to change
            if (settings.onHalfCheck && !jQuery(this).siblings(".checkbox").hasClass("half_checked"))
            settings.onHalfCheck(jQuery(this).parent());
            if(settings.checkedMarksChildrenChecked=="yes" || jQuery(this).hasClass("special_all")) {
            jQuery(this).prop("checked", false).siblings(".checkbox").removeClass("checked").addClass("half_checked");
            } else {
            jQuery(this).siblings(".checkbox").not(".checked").prop("checked", false).addClass("half_checked");
            }
        }
        })
        .hide()
    .end()

    .each(function() {

        // Go through and hide only ul's (subtrees) that do not have a sibling div.expanded:
        // We do this to not collapse *all* the subtrees (if one is open and checkTree is called again)
        jQuery(this).find("ul").each(function() {
        if (!jQuery(this).siblings(".expanded").length) jQuery(this).hide();
        });

        // Copy the label
        var $label = jQuery(this).children("label").clone();
        // Create or the image for the checkbox next to the label
        var $checkbox = jQuery('<div class="checkbox"></div>');
        // Create the image for the arrow (to expand and collapse the hidden trees)
        var $arrow = jQuery('<div class="arrow"></div>');

        // If the li has children:
        if (jQuery(this).is(":has(ul)")) {
        // If the subtree is not visible, make the arrow collapsed. Otherwise expanded.
        if (jQuery(this).children("ul").is(":hidden")) $arrow.addClass("collapsed");
        else $arrow.addClass("expanded");

        // When you click the image, toggle the child list
        $arrow.click(function() {
            jQuery(this).siblings("ul").toggle();

            // Swap the classes: expanded <-> collapsed and fire the onExpand/onCollapse events
            if (jQuery(this).hasClass("collapsed")) {
            jQuery(this)
                .addClass("expanded")
                .removeClass("collapsed")
            ;
            if (settings.onExpand) settings.onExpand(jQuery(this).parent());
            }
            else {
            jQuery(this)
                .addClass("collapsed")
                .removeClass("expanded")
            ;
            if (settings.onCollapse) settings.onCollapse(jQuery(this).parent());
            }
        });
        }

        // When you click the checkbox, it should do the checking/unchecking
        $checkbox.click(function() {
        // Toggle the checked class)
        if(settings.checkedMarksChildrenChecked=="no" && !jQuery(this).siblings("input").hasClass("special_all") && jQuery(this).hasClass("checked") && jQuery(this).siblings("ul").find(".checkbox").is(".checked")==true) {
            jQuery(this).removeClass("checked").addClass("half_checked").siblings(":checkbox").prop("checked", false)
        } else {
            jQuery(this)
            .toggleClass("checked")
            // if it's half checked, its now either checked or unchecked
            .removeClass("half_checked")
            // Send a click event to the checkbox to toggle it as well
            // (this is the actual input element)
            .siblings(":checkbox").prop("checked",jQuery(this).hasClass("checked"));
        }
        // Check/uncheck children depending on our status.
        if(settings.checkedMarksChildrenChecked=="yes" || jQuery(this).siblings("input").hasClass("special_all")) {
            if (jQuery(this).hasClass("checked")) {
            // Fire the check callback for this parent
            if (settings.onCheck) settings.onCheck(jQuery(this).parent());

            // Go to the sibling list, and find all unchecked checkbox images
            jQuery(this).siblings("ul").find(".checkbox").not(".checked")
            // Set as fully checked:
            .removeClass("half_checked")
            .addClass("checked")

            // For each one, fire the onCheck callback
            .each(function() {
                if (settings.onCheck) settings.onCheck(jQuery(this).parent());
            })

            // For each one, check the checkbox (actual input element)
            .siblings(":checkbox")
                .prop("checked", true)
            ;
          }

          // If Unchecked:
          else {
            // Fire the uncheck callback for this parent
            if (settings.onUnCheck) settings.onUnCheck(jQuery(this).parent());

            // Go to the sibling list and find all checked checkbox images
            jQuery(this).siblings("ul").find(".checkbox").filter(".checked")
            // Set as fully unchecked
            .removeClass("half_checked")
            .removeClass("checked")

            // For each one fire the onUnCheck callback
            .each(function() {
                if (settings.onUnCheck) settings.onUnCheck(jQuery(this).parent());
            })

            // For each one, uncheck the checkbox (the actual input element)
            .siblings(":checkbox")
                .prop("checked", false)
            ;
          }
           }

        // Tell our parent checkbox that we've changed (they might need to change their state)
        jQuery(this).parents("ul").siblings(":checkbox").change();
        });

        // Add the appropriate classes to the new checkbox image based on the old one:
        if (jQuery(this).children('.checkbox').hasClass('checked'))
        $checkbox.addClass('checked');
        else if (jQuery(this).children(':checkbox').prop("checked")) {
        $checkbox.addClass('checked');
        jQuery(this).parents("ul").siblings(":checkbox").change()
        }
        else if (jQuery(this).children('.checkbox').hasClass('half_checked'))
        $checkbox.addClass('half_checked');

        // Remove any existing arrows or checkboxes or labels
        jQuery(this).children(".arrow").remove();
        jQuery(this).children(".checkbox").remove();
        jQuery(this).children("label").remove();

        // Prepend the new arrow, label, and checkbox images to the front of the LI
        jQuery(this)
        .prepend($label)
        .prepend($checkbox)
        .prepend($arrow)
        ;
    })

    .find("label")
        // Clicking the labels should do the labelAction (either expand or check)
        .click(function() {
        var action = settings.labelAction;
        switch(settings.labelAction) {
            case 'expand':
            jQuery(this).siblings(".arrow").click();
            break;
            case 'check':
            jQuery(this).siblings(".checkbox").click();
            break;
        }
        })

        // Add a hover class to the labels when hovering
        .hover(
        function() {
            jQuery(this).addClass("hover");
            if (settings.onLabelHoverOver) settings.onLabelHoverOver(jQuery(this).parent());
        },
        function() {
            jQuery(this).removeClass("hover");
            if (settings.onLabelHoverOut) settings.onLabelHoverOut(jQuery(this).parent());
        }
        )
    .end()
    ;

    return $tree;
};
})(jQuery);



/**
 * jQuery custom checkboxes
 *
 * Copyright (c) 2008 Khavilo Dmitry (http://widowmaker.kiev.ua/checkbox/)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * @version 1.3.0 Beta 1
 * @author Khavilo Dmitry
 * @mailto wm.morgun@gmail.com
**/

(function($){
        /* Little trick to remove event bubbling that causes events recursion */
        var CB = function(e)
        {
                if (!e) var e = window.event;
                e.cancelBubble = true;
                if (e.stopPropagation) e.stopPropagation();
        };

        $.fn.checkbox = function(options) {
                /* IE6 background flicker fix */
                try     { document.execCommand('BackgroundImageCache', false, true);    } catch (e) {}

                /* Default settings */
                var settings = {
                        cls: 'jquery-checkbox',  /* checkbox  */
                        empty: 'empty.png'  /* checkbox  */
                };

                /* Processing settings */
                settings = $.extend(settings, options || {});

                /* Adds check/uncheck & disable/enable events */
                var addEvents = function(object)
                {
                        var checked = object.checked;
                        var disabled = object.disabled;
                        var $object = $(object);

                        if ( object.stateInterval )
                                clearInterval(object.stateInterval);

                        object.stateInterval = setInterval(
                                function()
                                {
                                        if ( object.disabled != disabled )
                                                $object.trigger( (disabled = !!object.disabled) ? 'disable' : 'enable');
                                        if ( object.checked != checked )
                                                $object.trigger( (checked = !!object.checked) ? 'check' : 'uncheck');
                                },
                                10 /* in miliseconds. Low numbers this can decrease performance on slow computers, high will increase responce time */
                        );
                        return $object;
                };
                //try { console.log(this); } catch(e) {}

                /* Wrapping all passed elements */
                return this.each(function()
                {
                        var ch = this; /* Reference to DOM Element*/
                        var $ch = addEvents(ch); /* Adds custom events and returns, jQuery enclosed object */

                        /* Removing wrapper if already applied  */
                        if (ch.wrapper) ch.wrapper.remove();

                        /* Creating wrapper for checkbox and assigning "hover" event */
                        ch.wrapper = $('<span class="' + settings.cls + '"><span class="mark"><img src="' + settings.empty + '" /></span></span>');
                        ch.wrapperInner = ch.wrapper.children('span:eq(0)');
                        ch.wrapper.hover(
                                function(e) { ch.wrapperInner.addClass(settings.cls + '-hover');CB(e); },
                                function(e) { ch.wrapperInner.removeClass(settings.cls + '-hover');CB(e); }
                        );

                        /* Wrapping checkbox */
                        $ch.css({position: 'absolute', zIndex: -1, visibility: 'hidden'}).after(ch.wrapper);

                        /* Ttying to find "our" label */
                        var label = false;
                        if ($ch.attr('id'))
                        {
                                label = $('label[for='+$ch.attr('id')+']');
                                if (!label.length) label = false;
                        }
                        if (!label)
                        {
                                /* Trying to utilize "closest()" from jQuery 1.3+ */
                                label = $ch.closest ? $ch.closest('label') : $ch.parents('label:eq(0)');
                                if (!label.length) label = false;
                        }
                        /* Labe found, applying event hanlers */
                        if (label)
                        {
                                label.hover(
                                        function(e) { ch.wrapper.trigger('mouseover', [e]); },
                                        function(e) { ch.wrapper.trigger('mouseout', [e]); }
                                );
                                label.click(function(e) { $ch.trigger('click',[e]); CB(e); return false;});
                        }
                        ch.wrapper.click(function(e) { $ch.trigger('click',[e]); CB(e); return false;});
                        $ch.click(function(e) { CB(e); });
                        $ch.bind('disable', function() { ch.wrapperInner.addClass(settings.cls+'-disabled');}).bind('enable', function() { ch.wrapperInner.removeClass(settings.cls+'-disabled');});
                        $ch.bind('check', function() { ch.wrapper.addClass(settings.cls+'-checked' );}).bind('uncheck', function() { ch.wrapper.removeClass(settings.cls+'-checked' );});

                        /* Disable image drag-n-drop for IE */
                        $('img', ch.wrapper).bind('dragstart', function () {return false;}).bind('mousedown', function () {return false;});

                        /* Firefox antiselection hack */
                        if ( window.getSelection )
                                ch.wrapper.css('MozUserSelect', 'none');

                        /* Applying checkbox state */
                        if ( ch.checked )
                                ch.wrapper.addClass(settings.cls + '-checked');
                        if ( ch.disabled )
                                ch.wrapperInner.addClass(settings.cls + '-disabled');
                });
        }
})(jQuery);



/*
 * jQuery Growfield Library 2
 *
 * http://code.google.com/p/jquery-dynamic/
 * licensed under the MIT license
 *
 * autor: john kuindji
 */

(function($) {

if ($.support == undefined) $.support = {boxModel: $.boxModel};
var windowLoaded = false;
$(window).one('load', function(){ windowLoaded=true; });

// we need to adapt jquery animations for textareas.
// by default, it changes display to 'block' if we're trying to
// change width or height. We have to prevent this.
$.fx.prototype.originalUpdate = $.fx.prototype.update;
$.fx.prototype.update = false;
$.fx.prototype.update = function () {
    if (!this.options.inline) return this.originalUpdate.call(this);
    if ( this.options.step )
        this.options.step.call( this.elem, this.now, this );
        (jQuery.fx.step[this.prop] || jQuery.fx.step._default)( this );
};

var growfield = function(dom) {
    this.dom = dom;
    this.o = $(dom);

    this.opt = {
        auto: true, animate: 100, easing: null,
        min: false, max: false, restore: false,
        step: false
    };

    this.enabled = this.dummy = this.busy =
    this.initial = this.sizeRelated = this.prevH = this.firstH = false;
};

growfield.prototype = {

    toggle: function(mode) {
        if ((mode=='disable' || mode===false)&&this.enabled) return this.setEvents('off');
        if ((mode=='enable' || mode===true)&&!this.enabled) return this.setEvents('on');
        return this;
    },

    setEvents: function(mode) {
        var o = this.o, opt = this.opt, th = this, initial = false;

        if (mode=='on' && !this.enabled) {
            var windowLoad = o.height() == 0 ? true : false;

            if (!windowLoad || windowLoaded) $(function() { th.prepareSizeRelated(); });
            else $(window).one('load', function() {th.prepareSizeRelated(); });

            if (opt.auto) { // auto mode, textarea grows as you type

                o.bind('keyup.growfield', function(e) { th.keyUp(e); return true; });
                o.bind('focus.growfield', function(e) { th.focus(e); return true; });
                o.bind('blur.growfield', function(e) { th.blur(e); return true; });
                initial = {
                    overflow: o.css('overflow'),
                    cssResize: o.css('resize')
                };
                if ($.browser.safari) o.css('resize', 'none');
                this.initial = initial;
                o.css({overflow: 'hidden'});

                // all styles must be loaded before prepare elements
                if (!windowLoad || windowLoaded) $(function() {
                    th.createDummy(); });
                else $(window).one('load', function() { th.createDummy(); });

            } else { // manual mode, textarea grows as you type ctrl + up|down
                o.bind('keydown.growfield', function(e) { th.manualKeyUp(e); return true; });
                o.css('overflow-y', 'auto');
                if (!windowLoad || windowLoaded) $(function() { th.update(o.height());});
                else $(window).one('load', function() { th.update(o.height()); });
            }
            o.addClass('growfield');
            this.enabled = true;
        }
        else if (mode=='off' && this.enabled) {
            if (this.dummy) {
                this.dummy.remove();
                this.dummy = false;
            }
            o.unbind('.growfield').css('overflow', this.initial.overflow);
            if ($.browser.safari) o.css('resize', this.initial.cssResize);
            this.enabled = false;
        }
        return this;
    },

    setOptions: function(options) {
        var opt = this.opt, o = this.o;
        $.extend(opt, options);
        if (!$.easing) opt.easing = null;
    },

    update: function(h, animate) {
        var sr = this.sizeRelated, val = this.o.val(), opt = this.opt, dom = this.dom, o = this.o,
              th = this, prev = this.prevH;
        var noHidden = !opt.auto, noFocus = opt.auto;

        h = this.convertHeight(Math.round(h), 'inner');
        // get the right height according to min and max value
        h = opt.min > h ? opt.min :
              opt.max && h > opt.max ? opt.max :
              opt.auto && !val ? opt.min : h;

        if (opt.max && opt.auto) {
            if (prev != opt.max && h == opt.max) { // now we reached maximum height
                o.css('overflow-y', 'scroll');
                if (!opt.animate) o.focus(); // browsers do loose cursor after changing overflow :(
                noHidden = true;
                noFocus = false;
            }
            if (prev == opt.max && h < opt.max) {
                o.css('overflow-y', 'hidden');
                if (!opt.animate) o.focus();
                noFocus = false;
            }
        }

        if (h == prev) return true;
        this.prevH = h;

        if (animate) {
            th.busy = true;
            o.animate({height: h}, {
                duration: opt.animate,
                easing: opt.easing,
                overflow: null,
                inline: true, // this option isn't jquery's. I added it by myself, see above
                complete: function(){
                    // safari/chrome fix
                    // somehow textarea turns to overflow:scroll after animation
                    // i counldn't find it in jquery fx :(, so it looks like some bug
                    if (!noHidden) o.css('overflow', 'hidden');
                    // but if we still need to change overflow (due to opt.max option)
                    // we have to invoke focus() event, otherwise browser will loose cursor
                    if (!noFocus) o.focus();
                    th.busy = false;
                },
                queue: false
            });
        } else dom.style.height = h+'px';
    },

    manualKeyUp: function(e) {
        if (!e.ctrlKey) return;
        if (e.keyCode != 38 && e.keyCode != 40) return;
        this.update(
            this.o.outerHeight() + (this.opt.step*( e.keyCode==38? -1: 1)),
            this.opt.animate
        );
    },

    keyUp: function(e) {
        if (this.busy) return true;
        if ($.inArray(e.keyCode, [37,38,39,40]) != -1) return true;
        this.update(this.getDummyHeight(), this.opt.animate);
    },

    focus: function(e) {
        if (this.busy) return true;
        if (this.opt.restore) this.update(this.getDummyHeight(), this.opt.animate);
    },

    blur: function(e) {
        if (this.busy) return true;
        if (this.opt.restore) this.update(0, false);
    },

    getDummyHeight: function() {
        var val = this.o.val(), h = 0, sr = this.sizeRelated, add = "\n111\n111";

        // Safari has some defect with double new line symbol at the end
        // It inserts additional new line even if you have only one
        // But that't not the point :)
        // Another question is how much pixels to keep at the bottom of textarea.
        // We'll kill many rabbits at the same time by adding two new lines at the end
        if ($.browser.safari) val = val.substring(0, val.length-1); // safari has an additional new line ;(

        if (!sr.lh || !sr.fs) val += add;

        this.dummy.val(val);

        // IE requires to change height value in order to recalculate scrollHeight.
        // otherwise it stops recalculating scrollHeight after some magical number of pixels
        if ($.browser.msie) this.dummy[0].style.height = this.dummy[0].scrollHeight+'px';

        h = this.dummy[0].scrollHeight;
        if (sr.lh && sr.fs) h += sr.lh > sr.fs ? sr.lh+sr.fs :  sr.fs * 2;

        // now we have to minimize dummy back, or we'll get wrong scrollHeight next time
        if ($.browser.msie) this.dummy[0].style.height = '20px'; // random number

        return h;
    },

    createDummy: function() {
        var o = this.o, val = this.o.val();
        // we need dummy to calculate scrollHeight
        // (there are some tricks that can't be applied to the textarea itself, otherwise user will see it)
        // Also, dummy must be a textarea too, and must be placed at the same position in DOM
        // in order to keep all the inherited styles
        var dummy = o.clone().addClass('growfieldDummy').attr('name', '').attr('tabindex', -9999)
                               .css({position: 'absolute', left: -9999, top: 0, height: '20px', resize: 'none'})
                               .insertBefore(o).show();

        // if there is no initial value, we have to add some text, otherwise textarea will jitter
        // at the first keydown
        if (!val) dummy.val('dummy text');
        this.dummy = dummy;
        // lets set the initial height
        this.update(!jQuery.trim(val) ? 0 : this.getDummyHeight(), false);
    },

    convertHeight: function(h, to) {
        var sr = this.sizeRelated, mod = (to=='inner' ? -1 : 1), bm = $.support.boxModel;
        // what we get here in 'h' is scrollHeight value.
        // so we need to subtract paddings not because of boxModel,
        // but only if browser includes them to the scroll height (which is not defined by box model)
        return h
            + (bm ? sr.bt : 0) * mod
            + (bm ? sr.bb : 0) * mod
            + (bm ? sr.pt : 0) * mod
            + (bm ? sr.pb : 0) * mod;
    },

    prepareSizeRelated: function() {
        var o = this.o, opt = this.opt;

        if (!opt.min) {
            opt.min = parseInt(o.css('min-height'), 10) || this.firstH || parseInt(o.height(), 10) || 20;
            if (opt.min <= 0) opt.min = 20; // opera fix
            if (!this.firstH) this.firstH = opt.min;
        }
        if (!opt.max) {
            opt.max = parseInt(o.css('max-height'), 10) || false;
            if (opt.max <= 0) opt.max = false; // opera fix
        }
        if (!opt.step) opt.step = parseInt(o.css('line-height'), 10) || parseInt(o.css('font-size'), 10) || 20;

        var sr = {
            pt: parseInt(o.css('paddingTop'), 10)||0,
            pb: parseInt(o.css('paddingBottom'), 10)||0,
            bt: parseInt(o.css('borderTopWidth'), 10)||0,
            bb: parseInt(o.css('borderBottomWidth'), 10)||0,
            lh: parseInt(o.css('lineHeight'), 10) || false,
            fs: parseInt(o.css('fontSize'), 10) || false
        };

        this.sizeRelated = sr;
    }
};

$.fn.growfield = function(options) {
    if ('destroy'==options) return this.each(function() {
        var gf = $(this).data('growfield');
        if (gf == undefined) return true;
        gf.toggle(false);
        $(this).removeData('growfield');
        return true;
    });
    if ('restart'==options) return this.each(function(){
        var gf = $(this).data('growfield');
        if (gf == undefined) return true;
        gf.toggle(false).toggle(true);
    });
    var tp = typeof options;
    return this.each(function() {
        if (!/textarea/i.test(this.tagName)||$(this).hasClass('growfieldDummy')) return true;
        var initial = false, o = $(this), gf = o.data('growfield');
        if (gf == undefined) {
            initial = true;
            o.data('growfield', new growfield(this));
            gf = o.data('growfield');
        }
        if (initial) {
            var opt = $.extend({}, $.fn.growfield.defaults, options);
            gf.setOptions(opt);
        }
        if (!initial && (!options || tp == 'object')) gf.setOptions(options);
        if (tp == 'string') {
            if (options.indexOf('!')==0 && $.fn.growfield.presets[options.substr(1)]) o.unbind('.'+i+'.'+options.substr(1));
            else if ($.fn.growfield.presets[options]) {
                var pOpt = $.fn.growfield.presets[options];
                gf.setOptions(pOpt, options);
            }
        }
        if (initial && !opt.skipEnable) gf.toggle(true);
        if (!initial && (tp == 'boolean' || options=='enable' || options == 'disable')) gf.toggle(options);
    });
};

$.fn.growfield.defaults = {};
$.fn.growfield.presets = {};

})(jQuery);

/*
    local functions
*/


/* activate CheckTree */
if(typeof opf_use_checktrees!='undefined') {
    $("ul.tree1").checkTree_my({labelAction: "check", allChildrenMarksParentChecked: "yes", checkedMarksChildrenChecked: "yes"});
    $("ul.tree2").checkTree_my({labelAction: "check", allChildrenMarksParentChecked: "yes", checkedMarksChildrenChecked: "yes"});

    //modules_checktree_visibility();
    $("input[class=activity]").checkbox({ cls:"activity", empty: IMAGE_URL + "/empty.gif"});

}



function modules_checktree_visibility() {
    i = document.outputfilter.type.selectedIndex;
    if(document.outputfilter.type.options[i].value == '6page_first' || document.outputfilter.type.options[i].value == '7page' || document.outputfilter.type.options[i].value == '8page_last' || document.outputfilter.type.options[i].value == '9page_final') {
        document.getElementById('OPF_ID_CHECKTREE').style.display = 'none';
        document.getElementById('OPF_ID_SEC_DESC').style.display = 'none';
        document.getElementById('OPF_ID_SEC_DESC_2').style.display = 'none';
        document.getElementById('OPF_ID_PAGE_DESC').style.display = '';
        document.getElementById('OPF_ID_PAGE_DESC_2').style.display = '';
    } else {
        document.getElementById('OPF_ID_CHECKTREE').style.display = '';
        document.getElementById('OPF_ID_SEC_DESC').style.display = '';
        document.getElementById('OPF_ID_SEC_DESC_2').style.display = '';
        document.getElementById('OPF_ID_PAGE_DESC').style.display = 'none';
        document.getElementById('OPF_ID_PAGE_DESC_2').style.display = 'none';
    }
}



/* activate EditArea */
if(typeof opf_use_css_editarea!='undefined') {
    editAreaLoader.init({ id : "css", syntax: "css", start_highlight: true });
}
if(typeof opf_editarea!='undefined') {
    if(opf_editarea=='editable')
        editAreaLoader.init({ id : "func", syntax: "php", start_highlight: true });
    else
        editAreaLoader.init({ id : "func", syntax: "php", start_highlight: true, is_editable: false });
}
if(typeof opf_editarea_list!='undefined') {for(var i in opf_editarea_list) {editAreaLoader.init({ id : opf_editarea_list[i], syntax: "php", start_highlight: true });}}


/* activate GrowField */
$("#desc").growfield();
if(typeof opf_growfield_list!='undefined') {
    for(var i in opf_growfield_list) {
        $("#"+opf_growfield_list[i]).growfield();
    }
}



/* popup-window */
function opf_popup(url) {
 w = window.open(url, "OutputFilter-Dashboard Documentation", "width=1024,height=768,resizable=yes,scrollbars=yes");
 w.focus();
 return false;
}


if(typeof document.outputfilter!='undefined') {
    if(typeof document.outputfilter.type!='undefined') {
        // display text-block for page-type or section-type, and module-checktree
        modules_checktree_visibility();
        // display page-checktree
        document.getElementById('OPF_ID_PAGECHECKTREE').style.display = '';
    }
}