<?php

/* -------------------------------------------------------------------
 * 
 * Author: Blupixel IT Srl
 * Last Modifcation Date: 23 Jan 2017
 * Website: www.blupixelit.eu
 * support at: support@blupixelit.eu
 * Copyright (c) 2017 Blupixel IT Srl - Trento (Italy)
 *
 * ===================================================================
 * Adopted for use with WebsiteBaker and compatible forks by 
 * Christian M. Stefan, stefek@designthings.de
 * ===================================================================
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 * 
 * ----------------------------------------------------------------- */ 
 
class IniEditor {
	
	protected $sIniFile;
	protected $sBackupDir    = '';
	protected $bEnableFill   = true;
	protected $bEnableMove   = false;
	protected $bEnableAdd    = false;
	protected $bEnableDelete = false;
	protected $bAddArrays    = false;
	protected $sScriptsDir   = false;
	protected $sLanguageDir  = '';
	protected $sCancelUrl    = '';
	protected $aTrans        = array();
	public    $bShowFileSrc  = false;
	
	
	// contructor
	public function __construct() {	
			$this->aTrans = $this->getTransArray();	
			$this->sScriptsDir = str_replace(array(WB_PATH, '\\'), array(WB_URL,'/'), __DIR__);
	}
	
	// set INI file to edit
	public function setIniFile($sFile) {
		$this->sIniFile = $sFile;
	}
	

	// set Language directory if you want to translate the key-strings
	public function setLanguageDir($sDir) {
		// check if wb/cms LANGUAGE is loaded
		if(defined('LANGUAGE_LOADED')){ 
			if(is_dir($sDir)){
				$this->sLanguageDir = $sDir;
				$this->aTrans = $this->getTransArray(); //overwrite previous setting				
			}
		}
	}
	
	// set backup directory where to save the backup before saving the new version
	// no backups will be made if directory not set
	public function setBackupDir($sDir) {
		$this->sBackupDir = $sDir;
	}
	
		
	// enable editing of the file
	public function enableEdit($bValue) {
		$this->bEnableFill = $bValue;
	}
	
	// enable adding conf and sections in the file
	public function enableAdd($bValue) {
		$this->bEnableAdd = $bValue;
	}		
	
	// enable adding arrays 
	public function enableAddArrays($bValue) {
		$this->bAddArrays = $bValue;
	}	
	
	// enable move pos in the editor
	public function enableMove($bValue) {
		$this->bEnableMove = $bValue;
	}	
	
	// set cancel URL
	public function setCancelUrl($sStr) {
		$this->sCancelUrl = $sStr;
	}
	
	
	// enable adding conf and sections in the file
	public function enableDelete($bValue) {
		$this->bEnableDelete = $bValue;
	}
	
	// get backup filename
	public function backupFilename($sFilename) {
		return str_replace('/', '_', $sFilename);
	}
	
	// wrap a value inside quotes
	public function wrapValue($sVal, $sType) {
		if ($sType == 'bool')
			return ($sVal) ? "1" : "0";
		else
			return '"'.str_replace('"', '\\"', $sVal).'"';
	}
	
	// find values in array using regexp on the key
	public function preg_grep_keys( $sPattern, $input, $flags = 0 ) {
		$keys = preg_grep( $sPattern, array_keys( $input ), $flags );
		$aVals = array();
		foreach ( $keys as $key ) {
			$aVals[$key] = $input[$key];
		}
		return $aVals;
	}
	
	public function test($str){
		if(strpos($str, "{") !== FALSE){
			/*$str = str_replace(
				array("{", "}", "&"),
				array("{{", "}}", "&&"), 
				$str
			);*/
			$str = htmlentities($str);
		}
		return $str;
	}
	
