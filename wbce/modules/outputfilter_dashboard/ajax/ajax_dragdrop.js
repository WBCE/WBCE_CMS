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
        jQuery(".dragdrop_form .move a").remove(); 
        
        jQuery(".dragdrop_form tbody").sortable({ 
            appendTo:     'body',
            handle:      '.dragdrop_handle',
            opacity:     0.8, 
            cursor:     'move', 
            delay:         100, 
            items:         'tr',
            dropOnEmpty: false,                    
            update: function() {                         
                jQuery.ajax({
                    type:        'POST',
                    url:         AJAX_PLUGINS+'/ajax_dragdrop.php', 
                    data:        jQuery(this).sortable("serialize", { 
                                     expression: /(.+)[:](.+)/
                                 }) + '&action=updatePosition', 
                    dataType:     'json',
                    success:    function(json_respond){
                        if( json_respond.success != true ) {                                        
                            alert(json_respond.message);
                        }
                        // due to expiring IDKEYs we have to refresh the page
                        if(window.location.href==MODULE_URL) {
                            window.location.reload();   
                        } else {
                            window.location.replace(MODULE_URL);
                        }
                    }
                });     
            }                    
        })
    });             
}//endif
