/*-- Addition for remembering expanded state of pages --*/
function writeSessionCookie(cookieName, cookieValue) {
    document.cookie = escape(cookieName) + "=" + escape(cookieValue) + ";";
}

function toggle_viewers() {
    if (document.add.visibility.value == 'private') {
        document.getElementById('viewers').style.display = 'block';
    } else if (document.add.visibility.value == 'registered') {
        document.getElementById('viewers').style.display = 'block';
    } else {
        document.getElementById('viewers').style.display = 'none';
    }
}

function toggle_visibility(id) {
    if (document.getElementById(id).style.display == "block") {
        document.getElementById(id).style.display = "none";
        writeSessionCookie(id, "0");//Addition for remembering expanded state of pages
    } else {
        document.getElementById(id).style.display = "block";
        writeSessionCookie(id, "1");//Addition for remembering expanded state of pages
    }
}

var plus = new Image;
plus.src = THEME_URL + "/images/expand.png";
var minus = new Image;
minus.src = THEME_URL + "/images/collapse.png";

function toggle_plus_minus(id) {
    var img_src = document.images['plus_minus_' + id].src;
    if (img_src == plus.src) {
        document.images['plus_minus_' + id].src = minus.src;
    } else {
        document.images['plus_minus_' + id].src = plus.src;
    }
}

if (typeof jQuery != 'undefined') {
    jQuery(document).ready(function ($) {
        // fix for Flat Theme
        $('div.pages_list table td').css('padding-top', 0).css('padding-bottom', 0);
        $('table.pages_view tbody tr td').css('line-height', '12px');
        //$('table.pages_view tbody tr td.list_menu_title').css('width','300px');
        //$('table.pages_view tbody tr td.list_page_id').css('width','68px');
        //$('td.header_list_page_id').css('width','60px').next('td').css('width','78px');
    });
}