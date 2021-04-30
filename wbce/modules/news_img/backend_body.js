$(function() {
    $("span.resize_defaults").unbind("click").on("click",function(e) {
        var size = $(this).data("value");
        $("input#resize_width").val(size);
        $("input#resize_height").val(size);
    });
    $("span.resize_defaults_thumb").unbind("click").on("click",function(e) {
        var size = $(this).data("value");
        $("input#thumb_width").val(size);
        $("input#thumb_height").val(size);
    });
    $("input#toggle_mode").unbind("click").on("click",function(e) {
        $("form[name=modify_mode]").submit();
    });
});

function nwi_toggle_2nd_block() {
    if($('input[name="use_second_block"]').is(":checked")) {
        $("tr.nwi_use_second_block").show('slow');
    } else {
        $("tr.nwi_use_second_block").hide('slow');
    }
}
$('input[name="use_second_block"]').on('click',function() { nwi_toggle_2nd_block() });
nwi_toggle_2nd_block();

// drag & drop

var MODULE_URL = WB_URL + '/modules/news_img';
var ICONS = WB_URL + '/modules/news_img/images';
var AJAX_PLUGINS =  WB_URL + '/modules/news_img/ajax';

$(function() {
    // Load external ajax_dragdrop file
    if($('.dragdrop_form').length > 0){
        $.get(AJAX_PLUGINS +"/ajax_dragdrop.js");
    }
});

var sheet = document.createElement('style');
sheet.innerHTML = ".mod_news_img_arrow {visibility:hidden;}"; 
document.body.appendChild(sheet);

function checkActionAndPosts() {
   if ($('#mod_news_post_list input[type=checkbox]:checked').length == 0 ) {	
       return false;
   }
}

/*
    Cross-Browser Tooltip von Mathias Karstädt steht unter einer Creative Commons Namensnennung 3.0 Unported Lizenz.
    http://webmatze.de/ein-einfacher-cross-browser-tooltip-mit-javascript-und-css/
*/
(function(window, document, undefined){
    var XBTooltip = function(element, userConf, tooltip) {
        var config = {
            id: userConf.id || undefined,
            className: userConf.className || undefined,
            x: userConf.x || 20,
            y: userConf.y || 20,
            text: userConf.text || undefined
        };
        var over = function(event) {
            tooltip.style.display = "block";
        },
        out = function(event) {
            tooltip.style.display = "none";
        },
        move = function(event) {
            event = event ? event : window.event;
            if ( event.pageX == null && event.clientX != null ) {
                var doc = document.documentElement, body = document.body;
                event.pageX = event.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
                event.pageY = event.clientY + (doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
            }
            tooltip.style.top = (event.pageY+config.y) + "px";
            tooltip.style.left = (event.pageX+config.x) + "px";
        }
        if (tooltip === undefined && config.id) {
            tooltip = document.getElementById(config.id);
            if (tooltip) tooltip = tooltip.parentNode.removeChild(tooltip)
        }
        if (tooltip === undefined && config.text) {
            tooltip = document.createElement("div");
            if (config.id) tooltip.id= config.id;
            tooltip.innerHTML = config.text;
        }
        if (config.className) tooltip.className = config.className;
        tooltip = document.body.appendChild(tooltip);
        tooltip.style.position = "absolute";
        tooltip.style.zIndex = "999";
        element.onmouseover = over;
        element.onmouseout = out;
        element.onmousemove = move;
        over();
    };
    window.XBTooltip = window.XBT = XBTooltip;
})(this, this.document);
