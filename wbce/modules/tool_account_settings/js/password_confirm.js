
jQuery(document).ready(function($) {
    // Custom events (enables button on certain score)
    // Check the readme for a detailed list of events
    $('#submit').attr('disabled', true);
    $('#submit').attr('title', MSG_CONFIRM);

    //Append a change event listener to ensure confirmation w/ a password
    $('#current_password').keyup(function(){
        //Validate if current_password is set
        var validated = false;        
        if($('#current_password').val().length > 5){
            validated = true;
        }

        //If current_password is set enable submit button
        if(validated == true){
            $("#submit").removeAttr("disabled").trigger('change');
            $("#submit").removeAttr("title");
        }                             
    });
     $('input:first').trigger('change');
});