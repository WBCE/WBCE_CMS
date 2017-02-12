# Changelog WebsiteBaker CE
This CHANGELOG was automatically created from a local WBCE Git repository.
The changelog may therefore not be correct or up-to date.
Please visit the [WBCE Github](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commits) repository for the most up to-date version.

## Auto generated Git commit history

 * **2017-02-12:** instantflorian [[9a1eb92](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9a1eb9270747221cb9596219f8459623bfc696c5)]
   > Update modify_settings.php
     undo last changes
 * **2017-02-12:** instantflorian [[331aa5e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/331aa5ed6116f2a54b58497a45ea096646cafc7d)]
   > Update modify_settings.php
     commented out hard codes see prev/next headlines
 * **2017-02-11:** instantflorian [[d939b23](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d939b23eb7b2feccec77c633d8e9233bd11a232e)]
   > Update module_settings.default.php
     correction to last commit, needs inverted commas instead of quotes
 * **2017-02-11:** instantflorian [[3423318](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/342331896c6f51790039969f7d598ff77f982029)]
   > Update module_settings.default.php
     Avoid problems with former UTF-8 invalid delimiter
 * **2017-02-11:** cwsoft [[7aa7c08](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7aa7c08cea2c45ea652d9a7e18ffb697e1ecee1a)]
   > Updated CHANGELOG.md

 * **2017-02-10:** cwsoft [[5030708](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/503070864134d5cc7159fb4ce01e61aa60e605fc)]
   > Updated PHP Mailer to v5.2.22
     - Security fix for the 3rd party library PHPMailer
     - See [CVE-2017-5223](https://web.nvd.nist.gov/view/vuln/detail?vulnId=CVE-2017-5223)
     
     https://github.com/PHPMailer/PHPMailer/releases/tag/v5.2.22

 * **2016-02-05:** NorHei [[89eff23](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/89eff235c20ccf8f0983478452e1d6619c27da81)]
   > Added a slightly modified version of PclZip
     This version avoides some problems whith PHP 7
     
     fetched from here :
     https://github.com/piwik/component-decompress/commit/deca40d71d29d6140aad39db007aea82676b7631

 * **2017-02-10:** cwsoft [[0b83765](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0b83765a68ae6d4055367fd510562307411ce8b5)]
   > Updated CHANGELOG.md

 * **2017-02-10:** instantflorian [[ca364d8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ca364d8c038ef325cc1116d82306c38d3e5256e1)]
   > Update version.php
     Changed version to 1.1.11
 * **2017-02-10:** cwsoft [[6393832](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/63938321b282b6a50880b4fca41a702a46855539)]
   > Removed outdated mysql calls in Topics upgrade scripts

 * **2017-02-09:** cwsoft [[9640ce9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9640ce9fb630c018847e32d522fdd1985997818b)]
   > Major code review and refinement of all Addon action handler files
     - unified permission checks and user input validation
     - removed obsolete code blocks
     - replaced inhouse code with PHP functions where possible

 * **2017-02-09:** cwsoft [[82da6f8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/82da6f8d7ebe7a6ef1baa064528af632c0fe87de)]
   > Code refinement for all Addon uninstall handlers

 * **2017-02-09:** cwsoft [[c199d77](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c199d7718e07ef81b43f84a4214c22657f5838d1)]
   > Merge pull request #215 from rjgamer/patch-4
     Update SecureForm.php

 * **2017-02-08:** cwsoft [[5d6848c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5d6848cf7432b740b8f92f408e42ac1d834c7062)]
   > Updated CHANGELOG.md

 * **2017-02-08:** NorHei [[7a4d23d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7a4d23df6f5858f888e01cda5b049ce6971c3a24)]
   > Added Ruuds Version of Short.php
     Ruud refined our Patch to have the intended 404 functionality.
     Thanks alot.
     o

 * **2017-02-08:** cwsoft [[c676c4a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c676c4a7b0ea60ade750698f937986d528424766)]
   > Merge fixes for WBCE media center from 1.2.x branch

 * **2017-02-06:** cwsoft [[227a3bc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/227a3bc62968204c2e3839b0ac9147770a0c1632)]
   > Took over filter settings for function media_filename from WBCE 1.2.x

 * **2017-02-06:** cwsoft [[e71198a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e71198a0ad168c5caebaed5257217d97ef8bf3f2)]
   > Filter some more characters in function media_filename

 * **2017-02-06:** cwsoft [[3c248a7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3c248a7f1088454850769a451ba5a579a33bd2cf)]
   > Some code refinement for JVN#53859609

 * **2017-02-03:** cwsoft [[ee90d10](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ee90d10ea3fd3cc6b7366449f0761a5a0bdeb5b2)]
   > Fix for JVN#10983966

 * **2016-12-28:** NorHei [[1f582e0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1f582e0ae5531db0cd10f3ef5bd69ba5b0569298)]
   > PHP Mailer just needed another patch
     Sorry for the inconvienience

 * **2016-12-27:** NorHei [[fee3215](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fee321522acaa841ad0cd7a6ae6f3450ca74fd29)]
   > Bumped Version

 * **2016-12-27:** NorHei [[4b39293](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4b3929375451b5463ace7968da878aa64e49abe8)]
   > PHP Mailer  Bug fix
     http://www.golem.de/news/websicherheit-phpmailer-bringt-eine-boese-weihnachtsueberraschung-1612-125255.html
     
     https://github.com/PHPMailer/PHPMailer/releases

 * **2016-12-09:** NorHei [[3bc384d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3bc384dd53d354d87478b257b1764aeb21e41987)]
   > Bugfix for frontend registration
     https://forum.wbce.org/viewtopic.php?id=811
     https://forum.wbce.org/viewtopic.php?id=812

 * **2016-04-27:** NorHei [[819d85c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/819d85c0a1d23c08a99b2975d2d9ca8c9edd75f5)]
   > NEw  Version 1.1.7

 * **2016-04-27:** NorHei [[1140097](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/114009721ec874cc5baf164e97fd08871e2680e2)]
   > One more security enhancement add ftan to /account
     edafe34
     https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/123
     
     Thanks to Krzysztof

 * **2016-03-23:** NorHei [[9c726d5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9c726d5d8b6b57421c312f1e2624cc559ab5b2f8)]
   > Bumped Version to 1.1.6

 * **2016-03-23:** NorHei [[4e0ba92](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4e0ba92085f5c5a6027bacef02be8b290e956089)]
   > If /admin dir is changed , drag and Drop in manage sections stops working.
     http://forum.wbce.org/viewtopic.php?pid=4109#p4109
     
     Thanks to Bernd

 * **2016-03-21:** NorHei [[4a8bd98](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4a8bd98e94a4dfcf0f4c569cc54750aa987c8e92)]
   > Patch for php 5.2 !!!
     Yess this is an outdated version , but as long as a single fix  lets this keep
     running , i see absolutely no problem in doing this .
     
     http://forum.wbce.org/viewtopic.php?id=491
     
     Thanks to Chio

 * **2016-03-21:** NorHei [[0333c0f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0333c0fcb9b0437103fee599528cd1e2a0cf94a6)]
   > Patch for problems whith WBstats when upgrading.
     http://forum.wbce.org/viewtopic.php?pid=4012
     
     Thanks To Marmot !!!

 * **2016-03-03:** NorHei [[4f4f38e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4f4f38e4eab8993eb85fc259e649cb810500edaa)]
   > more fixes for the fix

 * **2016-03-03:** NorHei [[45b229d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/45b229d2881a178cc29d8dcc76dc39133aa0eeaf)]
   > Security patches again
     http://forum.wbce.org/viewtopic.php?id=452
     
     http://forum.wbce.org/viewtopic.php?id=440

 * **2016-02-27:** NorHei [[88f8de5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/88f8de51daeb6d34251a685362a38efc96b0acf9)]
   > Security issue , some input fields directly piped to Database
     Those Fields where piped directly whitout any validation
     or escaping ... bad bad thing.
     
     http://forum.websitebaker.org/index.php/topic,28998.msg203463.html#msg203463
     https://www.htbridge.com/advisory/HTB23296
     
     As far as i can see we got it all contained now.
     
     Happy baking
     Norbert

 * **2016-02-21:** NorHei [[8c9211f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8c9211f53a4138986450aa212664ca53c4ca872b)]
   > Bumped Version Number to 1.1.4

 * **2016-02-21:** NorHei [[d016c41](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d016c41f695cc73e8e05b726445cda998d7a5c94)]
   > Constant WB_SECFORM_TIMEOUT not set on Upgrade from 2.8.1
     #110
     
     Still needs some more testing as possibly other constants fail too .
 * **2015-12-15:** NorHei [[5655bcb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5655bcbb0865ec8137df80368c908a6c5e7c25a6)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2015-12-15:** NorHei [[9adebc0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9adebc01ace665acc66728dd1e09679fc22a7a88)]
   > Set version number to stable

 * **2015-12-14:** instantflorian [[f0e1580](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f0e15809dcac500cfd59d34041dd77decc0dc72d)]
   > Yet another depreciated thing

 * **2015-12-13:** NorHei [[07a8ae5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/07a8ae532c4e738d155c86ca2ca0903ba8990887)]
   > Undefined index: _media_test in /.../admin/media/upload.php on line 103
     http://forum.wbce.org/viewtopic.php?pid=2593#p2593
     
     Problem is in mediacenter, because the mediasettings only exist, when at least one time the mediaoptions are stored. So storing the options will end up this notice until an new folder is created.
     One idea for solving this would be to check if mediasettings for a folder do exist:
     upload.php line 102
     
     [== PHP ==]
     if(file_exists($relative.$filename) && isset($pathsettings[$resizepath])) {

 * **2015-12-12:** NorHei [[bbc84c9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bbc84c920c3f7e29b2a90a2f36c1c3d11b2e762e)]
   > Deprecated: Methods with the same name as their class will not be constructors
     Deprecated: Methods with the same name as their class will not be
     constructors in a future version of PHP; Template has a
     deprecated constructor in /.../include/phplib/template.inc on line 70
     
     Deprecated: Methods with the same name as their class will not be
     constructors in a future version of PHP; wbmailer has a
     deprecated constructor in /.../framework/class.wbmailer.php on line 35

 * **2015-12-11:** instantflorian [[0218f41](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0218f418594564b88994289066704a68a2309931)]
   > Add missing miniform DE translations

 * **2015-12-10:** instantflorian [[5e40987](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5e40987b3290a7a25c7f33d63493e505cc088977)]
   > Topics: Fix for PHP7, remove username display

 * **2015-12-08:** instantflorian [[29eab64](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/29eab64cb0c9b226da224202ae2b581527a29b12)]
   > Replace index.html with index.php (and add another missing one)

 * **2015-12-08:** instantflorian [[e31a8eb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e31a8eb7b45a36314b22ce8557d077133bf80967)]
   > Create templates directory for miniform module
     to avoid irritating error messages during install

 * **2015-12-04:** NorHei [[971f38b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/971f38b367dee3f5f37fa0b3920ddc4e28409d8f)]
   > Fix for mysql strict mode.
     Hopefully this will fix problems whith strict mode for now.
     Not sure if its allowed to set this on every server.
     Later we will go to fix all SQL queries.
     
     Thanks to marmot in this thread:
     http://forum.wbce.org/viewtopic.php?pid=2483#p2483

 * **2015-12-01:** NorHei [[7a216b3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7a216b3b644e6ec369a61f5b5b9c9838a31a3cb2)]
   > Increased Version number  to 1.1.3-rc.1 please read more!
     YES i know that not entirely correct but i could not go to 1.1.2-rc.1 as this would be lower than 1.1.2
     From now on we strictly follow our versioning guidelines .
     
     https://github.com/WBCE/Versioning-Scheme

 * **2015-12-01:** NorHei [[7ad015f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7ad015f089475ce973e395d398738467c5012883)]
   > Installed miniform 0.8 to avoid template deletion on upgrade.
     Nothing else to say :-)

 * **2015-12-01:** NorHei [[6a199f5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6a199f50bcaf974e9badea243991ef300bf4e8c2)]
   > Revert "Odd semicolon"
     This reverts commit 98bda74703228b4271b3cc00f856d479884fe216.
     
     This nasty construct seems to rely on the concept that if the first expression in an if() is false , all othere aren't executed
     
     if(($addon['directory'] == DEFAULT_TEMPLATE) ? $selected = ' selected="selected"' : $selected = '');
     
     So i had to undo changes as changing the backend theme stopped to function properly.

 * **2015-11-27:** NorHei [[98bda74](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/98bda74703228b4271b3cc00f856d479884fe216)]
   > Odd semicolon
     admin\settings\index.php
     Ln:237 = Odd semicolon
     Ln:249 = Odd semicolon

 * **2015-11-25:** NorHei [[d322a7f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d322a7f48795e9977ffec100b60cf6d1519b19c3)]
   > This line does not end with the expected EOL:'LF'
     html/admin/pages/add.php
     ln:116/117
     This line does not end with the expected EOL:'LF'

 * **2015-11-25:** NorHei [[9aa8673](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9aa8673c99d435c23866ebd585a5aad1041a85c7)]
   > $key have the same name as the parent FOREACH $key
     framework\addon.precheck.inc.php
     Ln:161 = FOREACH $key have the same name as the parent FOREACH $key
     Ln:161 = FOREACH $value have the same name as the parent FOREACH $value
     Ln:298 = FOREACH $value have the same name as the parent FOREACH $value
     Ln:318 = FOREACH $key have the same name as the parent FOREACH $key
     
     Fixed this.

 * **2015-11-25:** NorHei [[10729d6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/10729d68d97d0110b260f4563f074f2ef74b8091)]
   > increase version number

 * **2015-11-23:** NorHei [[0c6d01a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0c6d01a9aaf4745e0a2c3c2b052ba7d7dd82de34)]
   > Notice: Undefined variable: menu_link in page_tree.php
     Notice: Undefined variable: menu_link in ...\wbce\admin\pages\page_tree\page_tree.php on line 161
     
     Issue #65
     
     Hello,
     when logged in to backend as a user I got a message in pages:
     Notice: Undefined variable: menu_link in ...\wbce\admin\pages\page_tree\page_tree.php on line 161
     
     Please initiate menu_link as below
     $menu_link = false;
     // manage SECTIONS and DATES Icons -->
     $canManageSections = (MANAGE_SECTIONS == 'enabled' && $admin->get_permission('pages_modify') == true && $can_modify == true)?true:false;
     
     Thanks to qssocial

 * **2015-11-23:** NorHei [[5efd6b5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5efd6b528a2b2c46cd7aa8cc1e5e4610678e6ed9)]
   > Wrong style align:... schould be text-align:...in module functions

 * **2015-11-23:** NorHei [[b591d37](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b591d37feab10c3fe8add98ca53110cf964e3c9e)]
   > brackets wrong again ...in search.php

 * **2015-11-23:** NorHei [[0eecc5e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0eecc5e2e623e4719e4942fb68f551aef8de00b2)]
   > missing bracket in search.php

 * **2015-11-20:** instantflorian [[91615ad](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/91615adac6d9f839443334e715bd0f692eeebb3c)]
   > WBCE reference in Argos BE theme

 * **2015-11-19:** instantflorian [[4fb357b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4fb357b1e99ed7d27c8599f87f0356cb81d2ca23)]
   > width of input fields should not be 98% per default

 * **2015-11-19:** NorHei [[dd0714c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dd0714c6a000631790087d16c533cda84fbe2eb2)]
   > Little typofix in HTTPS handling.
     Https handling in initialize.php had a little typo .

 * **2015-11-19:** NorHei [[95c5bb7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/95c5bb724b5209368716bfc37fe67a378c5bba7a)]
   > Updated version to 1.1.1 for beta 2
     This is a Bugfix only release , fixed some upgrade bugs and some installer
     bugs , a little but nasty bug in autoloader having trouble whith capital
     letters in path and many more fixes. Just take a look here at git.

 * **2015-11-19:** NorHei [[ca2f33a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ca2f33ac00f924c74ed27335586c6cb4ee4c481b)]
   > Bug in autoloader if WB_PATH contained Capital letters.
     The autoloader enforces  the use of non capital letters in classes loaded
     by directory selection. The strtolower function did too much and lowered the
     Path too ...

 * **2015-11-18:** NorHei [[15a191d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/15a191d7aa761f8c331f5486936528bab0d6a781)]
   > ['HTTPS'] is not reliable ... :-(

 * **2015-11-18:** NorHei [[3d58dd9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3d58dd99baa1c578c6398940a7f7b6f004826d66)]
   > $_REQUEST['search_path'] may not be an array
     PEntest experimented whith different contents in get vars.
     Thx to evaki

 * **2015-11-18:** NorHei [[2f6b837](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2f6b8370be748badf0c23cc28399338e325ec914)]
   > Prevent direct access on initialize.php

 * **2015-11-18:** Bianka Martinovic [[bb79cb4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bb79cb4a354dea41146da851662e8ae443cad85f)]
   > fix for issue #57

 * **2015-11-17:** NorHei [[b4a6b1f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b4a6b1ffa3fc9b557e3cb7be4ffddf372dd724ab)]
   > removed useless require for Secureform class as Autoloader takes care

 * **2015-11-17:** NorHei [[aaaf97a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/aaaf97a3c18cd1959b07ec98a35d89f22ddaae1f)]
   > removed useless require for DB class as it is already loaded

 * **2015-11-17:** NorHei [[448c389](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/448c38990e71e4c98f62d60e2d2f9a81f870b509)]
   > getFTAN() returns a hidden field whith title="" alt=""
     simply removed this
     
     Thx to Webbird  for reporting

 * **2015-11-17:** NorHei [[9fd6f52](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9fd6f525325f7d4ba1733c3493ccf60d0763b136)]
   > Changed position of the OPF Loader so its available in frontend.functions now.
     opf_controller was needed in frontend.functions.php too so i moved the
     opf Loader up to load before  frontend.functions.php  is loaded.
     
     Thx to Mrbaseman who found that problem.

 * **2015-11-16:** instantflorian [[31e487b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/31e487b4691ddfc7686acea1f3521bbffd8e2b2b)]
   > changes on default FE templates
     wbce template: replace short php tag with full php tag
     simple responsive template: add direct access prohibition according to
     wbce template
     advanced flat be theme: login link was not shown after sending new
     password

 * **2015-11-15:** NorHei [[57ce05d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/57ce05d2f91ecd24e59f23bc78d46ed22da0089e)]
   > Https Cookie Secure now set if called via https
     Added Check if called via https cookie is set to secure only.
     Later We schould add a switch for this.

 * **2015-11-15:** NorHei [[674ff66](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/674ff6609e302e9bf4b6cbb4d4b1a54a95c2d325)]
   > Search input was not sanitized enough.
     It was possible to submit arrays and provoke error messages.
     Thx To Evaki!

 * **2015-11-15:** NorHei [[21a3520](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/21a3520c1a04d38bc62b0b48f510db503ebe8fde)]
   > More changes for upgrade process
     Secureform and captchacontroll not correctly set on upgrade from old
     WB versions.

 * **2015-11-12:** instantflorian [[6756e97](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6756e97310cb27275e47b1b434dfa086e9917692)]
   > Error in name+directory in argos theme, Wording error in Advanced theme

 * **2015-11-11:** NorHei [[afa54d4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/afa54d4302a64d9e8394375d9b7e248324666021)]
   > Loginform Autocomplete and Upgrade Script
     Small review of upgrade script especially issue #48 and a few other
     minor changes. Now using Settings::Set() instead of the function build
     into the script as it allows to not overwrite existing Settings.
     
     Allowing loginforms to have autocomplete was regarded a security issue,
     So please templatebuilders  pull before  editing the templates as i already
     added this once a time ago ;-)

 * **2015-11-10:** instantflorian [[d514045](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d514045aea51a8e20f2f031a2b40205b6317928e)]
   > Bugfix, misplaced </div> + version number update

 * **2015-11-09:** NorHei [[d3705ef](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d3705efb886fec3c70f458ba341bb6bdeaf4121b)]
   > Added parameter overwrite to Settings::Set()
     Settings::Set($key,$value,$overwrite=true)
     
     If overwrite is set to false this method won't overwrite existing values.
     This was needed for upgrade script rewrite.

 * **2015-11-09:** cwsoft [[3d38caa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3d38caa93fe44017516ca849dd67060fb8fc9e83)]
   > Updated CHANGELOG.md

 * **2015-11-09:** instantflorian [[91e64e0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/91e64e098f790a414cc3c6ae74cf5b78e943252a)]
   > New slogan

 * **2015-11-07:** instantflorian [[2628e22](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2628e225469ff4ac55003c2d0e7eef5223daebdb)]
   > Update pagecloner to 1.0.1

 * **2015-11-07:** instantflorian [[79c4178](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/79c4178e2f5d70ab8dbdba7ab97b9b7932c64d59)]
   > Changes to Simple Responsive default template
     Removed referrer to Google fonts, included them in the new folder
     /fonts. Also created a editor.css

 * **2015-11-04:** instantflorian [[170978a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/170978a36c6a47a96da87e1c4c69dab66fca1dfd)]
   > Remove screaming exclamation marks from maintenance page template

 * **2015-11-03:** NorHei [[91cae8c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/91cae8c3db3208498811b5e0f33d675889384b01)]
   > Short Url stoped Linking external resources.
     It may have been possible to link external files whith url.
     Simply extended filter .
     
     Thx to Ruud.

 * **2015-11-03:** NorHei [[424119f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/424119f0e602f7cbb74cfa110ff24c8a3c7311ea)]
   > Short Url, added a filter for  Dir Up in path
     I dont like the idae of having --/ or ./ in Short Url Path.
     added a filter , so noone can send a Dir up or Homedir to shorturl.

 * **2015-11-03:** NorHei [[0be1ea6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0be1ea6e8292aafb04cfd15fa22f8dd4970ae3bb)]
   > Offering a way to suppress old output filter .
     Setting  WB_SUPPRESS_OLD_OPF to true  now completely suppres execution
     of old output filters.
     
     I soon add a switch for this.
     
     This is for better interaction whith OPF

 * **2015-11-02:** instantflorian [[e3cd6ab](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e3cd6abe8a94f3a7058e571db53be95b9a2db857)]
   > added webfont to wbce template, corrected wrong all of THEME_URL in some htt files

 * **2015-11-02:** instantflorian [[4c58c51](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4c58c51dd2927630efe6d659be8c0e78c83f16d6)]
   > added missing closing div, removed depreciated image attribute

 * **2015-11-01:** marmotwb [[bcd2bb1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bcd2bb12927dea08e767d37db9e0488ebb8ce2e8)]
   > Fix for issue 47
     fixes #47

 * **2015-10-29:** marmotwb [[51594b0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51594b094abfa7f00af936f426d28b8aebb39342)]
   > Fix for Issue 23
     Fix for Issue #23

 * **2015-10-29:** marmotwb [[5ca19ff](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5ca19ff5714da8fffe020848ee2bd9b876711a4a)]
   > update index.php
     Pull request #42, thx to rjgamer

 * **2015-10-28:** NorHei [[5b1a54d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5b1a54df11058640e1dd44d7edb67ef8fed99db5)]
   > Applied patch for better interaction whith OPF Dashboard
     As this will do no harm.
     http://forum.wbce.org/viewtopic.php?pid=2017#p2017
     
     Thx to mrbaseman

 * **2015-10-28:** instantflorian [[70ed738](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/70ed738e576a9b13cfcc235e5dbfef53ad8ab823)]
   > Change BE themes to use local fonts, remove unused icons

 * **2015-10-28:** instantflorian [[fc8fb1a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fc8fb1adbd9cca25cf651449f71e1cc52f1a9edb)]
   > minor corrections to framework files, removal of problematic shorturl autoloader from wbce fe template

 * **2015-10-28:** instantflorian [[fb861c7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fb861c798f9d1f0bc1d3414f28acf7a8f76766b4)]
   > fix for missing codemirror plugin icons

 * **2015-10-27:** NorHei [[1a36bb5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1a36bb56261b6e677f51ffb9ab103c48e90623cd)]
   > now setting the right user for Droplet last modified

 * **2015-10-27:** NorHei [[5578f92](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5578f920b7809e6cf517a2edaa83729fa62161eb)]
   > missuse of Git ... Fixes come here

 * **2015-10-27:** NorHei [[0f71777](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0f717777fd6ac29feadb83f02d5df42c07371a44)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition Several security fixes to problems send by Evaki .

 * **2015-10-27:** Bianka Martinovic [[357d1b7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/357d1b77e8dc18a57ba6df9efa91e639f842d249)]
   > fixed wrong call to $admin->user_id() -> $admin->get_user_id()

 * **2015-10-27:** NorHei [[4d79e36](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4d79e369380ab5fc8dc971d921a33c18a05f11e6)]
   > exits for the redirect

 * **2015-10-27:** NorHei [[241b709](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/241b7095d06557ebc8554e5790d798a6bc6122aa)]
   > Set cookies to httponly for security

 * **2015-10-27:** NorHei [[c1c8f95](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c1c8f95ec1e9f2da493cc2c9549b6e32584956ba)]
   > Some security fixes on short URL
     short.php used to inject REQUEST_URI int SCRIPT_NAME and PHP_SELF.
     
     Especially SCRIPT_NAME is normally considered save for self refering forms.
     So its  not a good Idea to inject the complete unsave REQUEST_URI into it.
     
     Now we filter REQUEST_URI to provide some sane and save values for
     shortened urls.

 * **2015-10-27:** NorHei [[726ba55](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/726ba558fa96c77156ec4bc7ebdf022f8254da75)]
   > removed insecure REQUEST_URI from Output filter fool

 * **2015-10-26:** NorHei [[9358ce7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9358ce78ac1d82323dbe0667c7d2fd72f6b28313)]
   > functions.php Function remove_path is obsolete/not in use!
     It seemed to be only halfway finisched , its removed now.
     
     Thx to Kant

 * **2015-10-26:** NorHei [[697a5b4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/697a5b4272ca84375038fb600d8e03276845e6ac)]
   > frontend.functions.php Function moveCssToHead is obsolete/not in use!
     has been removed as this is done by an outputfilter.
     
     Thx to Kant

 * **2015-10-26:** NorHei [[ec38225](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ec38225917fe8cafe87377a8ba904c1433a3ff63)]
   > Removement of completely unused stuff
     Unused require for class DB
     And some unused Constants.

 * **2015-10-26:** NorHei [[9e58224](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9e58224a5f9e3a57ed286f935f4aa32461a2a0c0)]
   > Typofix missing '' thx to Kant

 * **2015-10-26:** NorHei [[4c19ffa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4c19ffad3c56442c2619607c0f2902614561c3e2)]
   > SanitizeHttpReferer() removed parameter $sWbUrl
     WB_URL is a constant and it will stay vor a while $sWbUrl was introduced for
     removing all constants. As we don't plan to remove em we remove this feature.

 * **2015-10-26:** NorHei [[45422eb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/45422eb829d8c31f8de9197a9dfb5b02ecc78f48)]
   > Strange error on loading autoloader #45

 * **2015-10-26:** NorHei [[9004e66](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9004e66bffa248658713ec682d08fad173d48538)]
   > MoveCSStoHead was running into an endless loop on some occasions.
     Calling phpinfo() from a code section for example.
     
     This is the fix from the last public svn of WB.
     Thx to Kant

 * **2015-10-25:** NorHei [[7f1344a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7f1344ae873a9d93e3d0377e7943f1dd294c3b28)]
   > Added session livetime and checking.
     Session livetime now defined by WB_SECFORM_TIMEOUT, later this gets its
     own setting, but for now this will do.
     
     Session now checks for livetime and sets sookie livetime
     
     This is very basic functionality, later we replace this whith a nice
     session class.

 * **2015-10-25:** NorHei [[88dbc4b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/88dbc4bdcc53588821c7d33e3e7a62ef8b1cd6ad)]
   > Some Additions to error Reporting (thx to Kant)
     Added Error Level -1.
     Some cleanup to er_levels.php.

 * **2015-10-24:** NorHei [[a6ba55c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a6ba55ce21deb0e8f60e1b22f624f05e1356b38c)]
   > Small cleanups to initalize.php (Thx to Kant)
     Removed silly @ (suppressing errors never a good idea).
     
     $sWbUrl not used in SanitizeHttpReferer so removed in function definition.

 * **2015-10-24:** NorHei [[16cdda1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/16cdda1716d62371185624b7daf7ec2a08e78a69)]
   > Language Vars missing in SFS module (Thx to Kant)

 * **2015-10-21:** NorHei [[b4c0196](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b4c01963fe20c544ec7162b004f77578b8ebe548)]
   > Just a few comments changed.

 * **2015-10-21:** NorHei [[4c8c5ea](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4c8c5eaf2117aae5c21e1e3ee7eb3442b866758d)]
   > Captcha Controll now runns on Settings -> cleanup for Initialize.php
     Captcha controll was using its own DB table just to generate its
     Constants in a seperate script in Initialize.php right below the
     place where constants where created anyway if they use Settings class.
     
     Captcha controll now uses settings class so i could remove that useless
     extra code. In addition i remove the non functional javascript in
     the interface. And did some changes so you can add captchas whitout
     changing captcha Controll. (the javascript preloader onlyloaded
     a static set of images) All new installed captchas should show up
     imediately.

 * **2015-10-20:** instantflorian [[4c923d9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4c923d9c1b2183d49f491509a568075cf7316cdc)]
   > minor design fixes on argos BE theme

 * **2015-10-20:** NorHei [[ccf286f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ccf286f5348415d0884e52c95e0c3a49be334e81)]
   > Activated autoloader + Some cleanup in Initialize.
     Autoloader is now activated and available .
     And i did some cleanup to initialize.php
     Still there is lots of work to do.
     
     The autoloader now loads most WB libraries on autopilot.
     /framework/ folder is searched and some includes are Set right now.
     Twig and PHP mailer load their own autoloader.
     
     To register a new directory:
     WbAuto::AddDir("/classes/somewhere/");
     
     Register a special file (very fast plus you can load non standard conform stuff):
     WbAuto::AddFile("MultiCrud","/classes/multicrud.php");
     
     Register a new search shemata:
     WbAuto::AddType("class.%s.inc.php");
     
     Register Vars are global and can be printed for debug:
     echo  "<pre>"; print_r(WbAuto::$Dirs); echo  "</pre>";
     echo  "<pre>"; print_r(WbAuto::$Files); echo  "</pre>";
     echo  "<pre>"; print_r(WbAuto::$Types); echo  "</pre>";
     
     So if you would like to offer a library module you simply can do :
     <?php WbAuto::AddDir("/mysnippit/classes/");
     in the include.php et voila you got a library module.

 * **2015-10-19:** NorHei [[6571221](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/657122139db42c5736dfbd2fe47f340f6c69da0d)]
   > Added Autoloader Class for later inclusion

 * **2015-10-19:** NorHei [[de891e9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/de891e963174bf9412e0821e88c4bf95e4682d00)]
   > Added hooks for OPF dashboard.
     OpF dashboard is a powerfull outputfilter controlling system made by thorn.
     Cause of personal trouble it never made it into the core. Not at least its
     system hooks are intergrated into the core for as long as we don not have
     any other "real" output filter system.
     
     Thanks to mrbaseman :
     http://forum.wbce.org/viewtopic.php?id=176

 * **2015-10-19:** NorHei [[be5d565](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/be5d56539f9b4aa378cc28a3eb0188ccb4b09e5c)]
   > Add /config/ and /var/ folder to dir and installer for later use.
     Later we should move config.php to a folder as we avoid problems whith
     webroot being not writable. /var/ is needed for modules to store files
     that schould not be deleted when upgrading module(eg. templates).
     
     Added checks for both in /intall/index.php.

 * **2015-10-19:** NorHei [[910bae6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/910bae67165512996495c2c6838c4294020e1d02)]
   > Some tweaks to pagetree , thx to florian

 * **2015-10-19:** NorHei [[19ec392](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/19ec392961d307274c6f5ace18e915c521c590d7)]
   > Some tweaks to WbFlat theme, and a few fixes for jumpy icons , thx to florian

 * **2015-10-19:** NorHei [[9aa4843](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9aa484370d819fc6b034ea2afe7bb301d0dc50df)]
   > Some tweaks to argos theme , thx to florian

 * **2015-10-19:** NorHei [[c5e653b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c5e653b5b6ede94f6b57b557a8415ebbeb453129)]
   > there was a : missing in template.

 * **2015-10-19:** NorHei [[f9836bd](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f9836bdf6f0540e81ea52fee6843e9b0b1d1e67a)]
   > Updated all languagefiles for last change(Filename!=Menuentry)

 * **2015-10-19:** NorHei [[40ea9f9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/40ea9f9437d6408401067e5b83cdc6f677c4ef4c)]
   > Filename now may be != Menuname
     Implemented an old Patch from my former fork. That was one of the few things that where implemented into the temporary WB 2.8.4.
     Whithout changing the database we allowed to have filenames that are  different from the menu entry.

 * **2015-10-17:** NorHei [[d701cee](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d701cee36c5b5d479f2a0623d71a335eab9fdda6)]
   > Admin tools in default theme now all should be same height

 * **2015-10-17:** NorHei [[4ece5e4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4ece5e4e63adef1edfe33a7ac15d7053e81936e9)]
   > minor formating issue

 * **2015-10-17:** NorHei [[840b457](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/840b4577c86ba04d7104d5ba6041e0b580f2f130)]
   > Changed some umlauts and  some fileheaders

 * **2015-10-17:** NorHei [[f3f265f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f3f265f805b1b5eaf5e0613cd88c2e329af7cf5d)]
   > Some fixes for output_filter Tool
     Display full form on system messages
     Redundant loading of languages and system classes

 * **2015-10-17:** NorHei [[86129de](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/86129deb37fdcedc0c7b3ed7c99457f1eee6c0fc)]
   > Small code rewiew for captcha_controll admin tool
     Replaced Old PHPlib templatefile whith plain PHP
     Fixed Redundant loading  of features.
     Fixed output error displaying the form on system messages.

 * **2015-10-17:** NorHei [[d0f8c77](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d0f8c7766bdae61350acbdc80647ff92a1da99f1)]
   > Small code review for jsadmin module
     Removed redundant function calls and language file loading.
     Fixed a few display errors.(Like displaying the full form on error message)

 * **2015-10-17:** NorHei [[27148ba](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/27148ba33d8c9da948cbfdd8a35985664cc76dc0)]
   > Small code review for module  user_search
     Removed redundant calls like language files, class admin ....
     Fixed some umlauts, maybe some more needed.
     Fixed a template bug.
     Removed useless PHP end tags .
     
     This module still needs some more love ;-)

 * **2015-10-15:** NorHei [[4dfd117](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4dfd117dd82c34262a85966f11f4b48fc65233c2)]
   > error message in some php versions fixed

 * **2015-10-14:** NorHei [[71cfe31](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/71cfe3112315900d502a3cf67ef9d82400c7b457)]
   > Some Icon changes and other cosmetic stuff, thx to Florian

 * **2015-10-14:** NorHei [[e1ff35b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e1ff35b562a001d1f4182a55e4a017b607f64f69)]
   > Secureform switcher updated some headers

 * **2015-10-14:** NorHei [[e2f2429](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e2f2429ed26e588be179a25a0327c24688a39e6d)]
   > Secureform switcher some refinements for install uninstall and upgrade

 * **2015-10-14:** NorHei [[f4b31d0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f4b31d0c6a36e17244e3d5f368cbb62bd9779005)]
   > Complete rewrite of the Secureform Switcher

 * **2015-10-14:** NorHei [[75dfc33](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/75dfc331d5a4223012ef241c9c0b1137e089bf6c)]
   > Changed default setting in class secureform to not use fingerprinting

 * **2015-10-13:** NorHei [[107d8d2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/107d8d217b5d29c2249a528a4a6ec6937af80056)]
   > Upgraded maintainance_mode to use the new default feature

 * **2015-10-13:** NorHei [[68db0dc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/68db0dcf0fa2dedcdfda990b5fef4cedaafaa733)]
   > Some new vars for admin tool handling
     $saveSettings  True if form is send to save settings not back button , not default button
     $saveDefault   Tru if a button whith name and id ="save_default"

 * **2015-10-11:** NorHei [[6c4577d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6c4577dfea2f57add777d4a7037ebb76d7a6c9bf)]
   > Some small fixes for tools again

 * **2015-10-11:** NorHei [[9f448c5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9f448c550481c46aaf7b2056dfcd35eb21344bb9)]
   > Small fix for Output_Filter Tool, for undefined var notice

 * **2015-10-11:** NorHei [[e057018](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e057018820ff122a88ae8706c40d1635057ee084)]
   > Ups i forgot to remove some debug stuff 'Dumm gelaufen'

 * **2015-10-11:** NorHei [[6881763](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6881763e46ab370a02d7bff03413aa7261c52e6e)]
   > Maintainance_mode tool now is a demo tool and uses new features.
     Its now a demo tool whith lots of usefull information in the sourcecode.

 * **2015-10-11:** NorHei [[c98fcf4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c98fcf48cd337da9b1af621a7c7fca60a6c90449)]
   > print_error() now only exits if it has printed the admin footer
     print_error() and print_success() are a real mess both completely different.
     definitely work to do .

 * **2015-10-11:** NorHei [[a6e040e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a6e040e4fb1b86f3616f0f1183732dd67c4a7a02)]
   > Loading of admin tools now changed a lot
     - Check for ftan file is removed. checkfile removed too. Admin tools
       whithout ftan will function again.
     
     - Added functionality for bach to admin tools, simply set value="admin_tools"
       to the button. If $_POST['admin_tools'] this will redirect to admin tools.
     
     - Several additional vars for path and other purpose:
       $modulePath     Path to this module directory
       $languagePath   Path to language files of this module
       $returnToTools  Url to return to generic tools page
       $returnUrl      Url for return link after saving AND for sending the form!
       $doSave         Set true if form is send
       $toolDir        Plain tool directory name like "maintainance_mode"
       $toolName       The name of the tool eg "Maintainance Mode"
     
       For automated detection of sent form the submit button needs to have
       name+id ="save_settings". (Optional ($_POST['action']) == 'save')
     
     - The following WB classes and Modules are included automatic so no need
       to try manual loading:
     
       config.php
       framework/initialize.php
       framework/class.wb.php
       framework/class.admin.php
       framework/functions.php
     
     - some really old admin tools try to load backend.css and backend.js manually,
       please remove this code as we no longer need to suppot  WB 2.6.x.
     
     - Flattened out the structure to have a more linear workflow.
     
     - Language files are noaded here(if they exist) and we use new way of
       loading so even if language file is incomplete in its translation
       it will simply display englisch text instead of an error.

 * **2015-10-11:** NorHei [[2c269d9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2c269d98332624c07ed147daf3653018e0578d84)]
   > Changed error message and success message of default admin template.
     Changed both files to function whithout javascript.

 * **2015-10-09:** NorHei [[53d1e33](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/53d1e3349d799ef638456c38d81ed2e34efd35da)]
   > Maintainance mode admin tool adeded to default installation.
     This is just a tool to turn maintainance mode on/off.
     
     Its the first Admin tool using the new Settings class.

 * **2015-10-09:** NorHei [[c408491](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c408491036102d26f1013a2efbaee3fba01b9a49)]
   > Set version to 1.1.0

 * **2015-10-09:** NorHei [[5533094](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/553309407b4a4c0d562060c70811fca13c780b71)]
   > Install fails #41, one space before <?php

 * **2015-10-07:** NorHei [[ed578a5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ed578a55c9e726a95accc37f3acffccf42d2b3b6)]
   > Secureform Singletab is gone, i guess i wont be crying.
     Singletab is finally removed from Core the file is removed and the only
     secureform loaded now is the multi tab variant. I guess noone will be
     missing this.
     
     I still need to fix the secureform switcher as the singletab setting
     is no longer needed but the fingerprit options are still usefull.

 * **2015-10-07:** NorHei [[6472d0c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6472d0c4fd4e7642fe17f90adc54b13147cb6074)]
   > Changed folders /system/ in templatefolders to /systemplates/ as that is what it is.

 * **2015-10-06:** NorHei [[3a92964](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3a92964172a4a346bbbd7203d01b35df1c7d34ba)]
   > Installation of new settings class
     As i tried to make a admin tool  for setting of maintainance mode, i realized
     that there is nothing for easy management of WBs system settings.
     You still have to do it manually.  But i remembered that some module where
     using some set_settings() get_settings() functions(e.g. jsadmin).
     So i did a deep thought an decided that it would be a good idea that the
     whole settings management would be a lot more fun it we got some dedicated
     functions to handle this.
     If we use constants we schould do it in a good and clean way, so i put a
     few lines together and would like to know if you got some more ideas
     i can put into this.
     
     // create or edit a setting
     Settings::Set("wb_new_setting","the value");
     
     // using a setting
     if (WB_NEW_SETTING =="the value") echo "Horay";
     
     // there is a get function but this is mostly used internal
     $myValue= Settings::Get ("wb_new_setting");
     
     // deleting
     Settings::Delete("wb_new_setting");
     
     // if used in modules please prepend (shortened)module name to avoid collisions
     Settings::Set("wysi_new_setting","another value");
     
     Please keep in mind that WB stores settings always as strings.

 * **2015-10-06:** NorHei [[bbc675c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bbc675c877c13e0ab45e8e264685f68137d66c7d)]
   > Adding a maintainance mode
     The Maintainance mode is activated by setting a constant in the config.php.
     
     define('WB_MAINTAINANCE_MODE', true);
     
     You can deactivate it by either deleting the line or by setting the mode
     to false.
     
     The default template for maintainance mode can be found in
     /templates/system/maintainance.tpl.php
     
     Any template can have a custom maintainance template placed in
     /templates/yourtemplate/system/maintainance.tpl.php
     
     At this time the maintainance mode is limited to use the default template
     set in website options in the backend.
     
     As a next step there will be an admintool that allows to set the mode from
     the backend.

 * **2015-09-26:** NorHei [[c437f2c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c437f2c6c8460bd1c6f6a711c96dc32363934897)]
   > Hey finally we made it to 1.0.0-stable!

 * **2015-09-22:** NorHei [[c8d7aae](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c8d7aaed328d9df4a6a76e163f01f5900ce1c6f9)]
   > Problems whith characters not displaying properly in news.
     http://forum.websitebaker.org/index.php/topic,28648.msg200498.html#msg200498

 * **2015-09-15:** NorHei [[205ebf5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/205ebf56a3bea306f44b3bf30bbd3872b3f407f4)]
   > Some more quick and dirty fixes for jumpy icons , at least sections are ok so far.
     I am really unhappy as in needed to add styles right in template code :-(

 * **2015-09-14:** NorHei [[fc10985](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fc10985cbc31f290832cb62b86a2419ea3fbdb2d)]
   > Stopped jumpy icons from jumping.
     http://forum.wbce.org/viewtopic.php?id=157
     
     Thanks to Florian

 * **2015-09-14:** NorHei [[8d32d23](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8d32d23e4ba6d936b0339d05a6dd801b42445106)]
   > Revoved the PHP 4.3 stuff in last change as WBCE will not run on this anymore.

 * **2015-09-14:** NorHei [[a2ff751](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a2ff7511a46d47702990191394db040bce76d0ae)]
   > Moved handling of menu links from core to menu link module.
     Removed the extra handling of menu links in index.php
     Moved it to the view.php of module menu links.
     
     Now the extra functionality of this module is seperated from the
     core and you can uninstall it if you like.
     
     Freeing the core from unnecessary code.

 * **2015-09-14:** NorHei [[815f85d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/815f85d0aef9ab9bc4996375504207e68fa004ac)]
   > Changed default redirect for menu links to SEO friendly type 301.

 * **2015-09-14:** NorHei [[67eb1c9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/67eb1c9f8cb3e8fc319090cbd6aab1aba063852a)]
   > Delete groupe confirmation for NL and TR

 * **2015-09-09:** Christian Sommer [[511399d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/511399dee1199edcb18926c547781ee1c1f8a64f)]
   > Set version to v1.0.0-RC1

 * **2015-09-06:** marmotwb [[47d9033](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/47d9033a45f76ad8471629c4755657eab17f251a)]
   > Keep installer compatible with PHP < 5.4
     ! There is no need for SORT_NATURAL sort in language dropdown, see
     http://forum.wbce.org/viewtopic.php?id=134

 * **2015-09-02:** Bianka Martinovic [[c1b1167](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c1b1167b5df9aba5fb255a2927b907fdaca678b7)]
   > removed theme_new.css; removed empty lines

 * **2015-09-02:** Bianka Martinovic [[56ed734](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/56ed73445e60be70be7905955e633832f2b5dea7)]
   > removed theme_new.css; removed empty lines

 * **2015-09-02:** Bianka Martinovic [[277f315](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/277f3157049b2b60958907dbc6f960fb213e62c3)]
   > removed unneccessary checkbox on login page; added {message} placeholder to login page (shows errors); re-designed warning.html and "no pages" page; see also http://forum.wbce.org/viewtopic.php?id=135

 * **2015-09-01:** NorHei [[aacba27](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/aacba27581710850f5673341bfb9f1244b643442)]
   > Issue #28 fixed this for german and english

 * **2015-09-01:** NorHei [[91f8c81](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/91f8c81ddf813e36a0725d51eff6896c2e91cc67)]
   > Changed Language files for additional vars + First test of LF tool
     Added language vars for quicksearch in user management  and spread
     them through the language files by using the language file tool for
     non UTF enviroment.

 * **2015-09-01:** NorHei [[f042a41](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f042a41e2389f293c531bca566bb627f3682fa78)]
   > Add some additional langugae vars to user quicksearch

 * **2015-08-31:** marmotwb [[a019dde](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a019ddeac25b1e92b8ba05bb8bbc320a1b0fa642)]
   > cke update codemirror plugin
     ! update to latest plugin version 1.13, see also forum
     http://forum.wbce.org/viewtopic.php?id=130

 * **2015-08-30:** marmotwb [[f658228](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f658228c8dbb302240928303cd8a8f630b95385c)]
   > cke cleaning
     ! fixed wblink plugin to show dialog on doubleclick
     - deleted samples directory (there's no need to deliver samples in live
     version)

 * **2015-08-30:** marmotwb [[7e1905f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7e1905fffdd5a2282329cd15300c8191dd2a3b4f)]
   > Some language support
     ! new DE.php provieded here: http://forum.wbce.org/viewtopic.php?id=123
     ! language support for "back" button in advanced backend theme
     ! show title tags for sidebar elements in advanced backend theme (helps
     when sidebar ist collapsed)

 * **2015-08-29:** NorHei [[19f56e4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/19f56e4b82a39c2ecf6479c1b9c9b0c79e9dd123)]
   > Damm forgot to pull before starting to edit....
     Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2015-08-29:** NorHei [[852c57a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/852c57a35e485d62d2a65ba6fe3bded5f8b5da4d)]
   > make_dir() had trouble whith umask on certain server configs, this fixes the problem

 * **2015-08-28:** marmotwb [[85be132](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/85be1322b0c67b3760594f3af92fffbe37685330)]
   > set new version on upgrade
     ! upgrade script updates version in database now

 * **2015-08-28:** marmotwb [[4f2866b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4f2866bc23fea88c216d623657c2fc8b1d1bf722)]
   > no droplet deleting on upgarde
     ! fixed Issue #36

 * **2015-08-27:** marmotwb [[f235894](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f235894e7e6b89d6c55d51dbfc6a4052792b1b9d)]
   > fixed group selection
     ! fix for Issue #35, so groups with no users assgined can be selected
     again

 * **2015-08-24:** cwsoft [[87a1937](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/87a19378c343028d00b9025b49936b8c802e8e3e)]
   > Updated comment text in page access files
     - Fixes Issue #34
     - automatic code formatting
     - updated README.md

 * **2015-08-21:** cwsoft [[dcbd9ed](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dcbd9ed7aba60803a24b0525a573c0c43d636ba9)]
   > Cleaned up HTML skeleton used for initial intro page
     - Fixes Issue #30

 * **2015-08-21:** cwsoft [[f261ac0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f261ac0751e063aa5811b5a50a01c54faa15728e)]
   > Replaced CKEditor image preview text with lorem ipsum text
     - Fixes Issue #31

 * **2015-08-21:** marmotwb [[02d872f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/02d872f12849577fe3370663117b7dd9afd8bdf8)]
   > Fixes for topics module
     ! create accessfile for welcome topic Issue #3
     ! prevent notice on deleting a topics page

 * **2015-08-21:** cwsoft [[e58a432](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e58a432685c053c07fd18b87b113e89b213cae08)]
   > Updated Twig to v1.20.0

 * **2015-08-20:** cwsoft [[83ce179](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/83ce1793fff3f55a52d2d6a0dd74233e956e1296)]
   > Adapted help links in advanced WB flat theme:
     - updated links to WBCE help, forum and addons
     - set theme version to 0.3.9
     - Closes Issue #32

 * **2015-08-20:** cwsoft [[0069d37](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0069d3746e55208679607828d19d93950908ffe7)]
   > Updated WBCE Argos Theme to v1.7.4:
     - changed link in footer to point to WBCE website
     - increased version to 1.7.4
     - fixes Issue #33

 * **2015-08-20:** cwsoft [[db59e7c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/db59e7c2ae559a5c77974db0b79235bb177d0319)]
   > Fixed broken Twig autoloader

 * **2015-08-19:** marmotwb [[672a8a8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/672a8a8c5fadc3cc02f16d0582aa5114b593bfd8)]
   > correct group mangement and simple responsive template
     ! delete confusing info in group management, set unintentionally in last
     commit
     ! show logo in template "simple responsive" Issue #22

 * **2015-08-19:** marmotwb [[6b309fe](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6b309fedf39f4d2724c6deb4c143b1152ff5fb5a)]
   > update grup_id handling an insert section name on upgrade
     ! group_id is set new on upgrade issue #29
     ! some formal code changes as discussed in the forum:
     http://forum.wbce.org/viewtopic.php?id=84   / issue #26
     ! insert field "namesection" into sections table on upgrade to enable
     named section support

 * **2015-08-19:** cwsoft [[51936d9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51936d940ea8c804d085eafe7a76758e1a6aeae0)]
   > Set version to v1.0.0-Beta3

 * **2015-08-18:** cwsoft [[1d0c739](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1d0c73966f778596b8f268289d1654458ebd45c6)]
   > Updated CHANGELOG

 * **2015-08-18:** cwsoft [[3ecce07](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3ecce072ffe9f5f192f03b4211f670f3b1bcb0c2)]
   > Removed WBCE tools to [separate repository](https://github.com/WBCE/wbce_tools)
     - purpose is to keep WBCE small
     - keep away potentially dangerous tools (PHP Filemanager) from less experienced users

 * **2015-08-18:** cwsoft [[4007d1f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4007d1f2330b5c3a967c07c16f5d505328d29964)]
   > Updated CHANGELOG.md

 * **2015-08-18:** cwsoft [[5b18685](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5b18685d84cd78219c43be4dc11a57514bd62864)]
   > Converted switch endswitch blocks into PSR-2 coding style

 * **2015-08-18:** cwsoft [[aa4e12f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/aa4e12f30a5f23bbb749178bffdda146af14f7c5)]
   > Formatted PHP files following PSR-2 coding styles
     - no refactoring, just re-intend code, fix spaces, parentheses etc.

 * **2015-08-17:** NorHei [[16f1053](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/16f1053b7915ebbe0656012721b4d09c68aeed38)]
   > Accidentally uploaded wrong file to my local repo

 * **2015-08-16:** NorHei [[8277c5d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8277c5d9344e05dbb9e750d4b92bccbb9d885641)]
   > groups view now shows how many menbers are actually in that group

 * **2015-08-16:** NorHei [[f6c8ede](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f6c8ede9cd55ab52ed4edd9fd67a9e49688fe124)]
   > Again issue #26 Check is now based on group_id and groups_id
     Before check was only on group_id now it also checks groups_id.
     Check for group_id is only kept for old buggy entries to db.
     These entries my possibly still be in db, maybe this is something we should
     cleanup whith the upgrade script.

 * **2015-08-16:** NorHei [[a36cfa9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a36cfa9aad479d1fd0991489e383f13eed7bd7aa)]
   > Webadmin Filemnager
     Download from: https://gist.github.com/nic-o/1219610 as the original
     page (http://cker.name/webadmin/)had a disfunctional .zip file
     
     I added this opensource filemanager to remove files from the webspace
     that have been added by websitebaker whith invalid file and directory
     permissions on a Server that uses PHP as Apache module.
     
     On such a server it may happen that if you try to remove such files via
     FTP you may get a "insufficient permissions error". Thats because the
     files are created by the webserver under a user different from FTP user.
     
     Just copy this filemanager into the webroot of your page and call:
     
     www.yourdomain.com/webadmin.php
     
     !!! ATTENTION !!!
     Please keep in mind that this admintool also can be abused as a hacking
     tool (indeed its a pretty good one, almost a hackershell)
     So please remove it after use or install it into a password protected
     directory (Its not necessary to install it into webroot).
     Please be really carefull!

 * **2015-08-16:** NorHei [[983590b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/983590b2243b59feb026eb2c8878339de59161dc)]
   > Added this tool to reset admin theme if something goes wrong while experimenting

 * **2015-08-15:** cwsoft [[e90d58d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e90d58dde7c3c45490c6acd67c4da41e69de36f0)]
   > Fixed wrong file header

 * **2015-08-15:** cwsoft [[48e109a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/48e109a13b4c2cbff89224180cce8e2493aa7609)]
   > Replaced file headers of files we already worked on

 * **2015-08-14:** NorHei [[67bbe0e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/67bbe0e8a058e1557de575245865499c9c8b7c1a)]
   > When editing a user group_id was not changed, but groups_is was. I simply applid a few lines of code taken from add.php

 * **2015-08-14:** NorHei [[41a95a9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/41a95a9c6ac42d9241bedb9dbbc6f4e2c6f5e413)]
   > Fix for #25 error message when editing droplets whithout being group_id 1

 * **2015-08-13:** Bianka Martinovic [[150240f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/150240f8f3fb762b992b62910e773cd338ea3e15)]
   > issue #26: added english lang string for 'GROUP_HAS_MEMBERS' to all languages but DE

 * **2015-08-13:** Bianka Martinovic [[3d131b4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3d131b4cac88089f1b466073d157e92dc2684750)]
   > fix for issue #26; language strings for EN and DE only!

 * **2015-08-13:** Bianka Martinovic [[a15303c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a15303c3fc942ce53ae8dc70a12db14692dedbcd)]
   > fix for issue #24

 * **2015-08-13:** NorHei [[872b9b6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/872b9b61adc43eda1bbadd3038dd3a56c0def775)]
   > accidentally some Pushes to master did nor  show up on git
     Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2015-08-11:** cwsoft [[2d775ec](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2d775ec83a10d502a2fd13822e110adeb4b8a967)]
   > Fixed typos in README

 * **2015-08-11:** cwsoft [[3419069](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/341906916e54051f36f5f81e806aa7c1063fdb35)]
   > Updated markdown files in root folder
     - added more information to README and INSTALL
     - updated CHANGELOG

 * **2015-08-11:** NorHei [[2f8c32a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2f8c32a1afe9100055e5bc8ad09e0b28286dd6f2)]
   > Issue #13 Superuser concept Tried to grep all places where they introduced the userid "1" only idea. Replaced it whith group "1" only for now. I am not so sure if this concept makes sense at all but we will see.

 * **2015-08-11:** NorHei [[bf08e46](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bf08e46ec394fb726815cfe8616f829587a1b537)]
   > Updated IDNA class, and removed example file to avoid any security issues.

 * **2015-08-11:** NorHei [[af83427](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/af834277816667e4a9a2fdca9ca4853d31cc49c8)]
   > just added a few empty lines and a comment in Initialize

 * **2015-08-09:** cwsoft [[8592302](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/859230271a7f57cba7672b0a71547fae01da5c0b)]
   > Unified MIN_PASSWORD_LEN throughout scripts
     - defined in install/save.php, account/login.php, admin/login/index.php

 * **2015-08-09:** cwsoft [[54790c2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/54790c2900cd163880666d7e6215569dae796a20)]
   > Some more branding in installer
     - updated CHANGELOG
     - moved create_changelog.sh script into tools folder

 * **2015-08-09:** cwsoft [[19c98ca](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/19c98cac276be5ad1a35c307182b2be5ba97e12b)]
   > Removed Twig from modules addon_monitor and wbSeoTool
     - Twig is part of the WBCE core so no need to include Twig in module folders
     - this commit closes Issue [#21]

 * **2015-08-09:** cwsoft [[b62258f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b62258fbefc5e5cd11e2fc043d7890a8b93d8b74)]
   > Updated CHANGELOG.md

 * **2015-08-09:** cwsoft [[dc1d3ee](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dc1d3eef94abf2a4ad3c30462fc5886a25e14a23)]
   > Some more branding for WBCE
     - introduced WBCE_VERSION, WBCE_TAG to avoid side effects with WB-classic
     - some cosmetic changes in the installer script
     - adapted standard themes to show WBCE_VERSION and WBCE_TAG
     - started to rework the upgrade script (some more cleanup needed)

 * **2015-08-06:** cwsoft [[916eddb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/916eddbbc0fc781417d72458b6b36afff2f528ee)]
   > Swapped position of author and SHA1 hash in CHANGELOG

 * **2015-08-06:** cwsoft [[407330f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/407330f5422495aade17d35629aa38c8e4593214)]
   > Updated format of CHANGELOG.md

 * **2015-08-06:** cwsoft [[8decb62](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8decb62c30479316b0dcd4170f77aaa76d7e54c4)]
   > Added bash script to create CHANGELOG.md on the fly
     - changelog needed for GNU GPL compatibility
     - most recent CHANGELOG is always available at WBCE GitHub repository

 * **2015-08-05:** cwsoft [[fa53b1a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fa53b1a5ad40d3c489c9af04659e5d69f54a0e6d)]
   > Fix for commit https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1e7670a062685c59c145779024d098dd57c9ef44
     - modified function searching for first info.php file inside Addon archive
     - Fixes Issue #12

 * **2015-08-05:** Bianka Martinovic [[54153c9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/54153c9cf6cd2581a2bc94df16a876aa22d3cadf)]
   > Fix for issue #20

 * **2015-08-05:** cwsoft [[60f4991](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/60f49912b189ef158a812231de44ac891d19f362)]
   > Updated file comment headers (added missing word)

 * **2015-08-05:** cwsoft [[4da344a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4da344a4b5f1ea2dc9ae1f806fccbf73b63f4540)]
   > Updated README

 * **2015-08-05:** cwsoft [[e3527ad](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e3527ade9306d3f309fae549c9d8c4680a9c815b)]
   > Updated README

 * **2015-08-05:** cwsoft [[1ea1cf0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1ea1cf0b9241051babd9788a4ab58443f659a15b)]
   > Added copyright passage to README and updated file comment headers

 * **2015-08-04:** cwsoft [[1e7670a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1e7670a062685c59c145779024d098dd57c9ef44)]
   > Added support for nested Add-on ZIP archives:
     - modules and templates now support nested ZIP archives
     - fixes issue #12 (adds support for GitHub archives and wrong zipped ones)
     - some basic cleanup (should be further cleaned in a future release)

 * **2015-08-04:** cwsoft [[e2544d7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e2544d76ae34faa2f4d56c3bd0f1498c110115bb)]
   > Prepared WBCE for semantic versioning:
     - set VERSION to 1.0.0 (can be changed with final release)
     - added TAG (referring to GitHub release)
     - updated backend themes to show Version and Tag (no more SP and REV)
     - updated installer and upgrade script to update TAG in DB
     - reworked addon precheck to deal with WBCE_VERSION and WB_VERSION

 * **2015-08-03:** NorHei [[c9680fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c9680fab6caa9f7bd3a9867a795d31ba5ff4c5c7)]
   > Just changed a comment

 * **2015-08-02:** NorHei [[16cbdf1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/16cbdf1181e9bbdc12ec2cb66618b31d2d0a1685)]
   > OK, IDKEY next try, this stuff is a nightmare

 * **2015-08-02:** NorHei [[4ba2cd6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4ba2cd6f36460ca04c51426f164e911430f88892)]
   > Just a small code cleanup , so further editing is more fun

 * **2015-08-02:** NorHei [[818a00d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/818a00d0476818fe76931287100e05d8dbe22c5a)]
   > Needed to remove changes to Secureform as i got some additional issues fixing this and re add it later

 * **2015-08-01:** NorHei [[80758fc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/80758fce06d2dde44bf2c165ed93eb7546811507)]
   > Removing old vars final step

 * **2015-08-01:** NorHei [[a506b19](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a506b19cfa162560133d1d24f64ca014f70a6ffb)]
   > Removing old vars step 4

 * **2015-08-01:** NorHei [[14176d8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/14176d88c25c64f4e2cd69c97c2478e367caebff)]
   > Removing old vars step 3

 * **2015-08-01:** NorHei [[61ad324](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/61ad32438e05d36d2e93dc0819a045c373ea95f4)]
   > Removing old vars step 2

 * **2015-08-01:** NorHei [[ff42e83](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ff42e83faf1f78131932678652594e67c63769c9)]
   > Started removing old language vars

 * **2015-08-01:** NorHei [[377b711](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/377b71158a0add7a5ffddfbbba46e1a5c90e922a)]
   > Removed the unecessary filter that blocks CSS in Droplets. As we have a filter that moves all CSS to the head, its perfectly ok to have some CSS inside of normal droplets.

 * **2015-08-01:** NorHei [[b0bd015](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b0bd01544006942d5e989510cd84fc8f9d39c9e4)]
   > Finally changed the Language files and activated new loading mechanism.
     Changes according to :
     http://forum.wbce.org/viewtopic.php?id=59
     
     No entity conversion yet.

 * **2015-07-30:** Bianka Martinovic [[54dac88](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/54dac8853ec7b3827b72d0dd93657f981b0ae767)]
   > removed "Code2" and "Addon File Editor"

 * **2015-07-29:** NorHei [[930e861](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/930e86109a0cd2fccf688850c0385632f1a22e5a)]
   > deactivated last change completely , asking forum first

 * **2015-07-29:** NorHei [[50cad94](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/50cad94438e202b40f76275eba599b28277df75b)]
   > Undone change include old language file in init, asking first if they like utf8 language files

 * **2015-07-29:** NorHei [[949d418](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/949d4184a96a293fb90312765fd3cdb5f84f8630)]
   > Changed language file loading mechanism
     Now we load the default file (EN) and then we load the
     local(userdefined) file. That way even an incomplete Language file at
     least displays the english text instead of an error message.
     
     Loading of compatibility file now moved to initialize instead of
     doing it in each separated language file.

 * **2015-07-29:** NorHei [[f9b8e79](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f9b8e795cfac3bec12f86e9a0024819ee5a9eabf)]
   > Fixed IDKEY in Secureform
     I still had problems whith Security warnings on pages that used alot of
     IDKEYS. For example if i tried to move a field in the basic form module
     i had one  Warning on in about 15 atempts.
     
     Another Problem: unused old IDKEYS where never deleted so session got
     spammed by idkeys.
     
     So i made a more unique ID, recursive checking for overlapping ids,
     added a tiemout for IDKEYS (same as for FTAN) and added a page parameter
     to instantly delete all keys from a pagecall if one is used.
     
     Last thing i added is an additional parameter that allows the use of
     IDKEYS whith ajax scripts. If this setting is used, the key is not
     removed and can be used multiple times. (this is a small security risk
     but nothing compared to the 1 in 16 chance to guess the key that we had
     before. )
     
     This is a patch from my old fork only tested on my local installation.

 * **2015-07-29:** NorHei [[b5e5ea0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b5e5ea0356480488c20f88891583cdcaa1eb402f)]
   > Added form generator link to miniform
     Testing miniform for first time i found it a little annoying that i had to
     search through the help pages to find the generator . I added a Link and a few language vars.

 * **2015-07-29:** NorHei [[64cc031](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/64cc03133c7db708571b9bffd328d125c3986c53)]
   > Added Date and IP to User interface

 * **2015-07-29:** NorHei [[500efa0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/500efa07a6ae89d94b8e06e87f63311f61be26b3)]
   > Added usermanagement whith search
     http://forum.wbce.org/viewtopic.php?pid=281#p281
     
     Its not a perfect solution as it uses the browser search function as a helper
     but i guess its far better than having no search at all. For Advanced Search it
     relies un the "user search"  module , which is added too.
     (Version 0.41)
     
     http://www.websitebakers.com/pages/admin/admin-tools/user-search.php
     http://forum.websitebaker.org/index.php/topic,13040.0.html

 * **2015-07-29:** NorHei [[58349a7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/58349a731a4863a1a0644d05d2cf0bd0061f4387)]
   > Singletab is no longer recommended

 * **2015-07-29:** cwsoft [[c7d17c3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c7d17c366165482e8c8da4174876d4e7be69b6c8)]
   > Removed Twig from module as part of WBCE
     - Twig is part of WBCE since commit 79aa15e (WBCE/WebsiteBaker_CommunityEdition@79aa15e)
     - updated file comment blocks

 * **2015-07-28:** cwsoft [[79aa15e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/79aa15e876e04ceac424801bad3229eba6d9bd0b)]
   > Added 3rd party template engine Twig to WBCE
     - added Twig 1.18.2 from http://twig.sensiolabs.org/ to WBCE
     - Twig autoloader automatically loaded via /framework/initialize.php

 * **2015-07-28:** cwsoft [[6d1775e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6d1775ec2ce287d6d41aa6ac85ae5739ab1eb59c)]
   > Smaller changes for WBCE branding purposes
     - renamed wb root folder into wbce
     - converted WBCE global instructions to markdown

 * **2015-07-28:** cwsoft [[4b4cdf4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4b4cdf4ca20a7a8ee3a6aaeb39dd10818c97d85e)]
   > Removed obsolete template engine quickSkin
     This template engine was never used by WB or other modules and was replaced by Twig wis a later release.

 * **2015-07-28:** cwsoft [[882fa88](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/882fa884684418b85dc9eab16d54d816c44f906e)]
   > Updated README.md with infos about WBCE

 * **2015-07-28:** cwsoft [[ec3c041](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ec3c04158d70116c75f5adb09285e437c54f6206)]
   > Fixes #10: Incomplete CKEditor
     - replaces CKEditor with package from http://wbce.org/media/modules/ckeditor.zip

 * **2015-07-27:** cwsoft [[40971c6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/40971c6725a96a4ed5721cd390498228863265a7)]
   > Fixes #19
     - replaces addon-file-editor by [wbce-addon-file-editor](https://github.com/cwsoft/wbce-addon-file-editor)

 * **2015-07-27:** Bianka Martinovic [[df714f6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/df714f640be6d137aff0bd40d641e6554b0a3ce0)]
   > Delete google_sitemap_shorturl.php

 * **2015-07-24:** Bianka Martinovic [[11fd4ba](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/11fd4bacfdc39f6eafd586db3d6b911ace7830ac)]
   > fixed database type in installer (#9)

 * **2015-07-23:** Bianka Martinovic [[d05e04d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d05e04d077f6642816e9dcbe9fe77874d2a8c2ae)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_Clone

 * **2015-07-23:** Bianka Martinovic [[b8504fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b8504fa242f7511b55f47068ed4f4986f97d6fea)]
   > auto-rename config.php.new to config.php (untested)

 * **2015-07-21:** Bianka Martinovic [[7589d1b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7589d1b3995b2adb43e75cb688251c95d48895f4)]
   > added ShortURL droplet to the footer; thanks to "nibz"

 * **2015-07-21:** Bianka Martinovic [[ff873b1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ff873b17d4303631dd1d1f2f8c030c3c74077b49)]
   > added ShortURL droplet to the footer; thanks to "nibz"

 * **2015-07-13:** Bianka Martinovic [[a432449](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a432449e4e0892e6576c50c53192a29ac47c5658)]
   > added missing plugins to CKE; there is no full download of CKE 4.4.8 any more (or I've just not found it...)

 * **2015-07-08:** Bianka Martinovic [[5aef959](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5aef959bc3fee9379b52bda721bb2eaf16b36d18)]
   > replaced the favicon.ico

 * **2015-07-08:** Bianka Martinovic [[6b84d78](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6b84d7836d94a04fca943626d03dda2e12169c3c)]
   > changed mtab settings in SecureFormSwitcher/install.php (overwrites settings in install_data.sql) and upgrade-script.php

 * **2015-07-08:** Bianka Martinovic [[5c03f59](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5c03f59e97926598ebcd49bfe86ce4fc2c4f6e51)]
   > fix in class.database.php method get_one() - catch error

 * **2015-07-08:** Bianka Martinovic [[cd96d41](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cd96d4189a7a545f05fb9040fb792c21069b8ba7)]
   > removed ?> from wbstats/info.php as it caused problems after installation; fixed auto-deletion of install folder; fixed droplets/functions.inc.php

 * **2015-07-08:** Bianka Martinovic [[0c0b822](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0c0b822aab2585a0dd8e7fb404389a4584393bbe)]
   > fixed some issues in droplets module, see info.php (new version v1.75)

 * **2015-07-08:** Bianka Martinovic [[5d58fe6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5d58fe66173594b9225e2b00342ca0ca16994909)]
   > renamed .htaccess to htaccess.txt; removed [[Shorturl]] droplet from default settings; added google_sitemap_shorturl.php; added function to remove the installer automatically instead of just nagging

 * **2015-07-07:** Bianka Martinovic [[7e8a9c2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7e8a9c241210eddbf8062fac4d5372a7c58eaf90)]
   > inserted "CE" into some pages; replaced favicon; added German form templates to miniform; changed search default settings

 * **2015-07-07:** Bianka Martinovic [[c7676ec](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c7676ec687a4675ecaa4663013aebb7cd32b1100)]
   > added .htaccess und short.php; removed htaccess.txt and README-FIX

 * **2015-07-07:** Bianka Martinovic [[71c2920](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/71c2920a7cd400e6b017f00a6074ac9a27cae2b0)]
   > added templates "wbce" and "simple_responsive"

 * **2015-07-07:** Bianka Martinovic [[005593d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/005593df54d47ac4a87cf6188ae7e49a58048ac2)]
   > added shorturl droplet to installation; fixed little layout issue in droplets module and created new version 1.74; changed default template to "wbce" in installer

 * **2015-07-07:** Bianka Martinovic [[43c7d04](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/43c7d04a4b81d25e0fa65629d939b20de8f593c7)]
   > added Shorturl patch; removed "code"; added "code2"

 * **2015-07-06:** Bianka Martinovic [[4a8c783](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4a8c78359b1bb1fd3fc5237c2789fdd4e6990466)]
   > added the "Turbo page tree patch" (http://forum.websitebaker.org/index.php/topic,20062.0.html); upgraded pagecloner to v0.60; fixed a problem with ./modules/admin.php (page tree drag & drop sorting not functioning with XSS patch)

 * **2015-07-06:** Bianka Martinovic [[600d311](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/600d31140a8833456773760057cc1ef45855c04a)]
   > upgraded CKE to v4.4.8

 * **2015-07-06:** Bianka Martinovic [[8e37b0e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8e37b0e72f3c58c1010f1f33689f64750abef12a)]
   > added "named sections patch" (http://forum.websitebaker.org/index.php/topic,22746.msg178895.html#msg178895)

 * **2015-07-06:** Bianka Martinovic [[51adaa5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51adaa5f1e5cc5f99dba401f6deb317b69940363)]
   > upgraded "argos_theme" to fix two little bugs described here: http://websitebaker.meerwinck.com/pages/das-backend.php (German)

 * **2015-07-06:** Bianka Martinovic [[8bfc5e8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8bfc5e8687da8966555e5829331391bfcfb46843)]
   > removed module "News"

 * **2015-07-06:** Bianka Martinovic [[c30daa6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c30daa61818ce1f5b4c026a47102ac7208907698)]
   > added "topics" 0.9.0 RC

 * **2015-07-06:** Bianka Martinovic [[83a6610](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/83a661046fec896208a442118a5135f827ebe17a)]
   > updated Flat Theme  to version 0.3.6

 * **2015-07-06:** Bianka Martinovic [[b153488](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b1534887bed528d630604a62140ee7aea7c83ae3)]
   > updated simplepagehead to v0.51: Apple Touch icons and "WBCE" as CMS

 * **2015-07-03:** Bianka Martinovic [[ea5febe](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ea5febed095c8f17fad2c94f3f622e6743cda510)]
   > added "CE" to SP string; changed some default settings in installer

 * **2015-07-03:** Bianka Martinovic [[53f4d11](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/53f4d1160d54f742ec875aaa827656a6ab3f6e17)]
   > added google_sitemap.php

 * **2015-07-03:** Bianka Martinovic [[4266d9f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4266d9f7545e8aac64f140b91293c26494a92928)]
   > added "backup"

 * **2015-07-03:** Bianka Martinovic [[c77840e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c77840e89c270dfb5a2b04d7cadb95784e1293b5)]
   > added "sitemap"

 * **2015-07-03:** Bianka Martinovic [[8d011e3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8d011e30c215351578e5d6c4a8cff8d0987ee025)]
   > added "simplepagehead"

 * **2015-07-03:** Bianka Martinovic [[eb62e7d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/eb62e7dabafeaacdc342c26c83dd488f672fa8de)]
   > added "colorbox"

 * **2015-07-03:** Bianka Martinovic [[21246e9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/21246e9fe3ab64dd258dcdf3ee957993d9fab45c)]
   > added "wbSeoTool"

 * **2015-07-03:** Bianka Martinovic [[83f17f6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/83f17f6c1ac3635837f89789750aed7ebb8d016e)]
   > added "wbstats"

 * **2015-07-03:** Bianka Martinovic [[e21c623](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e21c623ba6f2df38d75386cf748b74fd0fa0653c)]
   > added "advancedThemeWbFlat"

 * **2015-07-03:** Bianka Martinovic [[4810cc5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4810cc5b59526f717c8a2ed2538cc80443448f00)]
   > replaced "form" with "miniform"

 * **2015-07-03:** Bianka Martinovic [[fd60a73](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fd60a73bd222ff497b7efb55cdcba3d8b320b8a2)]
   > added "addon_monitor" module

 * **2015-07-03:** Bianka Martinovic [[6895d2a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6895d2ad13250c353665f79a23ec3aa1cb4243ad)]
   > removed templates / themes "allcss", "round", "simple" and "wb_theme"

 * **2015-07-03:** Bianka Martinovic [[32f8d49](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/32f8d49c62d6e632128296c3d82462aecfeda84a)]
   > added addon-file-editor (removed the "cwsoft" prefix as that vendor does not support the module anymore)

 * **2015-07-03:** Bianka Martinovic [[42978d6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/42978d6f9ba1985cef6677a775c0349fb135c523)]
   > upgraded Droplets to version 1.73

 * **2015-07-02:** Bianka Martinovic [[03be93f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/03be93fc2369efcbc69768551ce5ede07c8ec80d)]
   > another fix for issue #1; it's not really necessary but in my opinion the code is easier to read and understand

 * **2015-07-02:** Bianka Martinovic [[9fe4a75](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9fe4a75161a7ac359e29d9e64d1c65b03778ce36)]
   > fix for issue #1; removed some files; added some SP4 files I forgot

 * **2015-07-02:** Bianka Martinovic [[fa2118b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fa2118bd665f5680f21e0ba36daf1cf64194a5d2)]
   > added ckeditor 4.4.3 140727

 * **2015-07-02:** Bianka Martinovic [[c1a9e20](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c1a9e20a4fd215f4ab38debe5d8ec4614db0aed9)]
   > upgraded to SP4; added empty file to media folder as it is not pushed otherwise

 * **2015-06-29:** Bianka Martinovic [[4157892](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/415789207d533120e19da59631dd4fa738b14210)]
   > WB 2.8.3 SP3 without FCK

 * **2015-06-29:** Bianka Martinovic [[fc40d4e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fc40d4ecde78b0b79e3afce97179b3fb50127ebd)]
   > Initial commit
