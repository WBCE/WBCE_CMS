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
    <form id="settings_general_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
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
        <br />
        
        <br />
        
        <hr />
        
        <label class="settingName" for="page_level_limit"><?php echo $TEXT['PAGE_LEVEL_LIMIT'] ?></label>
        <select name="page_level_limit" id="page_level_limit">
        <?php for($i = 1; $i <= 10; $i++): ?>
            <option value="<?php echo $i; ?>" <?php if (PAGE_LEVEL_LIMIT==$i) echo 'selected="selected"';?> ><?php echo $i; ?></option>
        <?php endfor; ?>
        </select>
        <hr />
        
        <!-- PAGE TRASH -->
        <div class="settingName" ><?php echo $TEXT['PAGE_TRASH'] ?></div>
        <input type="radio" name="page_trash" id="page_trash_inline" style="width: 14px; height: 14px;" value="inline" <?php if (PAGE_TRASH=="inline") echo 'checked="checked"'; ?> />
        <label for="page_trash_inline"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="page_trash" id="page_trash_disabled" style="width: 14px; height: 14px;" value="disabled" <?php if (PAGE_TRASH=="disabled") echo 'checked="checked"'; ?> />
        <label for="page_trash_disabled"><?php echo $TEXT['DISABLED'] ?></label>
        <hr />  
        
        <!-- PAGE LANGUAGES -->
        <div class="settingName" ><?php echo $TEXT['PAGE_LANGUAGES'] ?></div>  
        <input type="radio" name="page_languages" id="page_languages_true" style="width: 14px; height: 14px;" value="true" <?php if (PAGE_LANGUAGES)  echo 'checked="checked"';?> />
        <label for="page_languages_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="page_languages" id="page_languages_false" style="width: 14px; height: 14px;" value="false" <?php if (!PAGE_LANGUAGES)  echo 'checked="checked"';?> />
        <label for="page_languages_false"><?php echo $TEXT['DISABLED'] ?></label>   
        <hr />
           
        <!-- MULTIPLE MENUS -->
        <div class="settingName" ><?php echo $TEXT['MULTIPLE_MENUS'] ?></div> 
        <input type="radio" name="multiple_menus" id="multiple_menus_true" style="width: 14px; height: 14px;" value="true" <?php if (MULTIPLE_MENUS)  echo 'checked="checked"';?> />
        <label for="multiple_menus_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="multiple_menus" id="multiple_menus_false" style="width: 14px; height: 14px;" value="false" <?php if (!MULTIPLE_MENUS)  echo 'checked="checked"';?> />
        <label for="multiple_menus_false"><?php echo $TEXT['DISABLED'] ?></label>
        <hr />
        
        <!--HOME_FOLDERS -->
        <div class="settingName" ><?php echo $TEXT['HOME_FOLDERS'] ?></div>
        <input type="radio" name="home_folders" id="home_folders_true" style="width: 14px; height: 14px;" value="true" <?php if (HOME_FOLDERS)  echo 'checked="checked"';?> />
        <label for="home_folders_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="home_folders" id="home_folders_false" style="width: 14px; height: 14px;" value="false" <?php if (!HOME_FOLDERS)  echo 'checked="checked"';?> />
        <label for="home_folders_false"><?php echo $TEXT['DISABLED'] ?></label>         
        <hr />
        
        <!-- MANAGE_SECTIONS --> 
        <div class="settingName" ><?php echo $HEADING['MANAGE_SECTIONS'] ?></div> 
        <input type="radio" name="manage_sections" id="manage_sections_true" style="width: 14px; height: 14px;" value="true" <?php if (MANAGE_SECTIONS)  echo 'checked="checked"';?> />
        <label for="manage_sections_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="manage_sections" id="manage_sections_false" style="width: 14px; height: 14px;" value="false" <?php if (!MANAGE_SECTIONS)  echo 'checked="checked"';?> />
        <label for="manage_sections_false"><?php echo $TEXT['DISABLED'] ?></label>
        <hr />
        
        <!-- SECTION_BLOCKS -->
        <div class="settingName" ><?php echo $TEXT['SECTION_BLOCKS'] ?></div>   
        <input type="radio" name="section_blocks" id="section_blocks_true" style="width: 14px; height: 14px;" value="true" <?php if (SECTION_BLOCKS)  echo 'checked="checked"';?> />
        <label for="section_blocks_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="section_blocks" id="section_blocks_false" style="width: 14px; height: 14px;" value="false" <?php if (!SECTION_BLOCKS)  echo 'checked="checked"';?> />
        <label for="section_blocks_false"><?php echo $TEXT['DISABLED'] ?></label>
        <hr />
        
        <!-- INTRO_PAGE -->
        <div class="settingName" ><?php echo $TEXT['INTRO_PAGE'] ?></div>   
        <input type="radio" name="intro_page" id="intro_page_true" style="width: 14px; height: 14px;" value="true" <?php if (INTRO_PAGE)  echo 'checked="checked"';?> />
        <label for="intro_page_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="intro_page" id="intro_page_false" style="width: 14px; height: 14px;" value="false" <?php if (!INTRO_PAGE)  echo 'checked="checked"';?> />
        <label for="intro_page_false"><?php echo $TEXT['DISABLED'] ?></label>        
        <hr />
        
        <!-- HOMEPAGE_REDIRECTION -->
        <div class="settingName" ><?php echo $TEXT['HOMEPAGE_REDIRECTION'] ?></div>   
        <input type="radio" name="homepage_redirection" id="homepage_redirection_true" style="width: 14px; height: 14px;" value="true" <?php if (HOMEPAGE_REDIRECTION)  echo 'checked="checked"';?> />
        <label for="homepage_redirection_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="homepage_redirection" id="homepage_redirection_false" style="width: 14px; height: 14px;" value="false" <?php if (!HOMEPAGE_REDIRECTION)  echo 'checked="checked"';?> />
        <label for="homepage_redirection_false"><?php echo $TEXT['DISABLED'] ?></label>        
        <hr />
        
        <!-- SMART_LOGIN -->
        <div class="settingName" ><?php echo $TEXT['SMART_LOGIN'] ?></div>   
        <input type="radio" name="smart_login" id="smart_login_true" style="width: 14px; height: 14px;" value="true" <?php if (SMART_LOGIN)  echo 'checked="checked"';?> />
        <label for="smart_login_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="smart_login" id="smart_login_false" style="width: 14px; height: 14px;" value="false" <?php if (!SMART_LOGIN)  echo 'checked="checked"';?> />
        <label for="smart_login_false"><?php echo $TEXT['DISABLED'] ?></label>
        <hr />      
        
         <!-- LOGIN -->
        <div class="settingName" ><?php echo $TEXT['LOGIN'] ?></div>   
        <input type="radio" name="frontend_login" id="frontend_login_true" style="width: 14px; height: 14px;" value="true" <?php if (FRONTEND_LOGIN)  echo 'checked="checked"';?> />
        <label for="frontend_login_true"><?php echo $TEXT['ENABLED'] ?></label>
        <input type="radio" name="frontend_login" id="frontend_login_false" style="width: 14px; height: 14px;" value="false" <?php if (!FRONTEND_LOGIN)  echo 'checked="checked"';?> />
        <label for="frontend_login_false"><?php echo $TEXT['DISABLED'] ?></label>
        <hr />
 
        <!-- REDIRECT_TIMER -->
        <label for="redirect_timer" class="settingName" ><?php echo $TEXT['REDIRECT_AFTER'] ?></label>
        <input type="text" id="redirect_timer" name="redirect_timer" value="<?php echo REDIRECT_TIMER ?>"  />
        <label>&nbsp;&nbsp;( <b>-1</b> = <?php echo $TEXT['DISABLED'] ?>, <b>0 -10000</b> )</label>
        <hr />
        
        <!-- FRONTEND SIGNUP -->
        <?php $groups=gs_GetGroupArray(); ?>
        <label class="settingName" for="frontend_signup"><?php echo $TEXT['SIGNUP'] ?></label>
        <select name="frontend_signup" id="frontend_signup" <?php if (!$groups) echo 'disabled="disabled"'; ?> >
        <?php if (is_array($groups)) :?>
            <option value="false"><?php echo $TEXT['DISABLED'] ?></option>
            <?php foreach (gs_GetGroupArray() as $group): ?>
            <option value="<?php echo $group['group_id'] ?>" <?php if(FRONTEND_SIGNUP == $group['group_id']) echo 'selected="selected"'; ?> ><?php echo $group['name'] ?></option>
            <?php endforeach; ?>
        <?php endif; ?>    
        </select>
        <hr />
        
        <!-- ERROR_LEVEL -->
        <label class="settingName" for="er_level"><?php echo $TEXT['PHP_ERROR_LEVEL'] ?></label>
        <select name="er_level" id="er_level" >
            <?php require(ADMIN_PATH.'/interface/er_levels.php'); ?>
            <?php foreach ($ER_LEVELS AS $value => $title): ?>
            <option value="<?php echo $value ?>" <?php if(ER_LEVEL == $value) echo 'selected="selected"'; ?> ><?php echo $title ?></option>
            <?php endforeach; ?>
        </select>
        <hr />        
        
        <!-- WYSIWYG_STYLE -->
        <!--
        <label class="settingName" for="wysiwyg_style"><?php echo $TEXT['WYSIWYG_STYLE'] ?></label>
        <input type="text" id="wysiwyg_style" name="wysiwyg_style" maxlength="255"  value="<?php echo WYSIWYG_STYLE ?>" style="width:350px" /><br />
        <hr />       
        -->
        
        <!-- WYSIWYG_EDITOR -->
        <?php $groups=gs_GetEditorArray(); ?>
        <label class="settingName" for="wysiwyg_editor"><?php echo $TEXT['WYSIWYG_EDITOR'] ?></label>
        <select name="wysiwyg_editor" id="wysiwyg_editor">
            <option value="none" <?php if(WYSIWYG_EDITOR == "none") echo 'selected="selected"'; ?> ><?php echo $TEXT['NONE']; ?></option>
        <?php if (is_array($groups)) :?>
            <?php foreach (gs_GetEditorArray() as $addon): ?>
            <option value="<?php echo $addon['directory'] ?>" <?php if(WYSIWYG_EDITOR == $addon['directory']) echo 'selected="selected"'; ?> ><?php echo $addon['name'] ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select>
        <hr />
        
         <!-- Default FE template -->
        <?php $selects=ds_GetTemplatesArray(); ?>
        <label class="settingName" for="default_template"><?php echo $TEXT['TEMPLATE'] ?></label>
        <select name="default_template" id="default_template">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $value): ?>
            <option value="<?php echo $value['directory'] ?>" <?php if(DEFAULT_TEMPLATE == $value['directory']) echo 'selected="selected"'; ?> ><?php echo $value['name']." (".$value['directory'].")" ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select>
        <hr />      

        <!-- Default BE theme -->
        <?php $selects=ds_GetThemesArray(); ?>
        <label class="settingName" for="default_theme"><?php echo $TEXT['THEME'] ?></label>
        <select name="default_theme" id="default_theme">
         <?php if (is_array($selects)) :?>
            <?php foreach ($selects as $value): ?>
            <option value="<?php echo $value['directory'] ?>" <?php if(DEFAULT_THEME == $value['directory']) echo 'selected="selected"'; ?> ><?php echo $value['name']." (".$value['directory'].")" ?></option>
            <?php endforeach; ?>
        <?php endif; ?> 
        </select>
        <hr />      
 
        <br />
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        
    </form>
</div><!-- settingsGeneral -->
