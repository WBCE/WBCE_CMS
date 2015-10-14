<?
/**
 * @category        modules
 * @package         Secure Form Switcher
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */
/*
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

    <div class="sfs">
        <h2><?php echo $SFS['HEADER']; ?></h2>
        <form id="sfs_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
            <?php echo $admin->getFTAN(); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="2"><?php echo $SFS['DESCRIPTION']; ?>:</td>
                </tr>
                <tr>
                    <td width="200">
                        <input type="checkbox" name="useFP" id="useFP" value="true" <?php echo $useFP ?>/>
                    </td>
                    <td>
                        <label for="useFP"><?php echo $SFS['USEFP']; ?>
		                    <a class="tooltip" href="#">
                                <i class="fa fa-question-circle"></i> 
                                <span class="custom help">
                                    <?php echo $SFS['USEFP_TTIP']?>
                                </span>
                            </a>

                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="forSelect" >
                        <select name="ipOctets" >   
                            <option value="0"<?php if ($ipOctets=="0") echo $selected ?>>0</option>
                            <option value="1"<?php if ($ipOctets=="1") echo $selected ?>>1</option>
                            <option value="2"<?php if ($ipOctets=="2") echo $selected ?>>2</option>
                            <option value="3"<?php if ($ipOctets=="3") echo $selected ?>>3</option>
                            <option value="4"<?php if ($ipOctets=="4") echo $selected ?>>4</option>
                        </select></div>
                    </td>
                    <td>
                        <label for="ipOctets"><?php echo $SFS['USEIP']?>
		                    <a class="tooltip" href="#">
                                <i class="fa fa-question-circle"></i> 
                                <span class="custom help">
                                    <?php echo $SFS['USEIP_TTIP']?>
                                </span>
                            </a>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td >
                        <input type="text" name="tokenName" id="tokenName" value="<?php echo $tokenName ?>"/>
                    </td>
                    <td>
                        <label for="tokenName"><?php echo $SFS['TOKENNAME']; ?>
		                    <a class="tooltip" href="#">
                                <i class="fa fa-question-circle"></i> 
                                <span class="custom help">
                                    <?php echo $SFS['TOKENNAME_TTIP']?>
                                </span>
                            </a>

                        </label>
                    </td>
                </tr>
                <tr>
                    <td >
                        <input type="text" name="timeout" id="timeout" value="<?php echo $timeout ?>"/>
                    </td>
                    <td>
                        <label for="timeout"><?php echo $SFS['TIMEOUT']; ?>
		                    <a class="tooltip" href="#">
                                <i class="fa fa-question-circle"></i> 
                                <span class="custom help">
                                    <?php echo $SFS['TIMEOUT_TTIP']?>
                                </span>
                            </a>

                        </label>
                    </td>
                </tr>

                <tr>
                    <td >
                        <input type="text" name="secret" id="secret" value="<?php echo $secret ?>"/>
                    </td>
                    <td>
                        <label for="secret"><?php echo $SFS['SECRET']; ?>
		                    <a class="tooltip" href="#">
                                <i class="fa fa-question-circle"></i> 
                                <span class="custom help">
                                    <?php echo $SFS['SECRET_TTIP']?>
                                </span>
                            </a>

                        </label>
                    </td>
                </tr>
                <tr>
                    <td >
                        <input type="text" name="secretTime" id="secretTime" value="<?php echo $secretTime ?>"/>
                    </td>
                    <td>
                        <label for="secretTime"><?php echo $SFS['SECRETTIME']; ?> 
		                    <a class="tooltip" href="#">
                                <i class="fa fa-question-circle"></i> 
                                <span class="custom help">
                                    <?php echo $SFS['SECRETTIME_TTIP'];?>
                                </span>
                            </a>

                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="save_settings" id="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
                     </td>
                    <td class="tdright">
                        <input type="submit" onclick="return confirm('<?php echo $TEXT['ARE_YOU_SURE']?>'); " name="save_default" id="save_default" value="<?php echo $HEADING['DEFAULT_SETTINGS']; ?>" />
                        <input type="submit" name="admin_tools" id="admin_tools" value="<?php echo $HEADING['ADMINISTRATION_TOOLS']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td >
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td >
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>

            </table>
        </form>
    </div><!-- sfs -->
