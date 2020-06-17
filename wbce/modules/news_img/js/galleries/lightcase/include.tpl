<script src="<?php echo WB_URL ?>/modules/news_img/js/galleries/lightcase/src/js/lightcase.js"></script>
<script src="<?php echo WB_URL ?>/modules/news_img/js/galleries/lightcase/vendor/jQuery/jquery.events.touch.js"></script>

<link rel="stylesheet" href="<?php echo WB_URL ?>/modules/news_img/js/galleries/lightcase/src/css/lightcase.css" />

<script type="text/javascript">
jQuery(document).ready(function($) {
	new flexImages({ selector: '.flex-images', rowHeight:100 });

	$('a[data-rel^=lightcase]').lightcase({
		swipe: true,		
		transitionOpen: 'elastic',
		transitionClose: 'elastic',
		speedIn: 500,
		speedOut: 500,
		maxWidth: 2000,
		maxHeight: 1200,
		shrinkFactor: 0.9,
		fullscreenModeForMobile: true,		
		showSequenceInfo: false,
		showTitle: false
	});

});
</script>