# Changelog WebsiteBaker CE
This CHANGELOG was automatically created from a local WBCE Git repository.
The changelog may therefore not be correct or up-to date.
Please visit the [WBCE Github](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commits) repository for the most up to-date version.

## Auto generated Git commit history

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
