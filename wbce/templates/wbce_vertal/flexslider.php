<?php 

$limitslides = 5; // maximum random pictures loaded


//=========================================================================================
//Check for directory /slides
$slides_path = str_replace(WB_URL, WB_PATH, TEMPLATE_DIR.'/slides');

//Is there a file 'slideXX.jpg' (XX = PAGE_ID)?:
$s_file = $slides_path.'/slide'.PAGE_ID.'.jpg';
if ( file_exists($s_file) ) {
	//Use this singel image without fading:
	$s_file = str_replace(WB_PATH, WB_URL, $s_file);
	//echo '<h1>found: '.$s_file.'</h1>';
	echo '<img class="singleslidepic" src="'.$s_file.'" alt="">';
	return false;
	exit;
	
} 

//if NO single image - carry on:

//Check for directory . '/slides' . PAGE_ID
$slides_path_pageid = $slides_path.'/slides'.PAGE_ID;
if (is_dir($slides_path_pageid) ) { $slides_path = $slides_path_pageid; }

//echo '<h1>dir: '.$slides_path.'</h1>';
$images = glob($slides_path.'/*.jpg');

echo '
<div class="sliderbox"><div class="slider">
<div class="flexslider">
  <ul class="slides">
';



shuffle($images);
if ($limitslides < 1) {$limitslides = 1000;}

$i = -1;
foreach ($images as $pic) {
	$pic = str_replace(WB_PATH, WB_URL, $pic);
	 echo '<li><img src="'.$pic.'" alt=""></li>	
';
	$i++; if ($i > $limitslides) {break;}
}

?>
  </ul>
</div>
</div></div><!-- end headerbox -->



<script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>/FlexSlider/jquery.flexslider-min.js"></script>

<script type="text/javascript">
//Options: http://www.woothemes.com/flexslider/
 $(document).ready(function() {
  $('.flexslider').flexslider({
  	controlNav: false,
	slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
	animationSpeed: 1600,            //Integer: Set the speed of animations, in milliseconds
	direction: "horizontal", 
    animation: "slide"
  });
});
</script>
