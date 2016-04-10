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
    width:550px;
}
.settings textarea {
    height: 80px;
}

.settings .permissionTable {
    padding-left:250px;
} 

</style>
<script  language="javascript" type="text/javascript">
    function change_wbserver(type) {        
        if(type == 'linux') {
            document.getElementById('access_settings').style.display = 'block';
        } else if(type == 'windows') {
            document.getElementById('access_settings').style.display = 'none';  
        }
    }
</script>



<div class="settings">
    <h2><?php echo $HEADING['SERVER_SETTINGS']; ?></h2>
    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
        <?php echo $admin->getFTAN(); ?>
        <p><?php echo $module_description; ?></p>
        
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        <br />  <br />  
        <hr />    
 
     
        <!-- Filesystem permissions-->
        <div class="settingName" ><?php echo $TEXT['SERVER_OPERATING_SYSTEM'] ?></div>
        <input onclick="javascript: change_wbserver('linux');" type="radio" name="operating_system" id="operating_system_linux" style="width: 14px; height: 14px;" value="linux" <?php if (OPERATING_SYSTEM=="linux") echo WB_CHECK; ?> />
        <label for="operating_system_linux" onclick="javascript: change_wbserver('linux');"><?php echo $TEXT['LINUX_UNIX_BASED'] ?></label>
        <input onclick="javascript: change_wbserver('windows');" type="radio" name="operating_system" id="operating_system_windows" style="width: 14px; height: 14px;" value="windows" <?php if (OPERATING_SYSTEM=="windows") echo WB_CHECK; ?> />
        <label for="operating_system_windows" onclick="javascript: change_wbserver('windows');" ><?php echo $TEXT['WINDOWS'] ?></label>
        <hr />  
        
        <div id="access_settings" <?php if (OPERATING_SYSTEM=="linux") echo 'style="display:block"'; else echo 'style="display:none"'; ?>> 
            <div class="settingName" ><?php echo $TEXT['FILESYSTEM_PERMISSIONS'] ?></div></br>
            <div class="permissionTable">
                <table>
                    <tr>
                        <th colspan="6"><h4><?php echo $TEXT['FILES']."&nbsp;".STRING_FILE_MODE ?></h4></th>
                    </tr>
                    <tr>
                       <th colspan="2"><?php echo $TEXT['USER'] ?></th>
                       <th colspan="2"><?php echo $TEXT['GROUP'] ?></th>
                       <th colspan="2"><?php echo $TEXT['OTHERS'] ?></th>                      
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="file_u_r" id="file_u_r" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'u', 'r')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['READ'] ?></td>
                        <td><input type="checkbox" name="file_g_r" id="file_g_r" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'g', 'r')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['READ'] ?></td>
                        <td><input type="checkbox" name="file_o_r" id="file_o_r" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'o', 'r')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['READ'] ?></td>
                        
                    </tr>    
                    <tr>    
                        <td><input type="checkbox" name="file_u_w" id="file_u_w" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'u', 'w')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['WRITE'] ?></td>
                        <td><input type="checkbox" name="file_g_w" id="file_g_w" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'g', 'w')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['WRITE'] ?></td>
                        <td><input type="checkbox" name="file_o_w" id="file_o_w" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'o', 'w')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['WRITE'] ?></td>
                       
                    </tr>     
                    <tr>    
                        <td><input type="checkbox" name="file_u_e" id="file_u_e" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'u', 'e')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['EXECUTE'] ?></td>
                        <td><input type="checkbox" name="file_g_e" id="file_g_e" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'g', 'e')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['EXECUTE'] ?></td>
                        <td><input type="checkbox" name="file_o_e" id="file_o_e" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'o', 'e')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['EXECUTE'] ?></td>                        
                    </tr>
                </table>
            </div>
            <br />
            <div class="permissionTable">
                <table>
                    <tr>
                        <th colspan="6"><h4><?php echo $TEXT['DIRECTORIES']."&nbsp;".STRING_DIR_MODE ?></h4></th>
                    </tr>
                    <tr>
                       <th colspan="2"><?php echo $TEXT['USER'] ?></th>
                       <th colspan="2"><?php echo $TEXT['GROUP'] ?></th>
                       <th colspan="2"><?php echo $TEXT['OTHERS'] ?></th>                      
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="dir_u_r" id="dir_u_r" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'u', 'r')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['READ'] ?></td>
                        <td><input type="checkbox" name="dir_g_r" id="dir_g_r" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'g', 'r')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['READ'] ?></td>
                        <td><input type="checkbox" name="dir_o_r" id="dir_o_r" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'o', 'r')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['READ'] ?></td>
                        
                    </tr>    
                    <tr>    
                        <td><input type="checkbox" name="dir_u_w" id="dir_u_w" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'u', 'w')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['WRITE'] ?></td>
                        <td><input type="checkbox" name="dir_g_w" id="dir_g_w" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'g', 'w')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['WRITE'] ?></td>
                        <td><input type="checkbox" name="dir_o_w" id="dir_o_w" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'o', 'w')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['WRITE'] ?></td>
                       
                    </tr>     
                    <tr>    
                        <td><input type="checkbox" name="dir_u_e" id="dir_u_e" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'u', 'e')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['EXECUTE'] ?></td>
                        <td><input type="checkbox" name="dir_g_e" id="dir_g_e" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'g', 'e')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['EXECUTE'] ?></td>
                        <td><input type="checkbox" name="dir_o_e" id="dir_o_e" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'o', 'e')) echo WB_CHECK?> /></td>
                        <td><?php echo $TEXT['EXECUTE'] ?></td>
                        
                    </tr>
                </table>
            </div><br />           
        </div><!-- access_settings -->

        <!-- PAGES_DIRECTORY -->
        <label class="settingName" for="pages_directory"><?php echo $TEXT['PAGES_DIRECTORY'] ?></label>
        <input class="long" type="text" id="pages_directory" name="pages_directory" maxlength="255"  value="<?php echo PAGES_DIRECTORY ?>" /><br />
        <hr />   
        
        <!-- MEDIA_DIRECTORY -->
        <label class="settingName" for="media_directory"><?php echo $TEXT['MEDIA_DIRECTORY'] ?></label>
        <input class="long" type="text" id="media_directory" name="media_directory" maxlength="255"  value="<?php echo MEDIA_DIRECTORY ?>" /><br />
        <hr />   

        <!-- PAGE_EXTENSION -->
        <label class="settingName" for="page_extension"><?php echo $TEXT['PAGE_EXTENSION'] ?></label>
        <input class="long" type="text" id="page_extension" name="page_extension" maxlength="255"  value="<?php echo PAGE_EXTENSION ?>" /><br />
        <hr />   

        <!-- PAGE_SPACER-->
        <label class="settingName" for="page_spacer"><?php echo $TEXT['PAGE_SPACER'] ?></label>
        <input class="long" type="text" id="page_spacer" name="page_spacer" maxlength="255"  value="<?php echo PAGE_SPACER ?>" /><br />
        <hr />   
        
        <!-- RENAME_FILES_ON_UPLOAD -->
        <label class="settingName" for="rename_files_on_upload"><?php echo $TEXT['RENAME_FILES_ON_UPLOAD'] ?></label>
        <input class="long" type="text" id="rename_files_on_upload" name="rename_files_on_upload" maxlength="255"  value="<?php echo RENAME_FILES_ON_UPLOAD ?>" /><br />
        <hr />   

        <!-- ALLOWED_FILETYPES_ON_UPLOAD -->
        <!--<label class="settingName" for="allowed_filetypes_on_upload"><?php echo $TEXT['ALLOWED_FILETYPES_ON_UPLOAD']."fdfdfd" ?></label>
        <input class="long" type="text" id="allowed_filetypes_on_upload" name="allowed_filetypes_on_upload" maxlength="255"  value="<?php echo ALLOWED_FILETYPES_ON_UPLOAD ?>" /><br />
        <hr />   -->
        
        <!-- SESSION_IDENTIFIER -->
        <label class="settingName" for="app_name"><?php echo $TEXT['SESSION_IDENTIFIER'] ?></label>
        <input class="long" type="text" id="app_name" name="app_name" maxlength="255"  value="<?php echo APP_NAME ?>" /><br />
        <hr />   
        
        <!-- SEC_ANCHOR -->
        <label class="settingName" for="sec_anchor"><?php echo $TEXT['SEC_ANCHOR'] ?></label>
        <input class="long" type="text" id="sec_anchor" name="sec_anchor" maxlength="255"  value="<?php echo SEC_ANCHOR ?>" /><br />
        <hr />   
        
        
            
         <br /><br />
        <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" class="save_default" value="<?php echo $TEXT['SYSTEM_DEFAULT']; ?>" />
        <input type="submit" name="admin_tools" class="admin_tools" value="<?php echo $MENU['SETTINGS']; ?>" />
        
    </form>
</div><!-- settingsGeneral -->



