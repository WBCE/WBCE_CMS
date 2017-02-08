# Changelog WebsiteBaker CE
This CHANGELOG was automatically created from a local WBCE Git repository.
The changelog may therefore not be correct or up-to date.
Please visit the [WBCE Github](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commits) repository for the most up to-date version.

## Auto generated Git commit history

 * **2017-02-08:** NorHei [[cb9bc05](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cb9bc05068054308551ecd52c2d3ac28c88cc8e1)]
   > Updated short.php whith ruuds Version for better 404 Handling Thanks Ruud

 * **2017-02-08:** cwsoft [[e36ba43](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e36ba43c273ae03400b584132f90286fedaa7515)]
   > Further code refinement for WBCE media center

 * **2017-02-07:** cwsoft [[a90645a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a90645a25d178858cbfb29d6439cc1badb7bbfc0)]
   > Add lnk to forbidden media file extensions

 * **2017-02-07:** cwsoft [[f8bb05c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f8bb05c7710443dfbbd600ff2f9ad1072f446139)]
   > Some code cleanup of the media center

 * **2017-02-07:** cwsoft [[8d8f3a1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8d8f3a1858e0fefd6afa2f614e6349ed743e8a1f)]
   > Some more fixes for the media center

 * **2017-02-06:** cwsoft [[276efb4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/276efb4dd9f4aa2f9ba466e9ca696b6560eaeabb)]
   > Removed obsolete version file in project root folder

 * **2017-02-06:** cwsoft [[8fe1fee](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8fe1fee8932a4f104138d850c8b55a81b1727872)]
   > Fix for XSS problem JVN#53859609

 * **2017-02-02:** cwsoft [[182b17b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/182b17b79800f18a9bccf8a7f9cfabf7f2846209)]
   > Fix for JVN#10983966

 * **2017-02-01:** NorHei [[3b7eadc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3b7eadc96136b77a8fe2a81b9b8173fb23a56bce)]
   > Forgot some debug stuff in short.php

 * **2017-02-01:** NorHei [[d7f275e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d7f275ecdb0ffdcef84661beaa9e96988e11a6e6)]
   > Some more fixes for short.php

 * **2017-01-31:** NorHei [[5e6c28f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5e6c28f5a12bf4a244ee922ac9063a02843553b0)]
   > Deleted comments as they are no longer correct

 * **2017-01-31:** NorHei [[2398767](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/23987673b53bda68e953d537799fdd8f8d74d99c)]
   > one more regex fix

 * **2017-01-31:** NorHei [[d6cad8e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d6cad8eaf346319587f9fd07507e86dfc64387af)]
   > Fix for filter regex in short.php

 * **2017-01-31:** NorHei [[4ea8422](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4ea8422e4449da7998921116d93aaea77e028a31)]
   > Experimental fix for short.php JVN73083905
     Basically this exploit does not Work whith 1.2.x  but to add some additional security
     I added realpath() and a check if we still are inside of the WB directory.
     
     This is experimental , as i have no testserver whith short url.

 * **2017-01-30:** NorHei [[63c2ab4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/63c2ab41531fbad5545bb8881a16d67cfc68820f)]
   > It was possible to generate directories whith html Tags in filename.
     Pretty certainly it was possible too to load files whith such names .

 * **2017-01-30:** NorHei [[07c3cfe](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/07c3cfe1dbabeed0894081a86125fdbaaa1e3463)]
   > Fix for XSS problem JVN#53859609
     The "dir" Parameter was used completely unchecked...

 * **2017-01-27:** NorHei [[b74855f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b74855f448544a0814bb05c0e1d1215d2fcc603c)]
   > Forgot some debugging in last commit

 * **2017-01-27:** NorHei [[75cca40](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/75cca401989381bf12cb8b40643672bc10f0a609)]
   > class Settings . Not showing changes immediately in Settings::Get()
     Fixed now
     
     there was some strtoupper/strtolower confusion.

 * **2017-01-25:** Martin Hecht [[8a311f0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8a311f00a3ed6804d87f5b5b82430de9561b86e3)]
   > opf: immediately show correct active state and reflect settings in opf_filter_is_active

 * **2017-01-25:** NorHei [[30729a2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/30729a2234bc98d175264aeb4045594c26aed309)]
   > Custom Session handler fix for PHP7
     Under PHP 7:
     Warning: session_write_close(): Session callback expects true/false
     return value in /var/www/web913/html/eso/w0125/framework/classes/dbsession.php
     on line 62
     
     Solution is to always return true , but i am not happy wwhith that.
     Still i have no other solution.
     
     for more Details have a look at
     http://stackoverflow.com/questions/34117651/
     php7-symfony-2-8-failed-to-write-session-data
     
     https://github.com/snc/SncRedisBundle/blob/master/Session/Storage/
     Handler/RedisSessionHandler.php

 * **2017-01-25:** NorHei [[992a39a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/992a39ad4fcec2db3cc8f0008488a5a36cfc8425)]
   > Removed Secure Form Switcher , added Security settings

 * **2017-01-25:** NorHei [[14397f4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/14397f439e5c31d68d791cb71815c94dcbc07b74)]
   > Custom Session Handler now completely ignores PHP settings
     Internal timeout always overrides PHP settings.

 * **2017-01-25:** NorHei [[13b8716](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/13b87167c5f6f99e3afc0a7909675674f8a428f5)]
   > Tied CSRF tokeens to session , so they have no seperate timeout .
     Now hopefully we cann make a session timer...

 * **2017-01-25:** NorHei [[0e3efcc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0e3efcc88e1bef821e31b8e9e29cba2c4fe22e38)]
   > Added Class for halfway crypto proof generaion of Integers and TextToken

 * **2017-01-25:** NorHei [[b789c46](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b789c4685e6401d0167e5096e1a0c6f17369edde)]
   > DB based session handling added
     Filebased Default sessions encountered a Lot of Problems on different
     shared hostings. Shared Temp directories caused GCs from other clients
     clearing our sessions to early. Cron Scripts on some Debian derivates
     killing sessions after 24 Minutes ignoring all Settings.
     
     Another options we now have are:
     List active sessions.
     List Users whith active sessions.
     Disconnect Users.
     
     Still we need to build an Interface around this.

 * **2017-01-25:** NorHei [[f763525](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f763525afe3b2b6b62659c1ed24587d5dd263f7b)]
   > Default Table Engine now InnoDb. Some preps for DB based Sessions
     As i work whith tons of Tables on a dayly Basis , i can state there is no real
     option than using InnoDb.
     
     Some preparations for DB based Sessions.

 * **2017-01-24:** NorHei [[ff50c77](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ff50c77ffa1f3fb811724e8a0a399bf0f53db962)]
   > Added Marker , to know when upgrade Script is running .

 * **2017-01-23:** Martin Hecht [[fdb22d3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fdb22d3b9248f036acf1b75f52c810a0c9b44bc9)]
   > opf: remove redundant function calls and add meaningful description to filters

 * **2017-01-22:** Martin Hecht [[9c49f0e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9c49f0e3ebf0604f0c4b95a58572b7d0122dbad5)]
   > Merge pull request #213 from mrbaseman/opf
     opf: fix warning about global variable (#211)
 * **2017-01-22:** Martin Hecht [[b056a6d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b056a6d64e9ba4dbf96d858c8254ef0916d3bae2)]
   > opf: fix warning about global variable when switching filter active/inactive

 * **2017-01-22:** NorHei [[8acaaee](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8acaaee4251dc73f781a0cdb38256e22f469fb4f)]
   > Merge pull request #210 from mrbaseman/opf
     opf and filter modules: fix active/inactive
 * **2017-01-22:** Martin Hecht [[4e8a961](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4e8a961c0f7249fb110a2c1ee3d8be61fc21c6ed)]
   > opf and filter modules: fix active/inactive

 * **2017-01-21:** NorHei [[e9c4415](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e9c4415748de6af82244324f83d90ad78fff2a0f)]
   > CKE editor Fix (mysql_real_excape_string, require->require_once)
     I did not remove WB Classic compatibility.
     
     https://forum.wbce.org/viewtopic.php?pid=8295#p8295
     
     The code used mysql_real_escape_string() that will fail inf the server has no
     mysql module installed.
     
     Many servers only support mysqli and PDO.
     
     Another issue is that WBCE has an autoloader so all core classes are available
     as soon as you required config.php. If you try to require class admin that is
     already loaded at this point the code may fail because of double declaration.
     So please use require _once.

 * **2017-01-21:** NorHei [[70604ad](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/70604ad1deca5818d0444b06ef2a101202803697)]
   > Some fixes for theme.css

 * **2017-01-21:** NorHei [[0106646](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/01066462d2ba9c727a31a61a44deae8166735d3f)]
   > Page Language should not be activated by default
     Because if so all vistors from other countrys than your's will only see "UNder Construction"

 * **2017-01-21:** NorHei [[a1724f6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a1724f6ad9a1ff835828b76e46d6f9c542441205)]
   > Installer tried to install Droplets and OPF 2 Times

 * **2017-01-21:** NorHei [[7445f51](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7445f51758ca01793d97f906866cf376f22f370e)]
   > Copy paste error in installer

 * **2017-01-21:** NorHei [[f0d6135](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f0d6135a7a6b322883a3ab32c110262fed0374f7)]
   > New Constant WB_INSTALLER. small mods on OPF install procedure.
     Constant only set if install script is running.

 * **2017-01-21:** NorHei [[0d54d35](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0d54d351e85fe8ba6f6bfeb0bd82d3dace01fca5)]
   > Install error , trying to install droplets bevore droplets are installed.
     Error: e27a: /topics : Table '{TABLE}.{PREFIX}mod_droplets' doesn't exist
     
     {TABLE}, {PREFIX} = correct values
     
     Well, of course it does not exist, I'm trying to install a new instance... -.-
     
     I always thought this error was dorplets .. thanks to new debug , we now know thats Topics .. Trying to install a droplet bevore Droplets are installed ... :-)
     We have this as a duplicate somewhere here :-)
     
     Depending on the order of the files in the filesystem , this error may appear , or may not .
     On second run the droplets tables are installed , and topics runs through. :-)

 * **2017-01-21:** NorHei [[f7c87b0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f7c87b0f002690ab54d320a9e404ced448137f26)]
   > Bumped Version to 1.2.0-alpha.10

 * **2017-01-21:** NorHei [[575e26a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/575e26aad4be5513f37266909fa2c9516cc358fc)]
   > Some small fixes to OPF
     Error: e26b: /outputfilter_dashboard : You have an error in your SQL syntax;
      check the manual that corresponds to your MySQL server version for the right
     syntax to use near '`wbce000mod_output_filter`' at line 1
     
     Small correction on Mailfilter Installer
     Removed a few Lines from main installer , for more easy debugging on install process.
     The buildin Dashboard does not need to load the filter on itself
     
     Removed tool from mailfilter into.php, as ist not needed.

 * **2017-01-21:** NorHei [[d14799d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d14799d03e30f96379850b84f9c82f5ed07b5628)]
   > Merge pull request #204 from mrbaseman/opf
     rename mod_opf_relurl and update opf
 * **2017-01-20:** Martin Hecht [[f15abff](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f15abff6a93c50844b9dc82cd018b870ac23d625)]
   > check classical opf settings table for existence

 * **2017-01-20:** Martin Hecht [[c95d700](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c95d7009794abe23b2d6281587862fe9703d94b4)]
   > rename mod_opf_relurl and update opf

 * **2017-01-20:** NorHei [[3da84a9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3da84a98a54a6c6fa4cab51b3dd924064fd145cb)]
   > Enhanced Debugging in installer
     If modules fail cause of DB errors , installer now states what module failed.

 * **2017-01-20:** NorHei [[cf70ff3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cf70ff3e9faa13ed3f40508538359cbdfc229042)]
   > Fixed Small droplet bug, Dashboard now installs first on installer run.
     So Module filters can register after Dashboard installed.

 * **2017-01-20:** NorHei [[6020be3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6020be30c32acf5b40d433df8faf6df3d5af8beb)]
   > Topics Upgrade script now upgrades to Innodb too
     Only new tables , old ones are not changed yet

 * **2017-01-20:** NorHei [[09176bf](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/09176bfd084a489261bd8bd2a51f079c3b4ddbfb)]
   > Topics installer now uses Innodb and correct collation

 * **2017-01-20:** NorHei [[f4f2be4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f4f2be49b42f78247460b500868f3f53b917ab80)]
   > Removed Opf Simple backend  as its no longer needed

 * **2017-01-20:** NorHei [[b7fb81f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b7fb81f727b455a5c2951ecd2f5da8a170ca9a38)]
   > Merge branch '1.2.x' of https://github.com/WBCE/WebsiteBaker_CommunityEdition into 1.2.x

 * **2017-01-20:** NorHei [[274ce59](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/274ce59f5638f73fb6205d41e5e0ef6a6cbbdde5)]
   > New (default) templates for WBCE

 * **2017-01-20:** NorHei [[79dca0b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/79dca0b0fc21c664139b1f9374dcdba8a56bcf1a)]
   > On upgrade reloading of Languages was deactivated

 * **2017-01-19:** NorHei [[61b3a7c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/61b3a7c5469ad7fdc7c8409b9f5280729346f209)]
   > Small Changes for better compatibility whith OPf Dashboard .

 * **2017-01-19:** NorHei [[86be56c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/86be56c2e1207d48a413dbd18dee82340b276579)]
   > Update theme CSS to remove double Admintool icons
     To all !!! Please pull before commiting !!!
 * **2017-01-19:** NorHei [[ccbee73](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ccbee73e4b6051e360086f25abca4cd549db8a1e)]
   > Even more topics refixes .
     Have a look at
     
     https://github.com/WBCE/WebsiteBaker_CommunityEdition/commits/1.2.x/wbce/modules/topics
     
     for an overview.

 * **2017-01-19:** NorHei [[6382d5b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6382d5b04090c25926c1877c2c04ced74d8a4f7e)]
   > More Topics re fixes
     Lots of requre that better be require_once

 * **2017-01-19:** NorHei [[abd0374](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/abd0374bd82a62cc7250b760f55f1e548811de78)]
   > Re fixed Topics
     See
     8a5bafe646de0e56eddc922c0650f82e5b7aa095
     
     On instalations where mysql drivers no longer present this causes lots of errors.

 * **2017-01-19:** NorHei [[f5cb89c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f5cb89cfb326d2c429e129f40de1aaf5b625323e)]
   > Removed some Changes for a Pagetree that will never come

 * **2017-01-19:** NorHei [[ba1a9eb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ba1a9eb966c90a6f849a7b4dcc14140a1df573d7)]
   > Merge pull request #203 from mrbaseman/opf
     more opf fixes...
 * **2017-01-19:** Martin Hecht [[79931ae](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/79931ae274f35683eea7ea977aeebb45b59bd854)]
   > Merge branch '1.2.x' into opf

 * **2017-01-19:** Martin Hecht [[eed047c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/eed047ce96ea203d4d50d2736528dccb223c0ad5)]
   > more opf fixes...

 * **2017-01-18:** NorHei [[03d7ed3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/03d7ed3f35a65012af80c86ae942ebe45806a0f1)]
   > push push ,github not diplaying last push

 * **2017-01-18:** NorHei [[9ea8399](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9ea8399f8d203a7e8782fb76cb8a0b2e1dd96465)]
   > OPF install bug  Topics upgrade bug
     Warning prevented installation and was irritating on upgrade
     Martin plase have a look.
     
     Warning: hello opf_is_registered(): Filter not registred in /var/www/web207/html/wbce12x_norbert-heimsath_de/modules/outputfilter_dashboard/functions.php on line 673
     
     Topics , same as in  Install
     
     Notice in line 160 , @import was undefined

 * **2017-01-18:** NorHei [[8e5b043](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8e5b0435fb05fde6839ee68c8bdb328743f3e71e)]
   > Topics installer bug
     Notice: Undefined variable: imports in /.../modules/topics/install.php on line 304

 * **2017-01-18:** NorHei [[d301c4a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d301c4afe2088babb813935237bb45edccb8522c)]
   > Merge pull request #191 from mrbaseman/opf
     fixes for OpF Dashboard and filter modules
 * **2017-01-17:** Martin Hecht [[62a04ad](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/62a04ad1b16605f2a1daacdd52b88d8e62b3ceb6)]
   > fixes for OpF Dashboard and filter modules

 * **2017-01-17:** NorHei [[65ee1cc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/65ee1cce05a88c6b4dce126cd7fe6c4f4e07ef9b)]
   > testcommit

 * **2017-01-17:** NorHei [[b6a88e8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b6a88e8a7183b59a8a135d4d25da7f37993db83b)]
   > Just some more typos in OPF modules

 * **2017-01-17:** NorHei [[24bbfb3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/24bbfb3618435f6898c4f8ccaf97c7446ed18a40)]
   > Typo in function name module mod_opf_insert

 * **2017-01-16:** NorHei [[3575091](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/35750916ae14336be8fb3140b623d7809befa157)]
   > Upgrade topics to 0.9.2

 * **2017-01-16:** NorHei [[1ebbe43](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1ebbe439bfb8c0c4be2b042eb2f7006742060007)]
   > First TRy on multi installer script , to allow installation of multiple  modules at once
     Just put all module dirs into one Zipfile .

 * **2017-01-16:** NorHei [[30f9ef6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/30f9ef640ccf7d630d746534b92394557e6fd8f5)]
   > Merge pull request #185 from WBCE/master
     merge back 
 * **2017-01-16:** NorHei [[aef5ba9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/aef5ba983595167821a039dec026d1f3fdcb819e)]
   > Merge pull request #184 from WBCE/1.2.x
     1.2.x to master
 * **2017-01-16:** NorHei [[d69d2a4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d69d2a40d323f3239f4f19603e83186e49a5b65d)]
   > Merge pull request #182 from mrbaseman/opf
     add OpF Dashboard as replacement for classical output filter
 * **2017-01-15:** NorHei [[42eb6bc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/42eb6bcc2958671f58ca63d73929c7eb57929339)]
   > Added {TP}=={TABLE_PREFIX} for sql import MEthod

 * **2017-01-15:** NorHei [[819c0b0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/819c0b0e7922dbee2e83c2ea3118f4bb6ff6433c)]
   > Merge branch '1.2.x' of https://github.com/WBCE/WebsiteBaker_CommunityEdition into 1.2.x

 * **2017-01-15:** NorHei [[8a5f86c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8a5f86cfc3ba69dca877cab81bc0e3b2e5324146)]
   > First Try on fixing Droplets install error

 * **2017-01-15:** NorHei [[8ef8ca9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8ef8ca956b7a14ec4c70cfe82d1c8068af63b506)]
   > Merge pull request #183 from WebDesignWorx/1.2.x
     Set correct FontAwesome URL in "Flat Theme" theme.css
 * **2017-01-14:** WebDesignWorx [[fe9e3ec](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fe9e3ec49b4af8493da522fdd1b396880035c8d3)]
   > Set correct FontAwesome URL in "Flat" Theme (position changed after this commit: https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/291c0e9e809b1028680a99fef4c661b7245dff1e)

 * **2017-01-11:** Martin Hecht [[72297b8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/72297b8d32eaa59a8ae404a6458b61d570b3a130)]
   > add OpF Dashboard as replacement for classical output filter

 * **2017-01-09:** NorHei [[0e08574](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0e08574473dbefab8610154ba3ce8a2bcd76f445)]
   > Merge branch '1.2.x' of https://github.com/WBCE/WebsiteBaker_CommunityEdition into 1.2.x

 * **2017-01-09:** NorHei [[84cc7cb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/84cc7cb99774c6c6c601ec886c652b6214536d99)]
   > Update ckeditor to 4.5.11
     Many thanks to colinax!!

 * **2017-01-08:** NorHei [[614a629](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/614a6290f2bebb9eb29bda89179411f23ab7f287)]
   > Merge pull request #177 from WebDesignWorx/1.2.x
     Allow the content area of advancedThemeWbFlat to be responsive.
 * **2017-01-07:** Stefek [[997515b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/997515b45dfea22b4ab5492245320a9a8f2c316f)]
   > Allow the content area of advancedThemeWbFlat to be responsive. These changes were overwritten incidentally by my last commit.

 * **2017-01-07:** NorHei [[828d57b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/828d57b6a74204cdabd550a4aa8035713c95810b)]
   > Small issue in Class tool whith  $module_title
     Comments on  b80498e
     
     $module_title Variable doesn't exist.
     Please change back to $module_name as it breaks the output with a
     
     Notice: Undefined variable: module_title in [.....]framework\classes\class.tool.php on line 268

 * **2017-01-07:** NorHei [[cf6e737](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cf6e737bc88cc0dc723c97876dca8dba066d5777)]
   > Merge pull request #175 from WebDesignWorx/1.2.x
     1.2.x
 * **2017-01-07:** NorHei [[b479ec1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b479ec13b348a1c143a4bc4766e3e4a7008e5f02)]
   > Merge pull request #176 from WBCE/1.2.x
     1.2.x merge to master 
 * **2017-01-06:** Stefek [[3f487bb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3f487bbeec4c3b51b840b248f328e22227fed832)]
   > consecutive changes to previous commit; concerns mainly FireFox issue handling + FontAwesome replacements

 * **2017-01-06:** Stefek [[ab72107](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ab72107387da6102faa0519b51c56889a509996f)]
   > add additional changes to ArgosTheme concerning previous commit

 * **2017-01-06:** Mareike [[51faeeb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51faeebf3dc8ec9792f5e46151f1c995bf3f21e7)]
   > Revert "add additional changes to ArgosTheme concerning previous commit"
     This reverts commit fd7b7c881d0fbe91d4a345fda19090017805a764.
 * **2017-01-06:** Stefek [[fd7b7c8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fd7b7c881d0fbe91d4a345fda19090017805a764)]
   > add additional changes to ArgosTheme concerning previous commit

 * **2017-01-06:** Stefek [[2ba8d35](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2ba8d35465ceeafaddb6f6e7bc7c84106e650713)]
   > add more files allowing for consistent layouting of forms and buttons across Backend Themes. This commit intoduces overrides to the previously commited global files + overrides for the pageTree

 * **2017-01-06:** Stefek [[4c31d5a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4c31d5aa89d3dafa51301ef9a3c7b2203b21c59d)]
   > add 2 global files for use in BackendThemes allowing for consistent layouting of forms and buttons across Backend Themes

 * **2017-01-02:** NorHei [[811d722](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/811d7225a557f75c318a4ff8c6d76afafdde0e87)]
   > copy error sm2

 * **2017-01-01:** NorHei [[fa1fee1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fa1fee14238b88295615f51270692dfe1e79c5d0)]
   > Merge pull request #170 from WebDesignWorx/1.2.x
     Make content area of theme flat responsive and a few bug fixed . 
     Thanks to stefanek !!! 
 * **2016-12-31:** Mareike [[39d2503](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/39d25035947887171c7408add987796f532820b7)]
   > handled wrong index href  instead of src on line 694

 * **2016-12-31:** Stefek [[eed7a06](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/eed7a06e07b875805843940c0228500e96f7a18d)]
   > handled wrong index scr instead of src, causing the AddJs method to break.

 * **2016-12-31:** Stefek [[3e5db60](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3e5db60530f1724bae6aaabc63ca51ee54bbf792)]
   > Allow the content area of advancedThemeWbFlat to be responsive. This is needed along with further commits.

 * **2016-12-30:** NorHei [[c1c8855](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c1c885589bf2b6d91a29015f809c1805e9ad967c)]
   > More SQL cleanup in class frontend

 * **2016-12-30:** NorHei [[a522003](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a52200360c81afe25a7812df44d9b9279353b77a)]
   > Just some SQL cleanup in class frontend

 * **2016-12-30:** NorHei [[8ea7983](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8ea79833551e3772aa3b36e0985ff3d27c516ee8)]
   > Merge branch '1.2.x' of https://github.com/WBCE/WebsiteBaker_CommunityEdition into 1.2.x

 * **2016-12-30:** NorHei [[15735bd](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/15735bd39fa8660820a0fa794bb551ddb23db05c)]
   > Tried a Fix for an old show-menu2 Problem.
     https://forum.wbce.org/viewtopic.php?id=859

 * **2016-12-30:** NorHei [[516863f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/516863fc6671e9fa3e713430625e9a5d15212a40)]
   > Merge pull request #169 from WebDesignWorx/1.2.x
     1.2.x
     
     Notice: Use of undefined constant WB_PATH - assumed 'WB_PATH' in H:\WbPortable\root\wbce_dev\WebDesignWorx\WebsiteBaker_CommunityEdition\wbce\framework\classes\class.autoload.php on line 234
 * **2016-12-29:** NorHei [[d3ddda5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d3ddda52a867c608fb25809156965777cb5208e2)]
   > Very minor fixes to SM2

 * **2016-12-29:** Mareike [[c27f89b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c27f89bb19eced77e81dd8eb6347b26487e51ff2)]
   > Revert "establish connection from local IDE to GitHub"
     This reverts commit 33b768888d7def996c605b2e6dbe31c692caf50a.
 * **2016-12-28:** NorHei [[014b60c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/014b60cbb5acd0730f9e172b041ec8da990791eb)]
   > WB_FRONTEND was already defined
     added it double

 * **2016-12-28:** NorHei [[a0961b3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a0961b3e32e9c773a4cff64e948c19bca6c55968)]
   > Moved get_page_permission() from Class Admin to class Wb
     As Admin Extends WB , there schould be noch problems at all.
     
     get_page_permission() was needed in the frontend too so i
     moved it. (was needed by loginbox)

 * **2016-12-28:** NorHei [[d011c10](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d011c1008f1e449636c5347095806060cc7714d9)]
   > PHP Mailer needed another patch
     https://forum.wbce.org/viewtopic.php?id=857

 * **2016-12-27:** NorHei [[43d18bc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/43d18bccb3b2e28a259706cb468cba1fcadcf3de)]
   > Security Fix for PHP Mailer
     Updated PHP Mailer version , as it fixes a critical bug.
     
     http://www.golem.de/news/websicherheit-phpmailer-bringt-eine-boese-weihnachtsueberraschung-1612-125255.html
     
     https://github.com/PHPMailer/PHPMailer/releases

 * **2016-12-26:** NorHei [[291c0e9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/291c0e9e809b1028680a99fef4c661b7245dff1e)]
   > Removed Font Awesome from Flat Theme
     Thamks to WebDesignWorx

 * **2016-12-26:** NorHei [[c8333b0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c8333b0886e076b559b3b080e0c74fdbb8fab836)]
   > ADDED KONSTANT WB_FRONTEND
     Plus som real minor changes (formatting ... )

 * **2016-12-26:** NorHei [[5fd58fc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5fd58fcafec04c2ad8b22bc0ef94151ca4afcd57)]
   > Fixed Breadcrumb in Admintoools
     Tanks to Florian

 * **2016-12-25:** Stefek [[76e749b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/76e749b2a8befc27207fcc8f648633adb6bba740)]
   > define WB_PATH in framework/initialize.php

 * **2016-12-22:** NorHei [[d3e3581](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d3e35816d8dc0385b8a716c9b795509599a3c71b)]
   > Admin tools can be hidden now , like setting tools

 * **2016-12-21:** Stefek [[33b7688](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/33b768888d7def996c605b2e6dbe31c692caf50a)]
   > establish connection from local IDE to GitHub

 * **2016-12-19:** NorHei [[415767e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/415767e992eeb614a490e05912bd968d0dd94a76)]
   > Direct output pipe for all situations
     I extended class wb to offer a direct output pipe for modules/templates
     and even for the core. This is especial usefulll for Javascript ajax requests
     and JSON replies.
     
     If you call
     
     $admin->DirectOutput("ANTON AUS TIROL");
     or in frontend
     $wb->DirectOutput("ANTON AUS TIROL");
     
     It will output only "ANTON AUS TIROL" and  stopp script execution immediately.
     
     If you just set
     
     $admin->sDirectOutput="";
     or in frontend
     $wb->sDirectOutput="";
     
     It will runn through full Page generation but still "ANTON AUS TIROL"
     Will be the only answer given back as page.
     
     Replace ANTON AUS TIROL  whith you favourite JSON or XLM answer.

 * **2016-12-18:** NorHei [[2e15105](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2e151053eab406b60b4330b888563bfa86159d32)]
   > Invalid Level Display in Page Cloner
     https://forum.wbce.org/viewtopic.php?id=410
     
     Fixed
     Solution was given in thread, thanks to Bernd

 * **2016-12-18:** NorHei [[099042d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/099042da35a13189a8790b5bb3c5931d2d34a4f4)]
   > Fixed Maintainance Mode as Setting
     Maintainance Mode did not save setting.

 * **2016-12-18:** NorHei [[77122a1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/77122a1bf22526f882178e1d4f6ae1cd8dfd9994)]
   > Revert "Removed Admin tool for maintainance Mode, now using setting."
     This reverts commit 740d33a710a03c621396fa1ea759f1ce2345cb23.

 * **2016-12-18:** NorHei [[b80498e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b80498efb1166a72fa5e67c762e5f216f4efe7e9)]
   > Fixed Link generation for breadcrumb on class tool (Partial)
     This is partial cause the flat theme  somehow compeltely kills the Link.

 * **2016-12-18:** NorHei [[f2f8dee](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f2f8deeee570dbbaf86d44a64646c66586f6d709)]
   > Small language file change on settings_default

 * **2016-12-18:** NorHei [[0bbc317](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0bbc31775199e22f966a3b9df74bb072be104576)]
   > Some more corrections for REDIRECT_TIMER
     Defaults to 500

 * **2016-12-18:** NorHei [[c9a5f74](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c9a5f74965e15026cc5969fdaff1e2739749c4f6)]
   > WYSIWYG_STYLE commented out in settings as it is not used at all

 * **2016-12-18:** NorHei [[fbc99c4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fbc99c449a4aaf65b4cebe04677f41adf9200256)]
   > Redirect timer  to 500ms default

 * **2016-12-18:** NorHei [[28ca7fb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/28ca7fb8f83cb15754ae1404b3e97bfd33a38367)]
   > Upgrade script stoped cause of missing DB method.
     https://forum.wbce.org/viewtopic.php?pid=7591#p7591

 * **2016-12-13:** NorHei [[9727e2e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9727e2ed89402b8bcf3988dbb370393e4165667a)]
   > Small bugfix in frontend functions
     Thx to Mr Tenschert

 * **2016-12-10:** NorHei [[a05fe9b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a05fe9b30aec2118ef74c6308b14e8b0ab6af743)]
   > Forgot to update the System Templates on last Change

 * **2016-12-10:** NorHei [[4f8ab13](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4f8ab13ea477023ecbd852905a18c1ae570199e8)]
   > Added WbSearchBox class
     I was extremely tired of the complicated ways i had to use to display loginboxes and seachboxes
         in WBCE templates. there where a few snippets for this but they where not templateable at all,
         depended on TWIG , did not match all my needs....
     
         So here we got a new Searchbox whith enough Divs to get a decent styling, an option to override
         the whole design in your Template , options to override almost everything and you may fetch the
         result instead of echo it immediately.
     
         The Basic call usually looks like this:
         ````
             WbSearchBox::Show();
         ````
         You may supply an options array to this Static method.
         ````
             WbSearchBox::Show(array(
                 "sHeadline"   => "Search here:",
                 "sSendText"   => "GO"
                 "sFormMethod" => "post"
             ));
         ````
         Or you may use the OOP variant:
         ````
             $SB= new WbSearchBox()
             $SB->sHeadline    = "Search here:",
             $SB->sSendText    =  "GO"
             $SB->sFormMethod  = "post"
             $SB->Display();
         ````
         The Display Method allows for returning the value instead of echo it directly
         ````
             $sMySearchBoxHtml=$SB->Display(false);
         ````
         In addition it is possible to hand an options array directly to the constructor
         ````
             $aOptions= array(
                 "sHeadline"   => "Search here:",
                 "sSendText"   => "GO"
                 "sFormMethod" => "post"
             ));
     
             $SB= new WbSearchBox($aOptions)
             $SB->Display();
         ````
     
         __Templates__
     
         The Template for this Class is stored in: @n
             /templates/systemplates/searchbox.tpl.php
     
         To override it whith your own create the folder /systemplates/ in your template folder and copy the file
         "searchbox.tpl.php" from the default folder to your folder. Change the file to match your needs.
         The class will override the system file automatic.

 * **2016-12-10:** NorHei [[6a5d9d8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6a5d9d83419ddfd14870957a69acd46fb71bc403)]
   > Added a few helpfull functions wb_redirect() and wb_dump()
     The first is an enhanced redirect using javascript or
     meta refresh if basic redirect fails.
     
     The seccond is a convienient dump function for easy var checks.
     (no more echo "<pre>" ....)

 * **2016-12-09:** NorHei [[ac96acd](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ac96acd0010e83f875a6e244dcc2858b71d7a5a5)]
   > Templating: Page blocks and menus can be called by name now
     info.php
     ````
     // VARIABLES FOR ADDITIONAL MENUES AND BLOCKS
     $menu[1]       = 'Main';
     $menu[2]       = 'Top';
     $menu[99]       = 'Hidden';  // for hidden Pages , sometimes using the hidden page setting isn't enough
     // $menu[100]       = 'Hidden2'; //if you need multiple hidden menus , please continue counting from 99 up
     
     $block[1]      = 'Main';
     $block[2]      = 'Left';
     $block[99]     = 'Hidden'; // for hidden Blocks , sometimes blocks need to be hidden.
     // $block[100]     = 'Hidden2'; // if you need multiple hidden blocks , please continue counting from 99 up
     
     ````
     You now can call :
     
     `````
     show_menu2("Main");
     show_menu2("Top");
     
     <?php page_content("Left");
     <?php page_content("Main");
     
     `````
     
     Now we can more easyly define default blocks for templates

 * **2016-12-09:** NorHei [[5407343](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5407343e8b98c435a89aa9554dab0d07222e3174)]
   > Merge remote-tracking branch 'origin/1.2.x'

 * **2016-12-09:** NorHei [[6fd9fcc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6fd9fcca4e82c6aac25671cb3d428ef036824fa1)]
   > Merged whith 1.2.x Branch
     Replaced by 1.2.x to be more precise

 * **2016-12-08:** NorHei [[cd01cff](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cd01cfffc62b2c65dddc2969d7ff7a6b96c9c21d)]
   > Fix for  incorrect checks on frontend signup
     https://forum.wbce.org/viewtopic.php?id=812
     https://forum.wbce.org/viewtopic.php?pid=7287#p7287

 * **2016-12-07:** NorHei [[ab587a1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ab587a12c6d956148a067f15c5dbb2c27b046485)]
   > Disturbing error codes can be deactivated in installer
     For Stable releases you can deactivate the error codes by setting
     WB_DEBUG to false.
     
     Errorcodes are passed through the d() function, so they are only
     displayed when WB_DEBUG is true.

 * **2016-12-07:** NorHei [[f022635](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f022635ce611769bfc60b6b71a13ec875dd91b9b)]
   > Installer now displays WBCE version

 * **2016-12-07:** NorHei [[bfe745a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bfe745a64b09a7361bd209c0123ab58de48e5f50)]
   > Fixed another error on Save , Review step 3

 * **2016-12-07:** NorHei [[6d498c6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6d498c66935389950d192f93de7b52be41148708)]
   > Bumped Version

 * **2016-12-07:** NorHei [[b974280](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b974280480e5ea4ec3616c22e6d8b50f7bb72039)]
   > Moved alot of styles to stylesheet , removed long deactivated setting.
     Moved alot of styles to stylesheet, but nor all so far.
     
     Removed long deactivated "install tables" setting.
     
     Installing whitout tables makes no sense at all

 * **2016-12-06:** NorHei [[5970a3f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5970a3f710e79fadfd8f0bcb7eb66a586ed27f5b)]
   > Installer  Review part 2
     Moved some css to css file
     
     Removed a lot of Code from the Template .
     
     Added it as structured code to the installer script.
     
     Cleaaning up is a really ugly work ...:-(

 * **2016-12-06:** NorHei [[235764d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/235764db3c41f31443be25489b2006a4af97fc19)]
   > Installer Review,  Template file, more functions moved to external File.
     This installer is a real nightmare.
     
     The first review only does some cleanup , sorts out files ,
     seperates the Template from the Installer script, removes
     redundant stuff ...
     
     This now is only halfway done as i run outof time , but the installer
     schould still be fullly functional... hopefully...
     
     Still got to Remove a lot of spagetty code froom the template :-(
     
     Now i Know why noone wanted to do this ..

 * **2016-12-06:** NorHei [[8d05c7c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8d05c7cccb5ec3e9f336be5715015d040104e6ea)]
   > Continued on reviwing the installer
     Sorted Out error handling a bit more ...

 * **2016-12-06:** NorHei [[ed70ab3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ed70ab3690998f4bf2fe2b31b17007995728b970)]
   > Installer , better handling of whitespace chars, removed functions from save file.
     Installer now detects an empty field if there are only whitespace
     chars in the field.
     
     Moved the functions in the Save.php file to a seperate file.
     This is part of installer review.

 * **2016-12-05:** NorHei [[c89b475](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c89b4759fd9c24c50383504ef7169e292ff99887)]
   > Installer fix , not catching all errors
     As it says , after modyfying Error Messages to be collected and displayed
     all together i forgot some lines to catch other errors than for errors.
     
     Fixed now .

 * **2016-11-30:** NorHei [[329d1b2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/329d1b2f0270d405c871e5bf6f17f4411948c809)]
   > Added Flags to language folder

 * **2016-11-30:** NorHei [[2a3c8a8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2a3c8a8364277a3ef5768bc46200d1a51f6bc31b)]
   > Small Fix  for copy paste error in autoloader

 * **2016-11-29:** NorHei [[ba5fba9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ba5fba9a9300c47433b3c3e237e711c9f7db330c)]
   > Possibly re-added  field "group_id" on Upgrade script
     Upgrade testst from even 2.7 ran pretty smooth exept htat this
     field was missing. So i decided to take care of importing
     very old installations and add this field in upgrade script.

 * **2016-11-29:** NorHei [[0645845](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/06458451fe3435e4d8c90ce9e13535c402fd1ea0)]
   > Added autodetection for direct path in class WbAuto
     Now in most cases the direct path parameter is no longer needed.
     Thanks to Christian

 * **2016-11-20:** NorHei [[5d52391](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5d52391cd297a558b9fff4b1a4eb8419a122ec6f)]
   > Bugfix for Insert::DelJS() und DelCSS
     Copy paste error

 * **2016-11-19:** NorHei [[15c44d1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/15c44d1cc1e9b656f230f77386276870d75f9b28)]
   > page_content() now has the option to return its content ~~~~~~~~ page_content($block = 1, $echo=true) ~~~~~~~~
     If you set echo to false , it will retunrn its content
     as a returnvalue.

 * **2016-11-19:** NorHei [[c5acd73](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c5acd7342f662260c130edf14af550dd85e98869)]
   > Merge branch '1.2.x' of https://github.com/WBCE/WebsiteBaker_CommunityEdition into 1.2.x

 * **2016-11-19:** NorHei [[acec973](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/acec97337687bd3c3a7344d5e8dc00cb0ff2cf4b)]
   > Fixed emty script tags in page , added new methods to Insert Class
     DelCss ();
     DelJs ();
     
     Methods added to delete JS or CSS inserted entries if needed.
     Used this to fix Bug #156

 * **2016-11-19:** instantflorian [[9006edf](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9006edfd3e63b66be80bf10ad2af8f8af0bd1b77)]
   > Add module level to info.php of system modules
     to avoid unwanted uninstall

 * **2016-11-19:** NorHei [[35376fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/35376fad1b6a42d52a078929540f15cf225aa706)]
   > Frontend Login settings not displayed correct
     We using "bfalseb" and  "btrueb" if storing boolean values into the DB
     theese db Settings where not changed in install DB.
     "bfalseb" and  "btrueb"  are restored to true boolean values
     "false" and  "true" are only restored as strings

 * **2016-11-16:** instantflorian [[388627d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/388627df3b50091e95fb51057e0d11f2a71364f4)]
   > Implement select2 for module selection

 * **2016-11-16:** NorHei [[2e6a937](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2e6a937f81de393f612c8e9fd388cacc015e821e)]
   > Module uninstall throw error

 * **2016-11-16:** NorHei [[7b42a28](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7b42a286a6a97a04d524996938557a6ea197d180)]
   > Removed module that not yet in use

 * **2016-11-15:** NorHei [[bd7733e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bd7733e15c225798771f979b710cfc3a5112cb64)]
   > Preferences no longer shown if permissions deactivated.

 * **2016-11-15:** NorHei [[c2cef4f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c2cef4f142bc64ad308fad574f9a3cc38e5e636b)]
   > Class tool was overriding manual settings unintentionaly
     This caused problems whith preferences

 * **2016-11-15:** NorHei [[7427884](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/742788404292affca278f409c7e9ae1ed277c72d)]
   > The Word "Preferences" was not displayed in edit group .

 * **2016-11-15:** NorHei [[31b5320](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/31b532058f1b6479620d276cad66bcbf9d60095b)]
   > Merge branch '1.2.x' of https://github.com/WBCE/WebsiteBaker_CommunityEdition into 1.2.x

 * **2016-11-15:** NorHei [[1076392](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/107639219d554fee4cebc664ee87d5f87d835ba5)]
   > Double Javascript sysvars in FE
     The new automatic Placeholders and the old Frontend functions for Javascript collide and
     create a double output for the system vars .
     
     To avoid this , the old functions now overwrite the entriy for the new ones
     So sysvars only displayed once.
     
     As a result , we found that its necessar to add some new methods to class
     I and class Insert , to delete entries.

 * **2016-11-15:** NorHei [[368920b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/368920b18f1c372addd1d4a08fe32ba9758e95b1)]
   > Small fix for last commit  : missing ";"

 * **2016-11-15:** instantflorian [[f5a54ce](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f5a54ce5d358788ad6f020e97273eecb7f77a853)]
   > Save create user form-data for 60 seconds
     To avoid to enter all the data again if sth. was wrong or missing.
     Script from http://www.dynamicdrive.com/dynamicindex16/formremember.htm,
     changed storing time from 100 days to 60 seconds.
     Change in modules_details to remove hard coded "Website Baker".

 * **2016-11-15:** NorHei [[855fc26](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/855fc265d354084c1ad66e6da2e2ac4ea8bfb3de)]
   > Class insert does not need or want script tags

 * **2016-11-15:** NorHei [[90fc216](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/90fc216308c91336e8d4fdce82255c60f05ed628)]
   > "Session Time Run out , killing session" debug message still active
     Now deactivated

 * **2016-11-15:** NorHei [[e3e8173](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e3e8173756d3237f97c769dba969463e757fcd63)]
   > Uninstall of core modules mnot allowed
     We have so many core modules that are a really bad idea to uninstall as
     it could break the whole site or prevent backend settings , or possibly stop you from logging ins.
     So we decided to add an extra Parameter to the List in info.php.
     
     $module_level="<user|core>"
     
     Core modules cannot be deinstalled. You need to change the info.php via AFE or
     FTP before you can deinstall such modules.

 * **2016-11-14:** instantflorian [[44c553c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/44c553ca169bc92ff2b41403649d603a8208ae98)]
   > Clarification about intro page

 * **2016-11-14:** instantflorian [[48f51dd](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/48f51dd5e732f1da8c4d8897922802a3ad2caa3c)]
   > Output Filter Wording
     Minor fixes to info.php and de.php

 * **2016-11-12:** NorHei [[e50c91d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e50c91d40f1e1d0ea1d68492a3a69711445510ff)]
   > Login Possible whith any passord
     Fixed Bug .. There was a = instead of a == in an if clause ...

 * **2016-11-12:** NorHei [[740d33a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/740d33a710a03c621396fa1ea759f1ce2345cb23)]
   > Removed Admin tool for maintainance Mode, now using setting.

 * **2016-11-12:** NorHei [[a3fb1fb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a3fb1fbcd3b986002235f96b7307255b85b5e420)]
   > No Extra delay if you login correct on a single atempt.
     Still bcrypt takes a bit longer than old md5()

 * **2016-11-11:** NorHei [[1f2f60e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1f2f60e102f0f7e67f8a293d2ede68174bf7064f)]
   > Display users temp blocked by login
     Login sets users "active" to 2 for 3 Seconds
     We need to set active users to >=1 not just ==1

 * **2016-11-11:** NorHei [[55ad059](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/55ad059ad7c3336b0964c7032736bcfd8104972d)]
   > BE output filtering was not activated on install
     Insert and Css to head where not loaded upon default install  and, or
     Upgrade. Nedded to set the new System Settings in the module
     install.php and upgrade.php.

 * **2016-11-11:** NorHei [[3818501](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3818501e8dcefed89969dd480d041888d9339b17)]
   > Login Spam protection now only allows one Login for one user in 3 Sec
     Should run as expected now , update for last commit

 * **2016-11-11:** NorHei [[31c4d6a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/31c4d6aae3d5b09a80d4785562e567f611057bcc)]
   > Login delay extended , fix for WbSession
     ~~~~~~~~~~~
     self::IsStarted
     ~~~~~~~~~~~
     needed to be
     ~~~~~~~~~~~~~
     self::IsStarted()
     ~~~~~~~~~~~~
     
     in WbSession
     
     On Login every user is set inactive for  a few seconds
     
     WB_LOGIN_SLEEP is the constant
     
     So no additional Login atempts if one is already running ...
     
     arg damm this needs some fixing ... ok on next commit

 * **2016-11-11:** NorHei [[f19677a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f19677aa7ec8dcc664a453951ea7b52265f887f4)]
   > Reactivated Login protection, it was turned off for debugging

 * **2016-11-11:** NorHei [[24f6830](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/24f6830d818a8aa97af8f8195fff70e256805de0)]
   > commented out header installer save.php

 * **2016-11-11:** NorHei [[de407fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/de407fa1f9fe420f4064ba453e724fda82c91fc9)]
   > more bplaced ?
     not so sure

 * **2016-11-11:** NorHei [[aee09a4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/aee09a4552a832caa7a99194d1c12e97c673d19d)]
   > More bplaced and Login fixes
     Like
     Reduced WbSession::IsStarted to the simple basic WB Version
     As anything else caused login errors
     .

 * **2016-11-08:** NorHei [[ba2adb9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ba2adb9c01896fe2445e7fa8667731220baf7793)]
   > Out of memmory on Bplaced
     It seemed like the WbSession Class ran out of Memmoryy on certain Webservers
     Maybe it was running into an endless loop or a PHP bug ,
     i am not really sure about that.
     
     But changing the IsSession method helped ,
     so it seems like its fixed now.

 * **2016-11-07:** NorHei [[c8af098](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c8af098c8939874ecad977b3cdea0a820e926a8c)]
   > Small changes on Datetimepicker Module
     Thanks to Christian

 * **2016-11-06:** NorHei [[356212f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/356212f59df008b0aede7515e40f1b7ff73a7a80)]
   > Some more refinements on AddCss and AddJs
     WE only can check internal files for browser caching options

 * **2016-11-06:** NorHei [[69e276b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/69e276b465e813317dd83c4ca0ebab17c6599987)]
   > Class insert  If then for refreshing Browser cache was buggy
     href in CSS snd scr in JS where not loaded if refresh cache was disabled

 * **2016-11-06:** NorHei [[dff227d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dff227d76daf1309bdbd02cc77c23dc6c5f3409e)]
   > Typo fix for last change

 * **2016-11-06:** NorHei [[0768d83](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0768d8359039a2d4b01955e62e1fd29bce37412c)]
   > Removed old DB query mysql_somethin() -> $database->something

 * **2016-11-06:** NorHei [[8a5bafe](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8a5bafe646de0e56eddc922c0650f82e5b7aa095)]
   > mysql_error  to $databasee->error()
     I hope this is a good idea and the right replacement

 * **2016-11-06:** NorHei [[9210c2a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9210c2a9f2489659ed4438aa5090f8a993360ed6)]
   > more fixes

 * **2016-11-06:** NorHei [[9ae03af](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9ae03af403976d2ef0ca19ec5daa5b951748a2ca)]
   > collection of bugfixes

 * **2016-11-06:** NorHei [[182d307](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/182d3073fb36cdad26ff6f7f819d9c9d4b82f48e)]
   > Changed sorting of languages in installer
     This cannot be a final solution, as PHP functions asort() or natsort ()
     cannot handle UTF-8 Chars.

 * **2016-11-06:** NorHei [[7447df9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7447df9677be4bcd7867aaac44dc9299a0b24b8e)]
   > Deleting admin on install fixed
     In certain circumstance  the ad min user vas deleted on install ..
     
     connected to #149

 * **2016-11-04:** NorHei [[72f8cd7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/72f8cd7a4951d498f3fc7879d39a4572f571ee45)]
   > Installer issues  (Session error messages... )
     Installer now displays all error Messages at once.
     Displaying only on , and then on page reload showing the
      next one was pretty anoying.
     
     Changed Prefix to only lowercase and no "_" for future
     DB systems .
     
     Fixed Cookie Message to diplay only if problem is found .
     
     Added Array for later coloring of fields containing errors.
     Coloring need to be done in next stepp.
     
     $_SESSION['ERROR_FIELD']
     Contains all fields whith errors

 * **2016-11-03:** NorHei [[0414c70](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0414c70fff33d848c11c65ee56c3c9607e91c9c9)]
   > Parse Error in Upgrade script
     Parse error: syntax error, unexpected 'Settings' (T_STRING)
     in /var/www/web207/html/wbce12x_norbert-heimsath_de/upgrade-script.php
     on line 788
     
     Missing Bracket ")"

 * **2016-11-03:** NorHei [[5358376](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5358376013a8515088e35c8247a01354e914e470)]
   > Suppressing of Browser cache for class Insert new Constants
     WB_JS_REFRESH_BROWSER_CACHE
     If set true all JS files in class Insert get an parameter attached so
     browser thinks they are a new file.
     
     WB_CSS_REFRESH_BROWSER_CACHE
     If set true all CSS files in class insert get an parameter attached
     so browser thinks they are a new file.

 * **2016-11-03:** NorHei [[dd581d6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dd581d669b5ab56a2b14678edce67c46adcc6119)]
   > show_wysiwyg_editor was loaded multiple times , now fixed

 * **2016-10-25:** NorHei [[5cfa4e5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5cfa4e5d68eec26da15301722ec1aeadb34df150)]
   > Added testfiles for steffeks pagetree
     This files load the new pages module if present.
     
     If not they load the old legacy files.

 * **2016-10-23:** NorHei [[9986334](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9986334439035a7ac9ce79d40ebb441e269fe1a2)]
   > Language File loading in class Tools , some refinements.
     Some Language files relie on global Language Vars already set .

 * **2016-10-23:** NorHei [[81462c3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/81462c337e10f04a5aa303270726ccb2b2765f1a)]
   > Compatibility patch Tools Class will now load language files in Global context

 * **2016-10-18:** NorHei [[fa2e77d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fa2e77d45d8f92e36f936fae332f5df9cd96ddb6)]
   > Added Session vars display for debugging
     WbSession::Debug
     Returns an HTML overview of all Session vars

 * **2016-10-18:** NorHei [[37686e8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/37686e8c4ea7e45c1f44eea93b2de12a9a3e1657)]
   > Added Session Class
     From Now Modules can override the Session by registering another
     Session claass early and filebased.
     
     Added Methods for storing ang retrieving session values
     These store their values in a Sub array so the stay out of the way if
     we implment some external Software.
     WbSession::Set()
     WbSession::Get()
     WbSession::SetPerm()
     WbSession::GetPerm()
     
     We have a permanent session storage where data can survive a logout.
     As login is only session based, this is the place to move the logout.
     WbSession::Logout();
     
     Extra method for check session
     WbSession::IsStarted();

 * **2016-10-01:** NorHei [[0904fd6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0904fd69ef41aead234395447ff764c248e40c1a)]
   > MANAGE_SECTIONS constant was set to wrong value
     It was True , but should have been "enabled"

 * **2016-09-14:** NorHei [[dc9e76f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dc9e76fcf2c1a2c748ebfdb691f98cb0eb12cea3)]
   > Adapted Class WbGroup to the new DB functions ... love this

 * **2016-09-13:** NorHei [[d369fd7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d369fd73d9704319daab5dde3dbf5f461d964513)]
   > Bugfixes for class WbGroups , this should be functional so far

 * **2016-09-13:** NorHei [[f0cea8f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f0cea8f392cad8a9bb890cc406558b56c2b5bfc2)]
   > Lots of extensions to Class group

 * **2016-09-04:** NorHei [[4998d24](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4998d24a41cbf9c7f242686190116286e151c699)]
   > Almost finished WbGroup Class
     Class to manage Groups , far more comfotable than how its done in the old WB

 * **2016-09-03:** NorHei [[86913d2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/86913d250566135a35854940fc9fdfb5a9846932)]
   > account/preferences now only available , when preferences available in BE
     This now can be sett by using group settings

 * **2016-09-03:** NorHei [[098441f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/098441fddf623111a012b9ee30a022a83d05b1f1)]
   > Moved get_user_details and get_permission to Class WB
     The are now needed in FE too

 * **2016-09-03:** NorHei [[08a732b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/08a732b2232c524e92637fa58829584d773c120a)]
   > Set a more precise Version for Date Time Picker

 * **2016-09-03:** NorHei [[7895240](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/78952405941652f0fe8a3516ab84f4186137f2d1)]
   > Added new date time picker Module.

 * **2016-09-03:** NorHei [[8477430](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8477430c0dc9bc37811f2913ffb6e3632ae8c3e0)]
   > Adapted BE Templates to use class Insert
     Added the placeholders for use whith Class Insert(I)

 * **2016-09-02:** NorHei [[123582f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/123582f5c044393d42876b140a359c59cf0ceb44)]
   > An extra place for setting WB_FRONTEND (in index.php)

 * **2016-09-02:** NorHei [[5d8dfd6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5d8dfd625d2e59da86e6e117576b345be10f88d6)]
   > Constant WB_FRONTEND
     Whenever frontend functions are loaded this constant is defined.
     
     So use :
     
     if (defined(WB_FRONTEND)) {
     	// do Frontend speciffic stuff
             ....
     }

 * **2016-08-28:** NorHei [[504fac2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/504fac213eb2f700163e8f4f096204495e83cb4b)]
   > added some Error codes to install save.php

 * **2016-08-28:** NorHei [[972e97a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/972e97a6fd8a07198417f8b78195d9d65fde0e48)]
   > Little Bug in installer Sql

 * **2016-08-28:** NorHei [[76a3a51](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/76a3a5150f8f5a8346e2402779e544d90fed55e3)]
   > NEw DB Class method import renamed to the old SqlImport
     For more compatibility.
     
     Thx to stefek !!!

 * **2016-08-28:** NorHei [[3b15e09](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3b15e09b9d3d7f618dce93a9341c6a47a3c0a3df)]
   > Some simplification to installlation save script
     Thanks to Stefek !!!

 * **2016-08-28:** NorHei [[9ab4187](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9ab41873cf8dc58d21b8627c3f78c1bc605f8e46)]
   > Removed double Admintool icon
     Someone readded it .. it needs to stay unset in Theme CSS

 * **2016-08-28:** NorHei [[5d6e8d8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5d6e8d803274694e4a4d4222c4a0b8b7213245eb)]
   > Permissions for Preferences added
     The permission settings for Preferences where completely missing .
     Added them now.
     This was a problem as the Preferences module expected a permission setting
     (as it should)

 * **2016-08-28:** NorHei [[454ce76](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/454ce76f88a44484ffd84e719fae8f57d0bfebd2)]
   > Error in Class DB did stop installation from running

 * **2016-08-21:** NorHei [[3ea8189](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3ea8189de7637e6dd17ff6dfff3ece5ee58f1c3a)]
   > WbAuth for admin Login Forgot and usermanagement .

 * **2016-08-21:** NorHei [[3f06594](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3f06594f50d13cec81fd4fe9e7f947e20037dc9a)]
   > Class auth Was called WBAuth and is obsolete

 * **2016-08-20:** NorHei [[5462442](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/54624429275211a07c1d0398718d37d1f7296776)]
   > Added PasswordHash clas to uggrade script for removal

 * **2016-08-20:** NorHei [[10e8573](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/10e8573afb2f1f9bfdd42918664cc169fab17d0c)]
   > Removed PasswordHash Class as its not used any longer .

 * **2016-08-20:** NorHei [[db4a4e9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/db4a4e9e39f6dac8e52caeeca2389cab26429dda)]
   > Frontend account modified to use new WbAuth

 * **2016-08-20:** NorHei [[9a643bc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9a643bc1836d43d6a2d92fa5714eed8f35ae1aae)]
   > WbAuth::GenerateTempPassword retzurns Password now for further use .

 * **2016-08-20:** NorHei [[dd5fb02](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dd5fb02cb833bec29aeae954bd62aa0bb215d019)]
   > Preference module modified to us class WbAuth

 * **2016-08-20:** NorHei [[90a53e3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/90a53e3fb65c28e11861be524823838699046f0f)]
   > Login class using wbauth and wbuser now, minor fixes to  wbauth and wbuser

 * **2016-08-19:** NorHei [[49fc8a4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/49fc8a44240a3b4391249688a356f9623498ce76)]
   > Littlefix for last change

 * **2016-08-19:** NorHei [[5419538](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5419538e1904c2cb5439e096baa668bd1b10f1b3)]
   > PDO DB Class the __call function was not answering to method_exists() requests..
     As this is needed for Multi WB modules ...
     I hat to replace it whith old replacement functions..

 * **2016-08-18:** NorHei [[1e04701](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1e04701f017ef5b481e3d070d9d923ab4410b465)]
   > Rework the Login process first Step
     Added Classes WbUser and WbAuth
     
     WbUser is the basic class for doing a complete user management.
     In the future  WBUser should be responsible for adding , editing  and storing users.
     Theres alreads a lot of functionality , but its not finished yet.
     
     WbAuth is just a static class to do the basic workload of Authentication from
     simply everywhere in WB.
     Simply call WbAuth::AutoAuthenticate();
     
     And you are done.

 * **2016-08-18:** NorHei [[eb21c9d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/eb21c9dfd40889ebe51d6ac99879fcb82ac28d92)]
   > Database no longer stores password and username.
     We are working on removing all traces of DB access Data after connecting to the db
     but this class was giving this Data an exta place to store this stuff ...
     
     Now the class deletes the Variables after connecting as it should be in the future.

 * **2016-08-18:** NorHei [[fee6068](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fee606819bb1c067a1ee4d9bbc6208e3dbe5fdbc)]
   > Removed Smart Login stuff in Admin Login.

 * **2016-08-18:** NorHei [[49ea8d9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/49ea8d9a0fcb15b72d8070aff89c3fdff52e82a3)]
   > REmoved "remember me" from class login as it isnt really used.

 * **2016-08-18:** NorHei [[282271d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/282271d61497d25f2cd320ddbeca074f5e22d3b2)]
   > Very minor change to preferences for better debuging

 * **2016-08-03:** NorHei [[af32af6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/af32af65215f185ec6a022c86a3deff1b4f30f7c)]
   > A basic user klass that is needed for rewriting Login Stuff
     At least it will save me a lot of work when rewiting login

 * **2016-08-02:** NorHei [[3255fca](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3255fcae1e2d97a6fb24d9ed18489bf17a24b61e)]
   > Database Errors where always set to NULL

 * **2016-08-02:** NorHei [[93c6c8b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/93c6c8b6e5994cc3a5fc4970ad7969b72426d371)]
   > Future proof version of last fix

 * **2016-08-02:** NorHei [[77aafd1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/77aafd1b339cfbe3032f56883c09df66a8e59281)]
   > TABLE_PREFIX -> WB_TABLE_PREFIX comply to standard

 * **2016-07-25:** NorHei [[76bac76](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/76bac76407bae6d83e4f87cc5f228c69ba059f4c)]
   > Added Half ready classes

 * **2016-07-19:** NorHei [[d3c78b9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d3c78b91d0a93f4f7e5997213205d7a7d46de223)]
   > Wrong variable name in class WbAuto(Autoloader)

 * **2016-07-19:** NorHei [[b06d0c4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b06d0c479b74d566e46f1cb86248a9ded3c17b42)]
   > Old MYSQL constants where missing
     Soooner or later we need to replace em all

 * **2016-07-19:** NorHei [[329f96a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/329f96a5f9eef413e06acd9b13a13c62f7f0c324)]
   > Some fixes for the autoloader , may need some additional rework

 * **2016-07-19:** NorHei [[6515645](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/65156459c6f0c8de53e2d362ddefdb816e3bafc9)]
   > Merge pull request #144 from rjgamer/patch-3
     Update database.php
 * **2016-07-16:** Jonathan Nessier [[721392e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/721392e86912513b3a212a04e9c976b3177a9e26)]
   > Update database.php
     Fixed bugs, see PM conversation
 * **2016-07-16:** NorHei [[f9d6a73](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f9d6a736f670a66c34ede01a014784628cdd631a)]
   > fixed Install problem whith new DB class
     Thx to cwsoft for reporting.

 * **2016-07-15:** NorHei [[ed956d3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ed956d3235b649756f3698d6f73f6304ecbb4b2d)]
   > Small fix for class tool concerning output buffering/filtering

 * **2016-07-14:** NorHei [[0001a03](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0001a033835712507decc1a243c38eba94105ded)]
   > Some More  additions to PDO DB Class
     Added WDO websitebaker Data Object
     Thnx to RJGamer und steffek !

 * **2016-07-13:** NorHei [[292421d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/292421dd3437e37f61ddb0a46bb0ca7b81bfb1fd)]
   > Small fix for autoloader unter Windows

 * **2016-07-12:** NorHei [[fe34229](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fe342296913864d07681fe508a635961a8885c75)]
   > This needs a run of the upgrade script , so bumping version

 * **2016-07-12:** NorHei [[9cde887](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9cde8874bf6e304bd895682e2c412da09f0f3300)]
   > Droplets in Backend
     From Now on its possible to use Droplets in then BE pages .
     Instead of [[yourdroplet]] you have to use [[[yourdroplet]]]
     As filtering for [[yourdroplet]] in the backend would replace all
     droplets in WYSIWYG fields for example.
     
     In addition the extra <p> tags created by many Editors are filtered out.
     <p>[[yourdroplet]]</p> will become
     
     [[yourdroplet]] again.
     
     Not sure if thats a goob idea but we will see.
     This is only done in FE display.
     
     Right now all droplets can be used as BE Droplets, but i guess not many
     reallly make sense.
     
     I really need to free Droplets from this silly eval , as this slows down
     the execution speed drastically.

 * **2016-07-12:** NorHei [[fcf749e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fcf749e9e737cea1bc01fb427ff31c6ef4a23276)]
   > Forgot some debug display in class insert

 * **2016-07-11:** NorHei [[c9c5465](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c9c5465f88d8334f96e0ac15f9713cc3461d2d24)]
   > Added automatic insertion of placeholders for class insert
     The new placeholders are now added to templates that do not have em.
     This is done by a new output filter  contained inn Class insert.
     
     As its an output filter you can turn it on and off in the opf settings.
     
     If you encounter any trouble whith this please inform me.

 * **2016-07-10:** NorHei [[2616425](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2616425a5dc16a1f8b0b264d57f108577578db92)]
   > Evil droplets where eating my insert tags
     To be more precise they where eating all droplets that where not in
     the Droplet list in DB , replacing em whith empty strngs...
     
     That included my insert Tags... [[Css]]....

 * **2016-07-10:** NorHei [[c07cea9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c07cea9fab74a5828fb432d22258d087b815b5bf)]
   > CKE had trouble whith new DB class
     <b>Warning</b>:  mysql_real_escape_string() [<a href='function.mysql-real-
     escape-string'>function.mysql-real-escape-string</a>]: A link to the server
      could not be established in <b>/var/www/web207/html/wbce12x_norbert-heimsath
     _de/modules/ckeditor/ckeditor/plugins/wbdroplets/pages.php</b> on line
     <b>45</b><br />

 * **2016-07-10:** NorHei [[7dfe949](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7dfe9495844f4fc485a38cd85bb8f40944276091)]
   > Removed deprecated messages as they caus only tons of trouble
     CKE no longer working , dozens of header already send messages ..

 * **2016-07-10:** NorHei [[fbf8034](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fbf8034ca0629bb13d74ae178e2e985430ed0f09)]
   > Some major bugs in settings class and some cleanup
     Extendes settings class had some issues whith storing  boolean data.
     And some cleanups in module files .

 * **2016-07-09:** NorHei [[2837a93](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2837a9310d31c393dcc6130392cce19e8da5f55c)]
   > Class settings had problems whith storing and retrieving booleans

 * **2016-07-09:** NorHei [[316166c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/316166c800b034f15c2b7a87118f032ec157bc49)]
   > Merge pull request #143 from rjgamer/patch-2
     Fixed set error
 * **2016-07-09:** Jonathan Nessier [[fd4a496](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fd4a49609400e3e72300f1251bc1e35882107287)]
   > Fixed set error

 * **2016-07-09:** NorHei [[6d26922](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6d269228935e03defd0c318d43358a589145d7b3)]
   > PDO DB class produced some warnings
     Warning: getModulesArray() [function.getmodulesarray]:
     This PDORow is not from a writable result set in /var/www/web207/html/
     wbce12x_norbert-heimsath_de/modules/addon_monitor/functions.php on line 42
     
     Notice: Indirect modification of overloaded element of PDORow has no
     effect in /var/www/web207/html/wbce12x_norbert-heimsath_de/modules
     /addon_monitor/functions.php on line 51

 * **2016-07-09:** NorHei [[f0f6411](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f0f6411da7710dbd1823a3875e283bb58be83f7c)]
   > Some path adjustmets on database compatibility file

 * **2016-07-09:** NorHei [[edfe975](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/edfe97538def9ce5248ec2e87ba65bf2a5450a1e)]
   > Redirect installer , wrong file path

 * **2016-07-09:** NorHei [[3b1a5c9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3b1a5c91127b6b65a783f702c8a9e4b867df7e35)]
   > turned of deprecated messages for now

 * **2016-07-08:** NorHei [[f0be616](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f0be616251f55efd107320ff3cdc500da5e95fe5)]
   > Placeholders for insert class now case insensitive

 * **2016-07-08:** NorHei [[51fb074](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51fb0749d95968e19db2a891c59d98a344565803)]
   > Bug in class insert
     closure was missing a use , and closures dont function whith $this
     on old php versions

 * **2016-07-04:** NorHei [[6814eb5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6814eb5f2548d2f9a68385c7fade43a999a5c3c6)]
   > Put in deprecated messages again WE will add those to the old class too
     Maybe we have a nice idea to inform from where this was called .

 * **2016-07-04:** NorHei [[31a87f2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/31a87f2722c3bcceffba66a1938ee10f353b4287)]
   > Forgot to add initilaize.php

 * **2016-07-03:** NorHei [[e3642d9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e3642d9bfbb194b4887f071981b05b623a6fdabf)]
   > Commented Out deprecated messages  for nen method names

 * **2016-07-03:** NorHei [[699ce63](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/699ce63759da553d8103d3cf34d64aed1df8f435)]
   > Even More Framework modifications
     Autoloader now loads DB system too
     
     The old DB clas is upgraded to the new functions too
     Thanks to Steffek!!!
     
     persistence is renamed to lowercase   as all files should go for being lowercase.

 * **2016-07-02:** NorHei [[155a074](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/155a074225c275027caa11f2fbcdf50a33e24b88)]
   > Initialize loads correct database file now

 * **2016-07-02:** NorHei [[d3da15d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d3da15d0ba3dbc6d5ded27cd060b624d032c693a)]
   > Loads of cleanup in framework folder
     Removed some compatibility files , as new classes do not need them.
     
     Old DB class is back in and the include file looks for PDO drivers
     and only loads new class if they are available.
     
     An deprecated error message is shown when compatibility files are called.

 * **2016-07-02:** NorHei [[4b6d5e7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4b6d5e78670eb4dcc72320643890e808765ea1a9)]
   > Small fix for Settings
     Needed to  De serialize in GetPrefix  too :-)

 * **2016-07-02:** NorHei [[62f22c7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/62f22c77e881e3a2c7b0d56001f5accc58c94e2f)]
   > Extended Class Settings
     Now we can store Arrays and objects.
     
     And we can fetch all settings for a module for example by one simple call
     to the class .

 * **2016-06-30:** NorHei [[ac20c83](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ac20c83f3efead9e100e91cce98cd227f91e8cdd)]
   > Experimental code for modules loaded bevore Database

 * **2016-06-30:** NorHei [[fd99c0f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fd99c0f42c85f03d5a84e8b885d77ed0cc5de104)]
   > Enabled Autoloader to register direct paths
     Added a Parameter to Autoloader to register direct paths whithout a
     preset WB_PATH.
     Usefull to register things outside of webroot or before paths are loaded.

 * **2016-06-29:** NorHei [[b3c2672](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b3c2672f61ddb47c4be61cb91125ffaa49b082f9)]
   > WB_IGNORE_PHP_VERSION added
     WB_IGNORE_PHP_VERSION
     Only can be set in Config. If defined, it ignores the version
     check in /framework/inititialize.php (define anything  you want)

 * **2016-06-28:** NorHei [[421bcb0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/421bcb0c4f4802b9edfece3657deb6bab21ab9ec)]
   > Merge pull request #139 from rjgamer/1.2.x
     Database classes refactored
 * **2016-06-24:** NorHei [[a7a390c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a7a390cfd3535b7e3f0650947c47bdd0bcdb2a6b)]
   > Theme overrides for backend.css and backend.js
     Now it should be possible to override backend.css and backend.js
     via the BE Theme .
     
     Example  if the code moduel had a backend.css and backend.js
     you could do a Override in
     /templates/YOURTHEME/modules/code/
     Just add one or both files there.
     You can even override if codde has non of this files.

 * **2016-06-24:** NorHei [[d905fb6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d905fb62a4e4999c560d75ab9b8d2a3675d6604f)]
   > Problems loading Backend.css and Backend js .
     We ha d problems when an admin tool tried to act as a edit page for pages as
     class admin was einther loading files for a tool or a page editor.
     But we had a tool that was a page editor :-)

 * **2016-06-23:** rjgamer [[9834bb5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9834bb5b499f4545f3a1ae2043d95f2bebcb8e0e)]
   > Updated database classes

 * **2016-06-23:** rjgamer [[0b3198c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0b3198c531dc7791cfad07c005c54b52ace43488)]
   > Added and fixed some methods

 * **2016-06-23:** rjgamer [[4bd7aea](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4bd7aea8ef55c1c8ab07eabe42c315629b6eca97)]
   > Updated .gitignore

 * **2016-06-22:** rjgamer [[5782222](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/578222299a378aed6d5101f9b7cb79e5daa93225)]
   > Added new updateRow method

 * **2016-06-22:** rjgamer [[204c2e5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/204c2e5671b53fa9447491c869dec45300645291)]
   > Fixed some bugs

 * **2016-06-22:** rjgamer [[f6719f5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f6719f5bb6bc5b6c4659b32cacd7d50a94ec2d18)]
   > Added DB_DSN for SQLite

 * **2016-06-22:** rjgamer [[d7b6745](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d7b6745551b763f4bec8c648217e06c7acba8095)]
   > Added method getPdoStatement()

 * **2016-06-22:** rjgamer [[1ddc2f6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1ddc2f645cf973766d3deba7e22041f38f5446d2)]
   > Database classes refactored

 * **2016-06-13:** NorHei [[66388fe](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/66388feda6ea971f2a1d9229ae4b44f5e18e05af)]
   > Forgot to remove some debug stuff
     Thanks to Stephan

 * **2016-06-11:** NorHei [[c5d8b87](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c5d8b87b3b969e6d7b5fd5a9d4e8c7e0043c3b8c)]
   > Tools, backend, settings for backend_body.js
     Same as the last fix , just for backend_body.js

 * **2016-06-11:** NorHei [[0a95a0e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0a95a0ed8cc2b4932f030b1d7b3028ac21f89fd0)]
   > Changed class admin register_backend_modfiles for tool setting and backend modules
     Class admin now supports tool setting and backend modules and loads
     backend.js and css correct.
     
     Added regex for injections prevention.

 * **2016-06-10:** NorHei [[134cfec](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/134cfec3579b214663f20504a1387f4527b0cca8)]
   > Added the Var $aWrapper for easy access and handling of $this.
     Needed for new pagetree

 * **2016-06-09:** NorHei [[4d3d857](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4d3d857b334c43184b3e33d528600f08ea4d31a9)]
   > Reactivated new settings too, as the won't collide whith any old modules.
     Same as preferences

 * **2016-06-07:** NorHei [[e8439e4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e8439e4300390bc13d72a6ef14b0b6609342696f)]
   > Set Session Cookie Livetime to 0 = unlimited
     Wont interferee whith the session management then.
     Session management done serverside.

 * **2016-06-07:** NorHei [[ecb1593](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ecb15935438f854a81903eb05de52015d6c04de5)]
   > Session Timeout does not prolong after page load.
     After adding a function that certainly ends the session relyably after its
     timeout i found that the session does not prolong on page calls.
     
     Problem was that i set the cookie Lifetime and php does not prolong this
     when extending the session (PHP But/Feature)

 * **2016-06-05:** NorHei [[83d8c29](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/83d8c29e33857f38944ec8c4e69cad9891dfcb1e)]
   > Pushed templates Version numbers

 * **2016-06-05:** NorHei [[4f57264](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4f57264f4e3228338f2ebec10113f2276f3e045f)]
   > Reactivated the new  Preferences Admintool
     The new backendfunctionality is almost complete , and the new
     Preferences is far better than the old one.
     
     As this does not interferre whith any old Modules , i think this is a good Idea.

 * **2016-06-05:** NorHei [[b8315d1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b8315d16c078f951f4371159f85a83cb8370725b)]
   > Small changes on Argos BE Template
     Moved the informational Box to the bottom as it colided whith the navigation
     on small screens whith big fonts or if you extend the navigation.
     
     Added PHP version as informational field.

 * **2016-06-05:** NorHei [[adfa7ed](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/adfa7ed8d95e56b2555772ed358d8aa0d81e8f94)]
   > Added a few variables to the footer Template in print Footer

 * **2016-06-05:** NorHei [[4eda537](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4eda5377341dd84f5a897d34b381b339c405a86b)]
   > Just some alignments on the Logo in Flat Theme

 * **2016-06-05:** NorHei [[ec35516](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ec35516bebd69eace36033d1e40ac7750649e0b4)]
   > Flat BE theme missing admintool icon

 * **2016-06-05:** NorHei [[8821534](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8821534997943a605862bb04eec16930a2db6408)]
   > BE Theme was missing a font

 * **2016-06-02:** NorHei [[b0b01bc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b0b01bca6c6a4fda88ae800c6b3df79d985be578)]
   > Several small BE fixes
     Removed Link to Websitebaker
     Several minor fixes
     Just the first step , several more to come.

 * **2016-06-02:** NorHei [[51b400a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51b400a859c46ff5dda40ae51e037a52128a83ea)]
   > Pagetree fix
     Fixed a missing <td>&nbsp</td>
     
     http://forum.wbce.org/viewtopic.php?pid=4834#p4834
     
     Thanks to Stefek!!

 * **2016-05-30:** NorHei [[771827a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/771827a5d655dc4ab485067a938290e559cdc25e)]
   > Fix for modules using WB Classic exeption handler instead of redirecting directly
     Just for compatibility.

 * **2016-05-30:** NorHei [[91bc2a2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/91bc2a2fb63cc77924cdc7e00281929965787002)]
   > A wild hack for modules calling $admin in the FE
     Some modules like PRocalendar  or MPform  are calling on $admin in the Frontend.
     Possibly because under WB Classic getFTAN() is only available in BE.
     
     http://forum.wbce.org/viewtopic.php?pid=4743#p4743
     
     Done something very similar for  modules calling on $wb in the BE.

 * **2016-05-28:** NorHei [[f976481](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f97648190019959aacce53292693957f49b65fb2)]
   > Replaced new BE functionality whith old (modularity removed)
     Calling admintools from whithin a class caused several tools to fail.
     Now we first fix the modules and then add the new feature in a 2.0 Version.
     2.0 as it breaks something...
     
     For 1.2.x we stick whith the old variant to not create any bad surprises
     for users and have updated modules available when changes are applied.
     
     Still there are a lot of new variables and functionality available in
     admintools as the script calling the Tools has been enhanced.

 * **2016-05-27:** NorHei [[75d5bee](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/75d5bee9c23047cdf2a8d9414eff2f559ca4a843)]
   > Compatibility fix  for this release
     Many modules stoped working because they couldnt fin PHPlib, TWIG or did
     double includes of corefiles. The two libraries are put back as fake files.
     The Core Files now hace include_once files that stop old modules to load
     em more often than necessary. Next step is to restore the old Backend.

 * **2016-05-23:** instantflorian [[a18e725](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a18e7253b101b88e42d1fe6926fc96bb8091de5b)]
   > Typo in german language file, smaller template optimizations

 * **2016-05-19:** NorHei [[e4d7577](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e4d7577206d1960d75a4629acdaceb78e0c077fa)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-05-19:** NorHei [[6bc653f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6bc653f3e9f78153cb02de37d380bf72b45dc64e)]
   > framework/functions.php  page_link() makes trouble in FE
     As reported in  #134
     
     Topics:
     when calling a topics detail page in FE, instead of the page just an error occurs:
     Fatal error: Call to a member function page_link() on a non-object in /.../framework/functions.php on line 596
     
     This function is needed in FE and BE so i first decided to add an IF THEN , but then remembered that in BE i do a
     $wb = $admin;
     So a $wb is always available when a $admin is.
     
     So i only changed this back ti $wb.

 * **2016-05-16:** instantflorian [[abf4c2d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/abf4c2d0ef1aacbbf9448e0671040b025fb5b2eb)]
   > Fix for issue 133

 * **2016-05-15:** NorHei [[b8cc0da](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b8cc0da282e9fb608106346dd79ec69e656f9000)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-05-15:** NorHei [[3bbabab](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3bbababf2c62a1089e1d3d7098b6ea1c342ddc18)]
   > When not logged in tool.php throws error instead of redirecting
     Issue #132
     
     if a visitor calls http://domain.tld/admin/settings/tool.php
     he is not redirected to the login screen but sees
     Notice: Undefined index: MODULE_PERMISSIONS in /.../framework/class.tool.php on line 124
     Warning: implode(): Invalid arguments passed in /.../framework/class.tool.php on line 124
     Warning: Cannot modify header information - headers already sent by (output started at /.../framework/class.tool.php:124) in /.../framework/class.admin.php on line 34
     
     Schould be fixed by now ...
     Thx to Florian for reporting.

 * **2016-05-12:** instantflorian [[c867e2a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c867e2a71d177541a78ebe084e949ef6162a4a91)]
   > Added Save&Go Back to page settings + tooltip for filename/URL field
     Also corrected missing target pages on error (you were lead to the page
     tree instead of staying on the settings page)

 * **2016-05-12:** instantflorian [[061b74a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/061b74a3c1b72c610bfaad045f30991ccf714e68)]
   > Missing ? in breadcrumb, styles for new settings overview in argos theme
     This is NOT the fix for the reported bug 132!

 * **2016-05-12:** instantflorian [[a8672fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a8672fa49df8d6958392022d0fa5eba83a728263)]
   > SimplePageHead now checks if icons exist

 * **2016-05-12:** instantflorian [[5e331e6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5e331e6af186d90b4be28395ac878e742010aeae)]
   > Added missing reference to fontawesome webfont, minor branding issues

 * **2016-05-12:** NorHei [[348a000](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/348a000d126f87711fd6059b42df8465a928443f)]
   > It seems that language and templates are fetched from parent but menu is not
     Issue #124
     This is fixed now

 * **2016-05-12:** NorHei [[33d3eb3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/33d3eb3868768136beea3855c763aeb6afceda18)]
   > Aborted test to move  more functionality to class WB  and dry out Admin and Frontend classes
     Started in 6c85efbc98e6eb45999f4348e7517af9facd9a0d
     
     ending this for now . :-(

 * **2016-05-10:** NorHei [[7a25fc0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7a25fc0f2d5d4c495f13f5692c9408f35229565b)]
   > \admin\groups\groups.php sql error
     Issue #130
     
     Fatal error: Call to a member function numRows() on a non-object
     in \admin\groups\groups.php on line 87
     $result = $database->query('SELECT * FROM ' . TABLE_PREFIX . 'addons
     WHERE type = "module" AND LIKE "%page%" ORDER BY name');
     
     Change to
     $result = $database->query('SELECT * FROM ' . TABLE_PREFIX . 'addons
     WHERE type = "module" AND function LIKE "%page%" ORDER BY name');
     
     Thanks to 	qssocial!!
     I love bug reports whith solutions!!

 * **2016-05-10:** NorHei [[51f74b3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/51f74b363431d0bfeb00bcf2456b5c46385309d9)]
   > Finally  Html blocks are added to class Insert
     You now can use AddHtml() to insert  extra Html to your templates .
     Functionality is the same as AddJs() but you dont have different variants
     of html.
     
     This is usefull for dynamic footers , or Warning bars on top of the page
     and many other Applications.
     
     MAybe its nice to remember that this functions also in BE templates now.

 * **2016-05-06:** NorHei [[0a121d4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0a121d447bce3cd9cd659f394708a0ee588d6f93)]
   > Filter  Insert and CSS to head now available in BE
     They got their own settings in the filter settings.
     
     MAybe i should move those settings to settings ...

 * **2016-05-06:** NorHei [[15ca881](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/15ca881d09a0b97e7dfc2d996e89067b74662535)]
   > added Language vars "FRONTEND","BACKEND"

 * **2016-05-05:** NorHei [[e23500d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e23500d790551a16e9bfbe2b356260da091c3895)]
   > Global vars stoped working from inside a class namespace on WBSTATS
     Global Vars are seldom a good idea , now runnig inside a Class namespace
     they failed to function because theyy were only set in the local namespace
     not in the Global one.
     
     Personal i would prefer people do tools either whith real functional
     classes or simply by doing procedural code whith includes. Having a
     mixture mostly generates lots of problems.
     
     Hopefully i caught them all.

 * **2016-05-05:** NorHei [[16529d5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/16529d557c6682ba389d149f00f91da64ecbfaf9)]
   > admin/groups/index.php change: function` = "%page%"
     My new Job really makes me a bit tired ...
     
     Thankt to Krzysztof again !!!

 * **2016-05-05:** NorHei [[d4fd7fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d4fd7fa0d2247f31c4b0cfde5a46e85ffccb636c)]
   > Seo Tool was not ready to run inside class tools.

 * **2016-05-05:** NorHei [[423647d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/423647d647aa2eb29baaf06bddf7fac3ebd64a24)]
   > Error after moving some MEthods from class admin to class wb
     Strict Standards: Declaration of frontend::get_page_details() should be
     compatible with wb::get_page_details($page_id, $backLink = 'index.php')
     in /.../framework/class.frontend.php on line 17
     
     See #127
     
     should be fixed for now

 * **2016-05-04:** NorHei [[0bbecbc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0bbecbc325a9276b1e5eaa6018319e47c494d334)]
   > Added a new field to pages Table "dlink" for a fully functional menulink
     After several tests it turned out that a fully functional menulink needs
     an extra field. This field opens the option to have real url and wblinks
     in the menulink so redirect is no longer necessary , but still possible.
     
     I just add the field in this release as anything else depends on the
     modules  Menulink and SM2/3. They will adapt this after this Release.

 * **2016-05-01:** NorHei [[78cd300](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/78cd30045a3113997d3459b9a36435104ee59180)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-05-01:** NorHei [[e307600](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e3076002acc3d20291d6471f7f11009ffdbff2f5)]
   > Fixes for multifunctional modules. some missing %%
     Thanks to Krzysztof

 * **2016-04-29:** instantflorian [[ac4aa76](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ac4aa768da0f93dda6d37c0ffe0b6028a9eccf87)]
   > Language files and icon definitions for new settings
     also fixed a bug in list_settings which prevented usage of translated
     title. Also removed duplicate of maintainance mode settings folder in
     itself.

 * **2016-04-29:** NorHei [[faa5a86](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/faa5a86f93a754bab41c8cd810533c3a1b7c4f09)]
   > Detaailed access management for settings.
     Added access management for settings to /admin/groups/.
     
     You now can define exactly what settings a group has access to.

 * **2016-04-29:** NorHei [[cf983db](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cf983db84e9d1be7af8a5f6539066a2a797decbe)]
   > forgot to save one file

 * **2016-04-29:** NorHei [[6c85efb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6c85efbc98e6eb45999f4348e7517af9facd9a0d)]
   > Moved a lot of functions from class admin to class WB
     I really wanted  permissions in the FE too.

 * **2016-04-29:** NorHei [[e672da9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e672da91f5098da50e30b028d12de28358393319)]
   > Version should not only be avilable in BE (admin section).

 * **2016-04-28:** instantflorian [[9d9fb60](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9d9fb60a6ac54cdd838e74d6104506036ffdf3c8)]
   > Some legal stuff

 * **2016-04-27:** NorHei [[032b62e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/032b62e167789f45ce8a3831b871fc4cb5546010)]
   > Forgot some more debugging in class.tools.php
     Taht generated an output bevore calling header.

 * **2016-04-27:** NorHei [[edafe34](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/edafe34a68dabe40f905370e48757869aae62a9f)]
   > Adding FTAN to FE prefernces
     After
       https://www.htbridge.com/advisory/HTB23296
     
     Krzysztof thinks its a good idea add an FTAN to the frontend preferences ,
     and as long as this is not replaced by something better
     i guess he is right.
     
     Thanks Krzysztof

 * **2016-04-27:** NorHei [[5417462](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5417462a3265442435cc9f344aa43ac65b9586e3)]
   > Missing adaption to multifunctional modules
     https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/125
     
     Thanks to Krzysztof!

 * **2016-04-27:** NorHei [[f532398](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f5323981e185473eb63be251b1566cd6e4a4dce3)]
   > Just a bug in account/email.php

 * **2016-04-26:** NorHei [[f6e6920](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f6e69206e22a6aa277b02b6c6887554a0da6371b)]
   > Bug in Droplets?
     Notice: Undefined variable: HEADING in /var/www/web913/html/eso/wbce04251/
     modules/droplets/functions.inc.php on line 32
     
     Notice: Undefined variable: TEXT in /var/www/web913/html/eso/wbce04251/
     modules/droplets/functions.inc.php on line 33
     
     Did a wild guess , as i could not reproduce...

 * **2016-04-26:** NorHei [[d0dba83](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d0dba83173a46861a8072979598db82351c9fa4e)]
   > Class tools in maintainance mode, forgot to upload new settings.
     Class Tools was still in maintainance mode , redirects deactivated.
     
     Somehow i forgot to upload the new files for Settings.

 * **2016-04-26:** NorHei [[b294e10](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b294e10ba3e45dfcaa08a61aa5befe2128f63e67)]
   > LAtest version of OPF does not need any initialization .

 * **2016-04-26:** NorHei [[2fe5a67](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2fe5a6708a2b1fc08a905fa0745da82c85281cd4)]
   > New hooks for OPF Dashboard
     Filters can now be applied to the Backend too.

 * **2016-04-23:** NorHei [[ffd5245](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ffd5245881c2130aa56296987e0e059bec7cc396)]
   > SM2 flexible displaying of new DB fields and ficing ALLINFO
     SM2  now allows to access new fields in the 'pages' table , simply by
     addressing them via [column_name] SM2_ALLINFO needs to be set for this
     to functions.
     
     SM2_ALLINFO had a bug where if the cache was already filles whith a
     non ALLINFO tree the extra infos never got loaded.
     
     Now Allinfo sets a variable to check if a full scale array is already
     loaded into the cache , otherwise it fetches a new cache.

 * **2016-04-17:** NorHei [[70dc11e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/70dc11e63d5b081179766e38ac039ba94cd8755e)]
   > Too early use of new Constants
     Switched back to old ones

 * **2016-04-15:** NorHei [[060375b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/060375ba32b0878fa09b5b68ce0cce4a2e7d661c)]
   > Admin dasboard in Advanced Flat Theme tried to load Font Awesome several times
     It tried to load FA from the old Template location several times.
     BE now loads FA and Jquery(UI) by default.

 * **2016-04-15:** NorHei [[b67b9d2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b67b9d2d18fbc8a3751c53ad0a9cf0f27e4bd871)]
   > Looks like CLass LogFile can be removed savely

 * **2016-04-15:** NorHei [[d5e99d8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d5e99d83ca8ba6a5ecb65f9e07bed0f65fc8a51f)]
   > Bumped Version for easy Upgrade

 * **2016-04-15:** NorHei [[41b2138](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/41b2138c89f461dca6fbf8890a4e46169af3cf1d)]
   > Just a few more corrections
     Nothing serious

 * **2016-04-15:** NorHei [[8b6afb1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8b6afb115a58b5a0153e3f9712eae14a34eeae51)]
   > Partially ported Security fix from  1.1.6 .
     account/details.php            ported
     account/email.php              ported
     /admin/preferences/save.php    no longer exist
     admin/settings/save.php        no longer exist
     framework/class.admin.php      ported
     framework/functions.php        ported
     
     Some  Fields where piped directlyto DB  whitout any validation
     or escaping ... bad bad thing.
     
     http://forum.websitebaker.org/index.php/topic,28998.msg203463.html#msg203463
     https://www.htbridge.com/advisory/HTB23296

 * **2016-04-15:** NorHei [[ad08647](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ad08647d698884006afd869347f650832792f312)]
   > Patch for problems whith WBstats when upgrading. Possibly others.
     Problem appears when there is a "wb_" in the modul name and the table
     prefix is a "wb_" too .
     
     http://forum.wbce.org/viewtopic.php?pid=4012
     
     Thanks to Marmot !!!
     
     Ported from 1.1.6

 * **2016-04-14:** NorHei [[9ca1a64](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9ca1a64d403da8759cdb8d46d070be8f02fab62f)]
   > If /admin dir is changed , drag and Drop in manage sections stops working.
     http://forum.wbce.org/viewtopic.php?pid=4109#p4109
     
     Thanks to Bernd

 * **2016-04-12:** NorHei [[74e887d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/74e887d085723bc19b86268bd43da6695488847e)]
   > Bumped Version to Alpha3  for easy upgrading.

 * **2016-04-12:** NorHei [[4c238e2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4c238e276fc3c18777773d8d1162d1632290df3f)]
   > Activated new preferences . Now preferences are a simple module.
     You now can uninstall preferences Moduel and install a new one .

 * **2016-04-10:** NorHei [[3b2848b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3b2848b5b3ff21584baba84ca0c131982d189bf5)]
   > Some Template changes for previous BE changes , it now should look reasonable good.
     BE templates now can define extra formatig for Admintool type modules .
     
     Simply add /modules/modulename/templatename.php(twig)
     To your template. Only new Modules support this.

 * **2016-04-10:** NorHei [[7efbca5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7efbca515219d0527d70ebedcb8c574257abaf08)]
   > Small Fix for  class tool , it was  tied to testing enviroment.

 * **2016-04-10:** NorHei [[f964ce8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f964ce8bcd080a2bac0d04d111b1b82014fbd99f)]
   > Replaced admin/admintools/ whith files using class tools
     Only index is really necessary , but i leave tool.php for compatibility.
     Basically both files are the same. For example the Usermanagement relies to find
     User Search on tool.php?tool=usersearch.
     Later we can remove the Tool.php

 * **2016-04-10:** NorHei [[d2d31a4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d2d31a4de5c85f79521e87dc32ab5d13f40cec9e)]
   > Added all Modules needed for new BE funcktionality/modularity
     For now we got all modules for basic functionality.
     
     Listing Tools, Settings, BE pages and panels, where panels is not finished yet.
     And the BE page lister is only a admintool right now.

 * **2016-04-10:** NorHei [[c3cc3e1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c3cc3e15b08f379f4950d4d8aa6741b04195db9a)]
   > Lazy added WB_SELECT, WB_CHECK for for easy forms.
     define('WB_SELECT',' selected="selected" ');
     define('WB_CHECK',' checked="checked" ');

 * **2016-04-10:** NorHei [[fbe1752](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fbe17525235aed792694effe372201280bd25c5e)]
   > Publish the instance of class admin as  $wb in global var.
     Some frontend functions used in the backened need this, had a lot of
     strange bugs cause of missing $wb. Strange stuff ...

 * **2016-04-10:** NorHei [[64f047c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/64f047cd19697a168f3e16a52f35d3e3bd9caf6f)]
   > Added class Tool, the basis for moving the BE to consits only of tool modules.
     In the first Step class tool does take care of rendering admin tools.
     In the second it will render Settings, and in the last Step it will take
     care of rendering all BE Pages.
     
     A nice example for this will be thw new Preferences page that is no longer
     a real page. In the Upcomming upload it will be a simple call for a
     tool module whith class tool.

 * **2016-04-10:** NorHei [[a863d83](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a863d83926d597a4791ac13191f3ccfd4649df11)]
   > Added  multiple fields to Pages Table. Images, icons, and start/stop date.
     Added:
     icon         Page icon (FA icon for example and default)
     image        Page image
     thumb        Page Thumb
     image2       extra image
     image3       extra image
     image4       extra image
     publish      page publish date, unix timestamp
     unpublish    unpublish date, unix timestamp
     
     As WB Classic does not document or announce anyting about new DB fields i
     can not go for compatibility here. So simply lets do it.
     This all makes the pages table to big for my taste, but for now i do
     not see a better solution. Maybe in next review.

 * **2016-04-10:** NorHei [[18a82c5](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/18a82c5b7b228816cef02294982a94fff0a9c368)]
   > Some Minor changes ...
     Hex we are back again .. now i will upload all ste stuff in made here
     step by step

 * **2016-03-08:** NorHei [[45884fb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/45884fb5983e0fcdb633fed5afa85fe1015648d5)]
   > Added Some Languge Vars /some new constants defined in upgrade script
     Minor changes to CKE editor

 * **2016-02-24:** NorHei [[69e2c9e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/69e2c9e96e7acb4e21013e7095583b7158a65220)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-02-24:** NorHei [[ac299e8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ac299e856461c455258bf4edf62f8b278f015140)]
   > Fix for upgrade from WB 2.8.1
     WB_SECFORM_TIMEOUT was not set when upgrading from WB 2.8.1
     Issue #110

 * **2016-02-21:** cwsoft [[ae71573](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ae71573c26536f17293ca15063a9c835738fa221)]
   > Revert back CHANGELOG.md to previous state

 * **2016-02-21:** instantflorian [[00ac056](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/00ac05656845300886e541cab58b397c835362f0)]
   > Revert "New branding WBCE - Way Better Content Editing"
     This reverts commit 12e3011583cf8fe4f6ea77a093d258d0c415666f.
     
     # Conflicts:
     #	README.md

 * **2016-02-20:** cwsoft [[6a21b7a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6a21b7a9426871f8c5e203ba6bf977372eff71a8)]
   > Updated links
     Github resolves links to renamed projects automatically. However, explicit is better than implicit.

 * **2016-02-20:** cwsoft [[90d59ac](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/90d59ac357aeb2d30e75ff5e1ae8f403a4354de1)]
   > Added Screenshot to README

 * **2016-02-20:** cwsoft [[db39e54](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/db39e54b31b099024fdb17370854147baabc1255)]
   > Updated CHANGELOG

 * **2016-02-20:** cwsoft [[12e3011](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/12e3011583cf8fe4f6ea77a093d258d0c415666f)]
   > New branding WBCE - Way Better Content Editing
     Applied new branding for WBCE.

 * **2016-02-19:** NorHei [[8577210](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8577210bcf355e3d596b5c48855c991524ef2ce5)]
   > Unified direct access protections and removed DVs global exeption handler.
     Unified all but the ones used in topics as those are somewhat wrong
     and need to be changed manually.

 * **2016-02-12:** NorHei [[7bdd92f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7bdd92f0e8226699be4d690a62521973178895f7)]
   > Small bug in secureform default settings

 * **2016-02-11:** NorHei [[e0b0c12](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e0b0c12c1e570c48af7615ed4f56605796fdd68f)]
   > Bumped Version number for more easy testing .

 * **2016-02-11:** NorHei [[f77383e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f77383e59935191198386aec709154584896c2c9)]
   > Bumped Version number for more easy testing .

 * **2016-02-11:** NorHei [[59f9315](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/59f9315f06857dd159ae6c52dcc2291803514da4)]
   > A nice travel through all language files
     It showed me that  its not possible to include languageflies in an
     elegant way to the new Tool Class. Used this research tour to do some cleanup.

 * **2016-02-11:** NorHei [[9f34daf](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9f34dafe2e325bd3119414cac3c44802bb29ad5e)]
   > Feels like changing a zillion index.php

 * **2016-02-11:** NorHei [[31471b1](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/31471b1abde3799d6df11afd5948428356422b98)]
   > Wrapper language Files cleanup and some added or changed index.php

 * **2016-02-11:** NorHei [[e55792c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e55792c225ca9885aa753151cc39e90130da0948)]
   > WYSIWYG Small File Cleanup, end Tags and direct access

 * **2016-02-11:** NorHei [[6cd41e6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6cd41e6c3f41d8acc81c7694e2423bc4003294a1)]
   > WYSIWYG Language File Fixes

 * **2016-02-11:** NorHei [[bce0db4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/bce0db47d7113b22da134fbd67416062a293022d)]
   > Language file fix für backup modul.

 * **2016-02-05:** NorHei [[1968189](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/19681892d8bf60ea003678aecfa80a55a74d8853)]
   > Added a slightly modified version of PclZip
     This version avoides some problems whith PHP 7
     
     fetched from here :
     https://github.com/piwik/component-decompress/commit/deca40d71d29d6140aad39db007aea82676b7631

 * **2016-02-04:** NorHei [[d14199c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d14199c3afa5e07212306b6cb08a83ba3e51c2f5)]
   > Added system constants standard conform and some reformating on initialize
     Did some minor reformating and commenting on /framework/initialize.php
     
     The more important thing is i added standard conform system constants.
     As part of the cleanup process we now have all main system constants
     whith a  WB_ prefix.
     
     WB_PATH
     WB_URL
     WB_ADMIN_DIRECTORY
     WB_ADMIN_URL
     WB_ADMIN_PATH
     WB_OCTAL_FILE_MODE
     WB_OCTAL_DIR_MODE
     WB_THEME_URL
     WB_THEME_PATH
     
     Plus a few ones New to WB (not so new on WBCE)
     WB_PROTOCOLL
     WB_MEDIA_URL
     
     Still many constants use the old format whithout WB_ but alot of them
     will get their standard conform couterparts as soon as i had time to
     rework the General Settings.  After we remove them , we will add a
     compatibility module that still can create them for use in very old modules.
     
     Please use the new ones whenever possible.

 * **2016-02-02:** NorHei [[b5a4e22](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b5a4e226787e613b588547e4916c3b65f561a0bd)]
   > Just some file formating before doing changes .

 * **2016-02-02:** instantflorian [[0dd38e0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0dd38e0071aa0b3b68c039d4d7413597515b34dd)]
   > Page level limit correction in wblink, thx to bernd

 * **2016-02-02:** NorHei [[b2d541a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b2d541a0b87f48cb436e7f61b4203e92f9917464)]
   > Fixed some more Font awesome loading issues.

 * **2016-02-02:** NorHei [[3d4e2ab](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3d4e2abfd5270cd021ddc8044c65882b7a4ec3a3)]
   > Argos theme dynamic icons in admintools.
     Plus some Font awesome loading issues.

 * **2016-02-02:** NorHei [[8b33b63](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8b33b63cdf2da707b31cfaa1e9dc2ce878b5ba5f)]
   > Moved Font Awesome to /includes/

 * **2016-02-01:** NorHei [[c7a846c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c7a846ca6193da92e5ecba3538ceeb40011cb7ff)]
   > Custom Admintool Icons
     The template var {TOOL_ICON} now is available in admintools template.
     
     The admintool.htt from Flat Theme is adapted to this.

 * **2016-02-01:** NorHei [[6848fd6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6848fd6859f64e042c167f4b043a096712c8dc8f)]
   > Adapted theme.css for use of individual icons in admintools
     Advanced Flat theme .
     
     Override automatic addition of icon on admintools
     Line 2168
     
     Added individual icons to the rotate and scale effect
     Line 1984
     
     Styling for the individual Icon
     Line  1889
     
     It was hard to figure out how to do this , thats the reason
     for the detailed description.

 * **2016-02-01:** NorHei [[2f689d4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2f689d486e408e5836541caade7bdab076c290b6)]
   > Added Font Awesome icons to all admin tools for use in custom icons.
     Added icon code to info.php

 * **2016-02-01:** NorHei [[aae2504](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/aae2504cc974b9a88c9ff413580bc9e395f98c3e)]
   > Review for frontend-functions.php , small fix for topics
     Made a first rewiew of the frontend functions and as a
     result i had to remove a few old lines in topics.

 * **2016-02-01:** NorHei [[7531193](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/75311938fd9cb08234f587bc2840c59b4de27d95)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-01-29:** Bianka Martinovic [[4d0db19](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4d0db193753bff87e36a53dfa7f76c54bd90b448)]
   > fixed $language_name

 * **2016-01-29:** NorHei [[b2bd575](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b2bd575bc2b76e478ef2013f6bb13aac0a47060a)]
   > Just a nother small cleanup for index.php
     You now schould be able to understand the whole file by just reading
     it from top to the end .
     
     Its now even possible to see the structure how WB is actual working.
     A bit strange i think :-)

 * **2016-01-25:** Bianka Martinovic [[2435bbc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2435bbca4883669e611e2bad91d778469eefff26)]
   > Set version to 2.0.0

 * **2016-01-25:** NorHei [[7736758](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7736758455896a81ce2e7865ebac211969ba61c0)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-01-25:** NorHei [[6a27524](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6a27524563287eba666e7b4fd9ea386e5f86aba5)]
   > Could not create new droplets #107
     New Droplets are generated in  add_droplet.php.
     
     In tools.php  line 29 they are deleted again, always ...
     So the query in line 131 cannot return any values for this droplet as
     its already deleted .
     So the hidden field cannot be populated and no droplet_id is sent to save.
     
     So the Droplet is created , deleted , and then tried to edit ...
     
     I added an if clause that only deletes the empty droplets on normal tool
     view, whithout do=something. This should do it.  Hopefully.

 * **2016-01-25:** instantflorian [[e44264d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e44264d8d857b3c31e8495649076ede373afc62f)]
   > Spelling correction

 * **2016-01-25:** NorHei [[711ebf3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/711ebf3c5f55be19dfe65d90d6bf29d1b0dd0be9)]
   > Removed loading of jquery_frontend.js in Template Directory
     This is useless , just load your Stuff in the Template

 * **2016-01-25:** NorHei [[ba90896](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ba90896db817d1231052730ec97f89249177a893)]
   > mdcr.js is loaded by its output filter , not needed in frontend functions
     Removed mdcr.js in register_frontend_modfiles() as it is already loaded by
     mail filter outputfilter.

 * **2016-01-25:** NorHei [[912c484](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/912c4849486d16bbe611dcbb321fc42de8945e59)]
   > Frontend Functions, JS System Vars are now loaded whithout Jquery too
     And they are loaded only once.

 * **2016-01-24:** NorHei [[073959e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/073959e7ac906cb8871320d7d58c5ffdace7a6f1)]
   > Added jquery migrate to frontend functions
     The default now should support most browsers and most jquery plugins.
     At least this is the most compatible solution right now.
     Jquery 1.12.0 and migrate 1.3.0.
     
     In frontend we use migrate-min, if you need Console messages , please
     use the not minimized version.
     
     NExt Jquery upgrade will be done when Jquery 3 is really really stable and
     most modules are at least Jquery 2 Compatible.

 * **2016-01-24:** NorHei [[584b03a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/584b03a22faf7a9977de624b75c6c80627546b21)]
   > Added Jquery Migrate to the backend.

 * **2016-01-24:** NorHei [[5005fc4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5005fc445e9393120735026f1821bd1f97bfde9e)]
   > Set Jquery version to 1.12.0 added Jquery migrate 1.3.0.
     1.12 got same api but wider browser support, at least thats the
     statement of Jquery team.
     
     I will try to load debug version of migrate if WB_ DEBUG is
     turned on .

 * **2016-01-24:** NorHei [[4b8b23d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4b8b23d86a8e1a8d1572895165789cf8f67a5651)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-01-24:** NorHei [[fbf6c93](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fbf6c936d071dd4938782184c4517cbefc19f530)]
   > We dont need a collection of old Jquery

 * **2016-01-23:** cwsoft [[3b3240d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3b3240d3fd836dcda46aeccd15ad9425e3778136)]
   > Updated CHANGELOG.md

 * **2016-01-23:** NorHei [[c9521da](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c9521da92d9cfeae7dd3e0af05adb94d4968429b)]
   > ckeditor 4.5.5 loading css three times
     From :
     https://github.com/qssocial/WebsiteBaker_CommunityEdition/commit/ddb56403b57877653b245ede47d4c5f3096d3278
     
     1. Ckeditor loading tree times css !.
     2. CKEditor uses the functions "LoadOnFly" (we do not have it) for
     loading css.
     
     Therefore, "backend.css" not worked properly.
     I added to ckeditor.php in "private function init ()" loading of
     "backend.css" and removed ln 360
     " // $ this-> loadBackendCss ();"
     Please see if it works correctly.
     
     <?php
     /*
     * Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
     * For licensing, see LICENSE.html or http://ckeditor.com/license
     */
     
     /**
      * \brief CKEditor class that can be used to create editor
      * instances in PHP pages on server side.
      * @see http://ckeditor.com
      *
      * Sample usage:
      * @code
      * $CKEditor = new CKEditor();
      * $CKEditor->editor("editor1", "<p>Initial value.</p>");
      * @endcode
      */
     class CKEditor
     {
         /**
          * The version of %CKEditor.
          */
         const version = '4.5.5';
         /**
          * A constant string unique for each release of %CKEditor.
          */
         const timestamp = ' b34ea4d';
         /**
          * A string indicating the creation date of %CKEditor.
          * Do not change it unless you want to force browsers to not use previously cached version of %CKEditor.
          */
         public $timestamp = "b34ea4d";
     
         /**
          * URL to the %CKEditor installation directory (absolute or relative to document root).
          * If not set, CKEditor will try to guess it's path.
          *
          * Example usage:
          * @code
          * $CKEditor->basePath = '/ckeditor/';
          * @endcode
          */
         public $basePath;
         /**
          * An array that holds the global %CKEditor configuration.
          * For the list of available options, see http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
          *
          * Example usage:
          * @code
          * $CKEditor->config['height'] = 400;
          * // Use @@ at the beggining of a string to ouput it without surrounding quotes.
          * $CKEditor->config['width'] = '@@screen.width * 0.8';
          * @endcode
          */
         public $config = array();
         /**
          * A boolean variable indicating whether CKEditor has been initialized.
          * Set it to true only if you have already included
          * &lt;script&gt; tag loading ckeditor.js in your website.
          */
         public $initialized = false;
         /**
          * Boolean variable indicating whether created code should be printed out or returned by a function.
          *
          * Example 1: get the code creating %CKEditor instance and print it on a page with the "echo" function.
          * @code
          * $CKEditor = new CKEditor();
          * $CKEditor->returnOutput = true;
          * $code = $CKEditor->editor("editor1", "<p>Initial value.</p>");
          * echo "<p>Editor 1:</p>";
          * echo $code;
          * @endcode
          */
         public $returnOutput = false;
         /**
          * An array with textarea attributes.
          *
          * When %CKEditor is created with the editor() method, a HTML &lt;textarea&gt; element is created,
          * it will be displayed to anyone with JavaScript disabled or with incompatible browser.
          */
         public $textareaAttributes = array( "rows" => 8, "cols" => 60 );
         /**
          * An array that holds event listeners.
          */
         private $events = array();
         /**
          * An array that holds global event listeners.
          */
         private $globalEvents = array();
     
         /**
          * Main Constructor.
          *
          *  @param $basePath (string) URL to the %CKEditor installation directory (optional).
          */
         function __construct($basePath = null) {
             if (!empty($basePath)) {
                 $this->basePath = $basePath;
             }
         }
     
         /**
          * Creates a %CKEditor instance.
          * In incompatible browsers %CKEditor will downgrade to plain HTML &lt;textarea&gt; element.
          *
          * @param $name (string) Name of the %CKEditor instance (this will be also the "name" attribute of textarea element).
          * @param $value (string) Initial value (optional).
          * @param $config (array) The specific configurations to apply to this editor instance (optional).
          * @param $events (array) Event listeners for this editor instance (optional).
          *
          * Example usage:
          * @code
          * $CKEditor = new CKEditor();
          * $CKEditor->editor("field1", "<p>Initial value.</p>");
          * @endcode
          *
          * Advanced example:
          * @code
          * $CKEditor = new CKEditor();
          * $config = array();
          * $config['toolbar'] = array(
          *     array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
          *     array( 'Image', 'Link', 'Unlink', 'Anchor' )
          * );
          * $events['instanceReady'] = 'function (ev) {
          *     alert("Loaded: " + ev.editor.name);
          * }';
          * $CKEditor->editor("field1", "<p>Initial value.</p>", $config, $events);
          * @endcode
          */
         public function editor($name, $value = "", $config = array(), $events = array())
         {
             $attr = "";
             foreach ($this->textareaAttributes as $key => $val) {
                 $attr.= " " . $key . '="' . str_replace('"', '&quot;', $val) . '"';
             }
             $out = "<textarea id=\"" . $name . "\" name=\"" . $name . "\"" . $attr . ">" . htmlspecialchars($value) . "</textarea>\n";
             if (!$this->initialized) {
                 $out .= $this->init();
             }
     
             $_config = $this->configSettings($config, $events);
             $js = $this->returnGlobalEvents();
     
             if (!empty($_config))
                 $js .= "CKEDITOR.replace('".$name."', ".$this->jsEncode($_config).");";
             else
                 $js .= "CKEDITOR.replace('".$name."');";
     
             $out .= $this->script($js);
     
             if (!$this->returnOutput) {
                 print $out;
                 $out = "";
             }
             return $out;
         }
     
         /**
          * Replaces a &lt;textarea&gt; with a %CKEditor instance.
          *
          * @param $id (string) The id or name of textarea element.
          * @param $config (array) The specific configurations to apply to this editor instance (optional).
          * @param $events (array) Event listeners for this editor instance (optional).
          *
          * Example 1: adding %CKEditor to &lt;textarea name="article"&gt;&lt;/textarea&gt; element:
          * @code
          * $CKEditor = new CKEditor();
          * $CKEditor->replace("article");
          * @endcode
          */
         public function replace($id, $config = array(), $events = array())
         {
             $out = "";
             if (!$this->initialized) {
                 $out .= $this->init();
             }
     
             $_config = $this->configSettings($config, $events);
     
             $js = $this->returnGlobalEvents();
             if (!empty($_config)) {
                 $js .= "CKEDITOR.replace('".$id."', ".$this->jsEncode($_config).");";
             }
             else {
                 $js .= "CKEDITOR.replace('".$id."');";
             }
     
             $out .= $this->script($js);
             if (!$this->returnOutput) {
                 print $out;
                 $out = "";
             }
             return $out;
         }
     
         /**
          * Replace all &lt;textarea&gt; elements available in the document with editor instances.
          *
          * @param $className (string) If set, replace all textareas with class className in the page.
          *
          * Example 1: replace all &lt;textarea&gt; elements in the page.
          * @code
          * $CKEditor = new CKEditor();
          * $CKEditor->replaceAll();
          * @endcode
          *
          * Example 2: replace all &lt;textarea class="myClassName"&gt; elements in the page.
          * @code
          * $CKEditor = new CKEditor();
          * $CKEditor->replaceAll( 'myClassName' );
          * @endcode
          */
         public function replaceAll($className = null)
         {
             $out = "";
             if (!$this->initialized) {
                 $out .= $this->init();
             }
     
             $_config = $this->configSettings();
     
             $js = $this->returnGlobalEvents();
             if (empty($_config)) {
                 if (empty($className)) {
                     $js .= "CKEDITOR.replaceAll();";
                 }
                 else {
                     $js .= "CKEDITOR.replaceAll('".$className."');";
                 }
             }
             else {
                 $classDetection = "";
                 $js .= "CKEDITOR.replaceAll( function(textarea, config) {\n";
                 if (!empty($className)) {
                     $js .= "    var classRegex = new RegExp('(?:^| )' + '". $className ."' + '(?:$| )');\n";
                     $js .= "    if (!classRegex.test(textarea.className))\n";
                     $js .= "        return false;\n";
                 }
                 $js .= "    CKEDITOR.tools.extend(config, ". $this->jsEncode($_config) .", true);";
                 $js .= "} );";
     
             }
     
             $out .= $this->script($js);
     
             if (!$this->returnOutput) {
                 print $out;
                 $out = "";
             }
     
             return $out;
         }
     
         /**
          * Adds event listener.
          * Events are fired by %CKEditor in various situations.
          *
          * @param $event (string) Event name.
          * @param $javascriptCode (string) Javascript anonymous function or function name.
          *
          * Example usage:
          * @code
          * $CKEditor->addEventHandler('instanceReady', 'function (ev) {
          *     alert("Loaded: " + ev.editor.name);
          * }');
          * @endcode
          */
         public function addEventHandler($event, $javascriptCode)
         {
             if (!isset($this->events[$event])) {
                 $this->events[$event] = array();
             }
             // Avoid duplicates.
             if (!in_array($javascriptCode, $this->events[$event])) {
                 $this->events[$event][] = $javascriptCode;
             }
         }
     
         /**
          * Clear registered event handlers.
          * Note: this function will have no effect on already created editor instances.
          *
          * @param $event (string) Event name, if not set all event handlers will be removed (optional).
          */
         public function clearEventHandlers($event = null)
         {
             if (!empty($event)) {
                 $this->events[$event] = array();
             }
             else {
                 $this->events = array();
             }
         }
     
         /**
          * Adds global event listener.
          *
          * @param $event (string) Event name.
          * @param $javascriptCode (string) Javascript anonymous function or function name.
          *
          * Example usage:
          * @code
          * $CKEditor->addGlobalEventHandler('dialogDefinition', 'function (ev) {
          *     alert("Loading dialog: " + ev.data.name);
          * }');
          * @endcode
          */
         public function addGlobalEventHandler($event, $javascriptCode)
         {
             if (!isset($this->globalEvents[$event])) {
                 $this->globalEvents[$event] = array();
             }
             // Avoid duplicates.
             if (!in_array($javascriptCode, $this->globalEvents[$event])) {
                 $this->globalEvents[$event][] = $javascriptCode;
             }
         }
     
         /**
          * Clear registered global event handlers.
          * Note: this function will have no effect if the event handler has been already printed/returned.
          *
          * @param $event (string) Event name, if not set all event handlers will be removed (optional).
          */
         public function clearGlobalEventHandlers($event = null)
         {
             if (!empty($event)) {
                 $this->globalEvents[$event] = array();
             }
             else {
                 $this->globalEvents = array();
             }
         }
     
         /**
          *
          *
          * @param
          */
         protected function loadBackendCss(  )
         {
             $modPathName = basename(dirname(__DIR__));
             $out  = "<script type=\"text/javascript\">";
             $out .= "//<![CDATA[\n";
             $out .= 'if( document.querySelectorAll(".cke") ) {'
                   . 'LoadOnFly("head", WB_URL+"/modules/'.$modPathName.'/backend.css");' // We dont have function LoadOnFly / Krzysztof Winnicki
                   . '    }';
             $out .= "\n//]]>";
             $out .= "</script>\n";
     
             print $out;
         }
     
         /**
          * Prints javascript code.
          *
          * @param string $js
          */
         private function script($js)
         {
             //$this->loadBackendCss(); // ! loading tree times - css need load one time in init / Krzysztof Winnicki
             $out = "<script type=\"text/javascript\">";
             $out .= "//<![CDATA[\n";
             $out .= $js;
             $out .= "\n//]]>";
             $out .= "</script>\n";
             return $out;
         }
     
         /**
          * Returns the configuration array (global and instance specific settings are merged into one array).
          *
          * @param $config (array) The specific configurations to apply to editor instance.
          * @param $events (array) Event listeners for editor instance.
          */
         private function configSettings($config = array(), $events = array())
         {
             $_config = $this->config;
             $_events = $this->events;
             if (is_array($config) && !empty($config)) {
                 $_config = array_merge($_config, $config);
             }
     
             if (is_array($events) && !empty($events)) {
                 foreach ($events as $eventName => $code) {
                     if (!isset($_events[$eventName])) {
                         $_events[$eventName] = array();
                     }
                     if (!in_array($code, $_events[$eventName])) {
                         $_events[$eventName][] = $code;
                     }
                 }
             }
     
             if (!empty($_events)) {
                 foreach($_events as $eventName => $handlers) {
                     if (empty($handlers)) {
                         continue;
                     }
                     else if (count($handlers) == 1) {
                         $_config['on'][$eventName] = '@@'.$handlers[0];
                     }
                     else {
                         $_config['on'][$eventName] = '@@function (ev){';
                         foreach ($handlers as $handler => $code) {
                             $_config['on'][$eventName] .= '('.$code.')(ev);';
                         }
                         $_config['on'][$eventName] .= '}';
                     }
                 }
             }
     
             return $_config;
         }
     
         /**
          * Return global event handlers.
          */
         private function returnGlobalEvents()
         {
             static $returnedEvents;
             $out = "";
     
             if (!isset($returnedEvents)) {
                 $returnedEvents = array();
             }
     
             if (!empty($this->globalEvents)) {
                 foreach ($this->globalEvents as $eventName => $handlers) {
                     foreach ($handlers as $handler => $code) {
                         if (!isset($returnedEvents[$eventName])) {
                             $returnedEvents[$eventName] = array();
                         }
                         // Return only new events
                         if (!in_array($code, $returnedEvents[$eventName])) {
                             $out .= ($code ? "\n" : "") . "CKEDITOR.on('". $eventName ."', $code);";
                             $returnedEvents[$eventName][] = $code;
                         }
                     }
                 }
             }
     
             return $out;
         }
     
         /**
          * Initializes CKEditor (executed only once).
          */
         private function init()
         {
             static $initComplete;
             $out = "";
     
             if (!empty($initComplete)) {
                 return "";
             }
     
             if ($this->initialized) {
                 $initComplete = true;
                 return "";
             }
     
             $args = "";
             $ckeditorPath = $this->ckeditorPath();
     
             if (!empty($this->timestamp) && $this->timestamp != "%"."TIMESTAMP%") {
                 $args = '?t=' . $this->timestamp;
             }
     
             // Skip relative paths...
             if (strpos($ckeditorPath, '..') !== 0) {
                 $out .= $this->script("window.CKEDITOR_BASEPATH='". $ckeditorPath ."';");
             }
     
             // load one time css / Krzysztof Winnicki
             $modPathName = basename(dirname(__DIR__));
             //$out  .= '<link href="'.WB_URL.'/modules/'.$modPathName.'/backend.css" rel="stylesheet" type="text/css" /> ';
             $out  = "<script type=\"text/javascript\">";
             $out  .= ' var styles = document.createElement("link");';
             $out  .= ' styles.rel = "stylesheet";';
             $out  .= ' styles.type = "text/css";';
             $out  .= ' styles.href = "'.WB_URL.'/modules/'.$modPathName.'/backend.css'.'";';
             $out .= "//<![CDATA[\n";
             $out .= ' if ( document.querySelectorAll(".cke") ) { ';
             $out .= 'document.getElementsByTagName("head")[0].appendChild(styles);'. '    } ';
             $out .= "\n//]]>";
             $out .= "</script>";
     
             $out .= "<script type=\"text/javascript\" src=\"" . $ckeditorPath . 'ckeditor.js' . $args . "\"></script>\n";
     
             $extraCode = "";
             if ($this->timestamp != self::timestamp) {
                 $extraCode .= ($extraCode ? "\n" : "") . "CKEDITOR.timestamp = '". $this->timestamp ."';";
             }
             if ($extraCode) {
                 $out .= $this->script($extraCode);
             }
     
             $initComplete = $this->initialized = true;
     
             return $out;
         }
     
         /**
          * Return path to ckeditor.js.
          */
         private function ckeditorPath()
         {
             if (!empty($this->basePath)) {
                 return $this->basePath;
             }
     
             /**
              * The absolute pathname of the currently executing script.
              * Note: If a script is executed with the CLI, as a relative path, such as file.php or ../file.php,
              * $_SERVER['SCRIPT_FILENAME'] will contain the relative path specified by the user.
              */
             if (isset($_SERVER['SCRIPT_FILENAME'])) {
                 $realPath = dirname($_SERVER['SCRIPT_FILENAME']);
             }
             else {
                 /**
                  * realpath - Returns canonicalized absolute pathname
                  */
                 $realPath = realpath( './' ) ;
             }
     
             /**
              * The filename of the currently executing script, relative to the document root.
              * For instance, $_SERVER['PHP_SELF'] in a script at the address http://example.com/test.php/foo.bar
              * would be /test.php/foo.bar.
              */
             $selfPath = dirname($_SERVER['PHP_SELF']);
             $file = str_replace("\\", "/", __FILE__);
     
             if (!$selfPath || !$realPath || !$file) {
                 return "/ckeditor/";
             }
     
             $documentRoot = substr($realPath, 0, strlen($realPath) - strlen($selfPath));
             $fileUrl = substr($file, strlen($documentRoot));
             $ckeditorUrl = str_replace("ckeditor_php5.php", "", $fileUrl);
     
             return $ckeditorUrl;
         }
     
         /**
          * This little function provides a basic JSON support.
          *
          * @param mixed $val
          * @return string
          */
         private function jsEncode($val)
         {
             if (is_null($val)) {
                 return 'null';
             }
             if (is_bool($val)) {
                 return $val ? 'true' : 'false';
             }
             if (is_int($val)) {
                 return $val;
             }
             if (is_float($val)) {
                 return str_replace(',', '.', $val);
             };
             if (is_array($val) || is_object($val)) {
                 if (is_array($val) && (array_keys($val) === range(0,count($val)-1))) {
                     return '[' . implode(',', array_map(array($this, 'jsEncode'), $val)) . ']';
                 }
                 $temp = array();
                 foreach ($val as $k => $v){
                     $temp[] = $this->jsEncode("{$k}") . ':' . $this->jsEncode($v);
                 }
                 return '{' . implode(',', $temp) . '}';
             }
             // String otherwise
             if (strpos($val, '@@') === 0)
                 return substr($val, 2);
             if (strtoupper(substr($val, 0, 9)) == 'CKEDITOR.')
                 return $val;
     
             return '"' . str_replace(array("\\", "/", "\n", "\t", "\r", "\x08", "\x0c", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'), $val) . '"';
         }
     }
     Thanks to Krzysztof Winnicki (qsocial)

 * **2016-01-23:** NorHei [[68cd9c0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/68cd9c054263591db3fe0468a6ed5b649b774ec6)]
   > I am pretty sure this schould be 127.0.0.1 not 129.0.0.1
     Thanks to Krzysztof Winnicki (qsocial)

 * **2016-01-23:** NorHei [[3ee3b8f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3ee3b8fd55c378ff7e6025fc95f8a34a602ab1d2)]
   > Reset button on Wbstats is only available for group admin
     Like Ruud stated its not a good idea of having a client delete
     2 years of statistic data whith an(ok, maybe 2) accidental click(s).
     
     Thanks to Krzysztof Winnicki (qsocial)

 * **2016-01-22:** NorHei [[adef5b0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/adef5b0982f43670f2af7bddaae524d0a09ef9e3)]
   > Removed some unneeded requires for pclzip.

 * **2016-01-20:** NorHei [[7f7a228](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7f7a228a7ad924f3006e079ff0c04d96e941b1b6)]
   > Revert "wblink - correction short url"
     Its not wanted to change all single places in WBCE where Urls are
     generated as the shorturl filter takes care of this.
     
     As its still possible to have manual entered URLs for example,
     the filter is needed anyway.
     
     This reverts commit 6e97205e594468b33eaa6b6effefbfecba22fb9c.

 * **2016-01-20:** NorHei [[7495feb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7495feb24600c59899b77d62201601bd0d7aa909)]
   > Merge pull request #102 from qssocial/master
     Commiting pull from qsocial testing enviroment .
     
     NEw version of CKE now supports mobile devices , dont use any newer version as it got bugs. 
     Updated Jquery 
     Lots of updates for WBstats and Topics.
     New reset button for WBStats .
     Topics got new Jcrop.
 * **2016-01-19:** qssocial [[6e97205](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6e97205e594468b33eaa6b6effefbfecba22fb9c)]
   > wblink - correction short url

 * **2016-01-19:** qssocial [[e309fcb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e309fcb8c4c5df9d1d99d87c659d6ec144da6218)]
   > Last change

 * **2016-01-19:** NorHei [[7b34240](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7b342406fb097e6559b8926a303aec14e88423cc)]
   > Missing var $error_code , missing break in switch.

 * **2016-01-18:** NorHei [[34a4202](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/34a4202455ef986ecfade42c28bb3eadc4bd6617)]
   > Again #68
     Changed it to look like the statement around line 429

 * **2016-01-18:** NorHei [[0b9540f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0b9540f82dba814f771b953fe9b28b22e6c2ec56)]
   > ELSEIF have same conditions #68
     admin\pages\settings\settings.php
     Ln:331 = If and ELSEIF have same conditions
     Ln:334 = If and ELSEIF have same conditions
     
     It seems that the code has been changed to use page_code instead of page
     
     Simply commented out the second unused  option.

 * **2016-01-18:** NorHei [[e33d183](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e33d18387a7000b66c0767e6b558dbbd7100da5a)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-01-18:** NorHei [[957d7b8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/957d7b815c38d41a0a36bafc2b3b617e5f6522db)]
   > Removed function get_module_language_file readded
     Damm this thing really was needed by some modules.
     Here it is again.

 * **2016-01-18:** instantflorian [[057bb81](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/057bb816eb7ed1497c600f3ce9f9cecefc306237)]
   > Fixes for Topics
     prevent linking to deleted items and remove non-revertable default
     headlines for prev/next

 * **2016-01-18:** NorHei [[d6084fc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d6084fcc02c8952cc81294e7bdac858c1db1ac85)]
   > Last one was a buggyy bugfix

 * **2016-01-17:** qssocial [[4d13d82](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4d13d82f5ce2365ffd24996aeb07d79a66dddfc1)]
   > Class database throws error on field_exits(),  Topics require function.php

 * **2016-01-17:** NorHei [[b38b465](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b38b465f2728c3e67f2abd5776c1d3f6ab140ec0)]
   > Class database throws error on field_exits()
     Fatal error: Call to a member function numRows() on a non-object in S:\WBCE(php7Sql-strict)\html\framework\class.database.php on line 196
     
     Schould be fixed now.

 * **2016-01-17:** qssocial [[9d5260e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9d5260e726305ad00bb1893d0c3cf44efef17627)]
   > Update CKEditor 4.5.5

 * **2016-01-17:** NorHei [[fc7d5d2](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fc7d5d26625b73f51fa6152f0c2f47a5796a5dd0)]
   > Topics require function.php
     http://forum.wbce.org/viewtopic.php?pid=2969#p2969
     forgot to change a functions.php call

 * **2016-01-17:** qssocial [[89b605b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/89b605b06ae11afd1014918eadececf0a4357156)]
   > Update google_sitemap, short.php, htaccess.txt

 * **2016-01-15:** qssocial [[a34c953](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a34c953b296283e09efdc06c79c8039fc58172dc)]
   > change wbstats view.pho button reset

 * **2016-01-14:** qssocial [[02cd1bb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/02cd1bb33661d2f4e2c74e3613f5b8f2ebcbe1df)]
   > save_seealso.php - Fatal error: Cannot redeclare file_list() (previously declared and more
     save_seealso.php
     delete line 18 //require(WB_PATH.'/framework/functions.php');
     Fatal error: Cannot redeclare file_list() (previously declared in
     D:\Programming\www\wamp22ap1\www\wbcegit\framework\functions.php:181) in
     D:\Programming\www\wamp22ap1\www\wbcegit\framework\functions.php on line
     204

 * **2016-01-14:** qssocial [[ea6e3df](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ea6e3df9844eee57883731ee273adfe06a2afb78)]
   > Correction javascript.
     Correction javascript. Change live to on for compatible with jquery
     2.1.4

 * **2016-01-14:** qssocial [[1e63bce](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1e63bced151d4d50d1557e3905575c78f123fe95)]
   > Small change for testing
     + jquery 2.1.4
     + ckeditor 4.55
     change css classes in module wbstats
     + reset button in module wbstats
     small change in module topics
     small change in template advancedThemeWbFlat for jquery ui

 * **2016-01-13:** NorHei [[f40973a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f40973abdaa63aa2254fadf1fbcb94a29eb04c3a)]
   > Admintools permissions
     qsocial was so kind to send me a nice patch that allows to set
     permissions on admin tools. Now only those admin tools that you got
     the permission to use appear in admin tool list.
     If you try to acces tool you are not allowed via direct url , you are
     redirected to admin tool index.

 * **2016-01-13:** Bianka Martinovic [[5ed1e39](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5ed1e39113a268767eca855e2e51fa01e6d418a1)]
   > Fixed URL in line 91 (see #92)

 * **2016-01-11:** NorHei [[42c5732](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/42c573275c7ddb41364adb7786bb4db2e2fd5b97)]
   > MYSQL Strict patch got removed somehow , now hes back in.

 * **2016-01-09:** NorHei [[52807be](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/52807be1fe4623d46f609a479ead2b4c0cc82656)]
   > Added upload Class by Colin Verot (verot.net)
     This is a very nice Upload class whith some pratical image
     handling functions.
     
     More details at :
     http://www.verot.net/php_class_upload.htm
     
     Have fun
     Norbert

 * **2016-01-08:** NorHei [[87d84dd](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/87d84dde54561773760caaad07db60c99eff9c93)]
   > Installer notice that breaks the installer redirect
     Notice: Undefined variable: imports in \modules\topics\install.php on line 305

 * **2016-01-08:** Bianka Martinovic [[1975a7f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1975a7f3add8684a538558ed398cd4dc411890a8)]
   > fixed dirmode (#92)

 * **2016-01-08:** Bianka Martinovic [[f5af77a](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f5af77a4a470de64f2cdb9da087bae4c43598fde)]
   > fixed typo in class.admin.php and menu_link/view.php (#95); converted line endings in droplet RandomImage.php from MAC to DOS; some fixes in functions.inc.php (#92)

 * **2016-01-08:** Bianka Martinovic [[10220fa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/10220fa29bcbd6be8bac57ea1e1e0ccc79be4455)]
   > added missing templates folder

 * **2016-01-08:** NorHei [[d7625a4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d7625a45fe2ce36694acb3ff80b145e7b8a2325d)]
   > Bugfix on menu link

 * **2016-01-07:** Bianka Martinovic [[f0bb2a0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f0bb2a072beaef5839a4927d8ff048fbdb11bff4)]
   > fix for issue #91

 * **2016-01-07:** Bianka Martinovic [[744dbd8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/744dbd8d611ed8deb3958d982c024f95a95003d5)]
   > fix for issue  #94

 * **2016-01-07:** Bianka Martinovic [[6b693b3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6b693b3bdeb20fc22a63a6de0938abeddc001732)]
   > changed some more copyright headers

 * **2016-01-07:** Bianka Martinovic [[b47aef4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b47aef469a50302749ef0e1a0a2b417763e49251)]
   > some more fixes

 * **2016-01-07:** Bianka Martinovic [[8f44b5d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8f44b5dbfddb523b8bad067bd6db53bf94695fdc)]
   > left some debug output (oops)

 * **2016-01-07:** Bianka Martinovic [[1d8340d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1d8340dda0865541227875ff308337b8b55bdec2)]
   > some fixes, removed another file

 * **2016-01-07:** Bianka Martinovic [[164beaf](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/164beaf08a1c4928253fb891985a8a242eb005f7)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_Clone

 * **2016-01-07:** Bianka Martinovic [[a9d9709](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a9d970907ac4f1783283bfc877f5cedb37e9b6a8)]
   > complete review / recoding in progress

 * **2016-01-07:** NorHei [[c2a9215](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c2a9215daf008647258bf392c224196505dd9b6e)]
   > include.php for templates
     Your templates needs some special funtionality to be called before
      the actual templatefile loading  takes place.
     No problem as each template now may have a include.php that
     is executed directly before calling the template.

 * **2016-01-06:** NorHei [[5ab4b48](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5ab4b4800293310698e9446447056f834995ce0a)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-01-06:** NorHei [[8462c23](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8462c23d5b855710fd9de2055d095005b36cf13b)]
   > Headers already sent , when droplets backups are empty.
     Warning: Cannot modify header information - headers already sent by (output started at /var/www/web207/html/wbce_norbert_heimsath_de/modules/phplib/classes/phplib/template.inc:668) in /var/www/web207/html/wbce_norbert_heimsath_de/modules/droplets/manage_backups.php on line 61
     
     NEver mind i fixed it ...

 * **2016-01-06:** instantflorian [[9ee8aa7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9ee8aa794d69515e4fbd9f867c244e92cbeee5fc)]
   > Branding issue

 * **2016-01-06:** NorHei [[6557d9f](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6557d9f4a0ee8cded526f15eb183138a1e2ae187)]
   > Missing default values in Droplet function file
     Notice: Undefined variable: description in D:\Programming\www\wamp22ap1\www\wbce12\modules\droplets\functions.inc.php on line 205
     
     Notice: Undefined variable: usage in D:\Programming\www\wamp22ap1\www\wbce12\modules\droplets\functions.inc.php on line 205
     
     Still issue #88
     
     Thanks to qssocial

 * **2016-01-06:** NorHei [[ee13ea8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ee13ea82bcc91e368b9f8393e7b7ab0eaf39aa72)]
   > Typo in last change of class admin

 * **2016-01-06:** NorHei [[7bd62ed](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/7bd62edfccb641d426b743b173110b843cebe32e)]
   > Backup tool file not downloading
     Warning: Cannot set max_execution_time above master value of 45
     (tried to set 60) in
     /var/www/web913/html/eso/wbce0106/modules/backup/includes/backup-sql.php
     on line 26
     
     Fixed by supppressing warning. As trying to increase timelimit is
     extremely usefull.

 * **2016-01-06:** instantflorian [[95d5777](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/95d5777d4e8b53be5f3f4df0cc2bc51addaefe1e)]
   > Design fixes to installer, fix for install error, update language files of backup tool

 * **2016-01-05:** NorHei [[6d54341](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6d543416031c182a2a34754533383cd1d08d4b9b)]
   > Cloning droplets #88
     Cloning droplets #88
     When i try to Clone Droplets i get a nice bunch of errors .

 * **2016-01-05:** NorHei [[ba953c9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ba953c97530d39e1378466dfb869a7b7a0d1dc8f)]
   > Install and short_url
     Install and short_url #83
     
     Install was already fixed.
     
     For SHORT_URL:
     Class.admin add
     line 102
     add
     if (OPF_SHORT_URL) {$ view_url = WB_URL. $ row ['link']; }
     
     view.php menu_link
     add
     line 56
     if (OPF_SHORT_URL) {$ target_url = WB_URL. $ target_page_link. $ anchor; }
     
     Both added !
     
     Thanks to qssocial!

 * **2016-01-05:** NorHei [[54a0473](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/54a0473cc732ba16e6c7b012f7ff1e23ae06159a)]
   > Uncaught TypeError: Cannot read property 'style' of null
     Issue #87
     
     Uncaught TypeError: Cannot read property 'style' of null
     and
     browser.msie
     
     Thanks to qssocial!!!

 * **2016-01-05:** NorHei [[c0de5b8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/c0de5b86cc17ea109ed6da3ad4e0a46d53d7229b)]
   > Fix for outputfilter upgrade.php
     Notice:  Use of undefined constant OPF_MAILTO_FILTER - assumed
     'OPF_MAILTO_FILTER' in
     S:\WBCE(phpweb)\html\modules\output_filter\filter_routines.php on line 46
     
     Thanks to Evaki !

 * **2016-01-05:** NorHei [[0a86cd4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0a86cd498d0ebc6ee5f3e39ddd65e22645332a55)]
   > Admintools loading info.php  and allow for no_page via info.php.
     Admintools now load the info.php for additional avaialble information.
     This allows for storing additional options in info.php.
     
     On special option is supported now , you can suppress the full WB output in the Backend via
     $module_nopage = true;
     in info.php
     
     This was needed for Webadmin tool and maybe is needed for other tools too.
     That way webadmin now can run under userrights restrictions of WB as a protected include.
     For those who like to edit on a full scale texteditor there a several browser plugins
     that allow to use you local editor for working on a textarea.

 * **2016-01-05:** NorHei [[d9e382c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d9e382cee26c779f018a29e9315f0f6efb27d642)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2016-01-05:** NorHei [[f23b50c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f23b50c4043b7724cf4db392fc2d7aaff2202f12)]
   > Some more fixes for output filter upgrade.php and initialize.php

 * **2016-01-04:** NorHei [[4a75516](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4a755160f8414dd91b1331f6e3ca2a919ac12191)]
   > Some fixes for output filter upgrade.php

 * **2016-01-03:** instantflorian [[034fc51](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/034fc510dfdcd0dd9de9e95379203016cb25582d)]
   > removed fixed width of filename input field

 * **2016-01-03:** instantflorian [[43c5403](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/43c5403c45b53648034d87a58b854d829963180c)]
   > Fixes to install.php and de.php

 * **2016-01-03:** NorHei [[0cbee82](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/0cbee82dc6f985da2eec6dbc2999c6b1a63e9184)]
   > Allow external links and wblinks in page settings now
     You are now allowed enter external links or wblinks in the page settings.
     
     If you just enter :
     http://www.google.de
     or
     [wblink2]
     in the "filename" field in page settings.
     
     The link is shown in show_menu functions.
     
     This is made because i wanted menulinks that are actually real links,
     therefore we needed this functionality in the core first.
     
     But now i really think of hiding this filename field, as it can be
     very confusing to plain users.

 * **2016-01-03:** NorHei [[cb6f6a6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/cb6f6a6956c490fcfb449a5eed8145c9774a90bc)]
   > Moved preprocess to class wb as we needed some [wblinkxxx] in the backend.

 * **2016-01-02:** NorHei [[f072e44](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f072e44da7eb3230c616870510acdbdafbd83594)]
   > Fixed handling of wblink in class wb page_link()

 * **2016-01-01:** NorHei [[4e78912](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4e7891276b0240945de595382052de7cb8f3bce4)]
   > First Review for backup module
     - Now use a template file
     - tool.php is now more or less apeform like.
     - Manual include of libs and files mostly removed
     - No more manual access check needed
     - FTAN implemented
     - Cleaned up folder Structure
     - Uses "no_page" now.
     - Added return to admin tools button.
     - No more risk that an attacker attacks the backup-sql.php
     
     Using an include isn't the perfect solution(can't be reused),
     but it certainly will do for now.

 * **2016-01-01:** NorHei [[799ede7](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/799ede71de28c1a56b9a12122314dd6de88db86f)]
   > New function for admin tools no_page.
     I had the problem that i was working on a admin tool that was offering a
     file for download. the problem was that calling new admin() produced some
     headers and output cause of silly PHPlib.
     
     Now we have an additional post variable "no_page" to suppress all admin
     output.  Simply add:
     
     <input type="hidden" name="no_page" value="no_page" />
     
     To the template and you are done.
     
     This was needed to get modules like backup to the standard apeform format

 * **2016-01-01:** NorHei [[4407a4e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4407a4e1419c66e6d833be6b40995004bfa804b4)]
   > Output filter updated upgrade install and uninstall and upgrade script
     Should now upgrade smooth and easy

 * **2016-01-01:** NorHei [[d87cd45](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d87cd451851dd3cf65a44e887813cdab7944372b)]
   > Added German Translation vor output_filter

 * **2015-12-31:** NorHei [[3b3ca47](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/3b3ca479375aa4ef1777511b3fa952eb1d8a7d48)]
   > Output filter  modul, language files

 * **2015-12-30:** NorHei [[447d0bf](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/447d0bf5382b388cffa086c31174bd72b78f5416)]
   > Full rework of old outputfilter.
     - You now can switch ALL Filter on and of, even droplets and insert.
     - Fixed css to head as the old version only functioned whith XHTML,
       not html4 neither html5. And to be true it wasn't really functional
       at all.
     - Removed the bullshit Ftan_Supported File.(even though ftan is supported)
     - Removed useless/halfbaken filter API File.
     - Tool now runns whith Settings Class so no extra DB Table is needed.
     - All filter can be turned off completely.(for interaction whith OPF Dashboard)
     - Short URL is now a filter routine , so there no longer is a need for a
       droplet in the template.
     - Removed that odd mailfilter mojo which used binary filter to set options
       but only had 3 options.
     - Some smaller tweaks on several filter.
     - Some general cleanup.
     - filter_functions-php  is the main filter file again, and index.php is
       changed to use it.
     
     For me its fist time the old WB filter feel usefull and functional at least
     to some extend, and more importand you can deactivate what you don't need.
     
     Todo:
     - Fetch old DB settings on upgrade, remove old Table.
     - Add new Language Vars to Language files and code
     - Set default settinmgs on install

 * **2015-12-28:** NorHei [[ef1d3c9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ef1d3c9b4ec9ded7deb877662be7c53bc54dd5b3)]
   > Headers already sent on istall+ some more debug
     We had a headers already sent on install class login.
     A missing constant produced a notice and the info.php of wbstats had spaces after a ?>
     
     Added Constant WB_DEBUG for global Debug setting.

 * **2015-12-27:** NorHei [[454adda](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/454adda07bfd09e5dce33b42ed1ef7bf9975b908)]
   > Notice in wbstats/preinit.php if no referer is set.
     Notice: Undefined index: HTTP_REFERER in S:\WBCE(phpweb)\html\modules\wbstats\preinit.php on line 6

 * **2015-12-27:** NorHei [[4de24bb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4de24bb18467a1dfae12b5cd7193173b7228ea04)]
   > TypeError: $(...).poshytip is not a function
     JS error caused by wbstats frontend.js  \#81

 * **2015-12-23:** NorHei [[e516849](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e5168497d1a32d2a93cc4369b88064e3b8c52952)]
   > Small bug caused by removal of require functions.php
     Parse error: syntax error, unexpected 'if' (T_IF) in /var/www/web207/html/wbce_norbert_heimsath_de/admin/start/index.php on line 125
     
     Fixed

 * **2015-12-23:** NorHei [[ec18193](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/ec181934c7903c6bb58f4dac117f26503a576249)]
   > Another run to remove unecessary  require statements
     The last for now

 * **2015-12-23:** NorHei [[6668cf9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/6668cf91636938b99887df9dbdd14f3d4be14359)]
   > Another tour to remove unecessary require calls
     Calls for class.admin.php are handled by autoloader.
     Calls for functions.php are obsolete as its loaded in the /framework/initialize.php

 * **2015-12-23:** NorHei [[edd3f2e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/edd3f2eeb9a6c34fdf1661e15295ef7b527b7b23)]
   > Added /framework/functions.php  to initialize
     As it is loaded in almost any file
     
     require_once(WB_PATH.'/framework/functions.php');

 * **2015-12-23:** NorHei [[953c6f6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/953c6f66df60eb9c328338ddf9fdf44e162e4e23)]
   > Cleanup some index.php and some direct access on some modules

 * **2015-12-23:** NorHei [[e2df6aa](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e2df6aad899b475ffac5d9aed8c77ac7a14c8882)]
   > Removed unnecessary Require statements , quite a lot .

 * **2015-12-23:** NorHei [[9cb0371](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9cb0371bf488385619680938af99033272eafcdf)]
   > Bugfix for moving PHPlib some parts tried to load it manually
     Autoloader takes Care now , removed the manual require, as PHPlib is moved to a module

 * **2015-12-23:** NorHei [[af52d4b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/af52d4b9d7b009af0b2d4123208b57326f994d20)]
   > Bumped Version number to 1.2.0-alpha.1

 * **2015-12-23:** NorHei [[f95a234](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f95a234e53ae296ff5e4a9461b6ad9c424117f29)]
   > Merge remote-tracking branch 'origin/master' into continue

 * **2015-12-21:** NorHei [[dad31b8](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/dad31b8c352f48156f0671f45be44a43d70c0917)]
   > WbStats forgot preinit in function list.

 * **2015-12-21:** NorHei [[a4603a9](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/a4603a94c58644c32be016eee08bead008e20f5a)]
   > Added a WBStats that uses the new Features
     This wbstats no longer needs to be manually added to the template.
     Referer handling is done by the module itself, no need to modyfy the config.php.
     Wbstats now comes whith a beta state frontend module, so you can dieplay stats
     whithout granting BE access.

 * **2015-12-18:** NorHei [[2dbf460](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2dbf460407abd860a827432a04bb3cb6b63b4c9e)]
   > Deleted the includes that where moved to a module(Twig, PHPlib)

 * **2015-12-18:** NorHei [[513ae63](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/513ae632c35fb42af6f2a43be4f8b93dd73b9b99)]
   > Moved PHPlib(Template) to a module for later removing.
     After removing all old PHP lib templates from the core we will remove this module too.
     But ist still available vor manual install if older Modules require it

 * **2015-12-17:** NorHei [[d633417](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d6334170b2e1e08b2403ad5df3638056eafc03d8)]
   > Moved Twig to a module
     Whith the new module features this was extremely easy.
     Its still initialized in the Init process, only a few lines later.
     
     This serves two purposes. First its a sample modue to implement other
     template engines. Second, core is no longer tied to a template engine.
     Even it its unlikely that Twig will be removed from the corepackage, its
     at least possible.
     
     Have fun
     Norbert

 * **2015-12-17:** NorHei [[e44313d](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e44313d28d892fefe8d7b9dfd9675c9954d36514)]
   > Removed completely unused functions in module.functions.php

 * **2015-12-17:** NorHei [[8f55c69](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/8f55c69b2c79480f2908f4a7385a7134e8d97e97)]
   > Added class I to the list of outputfilters
     Now i still need to add compatibility for the old Frontend/Backend files.

 * **2015-12-17:** NorHei [[509a4ff](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/509a4fffd295e350dd56164f0b2034e4c888ff56)]
   > Added class Settings and class WBAuto(autoloader) whith doxygen documentation

 * **2015-12-17:** NorHei [[910ffe4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/910ffe4363ff1a4e29b6940888392d0ad4b87ed7)]
   > Added classes Insert and I for better handling of JS, CSS, Metas and Title
     Those two classes allow registering of JS, CSS, Metas and Title for later
     use in the template and provides a filter for replacement.
     
     "I" is a simple facade for class Insert here some examples:
     
     //Meta
     I::AddMeta(array (
         "setname"=>"charset",
         "charset"=>"ISO-8859-1"
     ));
     
     //CSS
     I::AddCss (array(
         'setname'=>"font-awesome",
         'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css",
         'media'  =>"screen"
     ));
     I::AddCss (array(
         'setname'=>"local-script2",
         'style'  =>"#htto {position:absolute}"
     ));
     
     //Js
     I::AddJs (array(
         'setname'=>"jquery",
         'position'=>"BodyLow",
         'src'=>"https://code.jquery.com/jquery-2.1.4.min.js"
     ));
     I::AddJs (array(
         'setname'=>"test2",
         'position'=>"HeadLow",
         'src'=>WB_URL."modules/colorbox/js/colorbox.min.js",
         'script'=>"jQuery('a.gallery').colorbox();"
     ));

 * **2015-12-15:** NorHei [[5655bcb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/5655bcbb0865ec8137df80368c908a6c5e7c25a6)]
   > Merge branch 'master' of https://github.com/WBCE/WebsiteBaker_CommunityEdition

 * **2015-12-15:** NorHei [[9adebc0](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/9adebc01ace665acc66728dd1e09679fc22a7a88)]
   > Set version number to stable

 * **2015-12-14:** NorHei [[2e7cd7e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/2e7cd7edf6a4e48c09a29ecca5a4e095c4d4f02c)]
   > Extend loading of module files (andmin/admintoos/tools.php)
     one sql statement needed change.

 * **2015-12-14:** NorHei [[46c40dd](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/46c40dd584436206ebbb74b11d77086a99182e41)]
   > Merge remote-tracking branch 'origin/master' into continue

 * **2015-12-14:** NorHei [[fbe5d25](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/fbe5d25a8833892152c9894fdc7fe835319fac9a)]
   > Extend loading of module files (Made sure that new modules install correct)
     As module could have multiple functions now i first made sure the string is
     kept functional on install(it was by default) and second took care of most
     common typos, spaces behind a comma or at end and beginning of the string.

 * **2015-12-14:** NorHei [[acda464](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/acda46472c58dfbdf4bab0c346ab0d960fa102f9)]
   > Another unneeded  require for pclZip this time.

 * **2015-12-14:** NorHei [[59967c6](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/59967c6719c0a5290c4c60fe3ef1dd8b22f10f39)]
   > Removed the old crumpy header suppression.
     This is no longer needed for WB(CE) as checkFtan runns whithout that bullshit.

 * **2015-12-14:** NorHei [[1b7373e](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1b7373e9b518b0ea93e6933c9483dff5c8eb1c89)]
   > No need to load class admin manually

 * **2015-12-14:** NorHei [[b23d58b](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b23d58b4026226e727e7137b12fc9aedc5eb3ee3)]
   > Yess, i want to see warnings during installation if i activated that in the BE
     And i moved helper Functions to the End.

 * **2015-12-14:** NorHei [[1abe33c](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/1abe33c0b9cd777ac054531dede8a4acf0454b32)]
   > Extend loading of module files (addon_monitor)
     Addon Monitor needed some fixes too for allowing multiple module functions.
     First function mow is used for Icon generation and i guess classification,
     but finally the whole concept needs to be rethought , as modules now can
     be in multiple categories.

 * **2015-12-14:** NorHei [[e909a52](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e909a529f801d511594f90f0b9f1bfdd97cb1f90)]
   > Extend loading of module files (language added module names)
     Added names for module type "initilaize" and "preinit" to the EN.php

 * **2015-12-14:** NorHei [[23269b4](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/23269b4624c0e6f30d326f17222f50557e86ad2c)]
   > Extend loading of module files (refine some corefiles)
     some corefiles could not handle having a CSV ind the modul function (initialize,preinit,tool,snippet)
     
     Now they can :-)

 * **2015-12-14:** NorHei [[08cf3ed](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/08cf3ed37c687393495db982fc6ec56f6c59b11b)]
   > Extend loading of module files (initialize.php, preinit.php)
     Greatly extendes the Loading mechanism for modules.
     
     /framework/initialize.php  now loads the file initialize.php and preinit.php
     if the module info contains "initialize" or "preinit"in $module_function.
     
     Preinit is hooked into initialize process very early, where only the database
     is loaded and nothing else. You even can set important System constants here or
     redefine the Page id. This is needed so things like the multidomain patch can
     be implemented as a module.
     
     Initialize is hooked near the end of the initialize process where most global
     stuff is already set. Autoloader is available , Settings are available, Path
     and other constants is set. This is a good place where you can register classes
     that schould be able to run in FE and BE. Library modules can be made this way.,
     just register your classes here for the autoloader. Classes in include.php only
     are avaialble in frontend right now.
     
     Including include.php has changed too in frontend functions. It now allows for
     loading snippets from any modul that got "snippet" in $module_function.
     This,  together whith initialize and preinit now allows a lot of combinations
     in modules. News can bring their own anynews, slider can bring a nice snipit
     with admintool combination, library modules are possible, multidomain as a module,
     ... and many more options.
     
     This still needs some addons to be fully functional, but those changes come up soon.

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

 * **2015-12-09:** NorHei [[0291384](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/02913846b78239cd0a0f943c2725f441f058fd25)]
   > Removed unused vars $include_head_file and  $include_body_file
     Both where only defined, but not used anywhere in the core.
     So i guess its save to remove em.

 * **2015-12-08:** NorHei [[af71dda](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/af71ddad7dbb9b5d08b1e02bdbce3ea75fef6d81)]
   > Merge remote-tracking branch 'origin/master' into continue

 * **2015-12-08:** instantflorian [[29eab64](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/29eab64cb0c9b226da224202ae2b581527a29b12)]
   > Replace index.html with index.php (and add another missing one)

 * **2015-12-08:** NorHei [[e75fb30](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e75fb30fe9071667710c89388d6cf811681b416a)]
   > Modules: added initialize.php and pre_init.php
     Both files allow the Module author to hook into the initialize process of WB(CE)
     pre_init.php hooks into initialize before anything else than the database
     initialization is done Yo can change System Constants like WB_PATH or change languages
     post parameters or maybe the domain or page_id.  That way even the multifomain patch may
     become a maintainable(via admin tool) modul.
     
     initialize.php is loaded after autoloader is activated , system constants are set, and
     session is activated. You now can login users, change Languages , still change the page_id
     and maybe register some additional system classes as the autoloader is already active.
     
     This is available vor ALL Modules and as a special feature it is available in the backend.
     So its possible to register classes for backend use here.
     
     Have Fun
     Norbert

 * **2015-12-08:** instantflorian [[e31a8eb](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/e31a8eb7b45a36314b22ce8557d077133bf80967)]
   > Create templates directory for miniform module
     to avoid irritating error messages during install

 * **2015-12-08:** NorHei [[d98bebc](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/d98bebcce00d14774645050c691ecefd2576341a)]
   > Constant FUNCTIONS_FILE_LOADED was removed
     It was not used anywhere in the whole WB core +modules.
     So i guess its save to remove.

 * **2015-12-08:** NorHei [[4796ec3](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/4796ec3d2bc53bd1712590773e3792eb314e9c16)]
   > Autoload removed unnecessary require(_once) statements.
     Removed lot of require(_once) statements in the /framework/ dir.
     almost all unecessary ones.

 * **2015-12-08:** NorHei [[b28bd40](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/b28bd40a1692fc1960f7fdab06260912d093fb41)]
   > We now allow include.php for all module.
     This way an admintool is allowed to bring its own frontend functions.
     Page modules can offer additional functions pagewide.
     
     Examples:
     Ruuds Slider that is maintained via an admin tool. Before this you
     had to install the admin tool and the snipit seperately, or do some
     ugly tricks.  Now you can use combinations like this whithout any extra trouble.
     (admin tool whith include.php)
     
     News module whith integrated functions like anynews that are available on any page.
     (page module whith additional include.php)
     
     Or maybe an Editor that comes whith functions for usieng it in frontend Forms.
     (wysiwyg whith include.php))
     
     The same works for gallerys too.
     
     Simply add an include.php to your module, if your module is installed and the file is there , its loaded.
     Although nice to register classes for the autoloader. That way you easyly create a library module.
     
     Hope Module authors have fun whith this.

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
