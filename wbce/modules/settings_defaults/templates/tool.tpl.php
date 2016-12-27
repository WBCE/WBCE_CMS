<?/*
For automated detection if form has benn sent the submit button needs to have 
name+id ="save_settings". (Optional ($_POST['action']) == 'save')
For return to admintools the responsible button must have name+id="admin_tools".
And to activate default setting it heed to have name+id="save_default".

$returnUrl      Is used as form Action it sends the form to itself(apeform)

Language vars whit preceding MOD_ can be found in the launguage file of this module
Other language vars are from the default WB language files.(e.g. $TEXT or $HEADING )

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
        width:450px;
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
        
        
        <!-- Language -->
        <?php $selects=ds_GetLanguagesArray(); ?>
        <label class="settingName" for="default_language"><?php echo $TEXT['LANGUAGE'] ?></label>
        <select name="default_language" id="default_language">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $value): ?>
            <option value="<?php echo $value['directory'] ?>" <?php if(DEFAULT_LANGUAGE == $value['directory']) echo 'selected="selected"'; ?> ><?php echo $value['name']." (".$value['directory'].")" ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br /> 

        <!-- Timezones -->
        <?php $selects=ds_GetTimezonesArray(); ?>
        <label class="settingName" for="default_timezone"><?php echo $TEXT['TIMEZONE'] ?></label>
        <select name="default_timezone" id="default_timezone">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $key=>$value): ?>
            <option value="<?php echo $key ?>" <?php if(DEFAULT_TIMEZONE == $key*60*60) echo 'selected="selected"'; ?> ><?php echo $value ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br /> 

        <!-- Dateformats -->
        <?php $selects=ds_GetDateFormatArray(); ?>
        <label class="settingName" for="default_date_format"><?php echo $TEXT['DATE_FORMAT'] ?></label>
        <select name="default_date_format" id="default_date_format">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $key=>$value): 
            $key = str_replace('|', ' ', $key);?>
            <option value="<?php echo $key ?>" <?php if(DEFAULT_DATE_FORMAT == $key) echo 'selected="selected"'; ?> ><?php echo $value ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br /> 

        <!-- timeformats -->
        <?php $selects=ds_GetTimeFormatArray(); ?>
        <label class="settingName" for="default_time_format"><?php echo $TEXT['TIME_FORMAT'] ?></label>
        <select name="default_time_format" id="default_time_format">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $key=>$value): 
            $key = str_replace('|', ' ', $key);?>
            <option value="<?php echo $key ?>" <?php if(DEFAULT_TIME_FORMAT == $key) echo 'selected="selected"'; ?> ><?php echo $value ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br /> 

         <!-- Default FE template -->
        <?php $selects=ds_GetTemplatesArray(); ?>
        <label class="settingName" for="default_template"><?php echo $TEXT['TEMPLATE'] ?></label>
        <select name="default_template" id="default_template">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $value): ?>
            <option value="<?php echo $value['directory'] ?>" <?php if(DEFAULT_TEMPLATE == $value['directory']) echo 'selected="selected"'; ?> ><?php echo $value['name']." (".$value['directory'].")" ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br />      

        <!-- Default BE theme -->
        <?php $selects=ds_GetThemesArray(); ?>
        <label class="settingName" for="default_theme"><?php echo $TEXT['THEME'] ?></label>
        <select name="default_theme" id="default_theme">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $value): ?>
            <option value="<?php echo $value['directory'] ?>" <?php if(DEFAULT_THEME == $value['directory']) echo 'selected="selected"'; ?> ><?php echo $value['name']." (".$value['directory'].")" ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select><br />      
        
        
   
        
         <hr /><br />
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        
    </form>
</div><!-- settingsGeneral -->
