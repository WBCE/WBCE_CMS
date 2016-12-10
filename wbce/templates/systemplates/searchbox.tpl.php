
<!-- Searchbox Template -->
<?php if (!empty($sBoxClass)) :?> 
    <div class="<?php echo $sBoxClass ?>" > 
<?php endif; ?>

    <?php if (!empty($sHeadline)) :?>    
        <div class="search_hl" >
            <?php echo $sHeadline ?>
        </div><!-- end search_hl -->
    <?php endif; ?>

        <form name="search" action="<?php echo $sFormAction ?>" method="<?php echo $sFormMethod ?>">
            <input type="hidden" name="referrer" value="<?php echo $sReferer ?>" />
            
            <div class="search_input" > 
                <input class="" type="text" name="string" value="<?php echo $sInputValue ?>" />
            </div>
            
        <?php if (!empty($sImageUrl)) :?>      
            <div class="search_image" > 
                <button type="submit" class="">
                    <img  src="<?php echo $sImageUrl ?>" alt="Save icon"/>
                </button>
            </div>
        <?php elseif (!empty($sIcon)) :?>  
        
            <div class="search_icon" > 
                <button type="submit" class="search_btn">
                            <?php echo $sSendText ?>
                </button>
            </div>
        <?php else :?> 
            <div class="search_submit" > 
                <input type="submit" name="submit" value="<?php echo $sSendText ?>"  />
            </div>
        <?php endif; ?>

        </form> 
<?php if (!empty($sBoxClass)) :?>     
    </div><!-- end <?php echo $sBoxClass ?> -->
<?php endif; ?>
<!-- Searchbox Template END -->
