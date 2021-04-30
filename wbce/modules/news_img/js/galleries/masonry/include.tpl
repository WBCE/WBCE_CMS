<script src="<?php echo WB_URL ?>/modules/news_img/js/galleries/masonry/masonry.min.js"></script>
<script src="<?php echo WB_URL ?>/modules/news_img/js/galleries/masonry/imagesLoaded.min.js"></script>
<link rel="stylesheet" href="<?php echo WB_URL ?>/modules/news_img/js/galleries/masonry/masonry.css" />


<script>    
    var $grid = $('.masonry-grid').imagesLoaded( function() {
      $grid.masonry({
        itemSelector: '.masonry-grid-item',
        percentPosition: true,
        columnWidth: '.masonry-grid-sizer'
      });
    });
</script>