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


<div class="settings">
    <h2><?php echo $MOD_SET_GENERAL['HEADER']; ?></h2>
    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
        <?php echo $admin->getFTAN(); ?>
        <p><?php echo $MOD_SET_GENERAL['DESCRIPTION']; ?></p>
        
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        <br />  <br />  
        <hr />
      
        <!-- Search visibility -->
        <label class="settingName" for="search"><?php echo $TEXT['VISIBILITY'] ?></label>
        <select class="long" name="search" id="search">
            <option value="public"<?php     if (SEARCH=="private") echo WB_SELECT ?>><?php echo $TEXT['PUBLIC']     ?></option>
            <option value="private"<?php    if (SEARCH=="private") echo WB_SELECT ?>><?php echo $TEXT['PRIVATE']    ?></option>
            <option value="registered"<?php if (SEARCH=="private") echo WB_SELECT ?>><?php echo $TEXT['REGISTERED'] ?></option>
            <option value="none"<?php       if (SEARCH=="private") echo WB_SELECT ?>><?php echo $TEXT['NONE']       ?></option>
        </select><br /> 
        <hr />
        
        <!-- Search FE template -->
        <?php $selects=se_GetTemplatesArray(); ?>
        <label class="settingName" for="search_template"><?php echo $TEXT['TEMPLATE'] ?></label>
        <select class="long" name="search_template" id="search_template">
            <option value="" <?php if ($SEARCH_TEMPLATE=="") echo WB_SELECT ?>><?php echo $TEXT['SYSTEM_DEFAULT'] ?></option>
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $value): ?>
            <option value="<?php echo $value['directory'] ?>" <?php if($SEARCH_TEMPLATE == $value['directory']) echo WB_SELECT; ?> ><?php echo $value['name']." (".$value['directory'].")" ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br />      
        <hr /> 
        
        <!-- Search HEADER -->
        <label class="settingName" for="search_header"><?php echo $TEXT['HEADER'] ?></label>
        <textarea id="search_header" name="search_header"    ><?php echo $SEARCH_HEADER ?></textarea><br />
        <hr />     
        
         <!-- Search results header -->
        <label class="settingName" for="search_results_header"><?php echo $TEXT['RESULTS_HEADER'] ?></label>
        <textarea id="search_results_header" name="search_results_header"><?php echo $SEARCH_RESULTS_HEADER ?></textarea><br />
        <hr />       

        <!-- Search results loop -->
        <label class="settingName" for="search_results_loop"><?php echo $TEXT['RESULTS_LOOP'] ?></label>
        <textarea id="search_results_loop" name="search_results_loop"    ><?php echo $SEARCH_RESULTS_LOOP ?></textarea><br />
        <hr />     

        <!-- Search results footer -->
        <label class="settingName" for="search_results_footer"><?php echo $TEXT['RESULTS_FOOTER'] ?></label>
        <textarea id="search_results_footer" name="search_results_footer"    ><?php echo $SEARCH_RESULTS_FOOTER ?></textarea><br />
        <hr />     

        <!-- Search no results -->
        <label class="settingName" for="search_no_results"><?php echo $TEXT['NO_RESULTS'] ?></label>
        <textarea id="search_no_results" name="search_no_results"    ><?php echo $SEARCH_NO_RESULTS ?></textarea><br />
        <hr />     

        <!-- Search footer -->
        <label class="settingName" for="search_footer"><?php echo $TEXT['FOOTER'] ?></label>
        <textarea id="search_footer" name="search_footer" ><?php echo $SEARCH_FOOTER ?></textarea><br />
        <hr />     


        <!-- MODULE_ORDER -->
        <label class="settingName" for="search_module_order"><?php echo $TEXT['MODULE_ORDER'] ?></label>
        <input class="long" type="text" id="search_module_order" name="search_module_order" maxlength="30"  value="<?php echo $SEARCH_MODULE_ORDER ?>" /><br />
        <hr />       
 
         <!-- MAX_EXCERPT -->
        <label class="settingName" for="search_max_excerpt"><?php echo $TEXT['MAX_EXCERPT'] ?></label>
        <input class="long" type="text" id="search_max_excerpt" name="search_max_excerpt" maxlength="30"  value="<?php echo $SEARCH_MAX_EXCERPT ?>" /><br />
        <hr />       
 
         <!-- TIME_LIMIT for each module -->
        <label class="settingName" for="search_time_limit"><?php echo $TEXT['TIME_LIMIT'] ?></label>
        <input class="long" type="text" id="search_time_limit" name="search_time_limit" maxlength="30"  value="<?php echo $SEARCH_TIME_LIMIT ?>"  /><br />
        <hr />       
 
   
        
         <br /><br />
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        
    </form>
</div><!-- settingsGeneral -->





