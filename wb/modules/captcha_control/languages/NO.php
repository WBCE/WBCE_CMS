<?php

// $Id: NO.php 966 2009-04-17 12:41:33Z Ruebenwurzel $

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
NORWEGIAN LANGUAGE FILE FOR THE CAPTCHA-CONTROL ADMIN TOOL
-----------------------------------------------------------------------------------------
  */

// Headings and text outputs
$MOD_CAPTCHA_CONTROL['HEADING']           = 'Captcha og ASP kontroll';
$MOD_CAPTCHA_CONTROL['HOWTO']             = 'Her kan du kontrolere hvordan du vil at "CAPTCHA" og "Avansert Spam Beskyttelse" (ASP) skal virke. For &aring; f&aring; ASP til &aring; fungere med en spesifik modul, m&aring; denne tilpasses for bruk sammen med ASP. ASP vil ellers ikke fungere med moduler hvor denne endringen ikke er gjort.';

// Text and captions of form elements
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF']      = 'CAPTCHA Konfigurasjon';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE']      = 'Type CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP']       = 'CAPTCHA instillinger for modulene, finner du under innstillinger i de respektive modulene.';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA']= 'Aktiver CAPTCHA for brukerregistreringen';
$MOD_CAPTCHA_CONTROL['ENABLED']           = 'Aktivert';
$MOD_CAPTCHA_CONTROL['DISABLED']          = 'Deaktivert';
$MOD_CAPTCHA_CONTROL['ASP_CONF']          = 'Konfigrering av Avansert Spam Beskyttelse';
$MOD_CAPTCHA_CONTROL['ASP_TEXT']          = 'Aktiver ASP (om tilgjengelig)';
$MOD_CAPTCHA_CONTROL['ASP_EXP']           = 'ASP vil pr&oslash;ve &aring; fastsl&aring; hvor hvidt innfyllingen i et skjema ble gjort av et meneske eller en spam-robot.';
$MOD_CAPTCHA_CONTROL['CALC_TEXT']         = 'Kalkuler som tekst';
$MOD_CAPTCHA_CONTROL['CALC_IMAGE']        = 'Kalkuler som bilde';
$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE']    = 'Kalkulering som bilde med varierende fonter og bakgrunner'; 
$MOD_CAPTCHA_CONTROL['TTF_IMAGE']         = 'Bilde med varierende fonter og bakgrunner';
$MOD_CAPTCHA_CONTROL['OLD_IMAGE']         = 'Gammel Type (Anbefales ikke!)';
$MOD_CAPTCHA_CONTROL['TEXT']              = 'Tekst-CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT']= 'Sp&oslash;rsm&aring;l og svar';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'] = 'Slett atlt dette for &aring; legge til dine egne innlegg ellers vil ikke endringene bli lagret!'."\n".'### eksempel ###'."\n".'Her kan du legge inn Sp&oslash;rsm&aring;l og Svar.'."\n".'Bruk:'."\n".'?Hva er <b>Claudia</b> Schiffer\'s <b>fornavn</b>?'."\n".'!Claudia'."\n".'?Sp&oslash;rsm&aring;l 2'."\n".'!Svar 2'."\n".''."\n".'om type spr&aring;k ikke er av betydning.'."\n".' ... '."\n".'Eller, om spr&aring;k er av betydning, bruk:'."\n".'?NO:Hva er <b>Claudia</b> Schiffer\'s <b>fornavn</b>?'."\n".'!Claudia'."\n".'?NO:Sp&oslash;rsm&aring;l 2'."\n".'!Svar 2'."\n".'?EN:What\'s <b>Claudia</b> Schiffer\'s <b>first name</b>?'."\n".'!Claudia'."\n".'?EN:Question 2'."\n".'!Answer 2'."\n".'?DE:Wie ist der <b>Vorname</b> von <b>Claudia</b> Schiffer?'."\n".'!Claudia'."\n".' ... '."\n".'### Eksempel ###'."\n".'';

$MOD_CAPTCHA['VERIFICATION']           = 'Verifikasjon';
$MOD_CAPTCHA['ADDITION']               = 'pluss';
$MOD_CAPTCHA['SUBTRAKTION']            = 'minus';
$MOD_CAPTCHA['MULTIPLIKATION']         = 'ganger';
$MOD_CAPTCHA['VERIFICATION_INFO_RES']  = 'Skriv inn ressultatet';
$MOD_CAPTCHA['VERIFICATION_INFO_TEXT'] = 'Skriv inn teksten';
$MOD_CAPTCHA['VERIFICATION_INFO_QUEST'] = 'Svar p&aring; sp&oslash;rsm&aring;let';
$MOD_CAPTCHA['INCORRECT_VERIFICATION'] = 'Feil ved verifiseringen';

?>