	// save the new file from form request
	public function saveForm() {
		if (!$this->bEnableFill) {
			return;
		}
		$backup = true;
		
		if($this->sBackupDir != ''){
			if (!file_exists($this->sBackupDir)) {
				mkdir($this->sBackupDir, 0755);
			}
			$backup = file_put_contents(
				$this->sBackupDir.'/'.basename($this->backupFilename($_REQUEST['ini_file'])).'_'.date('Ymd_His'), 
				file_get_contents($_REQUEST['ini_file'])
			);

		}
		
		$backup = true;
		if ($backup) {
			$aVals = $this->preg_grep_keys('/ini#.*$/', $_REQUEST);
			$aSave = array();
			foreach ($aVals as $key => $val) {
				$conf = explode('#', $key);
				if (!isset($aSave[$conf[1]])) {
					$aSave[$conf[1]] = array();
				}
				if (is_array($val)) {
					foreach ($val as $k => $v) {
						$aSave[$conf[1]][] = $conf[2] . '['.(!is_numeric($k) ? $k : '').'] = '.$this->wrapValue($this->test($v), $conf[3]);
					}
				} else {
					$aSave[$conf[1]][] = $conf[2] . ' = '.$this->wrapValue($this->test($val), $conf[3]);
				}
			}
			$sToFile = '';
			foreach ($aSave as $section => $rows) {
				$sToFile .= '['.$section.']'.PHP_EOL;
				$sToFile .= implode(PHP_EOL, $rows) . PHP_EOL . PHP_EOL . PHP_EOL;
			}
			$sPhpPrefix = '';
			$sPhpPrefix .=  ";<?php  die('Illegal file access'); ?>".PHP_EOL;
			$sPhpPrefix .=  ";###########################################".PHP_EOL;
			$sPhpPrefix .=  ";#   ".basename($_REQUEST['ini_file'])."".PHP_EOL;
			$sPhpPrefix .=  ";#   saved: ".date(DATE_RFC822)."".PHP_EOL;
			$sPhpPrefix .=  ";###########################################". PHP_EOL . PHP_EOL . PHP_EOL;
			
			$res = file_put_contents(str_replace('BASE_PATH', WB_PATH, $_REQUEST['ini_file']), $sPhpPrefix.$sToFile);
			
			global $admin;
			if (is_object($admin)) {
				if ($res) {
					echo '<style>.cpForm{display:none;}</style>';
					 $admin->print_success(
						sprintf($this->_Trans('%s saved'), basename($_REQUEST['ini_file']) ), 
						basename($_SERVER['REQUEST_URI']), true
					);
				} else {
					 $admin->print_error(
						sprintf($this->_Trans('%s cannot be saved'), basename($_REQUEST['ini_file']) ), 
						basename($_SERVER['REQUEST_URI']), true
					);
				}
			} else {
				if ($res) {
					echo '<div class="alert alert-success">'.sprintf($this->_Trans('%s saved'), basename($_REQUEST['ini_file']) ).'</div>';
				} else {
					echo '<div class="alert alert-error">'.sprintf($this->_Trans('%s cannot be saved'), basename($_REQUEST['ini_file']) ).'</div>';
				}
			}
		}
	}
	
	
	
