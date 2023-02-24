/**
 *
 *    This file will be loaded from the backend_body.js of the Module if needed
 */


/**
 *
 *    Ensure the sortable function is loaded
 *    if isn't yet, load the jquery UI ('sortable' is part of the UI)
 */

if(!jQuery().sortable){
    jQuery.insert(WB_URL+"/include/jquery/jquery-ui-min.js");

}

if(jQuery().sortable){

    /**
        Drag&Drop
        =========
        sortable | http://jqueryui.com/demos/sortable/
    */

    jQuery(function() {
        jQuery('.dragdrop_item').addClass('dragdrop_handle');
        //jQuery(".dragdrop_form .move a").remove();

        jQuery(".dragdrop_form tbody").sortable({
            appendTo: 'tbody',
            handle:   '.dragdrop_handle',
            opacity:  0.8,
            cursor:   'move',
            delay:    100,
            items:    'tr',
            dropOnEmpty: false,
            update: function() {
                jQuery.ajax({
                    type:        'POST',
                    url:         AJAX_PLUGINS  + '/ajax_dragdrop.php',
                    data:        jQuery(this).sortable("serialize", {
                                    attribute: 'data-idkey', // what attribute to use
                                    //key: 'id',
                                    // expression: /(.+)/
                                     expression: /(.+)[_](.+)/ /* split id '_' idkey */
                                 }) + '&action=updatePosition',
                    dataType:     'json',
                    success:    function(json_respond){
                        if( json_respond.success != true ) {
                            alert(json_respond.message);
                        }
                        console.log(json_respond.message);
                    }
                });
            }
        })
    });
}//endif


/**
 *  AJAX
 *  Function to toggle enabled|disabled status of a filter
 */
$(document).ready(function() {

    $('.status [type=checkbox]').click(function(event) {
        // get the ID from the checkbox
        var ID = event.target.id;
        // get rid of prefix in the ID
        var ID = ID.replace("switch_", "");
        // prepand with # for to get an easy selector
        var sRowID = '#' + ID;

        // get IDKEY from data-idkey attribute
        // PLEASE NOTE: It's not advisable to send IDKEY via the id attribute
        //              because the many special characters in it are not
        //              supportet for the id attribute and it causes problems
        var IDKEY  = $(sRowID).data('idkey').replace("id_", "");

        // prepare tr class for DOM
        var state = ($(this).is(':checked')) ? '1' : '0';
        var sOldClass = $(sRowID).attr('class');
        var sNewClass = ((state == 0) ? 'in' : '') + 'active';

        // prepare the DATASTRING to send via ajax
        var DATASTRING = 'purpose=toggle_status&action=' + state + '&idkey='+ IDKEY;
        jQuery.ajax({
            url: AJAX_PLUGINS +"/ajax_toggle_state.php",
            type: "POST",
            dataType: 'json',
            data: DATASTRING,
            success: function(json_respond)
            {
                if(json_respond.success == true) {
                    console.log(json_respond.message);
                    // now change tr class in DOM
                    $(sRowID).removeClass(sOldClass).addClass(sNewClass);
                } else {
                    console.log('something went wrong: ' + json_respond.message);
                }
            }
        });
    });



    $('tr').on("click", ".delete-item", function(event) {
        event.preventDefault();
        var ID     = $(this).parent().parent().attr('id');
        var sRowID = '#' + ID;

        // get IDKEY from data-idkey attribute
        // PLEASE NOTE: It's not advisable to send IDKEY via the id attribute
        //              because the many special characters in it are not
        //              supportet for the id attribute and it causes problems
        var IDKEY  = $(sRowID).data('idkey').replace("id_", "");

              //  .attr('title');
        var SWITCH = $(sRowID).find('td:eq(0)').html()
        var FILTER_NAME = $(sRowID).closest('tr').find('td:eq(1)').text()
        var MSG    = $(this).data('question').replace("%s", FILTER_NAME);
        var sCANCEL = $(this).data('cancel');
        var sDELETE = $(this).data('delete');

        // Create replacement HTML for table row
        // let's preserve the height of the row
        var height = $(sRowID).height() + 'px !important';
        var new_row = `
            <tr class="row-on-delete" id="`+ ID +`">
                <td class="low-opac">` + SWITCH + `</td>
                <td>` + FILTER_NAME + `</td>
                <td colspan="4">` + MSG + `</td>
                <td colspan="5" style="height: `+ height +`;">
                    <a href="javascript:void(0);" id="del" data-id="`+ ID +`" data-idkey="`+ IDKEY +`" class="btn-inline green">` + sDELETE + `</a>
                    <a href="javascript:void(0);" id="reset" data-row="`+ sRowID +`" class="btn-inline red">` + sCANCEL + `</a>
                </td>
            </tr>`;

        // apply replacement
        // the object oReplacement will be used in the next function with#reset
        oReplacement = $(sRowID).replaceWith(new_row);
    });

 });

$(document).on("click", "#reset", function() {
   // reset row to original
   $(this).parent().parent().replaceWith(oReplacement);
   return false;
});
$(document).on("click", "#del", function() {
   // Delete the row
    var ID    = $(this).data('id');
    var IDKEY = $(this).data('idkey');
    var DATASTRING = 'purpose=delete_row&idkey='+ IDKEY;
    
    var parentTbody = $("tr#"+ID).parent().attr('id');
    window.location.href = MODULE_URL + "&hilite="+ parentTbody;
    /*
    $.ajax({
        url: AJAX_PLUGINS + "/ajax_delete_row.php",
        type: "POST",
        data: DATASTRING,
        dataType: 'json',
        success: function (json_respond) {
            if (json_respond.success == true) {
                console.log('row ID: ' + ID + ' was removed successfully: ' + json_respond.message);
                // let's make the row disappear
                $("#" + ID).fadeOut(1250);
                window.location.href = MODULE_URL + "";
            } else {
                console.log('something went wrong: ' + json_respond.message);
            }
        }
    });
     * */
});