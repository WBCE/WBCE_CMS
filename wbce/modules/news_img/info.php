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
 * @license         https://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

$module_directory   = 'news_img';
$module_name        = 'News with Images';
$module_function    = 'page';
$module_version     = '5.0.21';
$module_platform    = '1.4';
$module_author      = 'Ryan Djurovich, Rob Smith, Silvia Reins, Martin Hecht, Florian Meerwinck, Bianka Martinovic';
$module_license     = 'GNU General Public License';
$module_description = 'This page type is designed for making a news page with Images and Lightboxeffect.';

/**
 * v5.0.21 - 2023/04/18
 *         - florian
 *         ! several bugfixes (see Github commits) 
 *
 * v5.0.20 - 2023/04/09
 *		   - florian
 *         ! fix update issue with new setting show_settings_only_admins
 *         * better handling of post activation (remove / rebuild access file)
 *
 * v5.0.19 - 2023/02/01
 *		   - florian
 *         ! missing changes on upgrade.php in single install package. no changes in WBCE core repo.
 * 
 * v5.0.18 - 2023/01/27
 *         - florian
 *         ! fix issue with non-replacement of {SYSVAR:MEDIA_REL}
 *
 * v5.0.17 - 2022-11-14
 *         - florian         
 *         * add option to show settings only admins

 * v5.0.16 - 2022-08-15
 *         - florian         
 *         ! PHP 8.1 fixes    
 *
 * v5.0.15 - 2022-03-05
 *         - webbird
 *           ! fixed upgrade changes wrong version number
 *
 * v5.0.14 - 2022-02-08
 *         - florian
 *           * Add [AOPEN] [ACLOSE] placeholders + webp compatibility
 *
 * v5.0.13 - 2021-12-25
 *         - gchriz
 *           * Add missing option 4 and make "order news by group" working
 *
 * v5.0.12 - 2021-12-10
 *         - Florian
 *           * fix rss.php (require section_id for sql query instead of page_id since page_id is no longer stored in news_img_post table)
 *
 * v5.0.11 - 2021-10-09
 *         - Florian
 *           * added HREF placeholder
 *
 * v5.0.10 - 2021-10-02
 *         - Florian
 *           ! fixed deleted tags are still in database
 *           * added Sort tags ascending
 *
 * v5.0.9  - 2021-09-19
 *         - Florian
 *           ! remove image database changes for unfinished image handling from upgrade.php
 *           ! Post images can be either deleted or replaced now (w/o deleting the old image file)
 *           ! Remove unnecessary sorting option PostID
 *           * improved view management: views/default/config.private.php -> views/default/config.php -> add.php
 *
 * v5.0.8  - 2021-07-11
 *         - Florian
 *           * better prev/next navigation when group is selected
 *
 * v5.0.7  - 2020-09-20
 *         - Various
 *           ! fixed languages files
 *           ! fixed import script
 *           ! fixed post_id
 *
 * v5.0.6  - 2020-07-20
 *         - Florian
 *           ! Remove unnecessary link to frotorama.css from frontend.css
 *
 * v5.0.5  - 2020-07-18
 *         - Florian
 *           ! fix wrong likn to fotorama.css (reported by klawin)
 *
 * v5.0.4  - 2020-06-18
 *         - Colinax
 *           ! fix error in install.php
 **/
