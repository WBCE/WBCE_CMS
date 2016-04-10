<style>
.preferences hr {
    margin:1px;
    padding:1px;
}
.preferences p {
    margin:1px;
    padding-top:5px;
    padding-bottom:10px
}


</style>

<div class="preferences">

<?php if ( $msgTxt != '') :?>
    <div class="msg-box"><?php echo $msgTxt?></div>
<?php endif; ?>

<?php if ( $errorTxt != '') :?>
    <div class="warning"><?php echo $errorTxt?></div>
<?php endif; ?>

<h2><?php echo $HEADING['MY_SETTINGS']?></h2>
    <p><?php echo $TOOL['DESCRIPTION']; ?></p>
    <div class="content_box">
        <form name="preferences_save" id="preferences_save" action="<?php echo $returnUrl; ?>" method="post">
            <?php echo $admin->getFTAN(); ?>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['USERNAME']?>:</label><br />
                </div>
                <div class="c60l">
                    <div id="username"><?php echo $userName?></div><br />
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['DISPLAY_NAME']?>:</label><br />
                </div>
                <div class="c60l">
                    <input type="text" id="display_name" name="display_name" value="<?php echo $userDisplayName?>" /><br />
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['LANGUAGE']?>:</label><br />
                </div>
                <div class="c60l">
                <select name="language" id="language">
                    <?php foreach ($languageData as $l) :?>
                        <option value="<?php echo $l['code']?>"<?php echo $l['selected']?>><?php echo $l['name']?> (<?php echo $l['code']?>)</option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['TIMEZONE']?>:</label><br />
                </div>
                <div class="c60l">
                    <select name="timezone" id="timezone">
                        <?php foreach ($timezoneData as $t) :?>
                            <option value="<?php echo $t['value']?>"<?php echo $t['selected']?>><?php echo $t['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['DATE_FORMAT']?>:</label><br />
                </div>
                <div class="c60l">
                    <select name="date_format" id="date_format">
                        <?php foreach ($dateData as $d) :?>
                            <option value="<?php echo $d['value']?>"<?php echo $d['selected']?>><?php echo $d['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['TIME_FORMAT']?>:</label><br />
                </div>
                <div class="c60l">
                    <select name="time_format" id="time_format">
                        <?php foreach ($timeformData as $f) :?>
                            <option value="<?php echo $f['value']?>"<?php echo $f['selected']?>><?php echo $f['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $HEADING['MY_EMAIL']?>:</label><br />
                </div>
                <div class="c60l">
                    <input type="text" id="email" name="email" value="<?php echo $userMail?>" /><br />
                </div>
            </div>
            <hr />
            <p><?php echo $TOOL['PASSWORD']?></p>
            <hr />
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['NEW_PASSWORD']?>:</label><br />
                </div>
                <div class="c60l">
                    <input type="password" id="new_password_1" name="new_password_1" value="" /><br />
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['RETYPE_NEW_PASSWORD']?>:</label><br />
                </div>
                <div class="c60l">
                    <input type="password" id="new_password_2" name="new_password_2" value="" /><br />
                </div>
            </div>
            <div class="subcolumns">
                <div class="c25l">
                    <label><?php echo $TEXT['NEED_CURRENT_PASSWORD']?>:</label><br />
                </div>
                <div class="c60l">
                    <input type="password" id="current_password" name="current_password" value="" /><br />
                </div>
            </div>
            <div class="subcolumns save_section">
                <div class="c25l">
                    <input type="submit" name="save_settings" class="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
                </div>
                <div class="c60l"  >
                    <input  type="reset" id="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
                </div>
                <div class="c25l">&nbsp;</div>
            </div>
            
            
                

        </form>
    </div><!-- content_box -->

</div><!-- preferences -->




