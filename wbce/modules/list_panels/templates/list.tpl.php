<?
/**
Tools list Tempalte
*/
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));
?>
<div id="admintools">
    <h2><?php echo $categoryName ?></h2>
    <ul>   
<?php foreach($myTools  as  $tool): ?>   
<?php  $toolLink=ADMIN_URL.$typeDir."/$toolFile?tool=".$tool['directory']; ?> 
        <li>
            <table summary="" cellpadding="0" cellspacing="0" border="0" class="section" width="100%">
                <tr>
                    <td class="graphic" align="center" valign="middle" rowspan="2">
                        <a class= "title" href="<?php echo $toolLink;?>"><i class="<?php echo $tool['icon'];?>" ></i></a>
                    </td>
                    <td class="description" valign="top">
                        <a href="<?php echo $toolLink;?>"><span class="title"><?php echo $tool['name'];?></span></a><?php echo $tool['description'];?>
                    </td>
                </tr>
            </table>
        </li>
<?php endforeach; ?>     
    </ul>
</div><!-- admintools -->