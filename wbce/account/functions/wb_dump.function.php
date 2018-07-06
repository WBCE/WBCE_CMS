<?php 	
if(!function_exists('wb_dump')){
	
	/**
    @brief This is a simple function to show var_dump or print_r output
    in a predefined wrapper, useful for development purposes mostly
    
    Example usage:
    ~~~~~~~~~~~~~~
    wb_dump($admin, 'display $admin object', true);
    wb_dump(TEMPLATE_DIR);


    @param mixed $mVar
    @param string $sHeading (optional)
    @param bool $bShowWithVarDump  decide whether you want var_dump or simple print_r as output 

    @return string   The debug output
	*/
	
	function wb_dump($mVar = '', $sHeading ='', $bUse_var_dump = false){
		$sRetVal = '';
		
		$sVarType = '';		
		$sRetVal .=  wb_dump_css($sVarType); 
		
		
		// get Type of variable
		switch (true){
			case is_object($mVar): $sType = 'object'; break;				
			case is_array($mVar):  $sType = 'array';  break;				
			case is_string($mVar): $sType = 'string'; break;				
			case is_bool($mVar):   $sType = 'bool';   break;				
			case is_int($mVar):    $sType = 'int';	  break;				
			case is_scalar($mVar): $sType = 'scalar'; break;				
			default: $sType = 'unknown var type'; 		
		}
		
		$sClass = ' '.$sType;
		$sRetVal .=  '<fieldset class="wb_dump_frame'.$sClass.'">';
		if($sHeading != ''){
			$sRetVal .=  '<legend style="color: blue;"><span class="var-type">('.$sType.') </span> '.$sHeading.':</legend>';
		}
		$sRetVal .=  '<pre>';
		if((is_array($mVar)) or (!is_array($mVar) && $mVar != '' )){
			$func = ($bUse_var_dump == true) ? 'var_dump' : 'print_r';
			ob_start();
			// echo '<h4>'.$func.':</h4>';
			$func($mVar);
			$sRetVal .= ob_get_clean();
		} else {
			$sRetVal .=  '<i>~ (empty) ~</i>';
		}
		
		$sRetVal .=  '</pre></fieldset>';  

		// apply RegEx for colorization if the output is an Array or an Object
		#if($sVarType != ''){			
			
			$aRegEx = array(				
				0 => array(
					'find'    => "/\=\>\n/",
					'replace' => "=><br /><span class=\"tab\"></span>",
				), 
				1 => array(
					'find'    => '/\=\>/',
					'replace' => "<span class=\"arrow\">=></span>",
				),  
				2 => array(
					'find'    => '#(?<=\[)(.*?)(?=\])#',
					'replace' => '<span class="keyname">$1</span>',
				), 	
				3 => array(
					'find'    => '/\[/',
					'replace' => "<div class=\"vert-spacer\">&nbsp;</div><span class=\"tab\"></span><span class=\"brackets\">[</span>",
				), 				
				4 => array(
					'find'    => '/\]/',
					'replace' => '<span class="brackets">]</span>',
				),  		
				5 => array(
					'find'    => '/(string|array|int)(\()([1-9][0-9]*)(\))/',
					'replace' => '<span class="var-type">$1</span><span class="brackets">$2</span><span class="str-length">$3</span><span class="brackets">$4</span>',
				),  
				 /*
				6 => array(
					'find'    => '#(?<=\()(.*?)(?=\))#',
					'replace' => '<span class="str-length">$1</span>',
				), 
				*/
			);			
			$sRetVal = wb_simple_regex($aRegEx, $sRetVal);
		#}		
		echo $sRetVal;
	}	
}
function wb_dump_css($sType = '')
{
		
return <<<_EOCSS
<!--(MOVE) CSS HEAD BTM- -->
<style type="text/css">
fieldset.wb_dump_frame {
	background: lightyellow; 
	padding:6px;
	border: 1px dotted grey;
	
}
.wb_dump_frame legend {
	margin-top:6px;
	padding-top:6px;
	font-weight: 600;
	font-size: 120%;
	background: lightyellow; padding:6px;
}
.wb_dump_frame pre {
	font-family: monospace;
	color: #424f60;
	line-height: 90%;
}
.wb_dump_frame span.arrow {
	color:magenta;
	font-weight: 600;
	font-size: 85%;
	margin: 0.13em;
}
.wb_dump_frame legend span.var-type {
	color: magenta;
	font-weight: 600;
	font-size: 75%;
}
.wb_dump_frame span.brackets {
	color: #8696aa;
}
.wb_dump_frame span.keyname {
	color: #0047d6;	
	font-size: 105%;
	margin: 0.04em;
}
.wb_dump_frame span.tab {
	margin-left: 1.5em;
}
.wb_dump_frame div.vert-spacer {
	display: inline-block;
	margin-top: 12px !important;
	margin-left: -30px !important;
}
.wb_dump_frame div.vert-spacer::before {
	//content: ' '; display: block;
	//content: "\A";
}
.wb_dump_frame span.var-type {	
	color: green;		
	margin: 3px;
}
.wb_dump_frame span.str-length {	
	color: orange;	
	margin: 1px;
}
</style>
<!--(END)-->
_EOCSS;
		
}

if(!function_exists("wb_simple_regex")){
	function wb_simple_regex($aRegEx, &$str){					
		if(is_array($aRegEx))
			foreach($aRegEx as $regex)
				$str = preg_replace($regex['find'], $regex['replace'], $str); 
		return $str;
	}
}

