<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

$module_directory   = 'news_img';
$module_name        = 'News with Images';
$module_function    = 'page';
$module_version     = '4.0.2';
$module_platform    = '1.3';
$module_author      = 'Ryan Djurovich, Rob Smith, Silvia Reins, Martin Hecht, Florian Meerwinck, Bianka Martinovic';
$module_license     = 'GNU General Public License';
$module_description = 'This page type is designed for making a news page with Images and Lightboxeffect.';

/**
 * v4.0.2  - 2020-04-21
 *         - Bianka Martinovic
 *           + if GDLib is missing, a warning will be shown in the backend
 *             and no image resizing will happen
 *
 * v4.0.1  - 2019-06-11
 *         - Florian Meerwinck
 *           + add placeholder SHORT also in detail view and CONTENT_LONG in overview and detail view
 *           + add option to disable gallery completely
 *           + remove outdated webfont formats
 *			 + update readme.html
 *           + remove translated readme.md files (contents in readme.html)
 * v4.0.0  - 2019-05-28 
 *         - Martin Hecht
 *           + make drag&drop work again on WB 2.12
 *           + hide the move-up/move-down arrows again when drag&drop works 
 *             (i.e. when js could be loaded)
 *           + handle IDKEYs for recent WB versions (#44)
 *           + reorganize inclusion of framework functions
 *           + also, remove the framework fallback functions again from functions.inc.php
 *           + and include the framework/functions.php there instead.
 *           + include fallbacks for framework functions for interoperability (fixes #45)
 *           + specify maximum gallery image size in kilobytes (#41)
 *           + minor corrections to the Dutch language support, thanks to Ruud Eisinga, Dev4me
 *         - Florian Meerwinck
 *           + Sanitize group title
 *           + Update jquery.dm-uploader.css
 *           + Show Select File Button also in AR BE Theme, thx to Bernd
 *           + Use FA icons, UI optimizations
 *           + group actions as select
 *           + fix for update script (#40)
 *
 * v3.7.16 - 2019-05-22 
 *         - Martin Hecht
 *           + allow importing settings from other nwi sections  (#35)
 *           + activate / deactivate posts with mass actions (#34)
 *           + set posts active/inactive directly in overview (#34)
 *           + do not use section_id as idkey
 *           + fix idkey issue when required fields are not filled in
 *           + correct two more occurrences of fetch_result for #38
 *           + fix for #39 - we should not use a variable called $link inside delete.php
 *           + fix for notice beim import (#38)
 *           + avoid invalidating the img_id key when deleting the image (#36)
 *           + try to avoid NaN entries in js calendar, e.g. when jscal_format is messed up (#33)
 *           + avoid warnings during deletion of section when files do not exist (#26)
 *           + update post access file when moving posts (#30),  updating file date is needed
 *           + moving posts redirected to an inaccessible backend url (#30)
 *           + fix date for js calendar if textfield is empty (like by default for enddate) (#33)
 *           + make delete_post work again (#31)
 *           + make view.php with group parameter work again
 *           + fix gallery upload with idkeys (parameter for ajax=true was missing) #29
 *           + add ftan support and use idkeys for get-requests
 *         - Florian Meerwinck
 *           + Translation/integration of README
 *
 * v3.7.15 - 2019-05-16 
 *         - Martin Hecht
 *           + add ftan support and use idkeys for get-requests
 *           + sanitize and escape strings, explicitly convert integers
 *           + fix image upload issues
 *           + move pictures from "old" place to the new one
 *           + several improvements for the gallery upload
 *             - add index.php files in the subdirectories of the uploader
 *             - avoid reloading the whole page
 *             - re-add progress-bar and status messages about the upload
 *             - cleanup of unused files (and unused functions and styles)
 *             - impose the correct file size limit and file type on the client side
 *             - display a hint to save changes upon completed upload
 *           + a correction to the previous commit for the fallback case when js disabled
 *           + Ajax/jQuery Uploader for gallery images
 *           + add config.php that allows switching off the second block
 *           + add a confirmation dialog for changing the gallery type
 *           + update language support
 *           + fixes and cleanup:
 *             - block2 fixed: the default value from settings was not applied in details view of a news page
 *             - import: a bit of fine-tuning on the topics placeholders
 *             - a bit of better coding style in import.php
 *           + support deleting several posts at once, just like copying and moving posts
 *           + fixed import of settings for topics
 *             - corrected the db statement
 *             - added proper escaping
 *             - optimized placeholder replacement
 *             - copy topics pictures into nwi gallery
 *           + some improvements for topics import
 *         - Florian Meerwinck
 *           + Minor UI improvements
 *           + Added Readme, changed block2 placement in settings
 *
 * v3.7.14 - 2019-05-02 
 *         - Martin Hecht
 *           + implement importing of topics sections
 *           + add second block in settings which is used if not configured in the post
 *           + when copying posts do not delete the old access file of the source post
 *           + re-add up/down arrows and make them invisible with js
 *           + if js is disabled, drag&drop does not work, but the arrows are shown then
 *           + support importing classical news sections
 *           + bugfixes for the import of nwi sections
 *
 * v3.7.13 - 2019-05-01 
 *         - Martin Hecht
 *           + implement importing of complete sections (in a first step NWI sections)
 *           + when copying posts do not delete the old access file of the source post
 *           + when copying posts set the correct link in database
 *           + show time in modify_post as the timezone used by the current user and store as gmt
 *           + explicitly include language files in the frontend each time using require()
 *           + when copying posts create the access file with the right name
 *           + copy the galleries to the correct folder when copying a post
 *           + use gmtime(date+TIMEZONE) throughout the module when generating output of time
 *           + delete page - once more fix warnings about images
 *           + fix notice on manage_posts page
 *           + bugfixes for copying posts:    
 *             - make sure to copy the link and update it to the new post_id
 *             - disable the copy of the post so that it does not appear immediately in FE
 *             - ensure the new post access file is created
 *           + new directory structure in delete.php
 *        - Florian Meerwinck
 *           + Minor UI fixes
 *
 * v3.7.12 - 2019-04-26 
 *         - Martin Hecht
 *           + implement drag&drop for gallery images
 *           + reflect order of groups in the overview of posts in the frontend
 *           + implement drag&drop for groups
 *           + change variable $pid to $original_post_id
 *           + fix for the titles in the overview
 *           + fix preview of gallery images
 *           + use groups in fact to group the posts in frontend overview
 *           + show drag&drop handles only for "custom" ordering
 *           + support drag&drop sorting of posts
 *           + copy images together with the posts
 *         - Bianka Martinovic
 *           + renamed grid to masonry-grid
 *           + added data-caption to fotorama image loop; added strip_tags() to image description before save
 *           + fixed copy_post.php
 *           + several fixes for image handling
 *           + added "mod_nwi_" prefix to method names in functions.inc.php
 *           + moved language loading and some vars to functions.inc.php
 *           + most scripts now require functions.inc.php
 *           + moved language loading and some central vars to functions.inc.php; most files include functions.inc.php
 *           + moved media/news_img to already existing media/.news_img
 *           + fix for renamed database columns
 *           + added default preview size (100x100)
 *           + added missing [CONTENT] placeholder
 *           + fix for renamed database columns
 *           + added default preview size (100x100)
 *           + added missing [CONTENT] placeholder
 *           + unified thumb creation in group handling (use module functions)
 *           + fixed install.php
 *     
 * v3.7.11 - 2019-04-24 Bianka Martinovic
 *          + added "mod_nwi_" prefix to method names in functions.inc.php
 *          + moved language loading and some vars to functions.inc.php
 *          + most script now require functions.inc.php
 *          + manually merged PR #17
 *
 * v3.7.10 - 2019-04-24 Bianka Martinovic
 *          + moved media/news_img to already existing media/.news_img
 *          + fix for renamed database columns
 *          + added default preview size (100x100)
 *          + added missing [CONTENT] placeholder
 *          + unified thumb creation in group handling (use module functions)
 *
 * v3.7.9 - 2019-04-23 Bianka Martinovic
 *          + fixed install.php
 *
 * v3.7.8 - 2019-04-23 Bianka Martinovic
 *          + renamed db column "bildname" to "picname", "bildbeschreibung" to "picdesc"
 *          + editable link -> autofilled by jQuery plugin
 *          + show publishing dates in backend -> list view
 *
 * v3.7.7 - 2019-04-18 Bianka Martinovic
 *          + removed all commenting options / tables / columns / files
 *
 * v3.7.6 - 2019-04-16 
 *        - Martin Hecht
 *          + copy posts
 *          + display post id in backend
 *        - Bianka Martinovic 
 *          + moved images folder to MEDIA_DIRECTORY/news_img
 *        - Florian Meerwinck
 *          + Changes to frontend display
 *          + add default preview size
 *
 * v3.7.5 - 2019-04-15 Martin Hecht
 *          + allow moving posts across section borders
 *          + a few bugfixes
 *
 * v3.7.4 - 2019-04-14 
 *        - Martin Hecht:
 *          + use news_img throughout the module, especially in the search tables
 *        - Bianka Martinovic: 
 *          + placeholder [GROUP_IMAGE_URL]
 *          + show group image in group settings
 *          + fixed zebra markup for settings table (table class "striped")
 *          + added odd and even table row colors (backend.css)
 *          + moved table styles to css
 *          + added info for "custom" sort order
 *        - Florian Meerwinck:
 *          + Preparation for Blog Menu
 *          + Remove non-translated language files
 *          + UI tweaks and language support:
 *            Replacing hard coded strings with language vars, 
 *            some optimizations on look & feel
 *
 * v3.7.3 - 2019-04-13 Martin Hecht
 *          + added automatic ordering
 *          + bugfixes for the gallery settings
 *
 * v3.7.2 - 2019-04-12 Martin Hecht
 *          + added second block
 *
 * v3.7.1 - 2019-04-12 Bianka Martinovic
 *          + added Masonry
 *          + added Gallery setting
 *
 * v3.7.0 - 2019-04-12 Bianka Martinovic
 *          + added Fotorama as default image gallery
 *          + added settings for post content markup and image loop
 *
 * v3.6.6 - 2019-04-10 Bianka Martinovic
 *          + Remove all tags from news title
 *
 * v3.6.5 - 2019-04-10 Bianka Martinovic
 *          + Fix: Warning: sizeof(): Parameter must be an array
 *          + Fix: Undefined index: crop_preview
 **/
