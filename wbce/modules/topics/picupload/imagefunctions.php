<?php
// Direkter Zugriff verhindern
if (!defined('WB_PATH')) die (header('Location: index.php'));

 
function resizepic($filepath, $newfilepath, $pic_soll_w, $pic_soll_h, $checksize = 0, $positionX = 0, $positionY = 0, $positionW = 0, $positionH = 0){

	//echo "<p>$pic_soll_w | $pic_soll_h</p>";
	//die();
	if(!is_file($filepath))  {echo '<b>Missing file:</b> '.$filepath.'<br/>'; return 0;}	
	
	$bg = "ffffff"; //"D1F6FF";	
	$bg = ""; 	
	//$fullpercent = 50;
	$fullpercent = 100; 
	
		
	list($pic_ist_w, $pic_ist_h, $type, $attr) = getimagesize($filepath);
	
	
	
	/*
	Megapixel-Image?
	$fl = ceil(($pic_ist_w * $pic_ist_h) / 1000000);
	if ($fl > $megapixel_limit){
		if ($showmessage==1) { echo '<br/><b>'.$fl. ' Megapixel; skipped!</b>';}
		return -1;
	}*/
	
	
	
	//------------------------------------------------------------//
	//Werte berechnen:
	
	
	
	//ratio > 1: Querformat
	$ist_ratio = $pic_ist_w / $pic_ist_h;
	if  ($positionW > 0 AND  $positionH > 0) { $ist_ratio = $positionW / $positionH;}
	
	if  ($pic_soll_w > 0 AND  $pic_soll_h > 0) {
		$soll_ratio = $pic_soll_w / $pic_soll_h;
	} else {
		$soll_ratio = $ist_ratio;
	}
	
	//Wenn ein Soll-Wert 0 ist, aus soll_ratio berechnen
	//soll_ratio ist uU. bereits identisch mit ist_ratio
	if ($pic_soll_w < 1) {$pic_soll_w = $pic_soll_h * $soll_ratio; }
	if ($pic_soll_h < 1) {$pic_soll_h = $pic_soll_w / $soll_ratio; }
	
	
	//_calc kann am Ende abweichen, dh jetzt gleich kopieren.
	$pic_soll_w_calc = $pic_soll_w;
	$pic_soll_h_calc = $pic_soll_h;
	
	
	if ($ist_ratio > $soll_ratio) {
		//Bild ist breiter als der Rahmen erlaubt
		//echo '<p>breiter: ' .$ist_ratio.' '.$file.'</p>';		
		
		$pic_soll_w_calc = $pic_soll_h_calc * $ist_ratio;			
		$ofx = ($pic_soll_w_calc - $pic_soll_w) / -2;		
		$ofy = 0;
		
		//values without crop:		
		$pic_soll_h_calc2 = $pic_soll_w_calc / $ist_ratio;
		$ofx2 = 0;
		$ofy2 = ($pic_soll_h_calc2 - $pic_soll_h) / -3; 
		
	} else {
		//Bild ist hoeher als der Rahmen erlaubt
		//echo '<p>hoeher: ' .$ist_ratio.' '.$file.'</p>';
		
		$pic_soll_h_calc = $pic_soll_w_calc / $ist_ratio;
		$ofx = 0;		
		$ofy = ($pic_soll_h_calc - $pic_soll_h) / -3;//Eher oberen Teil, dh /-3
		
		
		//values without crop:		
		$pic_soll_w_calc2 = $pic_soll_h_calc * $ist_ratio;	
		$ofy2 = 0;
		$ofx2 = ($pic_soll_w_calc2 - $pic_soll_w) / -2; 			
	}
	
	
	//mix crped and non-cropped values by percent:
	
	//Relikt:
	//Mischung aus $pic_soll_X_calc und pic_soll_X_calc2 = teilweiser Anschnitt.
	//hier im allgemeinen aber 100% $pic_soll_X_calc, 0% pic_soll_X_calc2 
	/*
	$f1 = 0.01 * $fullpercent;
	$f2 = 1.0 - $f1;
	$pic_soll_w_calc = floor(($f1 * $pic_soll_w_calc) + ($f2 * $pic_soll_w_calc2));
	$pic_soll_h_calc = floor(($f1 * $pic_soll_h_calc) + ($f2 * $pic_soll_h_calc2));
	$ofx = floor(($f1 * $ofx) + ($f2 * $ofx2));
	$ofy = floor(($f1 * $ofy) + ($f2 * $ofy2));
	
	$pic_soll_w_calc = floor($pic_soll_w_calc);
	$pic_soll_h_calc = floor($pic_soll_h_calc);
	*/
	
	
	
	
	//Ausnahme: Bild ist in w UND h kleiner als newfile	
	//Möglichkeiten:	
	
	if ($pic_ist_w <=  $pic_soll_w_calc AND $pic_ist_h <=  $pic_soll_h_calc) {	
		if ($bg == '') {
		//1: Wenn keine Füllfarbe angegeben: das bild as is kopieren
			//echo '1 width/height: '.$pic_ist_w.'/'.$pic_ist_h.'<br/>';
			//die();
			copy($filepath, $newfilepath);
			return 0;
		} else {
		//2: weitermachen und Auffüllen
		echo '2 width/height: '.$pic_ist_w.'/'.$pic_ist_h.'<br/>';
			die();
			$ofx = 0; $ofy = 0; $pic_soll_w_calc = $pic_ist_w;  $pic_soll_h_calc = $pic_ist_h; 
			$ofx = floor(($pic_soll_w_calc - $pic_ist_w) / 2);	
			$ofy = floor(($pic_soll_h_calc - $pic_ist_h) / 2);			
		}
	}
	
	//============================================================
	//16.04.2013: 
	//Bilder sollten nicht verändert werden, wenn sie zu klein sind UND Keine Soll-Höhe/Breite angegeben wurde:
	if ($pic_soll_h < 1 AND $pic_soll_h_calc < $pic_soll_h_calc) {$pic_soll_h_calc= $pic_soll_h_calc; $ofy = 0;}
	if ($pic_soll_w < 1 AND $pic_soll_w_calc < $pic_soll_w_calc) {$pic_soll_w_calc= $pic_soll_w_calc; $ofx = 0;}
	

	//---------------------------------------------------------
	//Check if resizing is neccessary
	if(is_file($newfilepath) AND $checksize == 1)  {		
		list($nwidth, $nheight, $ntype, $nattr) = getimagesize($newfilepath);
		if ($pic_soll_w_calc == $nwidth AND $pic_soll_h_calc == $nheight) { return 0;}
	} 
	
	/*
	echo 'pic_ist_w/h: '.$pic_ist_w.'/'.$pic_ist_h.'<br/>';
	echo 'pic_soll_w/h: '.$pic_soll_w.'/'.$pic_soll_h.'<br/>';
	echo 'pic_soll_w/h_calc: '.$pic_soll_w_calc.'/'.$pic_soll_h_calc.'<br/>';
	*/
//die();

	//---------------------------------------------------------
	//resize:
		
	if ($type == 1) { $original = @imagecreatefromgif($filepath); }
	if ($type == 2) { $original = @imagecreatefromjpeg($filepath);}			
	if ($type == 3) { $original = @imagecreatefrompng($filepath); }		
	
	if ( !isset($original) )  { die('Could not create image'); } //Problem
		

	
	
	
	//Änderungen der Variablen die für JCrop newfileerstellung anders sein müssen	
	//smallheight wird über ratio vom ausschnitt übernommen:
	if (!empty ($positionW) && !empty($positionH)) {	
		$pic_soll_w_calc = $pic_soll_w;
		$pic_soll_h_calc = $pic_soll_h;
		$pic_ist_w = $positionW;
		$pic_ist_h = $positionH;
		$ratio = $pic_ist_w / $pic_ist_h; 		
		$pic_soll_h_calc = $pic_soll_w_calc / $ratio;	
		$ofx = 0;
		$ofy = 0;
		
		//$pic_soll_w = $pic_soll_w_calc;
		$pic_soll_h = $pic_soll_h_calc;
	}
	
	//Now: create newfile image:	
	if (function_exists('imagecreatetruecolor')) {
		$small = imagecreatetruecolor($pic_soll_w, $pic_soll_h);		
	} else {
		$small = imagecreate($pic_soll_w, $pic_soll_h);
	}
	
	//fill it:
	sscanf($bg, '%2x%2x%2x', $red, $green, $blue);
	$b = imagecolorallocate($small, $red, $green, $blue);
	imagefill($small, 0, 0, $b);
	
	
	if (function_exists('imagecopyresampled')) {	
		imagecopyresampled($small, $original, $ofx, $ofy, $positionX, $positionY, $pic_soll_w_calc, $pic_soll_h_calc, $pic_ist_w, $pic_ist_h);		
	} else {	
		imagecopyresized($small, $original, $ofx, $ofy, $positionX, $positionY, $pic_soll_w_calc, $pic_soll_h_calc, $pic_ist_w, $pic_ist_h);
	}	
	
	//if(is_file($newfile)) { unlink($newfile); }
	if ($type == 1) { imagegif($small,$newfilepath); }
	if ($type == 2) { imagejpeg($small,$newfilepath); }	
	if ($type == 3) { imagepng($small,$newfilepath); }
		
		
	imagedestroy($original);
	imagedestroy($small);
	return 1;


}

?>