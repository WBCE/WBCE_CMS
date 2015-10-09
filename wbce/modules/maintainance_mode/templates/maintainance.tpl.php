   <h2><?php echo $MOD_MAINTAINANCE['HEADER']; ?></h2>
   <form id="maintainance_mode_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<?php echo $admin->getFTAN(); ?>
   <table cellpadding="4" cellspacing="0" border="0">
   <tr>
	     <td colspan="2"><?php echo $MOD_MAINTAINANCE['DESCRIPTION']; ?>:</td>
   </tr>
   <tr>
	     <td width="20"><input type="checkbox" name="mmode" id="mmode" value="true" <?php echo $mmode ?>/></td>
	     <td><label for="mmode"><?php echo $MOD_MAINTAINANCE['CHECKBOX']; ?></label></td>
   </tr>
   <tr>
	     <td>&nbsp;</td>
	     <td>
		   <input type="submit" name="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
	    </td>
   </tr>
   </table>
   </form>