	// get the form from the file
	public function getForm() {
		$conf = parse_ini_file($this->sIniFile, true);
		$sToHtml = '<div class="editor-container cpForm">';
		if (isset($_REQUEST['save_ini_form'])) {
			$sToHtml .= $this->saveForm();
		}
	
		if ($this->bShowFileSrc) {
			$sToHtml .= '<h3><span class="h3-label">'.$this->_Trans('Selected file').':</span>'.basename($this->sIniFile).'</h3>';
		}
		if ($this->bEnableAdd && $this->bEnableFill) {
			$sToHtml .= '<span><a href="javascript:;" class="addBtnSection btn btn-primary">'.$this->_Trans('Add section').'</a></span>';
		}
		
		if (!is_writeable($this->sIniFile)) {
			$sToHtml .= '<h4 style="color:red;">'.sprintf($this->_Trans('%s is not writable'), $this->sIniFile).'</h4>';
		}
		
		$sToHtml .= $this->getScripts();
		
		$sToHtml .= '<form method="post">
		<input type="hidden" name="save_ini_form" value="1"/>
		<input type="hidden" name="ini_file" value="'.str_replace(WB_PATH, 'BASE_PATH', $this->sIniFile).'"/>'; //prevent PATH from being seen
		
		$additional = array();
		foreach ($conf as $c => $cv) {
			if (in_array('id', array_keys($cv)))
				$conf[$c] = array_merge($additional, $cv);
		}
		foreach ($conf as $c => $cv) {
			$sToHtml .= '<fieldset><legend>'. PHP_EOL;
									
			$sToHtml .= '<span class="secTitle" onclick="$(this).parent().next().slideToggle();">'.$this->_Trans($c).'</span>';
			$sToHtml .= $this->renderAddButtons();
			$sToHtml .= '</legend>'. PHP_EOL;
			$sToHtml .= '<div class="cpForm">'. PHP_EOL;
			
			foreach ($cv as $label => $val) {
				$sToHtml .= '<div class="formRow">';
				if (!is_array($val)) {
					$sIdentifier = 'ini#'.$c.'#'.$label.'#text';
					if ((isset($c[$label]) && is_bool($c[$label])) || $val == '1' || (!$val && $val != "")) {
						$sIdentifier = 'ini#'.$c.'#'.$label.'#bool';								
					}
					$sToHtml .= '<div class="settingName"><div>';
					if ($this->bEnableFill) {
						$sToHtml .= $this->renderMoveArrows();
						if ($this->bEnableDelete) {
							$sToHtml .= ' <a href="javascript:;" class="btn btn-danger" onclick="$(this).parents(\'.formRow\').remove();">' .
							 '<i class="fa fa-lg fa-trash red" title="remove"></i>' . 
							 '</a>';
						}
					}
					#$sToHtml .= ' <label><input type="text" size="1"/>'.$label.'</label>';					
					$sToHtml .= ' <label title="key:  '.$label.'" for="'.$sIdentifier.'">'.$this->_Trans($label).'</label>';
					$sToHtml .= '</div></div>';
					
					$sToHtml .= '<div class="settingValue">';
					if ((isset($c[$label]) && is_bool($c[$label])) || $val == '1' || (!$val && $val != "")) {
						$sToHtml .= '<input type="hidden" name="'.$sIdentifier.'" value="0"/>';
						$sToHtml .= '<input type="checkbox" id="'.$sIdentifier.'" name="'.$sIdentifier.'" value="1" '.($val?'checked="checked"':'').'/>';
					} else {
						$sToHtml .= '<textarea rows="1" name="'.$sIdentifier.'">'.$val.'</textarea>';
					}
					
					// generate hints if defined in the language file
					if(isset($this->aTrans[$label.':hint'])){
						$sToHtml .= ' <p class="hint">'.$this->_Trans($label.':hint').'</p>';
					}
					$sToHtml .= '</div>';
					unset($sIdentifier);
				} else {
					// Array
					$sToHtml .= '<div class="cpForm"><div class="formHeading">';
					if ($this->bEnableFill) {
						$sToHtml .= $this->renderMoveArrows(0);
						
						if ($this->bEnableDelete) {
							$sToHtml .= '<a class="remove-btn" href="javascript:;" onclick="$(this).parents(\'.formRow\').fadeOut().remove();">X</a> ';
						}
					}
					
					$sToHtml .= ' <label class="col-form-label"><input type="text" class="move-input" size="1"/>'.$label.'</label>';
					$sToHtml .= '</div>';
					
					$sToHtml .= '<div class="cpForm" style="width: 80%; float:right;">';
					foreach ($val as $k => $v) {
							$sIdentifier = 'ini#'.$c.'#'.$label.'#text['.$k.']';
							if (is_bool($val[$k]) || $v == '1' || !$v) {
								$sIdentifier = 'ini#'.$c.'#'.$label.'#bool['.$k.']';								
							}
							$sToHtml .= '<div class="formRow">';
							$sToHtml .= '<div class="settingName"><div>';
							if ($this->bEnableFill) {
								$sToHtml .= $this->renderMoveArrows();	
								if ($this->bEnableDelete) {
									$sToHtml .= '  <a class="btn btn-danger" href="javascript:;" class="remove-btn" onclick="$(this).parent().parent().parent(\'.formRow\').fadeOut().remove();">' .
										'<i class="fa fa-lg fa-trash red" title="delete"></i>' . 
									'</a>';
								}
							}
							if (!is_numeric($k)) {
								$sToHtml .= '<label class="" for="'.$sIdentifier.'">'.$k.'</label>';
							}
							
							$sToHtml .= '</div>';							
							$sToHtml .= '</div>';							
							$sToHtml .= '<div class="settingValue">';
							
							if (is_bool($val[$k]) || $v == '1' || !$v) {
								$sToHtml .= '<input class="form_checkbox" type="hidden" name="'.$sIdentifier.'"/>';
								$sToHtml .= '<input class="form_checkbox" type="checkbox" id="'.$sIdentifier.'" name="'.$sIdentifier.'" value="1" '.($v?'checked="checked"':'').'"/>';
							} else {
								$sToHtml .= '<textarea rows="1" class="form-control" id="'.$sIdentifier.'" name="'.$sIdentifier.'">'.$v.'</textarea>';
							}					
							// generate hints if defined in the language file
							if(isset($this->aTrans[$label.':hint'])){
								$sToHtml .= ' <p class="hint">'.$this->_Trans($label.':hint').'</p>';
							}
							$sToHtml .= '</div>';							
							$sToHtml .= '</div>';
							unset($sIdentifier);
					}
					$sToHtml .= '</div>';
					
					if ($this->bEnableAdd && $this->bEnableFill) {
						$sToHtml .= '
						<table width="100%" class="array_add_value">
							<tr>
								<td align="center">
									<a href="javascript:;" class="btn btn-info" onclick="javascript:addArrayRow(this, \'text\');">'.$this->_Trans('Add value').'</a>
								</td>
							</tr>
						</table>';
					}
					$sToHtml .= '</div>';
				}
				$sToHtml .='</div>'. PHP_EOL;
			}			
			$sToHtml .= '</span>';
			
			$sToHtml .= '<div class="buttonRow">';			
			if ($this->bEnableFill) {
				$sToHtml .= '<button type="submit" class="button ico-save">'.$this->_Trans('Save').'</button>';
				$sToHtml .= '<button type="reset" class="button ico-reset  pull-right">'.$this->_Trans('Reset').'</button>';
			}
			if ($this->sCancelUrl) {
				$sToHtml .= '<a class="button ico-cancel pull-right" href="'.$this->sCancelUrl.'">'.$this->_Trans('Cancel').'</a>';
			}			
			$sToHtml .= '</div>';
			

			$sToHtml .= '</fieldset>'. PHP_EOL;
		}
		$sToHtml .= '</form>';
		
		$sToHtml .= '</div>';
		
		// load CSS File if Backend Theme not ACPI Ready
		$sAcpiCheckfile = WB_URL.'/templates/'.DEFAULT_THEME.'/ACPI_READY';
		if(!is_file($sAcpiCheckfile)){
			#$sToHtml .= '<link href="'.$this->sScriptsDir.'/ACPI_buttons.css" rel="stylesheet" type="text/css">';
		}
		$sToHtml .=  '<!--(MOVE) CSS HEAD BTM- -->';
		$sToHtml .=  '<style>
			.editor-container div.settingValue textarea {
				padding: 0.3em;
				vertical-align: top;
				width: 100%;
				min-height: 0.6em;
			}
			.editor-container div.settingValue textarea:focus {
				outline-style: solid;
				outline-color: lightblue;
				outline-width: 1px;
				background-color: #fff;
			}
			</style>';
		$sToHtml .=  '<!--(END)-->';
		#$sToHtml .=  '<!--(MOVE) JS BODY BTM- -->';
		$sToHtml .=  '<script src="'.$this->sScriptsDir.'/iniEditor_body.js" type="text/javascript"></script>';
		#$sToHtml .=  '<!--(END)-->';
		return $sToHtml;
	}
	
