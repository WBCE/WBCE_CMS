<?php

// $Id: EN.php 1179 2009-11-24 18:15:00Z Luisehahne $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2009, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

 -----------------------------------------------------------------------------------------
  ENGLISH LANGUAGE FILE FOR THE CAPTCHA-CONTROL ADMIN TOOL
 -----------------------------------------------------------------------------------------
*/

// Headings and text outputs
$MOD_CAPTCHA_CONTROL['HEADING']           = 'Captcha and ASP control';
$MOD_CAPTCHA_CONTROL['HOWTO']             = 'Here you can control the behavior of "CAPTCHA" and "Advanced Spam Protection" (ASP). To get ASP work with a given module, this special module has to be adapted to make use of ASP.';

// Text and captions of form elements
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF']      = 'CAPTCHA Configuration';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE']      = 'Type of CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP']       = 'CAPTCHA settings for modules are located in the respective module settings';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA']= 'Activate CAPTCHA for signup';
$MOD_CAPTCHA_CONTROL['ENABLED']           = 'Enabled';
$MOD_CAPTCHA_CONTROL['DISABLED']          = 'Disabled';
$MOD_CAPTCHA_CONTROL['ASP_CONF']          = 'Advanced Spam Protection Configuration';
$MOD_CAPTCHA_CONTROL['ASP_TEXT']          = 'Activate ASP (if available)';
$MOD_CAPTCHA_CONTROL['ASP_EXP']           = 'ASP tries to determine if a form-input was originated from a human or a spam-bot.';
$MOD_CAPTCHA_CONTROL['CALC_TEXT']         = 'Calculation as text';
$MOD_CAPTCHA_CONTROL['CALC_IMAGE']        = 'Calculation as image';
$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE']    = 'Calculation as image with varying fonts and backgrounds'; 
$MOD_CAPTCHA_CONTROL['TTF_IMAGE']         = 'Image with varying fonts and backgrounds';
$MOD_CAPTCHA_CONTROL['OLD_IMAGE']         = 'Old style (not recommended)';
$MOD_CAPTCHA_CONTROL['TEXT']              = 'Text-CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT']= 'Questions and Answers';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'] = 'Delete this all to add your own entries'."\n".'or your changes won\'t be saved!'."\n".'### example ###'."\n".'Here you can enter Questions and Answers.'."\n".'Use:'."\n".'?What\'s Claudia Schiffer\'s first name?'."\n".'!Claudia'."\n".'?Question 2'."\n".'!Answer 2'."\n".''."\n".'if language doesn\'t matter.'."\n".' ... '."\n".'Or, if language do matter, use:'."\n".'?EN:What\'s Claudia Schiffer\'s first name?'."\n".'!Claudia'."\n".'?EN:Question 2'."\n".'!Answer 2'."\n".'?DE:Wie ist der Vorname von Claudia Schiffer?'."\n".'!Claudia'."\n".' ... '."\n".'### example ###'."\n".'';

$MOD_CAPTCHA['VERIFICATION']           = 'Verification';
$MOD_CAPTCHA['ADDITION']               = 'add';
$MOD_CAPTCHA['SUBTRAKTION']            = 'subtract';
$MOD_CAPTCHA['MULTIPLIKATION']         = 'multiply';
$MOD_CAPTCHA['VERIFICATION_INFO_RES']  = 'Fill in the result';
$MOD_CAPTCHA['VERIFICATION_INFO_TEXT'] = 'Fill in the text';
$MOD_CAPTCHA['VERIFICATION_INFO_QUEST'] = 'Answer the question';
$MOD_CAPTCHA['INCORRECT_VERIFICATION'] = 'Verification failed';

?>