// $Id: readme.txt 915 2009-01-21 19:27:01Z Ruebenwurzel $


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



One can improve all CAPTCHA-types with varying fonts and backgrounds
- by adding backgrounds (PNG-images, 140x40 pixels) to backgrounds/
- and by adding TrueType-fonts to fonts/


How to use:

1.)
put 
  require_once(WB_PATH.'/include/captcha/captcha.php');
in your file.


2a.)
put 
  <?php call_captcha(); ?>
into your form.
This will output a table with varying columns (3 or 4) like this example:
<table class="captcha_table"><tr>
  <td><img src="http://www.example.org/include/captcha/captchas/ttf.php?t=64241454" alt="Captcha" /></td>
  <td><input type="text" name="captcha" maxlength="5" style="width:50px" /></td>
  <td class="captcha_expl">Fill in the result</td>
</tr></table>


2b.)
If you want to use your own layout, use additional parameters to call_captcha():
call_captcha('all') will output the whole table as above.

call_captcha('image', $style); will output the <img>-tag for the image only (or the text for an text-style captcha):
Examples:
  call_captcha('image', 'style="...; title="captcha"');
    <img style="...; title="captcha" src="http://www.example.org/include/captcha/captchas/captcha.php?t=46784246" />
    or
    <span style="...; title="captcha">4 add 6</span>
	call_captcha('image');
    <img src="http://www.example.org/include/captcha/captchas/captcha.php?t=46784246" />
    or
    4 add 6

call_captcha('input', $style); will output the input-field:
  call_captcha('input', 'style"...;"');
    <input type="text" name="captcha" style="...;" />
  call_captcha('input');
    <input type="text" name="captcha" style="width:50px;" maxlength="10" />

call_captcha('text', $style); will output a short "what to do"-text
  call_captcha('text', 'style="...;"');
	  <span style="...;">Fill in the result</span>
  call_captcha('text');
	  Fill in the result



The CAPTCHA-code is allways stored in $_SESSION['captcha'] for verification with user-input.
The user-input is in $_POST['captcha'] (or maybe $_GET['captcha']).
