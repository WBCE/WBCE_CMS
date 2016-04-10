<?/*
For automated detection if form has benn sent the submit button needs to have 
name+id ="save_settings". (Optional ($_POST['action']) == 'save')
For return to admintools the responsible button must have name+id="admin_tools".
And to activate default setting it heed to have name+id="save_default".

$returnUrl      Is used as form Action it sends the form to itself(apeform)

Language vars whit preceding MOD_ can be found in the launguage file of this module
Other language vars are from the default WB (e.g. $TEXT or $HEADING are from the 
WBCE language files)

The default button uses a simple Javascript return confirm()for a simple "Are you sure?"
*/?>
<style>
.settings form {
    width: 90%;

}
.settings label,
.settings input,
.settings .settingName {
    float: left;
}    
.settings .settingName {
    width: 250px;
}
.settings .settingName::after {    
    content: ": ";
}

.settings hr,
.settings br {
clear:both;
margin:2px; padding:0;
}

.settings .admin_tools{
    float:right;
}

.settings .save_default{
    margin-left: 30px;
}

.settings select {
    width:350px;
}
.settings .long,
.settings textarea {
    width:600px;
}
.settings textarea {
    height: 80px;
}
 
</style>
<script  language="javascript" type="text/javascript">
    function change_wbmailer(type) {
        
        if(type == 'smtp') {
            document.getElementById('smtp_settings').style.display = 'block';
        } else if(type == 'php') {
            document.getElementById('smtp_settings').style.display = 'none';  
        }
    }
    function toggle_wbmailer_pass(){

        if(document.getElementById('wbmailer_smtp_password').type=='password'){
                    alert(document.getElementById('wbmailer_smtp_password').type);
            document.getElementById('wbmailer_smtp_password').type = "text";
        } 
        if('text'==document.getElementById('wbmailer_smtp_password').type){
            document.getElementById('wbmailer_smtp_password').type='password';
        } 
    }   
</script>



<div class="settings">
    <h2><?php echo $MOD_SET_MAIL['HEADER']; ?></h2>
    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
        <?php echo $admin->getFTAN(); ?>
        <p><?php echo $MOD_SET_MAIL['DESCRIPTION']; ?></p>
        
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        <br />  <br />  
        <hr />
      
      
        <!-- DEFAULT_SENDER_MAIL -->
        <label class="settingName" for="server_email"><?php echo $TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] ?></label>
        <input class="long" type="text" id="server_email" name="server_email" maxlength="255"  value="<?php echo SERVER_EMAIL ?>" /><br />
        <hr />       

        <!-- DEFAULT_SENDER_MAIL -->
        <label class="settingName" for="wbmailer_default_sendername"><?php echo $TEXT['WBMAILER_DEFAULT_SENDER_NAME'] ?></label>
        <input class="long" type="text" id="wbmailer_default_sendername" name="wbmailer_default_sendername" maxlength="255"  value="<?php echo WBMAILER_DEFAULT_SENDERNAME ?>" /><br />
        <hr />       
 
     
        <!-- WBMAILER_FUNCTION -->
        <div class="settingName" ><?php echo $TEXT['WBMAILER_FUNCTION'] ?></div>
        <input onclick="javascript: change_wbmailer('php');" type="radio" name="wbmailer_routine" id="wbmailer_routine_phpmail" style="width: 14px; height: 14px;" value="phpmail" <?php if (WBMAILER_ROUTINE=="phpmail") echo WB_CHECK; ?> />
        <label for="wbmailer_routine_phpmail" onclick="javascript: change_wbmailer('php');"><?php echo $TEXT['WBMAILER_PHP'] ?></label>
        <input onclick="javascript: change_wbmailer('smtp');" type="radio" name="wbmailer_routine" id="wbmailer_routine_smtp" style="width: 14px; height: 14px;" value="smtp" <?php if (WBMAILER_ROUTINE=="smtp") echo WB_CHECK; ?> />
        <label for="wbmailer_routine_smtp" onclick="javascript: change_wbmailer('smtp');" ><?php echo $TEXT['WBMAILER_SMTP'] ?></label>
        <hr />  
        
        <div id="smtp_settings" <?php if (WBMAILER_ROUTINE=="smtp") echo 'style="display:block"'; else echo 'style="display:none"'; ?>> 
            <br />
            <p><?php echo $TEXT['WBMAILER_NOTICE'] ?></p>
            <hr /> 

            <!-- WBMAILER_SMTP_HOST -->
            <label class="settingName" for="wbmailer_smtp_host"><?php echo $TEXT['WBMAILER_SMTP_HOST'] ?></label>
            <input class="long" type="text" id="wbmailer_smtp_host" name="wbmailer_smtp_host" maxlength="250"  value="<?php echo WBMAILER_SMTP_HOST ?>" /><br />
            <hr />    

            <!-- WBMAILER_SMTP_AUTH -->
            <label class="settingName" for="wbmailer_smtp_auth"><?php echo $TEXT['WBMAILER_SMTP_AUTH'] ?></label>
            <input type="checkbox" name="wbmailer_smtp_auth" id="wbmailer_smtp_auth" value="true" disabled="disabled" checked="checked" /><br />
            <hr /> 
            
            <!-- WBMAILER_SMTP_USERNAME -->
            <label class="settingName" for="wbmailer_smtp_username"><?php echo $TEXT['WBMAILER_SMTP_USERNAME'] ?></label>
            <input class="long" type="text" id="wbmailer_smtp_username" name="wbmailer_smtp_username" maxlength="250"  value="<?php echo WBMAILER_SMTP_USERNAME ?>" /><br />
            <hr />    

            <!-- WBMAILER_SMTP_PASSWORD -->
            <label class="settingName" for="wbmailer_smtp_password"><?php echo $TEXT['WBMAILER_SMTP_PASSWORD'] ?></label>
            <input class="long" type="password" id="wbmailer_smtp_password" name="wbmailer_smtp_password" maxlength="250"  value="<?php echo WBMAILER_SMTP_PASSWORD ?>" /><br />
            <hr />    

            
            
            
        </div><!-- smtp_settings -->
        
            
         <br /><br />
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        
    </form>
</div><!-- settingsGeneral -->



