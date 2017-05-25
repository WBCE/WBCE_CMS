<?php header("Location: ../index.php",true,301);
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */
?>

<!-- BEGIN main_block -->

<br />
</div>
<div class="{DISPLAY_ADD}">
 <h3>{HEADING_ADD_PAGE}</h3>
 <form name="add" action="tool_doclone.php" method="post">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" align="center">
   <tr>
    <td width="140" height="20"><label for="title">{TEXT_TITLE}:</label></td>
    <td with="240" height="20"><input type="text" name="title" id="title" value="{TEXT_DEFAULT}" style="width: 232px;" /></td>
   </tr>
   <tr height="20">
    <td width="140"><label for="include_title" title="{TEXT_INCLUDE_PAGETITLE_HELP}">{TEXT_INCLUDE_PAGETITLE}:</label></td>
    <td with="240"><input type="checkbox" name="include_title" id="include_title" value="include_title" /></td>
   </tr>
   <tr height="20">
    <td width="140"><label for="parent">{TEXT_PARENT}:</label></td>
    <td with="240"><select name="parent" id="parent" style="width: 240px;">
      <!-- BEGIN page_list_block2 -->
      <option value="{ID}"{DISABLED}>{TITLE}</option>
      <!-- END page_list_block2 -->
     </select></td>
   </tr>
   <tr height="20">
    <td width="140"><label for="include_subs">{TEXT_INCLUDE_SUBS}:</label></td>
    <td with="240"><input type="checkbox" name="include_subs" id="include_subs" value="include_subs" /></td>
   </tr>
   <tr height="20">
    <td width="140"><label for="parent">{TEXT_VISIBILITY}:</label></td>
    <td with="240"><select name="visibility" id="visibility" style="width: 240px;">
      <option value="public">{TEXT_PUBLIC}</option>
      <option value="hidden" selected="selected">{TEXT_HIDDEN}</option>
      <option value="none">{TEXT_NONE}</option>
     </select></td>
   </tr>
   <tr>
    <td width="70">&nbsp;</td>
    <td colspan="4"><input type="hidden" name="pagetoclone" value="{TEXT_PAGETODO}" />
     <input type="submit" name="submit" value="{TEXT_ADD}" style="width: 117px;" />
     <input type="reset" name="reset" value="{TEXT_RESET}" style="width: 117px;" /></td>
   </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   </tr>
  </table>
 </form>
</div>
<!-- END main_block -->