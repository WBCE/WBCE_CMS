
$( document ).ready(function() {
    
    $.insert(WB_URL+"/modules/droplets/js/jquery.tablesorter.js");
    
    $(".show-droplet-code").on("click", function(event){
        event.preventDefault();
        $(this).next('.droplet-info').toggle();
    }); 
    
    
    $("#checkAll").click(function () {
       $('input:checkbox.checker').not(this).prop('checked', this.checked);
    });
    
    $('#markeddroplet').change(function() {
        $('#operateChecked').toggle();
    });
    
    var countChecked = function () {
        var n = $("input:checked.checker").length;
        if (n >= 1) {
            $("#operateChecked").css({
                "display": "block"
            });
        } else {
            $("#operateChecked").css({
                "display": "none"
            });
        }
    };
    countChecked();

    $("input[type=checkbox]").on("click", countChecked);
});


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
        console.log(sNewClass);
        // prepare the DATASTRING to send via ajax
        var DATASTRING = 'purpose=toggle_status&action=' + state + '&idkey='+ IDKEY;     
        jQuery.ajax({
            url: WB_URL +"/modules/droplets/ajax_toggle_state.php",
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
    
    // toggle show_date
    $('#show_date').on( "click", function () {  
        var uri = $(this).data('uri');
        window.location = uri;// + '#show_date';
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
        
        var FILTER_NAME = $(sRowID).closest('tr').find('td:eq(1)').text()
        var MSG    = $(this).data('question').replace("%s", FILTER_NAME);
        var sCANCEL = $(this).data('cancel');
        var sDELETE = $(this).data('delete');
        
        // Create replacement HTML for table row
        // let's preserve the height of the row
        var height = $(sRowID).height() + 'px !important';
        var colspan = DROPLETS_SHOW_DATE ? 6 : 5;
        var new_row = `
            <tr class="row-on-delete" id="`+ ID +`">
                <td colspan="`+ colspan +`" style="height: `+ height +`;">
                <table class="delete-table" width="100%">
                    <tr>
                        <td width="34"></td>
                        <td style="text-align:left;font-size: 14px;font-weight:800" class="droplet-name"><i class="fa fa-fw fa-edit"></i> ` + FILTER_NAME + `</td>
                        <td style="text-align:right;font-size: 15px;">` + MSG + `</td>
                        <td  style="height: `+ height +`;">
                            <a href="javascript:void(0);" id="reset" data-row="`+ sRowID +`" class="btn-inline red">` + sCANCEL + `</a>
                            <a href="javascript:void(0);" id="del" data-id="`+ ID +`" data-idkey="`+ IDKEY +`" data-uri="` + $(this).data('del-uri') + `" class="btn-inline green">` + sDELETE + `</a>

                        </td>
                    </tr>
                </td>
            </tr>`;
        
        // apply replacement
        // the object oReplacement will be used in the next function with#reset
        oReplacement = $(sRowID).replaceWith(new_row);
    });    
    

    $(document).on("click", "#reset", function() {
       // reset row to original
       $(this).parentsUntil( $( "tr.row-on-delete" )).parent().replaceWith(oReplacement);  
       return false;
    });

    $(document).on("click", "#del", function() {
       // Delete the row
        var ID    = $(this).data('id');
        var IDKEY = $(this).data('idkey');
        var DATASTRING = $(this).data('uri');
        $("#" + ID).fadeOut(1250).delay(2000).fadeIn(1);
        window.location = DATASTRING;
    });
});