	public function renderDeleteBtn(){
		$sToHtml = '';
		if ($this->bEnableDelete) {
			$sToHtml .= ' <a href="javascript:;" class="btn btn-danger" onclick="$(this).parents(\".formRow\").fadeOut().remove();">' .
							 '<i class="fa fa-lg fa-trash red" title="remove"></i>' . 
							 '</a> ';
		}
		return $sToHtml;
		
	}	
	
	public function renderMoveArrows($iIconFormat = 1){
		$sToHtml = '';
		if ($this->bEnableMove) {
			$sToHtml .= 
			'<a href="javascript:;" class="down-arr">' .
			'<i class="fa fa-lg fa-chevron-'.($iIconFormat == 1 ? 'circle-' : '').'down" title="down"></i>'.
			'</a>'.
			'<a href="javascript:;" class="up-arr">' .
			 '<i class="fa fa-lg fa-chevron-'.($iIconFormat == 1 ? 'circle-' : '').'up" title="up"></i>' . 
			 '</a>';
		}
		return $sToHtml;
		
	}	
	
	public function renderAddButtons($sToJS = 0){
		$sToHtml = '';
		if ($this->bEnableAdd && $this->bEnableFill) {
				$sToHtml .= '<span class="pull-right">';
				$sToHtml .= '<a class="addBtnText btn btn-info" href="javascript:;">'.$this->_Trans('ADD_TEXTFIELD').'</a>';
				$sToHtml .= '<a class="addBtnBool btn btn-info"  href="javascript:;">'.$this->_Trans('ADD_CHECKBOX').'</a>';
				if ($this->bAddArrays) {
					$sToHtml .= '<a class="addBtnArray btn btn-info" href="javascript:;">'.$this->_Trans('ADD_ARRAY').'</a>';
				}
				$sToHtml .= '</span>';
			}
			if($sToJS == 1){
				str_replace('\'', '\\\'', $sToHtml);
			}
		return $sToHtml;		
	}
	
