<?
/**
Tools list Tempalte
*/
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


?>

<div id="page_admintools">
    <h2 class="page_titel"><?php echo $categoryName ?></h2>
    <div id="admintools">
        <div class="dynamicGrid-outer">
<?php foreach($myTools  as  $tool): ?>    
<?php  $toolLink=ADMIN_URL.$typeDir."/$toolFile?tool=".$tool['directory']; ?> 
            <div class="admintools_tool themebox dynamicGrid_2">
                <div class="tb_header" >            
                    <i class="<?php echo $tool['icon'];?>"></i><a href="<?php echo $toolLink;?>"></a>
                </div>          
                <div class="tb_content_outer">
                    <div class="tb_content" style="height:185px; overflow:hidden; padding-right:45px; margin-bottom:5px;">          
                        <a class="tb_titel"  href="<?php echo $toolLink;?>"><?php echo $tool['name'];?></a>
                        <?php echo $tool['description'];?>              
                    </div><!-- tb_content -->
                </div><!-- tb_content_outer -->         
                <div class="tb_footer">
                    <a href="$toolLink"></a>
                </div><!-- tb_footer -->
            </div><!-- themebox -->
<?php endforeach; ?>                
        </div><!-- dynamicGrid -->  
    </div><!-- admintools -->
</div><!-- page_admintools -->
