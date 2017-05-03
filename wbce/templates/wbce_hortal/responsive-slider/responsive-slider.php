<?php 
echo '
<script src="'.TEMPLATE_DIR.'/responsive-slider/responsiveslides.min.js"></script>
<ul class="rslides">
';

$image_dir = TEMPLATE_DIR.'/head-slides/';
$image_path = str_replace(WB_URL, WB_PATH, $image_dir);
$images = glob($image_path.'*');

//shuffle($images);
$i = 0; 
foreach ($images as $pic) {
	$pic = str_replace(WB_PATH, WB_URL, $pic);
	 echo '<li><img src="'.$pic.'" alt=""></li>'; 
	$i++;
	if ($i > 4) {break;}
}
?>
</ul>

<script>
	$(".rslides").responsiveSlides({
	auto: true,             // Boolean: Animate automatically, true or false
	speed: 1000,            // Integer: Speed of the transition, in milliseconds
	timeout: 4000,          // Integer: Time between slide transitions, in milliseconds
	pager: false,           // Boolean: Show pager, true or false
	nav: false,             // Boolean: Show navigation, true or false
	random: false,          // Boolean: Randomize the order of the slides, true or false
	pause: false,           // Boolean: Pause on hover, true or false
	pauseControls: true,    // Boolean: Pause when hovering controls, true or false
	prevText: "Previous",   // String: Text for the "previous" button
	nextText: "Next",       // String: Text for the "next" button
	maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
	navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
	manualControls: "",     // Selector: Declare custom pager navigation
	namespace: "rslides",   // String: Change the default namespace used
	before: function(){},   // Function: Before callback
	after: function(){}     // Function: After callback
});
</script>

