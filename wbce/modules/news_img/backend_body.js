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
});

// drag & drop

var MODULE_URL = WB_URL + '/modules/news_img';
var ICONS = WB_URL + '/modules/news_img/images';
var AJAX_PLUGINS =  WB_URL + '/modules/news_img/ajax';


$(function() {
        // Load external ajax_dragdrop file
    if($('.dragdrop_form').length > 0){
        $.insert(AJAX_PLUGINS +"/ajax_dragdrop.js");
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
