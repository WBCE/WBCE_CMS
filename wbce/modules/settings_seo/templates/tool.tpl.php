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

 
</style>


<div class="settings">
    <h2><?php echo $MOD_SET_GENERAL['HEADER']; ?></h2>
    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
        <?php echo $admin->getFTAN(); ?>
        <p><?php echo $MOD_SET_GENERAL['DESCRIPTION']; ?></p>
        
        <?php /*
        <label class="settingName" for="bname">Benutzername</label>
        <input type="text" id="bname" maxlength="30"> <br />
        <label class="settingName" for="bname">Benutzername</label>
        <input type="text" id="bname" maxlength="30">
        */ ?>
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        <br />  <br />  
        <hr />
      
        
        
        <!-- WEBSITE_TITLE -->
        <label class="settingName" for="website_title"><?php echo $TEXT['WEBSITE_TITLE'] ?></label>
        <input type="text" id="website_title" name="website_title" maxlength="30"  value="<?php echo WEBSITE_TITLE ?>" style="width:350px" /><br />
        <hr />       
 
        <!-- WEBSITE DESCRIPTION -->
        <label class="settingName" for="website_description"><?php echo $TEXT['WEBSITE_DESCRIPTION'] ?></label>
        <textarea id="website_description" name="website_description"   style="width:350px" ><?php echo WEBSITE_DESCRIPTION ?></textarea><br />
        <hr />       
 
        <!-- WEBSITE KEYWORDS -->
        <label class="settingName" for="website_description"><?php echo $TEXT['WEBSITE_KEYWORDS'] ?></label>
        <textarea id="website_keywords" name="website_keywords"    style="width:350px" ><?php echo WEBSITE_KEYWORDS ?></textarea><br />
        <hr />     
        
        <!-- WEBSITE HEADER -->
        <label class="settingName" for="website_description"><?php echo $TEXT['WEBSITE_HEADER'] ?></label>
        <textarea id="website_header" name="website_header"   style="width:350px" ><?php echo WEBSITE_HEADER ?></textarea><br />
        <hr />     
        
         <!-- WEBSITE FOOTER -->
        <label class="settingName" for="website_description"><?php echo $TEXT['WEBSITE_FOOTER'] ?></label>
        <textarea id="website_footer" name="website_footer"    style="width:350px" ><?php echo WEBSITE_FOOTER ?></textarea><br />
        <hr />       



   
        
         <br /><br />
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        
    </form>
</div><!-- settingsGeneral -->
