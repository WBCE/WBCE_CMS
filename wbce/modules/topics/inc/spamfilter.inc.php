<?php

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

/* start something stupid by chio  */
		// $feedback = '';
		 
		 
	 /*check, if someone tries to have more than one emailaddress  */
	$spamcount = -1 + substr_count($themail,"@"); 
	$spamcount += substr_count($themail,"%"); 
	$spamcount += substr_count($themail,","); 
	$spamcount += substr_count($themail,";"); 
		 		 
	$spamcount = 5*$spamcount;
		
	//SpamCheck:
		
	$spamrefstring = strtolower($themail . $comment . $thename); //check over all fields, some use even the name for spam
		
		
	$spamcount += 10*(substr_count($spamrefstring,"url=http")); 
	$spamcount += 5*(substr_count($spamrefstring,"http://")); 
	$spamcount += 4*(substr_count($spamrefstring,"https://")); 
		
	if (LANGUAGE != 'EN') {	
		$spamcount += 20*(substr_count($spamrefstring,"sexy teachers"));
		
		$spamcount += 2*(substr_count($spamrefstring,"casino")); 
		$spamcount += 4*(substr_count($spamrefstring,"asian")); 
		$spamcount += 8*(substr_count($spamrefstring,"cialis"));
		$spamcount += 8*(substr_count($spamrefstring,"porn"));
		$spamcount += 4*(substr_count($spamrefstring,"free"));
		$spamcount += 4*(substr_count($spamrefstring,"huns"));
		$spamcount += 4*(substr_count($spamrefstring,"oral"));
		$spamcount += 2*(substr_count($spamrefstring,"viagra")); 
		$spamcount += 2*(substr_count($spamrefstring,"pills"));
			
		$spamcount += substr_count($spamrefstring,"cheap"); 
		$spamcount += substr_count($spamrefstring,"buy"); 
		$spamcount += substr_count($spamrefstring,"online"); 
		$spamcount += 10*(substr_count($spamrefstring,"zoloft"));
		$spamcount += 5*(substr_count($spamrefstring,"weight"));
	}			
		//to be continued
		
		if (strlen($spamrefstring) > 200) {$spamcount -= 10;}
		if (strlen($spamrefstring) > 500) {$spamcount -= 10;}
		
		//Wenn moderiert: Etwas toleranter
		if ($commenting < 3) { $spamcount = $spamcount / 3; }
		
		$spamlevel = 0;
		if ($spamcount > 15) { $spamlevel = 1;}
		if ($spamcount > 40) { $spamlevel = 2;}
		if ($spamcount > 80) { $spamlevel = 3;}
		
		


?>