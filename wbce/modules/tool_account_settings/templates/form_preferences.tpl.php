<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');

// do we have any Error Messages to display?
if(empty($error) == false){ ?>
<div class="alert alert-error">
    <?php foreach($error as $k=>$message){ ?>
            <p id="<?=$k?>"><?=$message?></p>
    <?php 
    } 
    ?>
</div>
<?php 
}

if (!empty($success)) {
    $wb->print_success(implode('<br />', $success), WB_URL.'/account/preferences.php'); //."?lang=".$_SESSION['LANGUAGE']);
}
?>
<h1><?=$TOOL_TXT['PREFERENCES'] ?></h1>
<?php 
/** 
 * USER BASE Connector 
 * this form is generated automatically  according  to  the
 * configuration of the UserBase Admin-Tool (if installed).
 */
echo $sUserBaseForm; 
?>
<div class="cpForm">
    <form name="details" action="" method="post">
        <?=$wb->getFTAN(); /* Important: keep this in template! */?>
        <h3><?=$HEADING['MY_SETTINGS'] ?></h3>

        <div class="formRow">
            <label class="settingName" for="display_name"><?=$TEXT['DISPLAY_NAME']; ?></label>
            <div class="settingValue">
                <input type="text" id="display_name" name="display_name" value="<?=$sDisplayName ?>" />
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="email"><?=$TEXT['EMAIL']; ?></label>
            <div class="settingValue">
                <input type="text" id="email"  name="email" value="<?=$sEmail ?>" />
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="language"><?=$TEXT['LANGUAGE']; ?></label>
            <div class="settingValue">
                <select name="language" id="language">
                    <option value="" disabled><?=$TOOL_TXT['PLEASE_SELECT'] ?>...</option>
                    <?php foreach($aLanguages as $lang) { ?>
                            <option value="<?=$lang['CODE'] ?>"<?=($lang['SELECTED'] == true) ? ' selected' : ''?> style="background: url(<?=$lang['FLAG'] ?>.png) no-repeat center left; padding-left: 20px;"><?=$lang['NAME'] ?> (<?=$lang['CODE'] ?>)</option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="timezone"><?=$TEXT['TIMEZONE']; ?></label>
            <div class="settingValue">
                <select name="timezone" id="timezone">
                    <option value="-20" disabled><?=$TOOL_TXT['PLEASE_SELECT'] ?>...</option>
                    <?php foreach($aTimeZones as $rec) { ?>
                            <option value="<?=$rec['VALUE'] ?>"<?=($rec['SELECTED'] == true) ? ' selected' : ''?>><?=$rec['NAME'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="date_format"><?=$TEXT['DATE_FORMAT']; ?></label>
            <div class="settingValue">
                <select id="date_format" name="date_format">
                    <option value="" disabled><?=$TOOL_TXT['PLEASE_SELECT'] ?>...</option>
                    <?php foreach($aDateFormats as $rec) { ?>
                            <option value="<?=$rec['VALUE'] ?>"<?=($rec['SELECTED'] == true) ? ' selected' : ''?>><?=$rec['NAME'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="time_format"><?=$TEXT['TIME_FORMAT']; ?></label>
            <div class="settingValue">
                <select name="time_format" id="time_format">
                    <option value="" disabled><?=$TOOL_TXT['PLEASE_SELECT'] ?>...</option>
                    <?php foreach($aTimeFormats as $rec) { ?>
                        <option value="<?=$rec['VALUE'] ?>"<?=($rec['SELECTED'] == true) ? ' selected' : ''?>><?=$rec['NAME'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <h3><?=$TEXT['NEW_PASSWORD'] ?></h3>
        <div class="formRow">
            <label class="settingName" for="new_password"><?=$TEXT['NEW_PASSWORD']; ?></label>
            <div class="settingValue">
                <?=$wb->passwordField('new_password');?>
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="new_password2"><?=$TEXT['RETYPE_NEW_PASSWORD']; ?></label>
                <div class="settingValue">
                    <input type="password" name="new_password2"  id="new_password2" />
                    <div class="formHint"><?=$MESSAGE['USERS_CHANGING_PASSWORD']?>.</div>
                </div>
        </div>
        <br />
        <div class="formRow">
            <label class="settingName" for="current_password"><?=$TEXT['CURRENT_PASSWORD']; ?></label>
            <div class="settingValue">
                <input type="password" name="current_password" id="current_password" />
                <div class="formHint"><?=$TEXT['NEED_CURRENT_PASSWORD']?>.</div>
            </div>
        </div>

        <div class="buttonsRow">
            <button type="button" value="cancel" onClick="javascript: window.location = '<?=$sHttpReferer ?>';"><?=$TEXT['CANCEL'] ?></button>
            <button type="reset" name="reset" value="reset" class="button" ><?=$TEXT['RESET'] ?></button>
            <button type="submit" id="submit" name="action" value="profile" class="button pos-right"><?=$TOOL_TXT['SAVE_PREFERENCES'] ?></button>
        </div>
    </form>
</div>