	public function getTransArray(){
		$INI_EDITOR = array();		
		

		
		// check if additional folder was added and include its contents if yes
		if ($this->sLanguageDir != ''){ // return if language dir not set
			if (is_readable($this->sLanguageDir.'/EN.php'))
				include($this->sLanguageDir.'/EN.php');
			
			if(LANGUAGE != 'EN'){
				$sLangFile = $this->sLanguageDir.'/'.LANGUAGE.'.php';
				if (is_readable($sLangFile)) 
					include($sLangFile);
			}
		}
		
		// lets include the iniConfigEditor language file first
		include(__DIR__.'/languages/EN.php');
		if(LANGUAGE != 'EN'){
			$sLangFile = __DIR__.'/languages/'.LANGUAGE.'.php';
			if (is_readable($sLangFile)) 
				include($sLangFile);
		}
		
		return $INI_EDITOR;
	}
	
	private function _Trans($sStr){		
		$sRetVal = $sStr;
		$sTraditional = str_replace(' ', '_', strtoupper($sStr)); // All Caps Underscore spelling

		if (isset($this->aTrans[$sStr])) {
			// check for exact spelling
			$sRetVal = $this->aTrans[$sStr];	
		} elseif (isset($this->aTrans[$sTraditional])) {
			// check for All Caps Underscore spelling
			$sRetVal = $this->aTrans[$sTraditional];
		} 
		return $sRetVal;
	}
	
	// get script used to manage the button callback
	public function getScripts() {
		$sRetVal = '';
		// get the css file 	
		$sRetVal .=  '<!--(MOVE) CSS HEAD TOP- -->';
		$sRetVal .=  '<link href="'.$this->sScriptsDir.'/iniEditor.css" rel="stylesheet" type="text/css">';
		$sRetVal .=  '<!--(END)-->';
		#$sRetVal .=  '<!--(MOVE) JS BODY TOP- -->';
		#$sRetVal .=  '<script src="'.$this->sScriptsDir.'/iniEditor_body.js" type="text/javascript">';
		#$sRetVal .=  '<!--(END)-->';
		if ($this->bEnableFill == false) {
			$sRetVal .=  '
			<style>
				.settingValue textarea,
				.settingValue input[type=checkbox]
				{
					pointer: not-allowed;
					pointer-events: none;
					background: #ecf0f5; 
					color: #333; 
					border: 1px solid #666 
				}			
			</style>';	
		}
		if ($this->bEnableMove) {		
			$sRetVal .=  '
			<style>
				input.move-input {
					width: 20px;
					float: left;
					display: inline;
					background: #D9D9D9;
					border: 3px dotted #888;
					margin-right: 5px;
				}
				input.move-input:focus {
					background: #00c4ff;
				}			
			</style>';			
		} else {			
			$sRetVal .=  '<style>
			input.move-input { display: none; }
			
			.down-arr, .up-arr,
			input.move-input { display: none; }
			</style>'.PHP_EOL;
		}

		$sRetVal .= '<script>var ENABLE_MOVE = '.($this->bEnableMove ? 'true' : 'false').';</script>'.PHP_EOL;
		$sRetVal .= '<script>var ENABLE_DELETE = '.($this->bEnableDelete ? 'true' : 'false').';</script>'.PHP_EOL;
		$sRetVal .= '<script>var ENABLE_ADD_ARRAYS = '.($this->bAddArrays ? 'true' : 'false').';</script>'.PHP_EOL;
		
		$sRetVal .= '<script>'.PHP_EOL;
		$sRetVal .= "var MOVE_ARROWS = '".$this->renderMoveArrows($iIconFormat = 1)."';".PHP_EOL;
		$sRetVal .= "var sDeleteBtn = '".$this->renderDeleteBtn()."';".PHP_EOL;
		$sRetVal .= "var renderAddButtons = '".$this->renderAddButtons(1)."';".PHP_EOL;
		$sRetVal .= "var ADD_ARRAYS = '".$this->bAddArrays."';".PHP_EOL; 
		// Lang Strings
		$aLangStrings = array(
			'ADD_TEXTFIELD', 
			'ADD_CHECKBOX', 
			'ADD_ARRAY', 
			'NEW_CONFIG_FIELD_NAME', 
			'NEW_SECTION_NAME', 
			'NEW_CONFIG_FIELD_NAME_FIRST', 
		);
		foreach($aLangStrings as $str){
			$sRetVal .= "var ".$str." = '".$this->_Trans($str)."';".PHP_EOL;
		}
		$sRetVal .= '</script>'.PHP_EOL;
		return $sRetVal;
	}
	
	// print the form from the file
	public function printForm() {
		echo $this->getForm();
	}
	
}
