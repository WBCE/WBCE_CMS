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

// Modul Description
$module_description = 'Module for creating news items with item image and item gallery (optional).';

// Variables for the backend
$MOD_NEWS_IMG['ACTION'] = "Marked posts";
$MOD_NEWS_IMG['ACTIVATE'] = "activate";
$MOD_NEWS_IMG['ACTIVATE_POST'] = "activate post";
$MOD_NEWS_IMG['ADD_GROUP'] = 'Add group';
$MOD_NEWS_IMG['ADD_POST'] = 'Add post';
$MOD_NEWS_IMG['ADD_TAG'] = 'Add tag';
$MOD_NEWS_IMG['ADVANCED_SETTINGS'] = 'Advanced settings';
$MOD_NEWS_IMG['ALL'] = "All";
$MOD_NEWS_IMG['ASSIGN_GROUP'] = "Assign group";
$MOD_NEWS_IMG['ASSIGN_TAGS'] = "Assign tags";
$MOD_NEWS_IMG['CONTINUE'] = "Next";
$MOD_NEWS_IMG['COPY'] = "copy";
$MOD_NEWS_IMG['COPY_WITH_TAGS'] = 'copy (incl. tags)';
$MOD_NEWS_IMG['COPY_POST'] = 'Copy post';
$MOD_NEWS_IMG['CURRENT_SECTION'] = 'Current section';
$MOD_NEWS_IMG['DEACTIVATE'] = "deactivate";
$MOD_NEWS_IMG['DEACTIVATE_POST'] = "deactivate post";
$MOD_NEWS_IMG['DELETE'] = "delete";
$MOD_NEWS_IMG['DELETEIMAGE'] ='Delete this image?';
$MOD_NEWS_IMG['DESCENDING'] = 'descending';
$MOD_NEWS_IMG['EXPERT_MODE'] = 'expert mode';
$MOD_NEWS_IMG['EXPIRED_NOTE'] = 'The posting is no longer displayed in the frontend because the expiration date has passed.';
$MOD_NEWS_IMG['FIRST_EXPIRING_LAST'] = 'first expiring last';
$MOD_NEWS_IMG['GALLERY_SETTINGS'] ='Gallery / image settings';
$MOD_NEWS_IMG['GALLERYIMAGES'] = 'Gallery images';
$MOD_NEWS_IMG['GENERIC_IMAGE_ERROR'] ='Issues with post and/or gallery image(s). Please check file name, file type amd file size.';
$MOD_NEWS_IMG['GLOBAL'] = 'Global tag';
$MOD_NEWS_IMG['GOBACK'] = 'Back';
$MOD_NEWS_IMG['GROUP'] = 'Group';
$MOD_NEWS_IMG['GROUPS'] = 'Groups';
$MOD_NEWS_IMG['IMAGE_FILENAME_ERROR'] ='Filename is too long (max. 256 characters allowed)';
$MOD_NEWS_IMG['IMAGE_INVALID_TYPE'] = 'Image type not supported';
$MOD_NEWS_IMG['IMAGE_LARGER_THAN'] ='Image is too large, max. size: ';
$MOD_NEWS_IMG['IMAGE_TOO_SMALL'] = 'Image is too small';
$MOD_NEWS_IMG['IMAGEUPLOAD'] = 'Upload images';
$MOD_NEWS_IMG['IMPORT_OPTIONS'] = "Import options";
$MOD_NEWS_IMG['INFO_GLOBAL'] = "Global tags can be used in all news with images sections.";
$MOD_NEWS_IMG['INFO_RELOAD_PAGE'] = "This will reload the page; all unsaved data will be lost!";
$MOD_NEWS_IMG['LINK'] = 'Link';
$MOD_NEWS_IMG['LOAD_VALUES'] = "Load values";
$MOD_NEWS_IMG['MANAGE_POSTS'] = "manage posts";
$MOD_NEWS_IMG['MOVE'] = "move";
$MOD_NEWS_IMG['MOVE_WITH_TAGS'] = 'move (incl. tags)';
$MOD_NEWS_IMG['NEW_POST'] = 'Create new post';
$MOD_NEWS_IMG['NEWEST_FIRST'] = 'most recent on top';
$MOD_NEWS_IMG['NONE'] = "None";
$MOD_NEWS_IMG['OPTIONS'] ='Options';
$MOD_NEWS_IMG['OR'] = 'or';
$MOD_NEWS_IMG['ORDER_CUSTOM_INFO'] = 'The setting &quot;custom&quot; allows the manual sorting of articles via up/down arrows.';
$MOD_NEWS_IMG['ORDERBY']  = 'Order by';
$MOD_NEWS_IMG['OVERVIEW_SETTINGS'] ='Overview page settings';
$MOD_NEWS_IMG['POST_ACTIVE'] = 'Post is visible';
$MOD_NEWS_IMG['POST_CONTENT'] = 'Post content';
$MOD_NEWS_IMG['POST_INACTIVE'] = 'Post is not visible';
$MOD_NEWS_IMG['POST_SETTINGS'] = 'Post settings';
$MOD_NEWS_IMG['POSTED_BY'] = 'Posted by';
$MOD_NEWS_IMG['POSTS'] = 'Posts';
$MOD_NEWS_IMG['PREVIEWIMAGE'] = 'Preview image';
$MOD_NEWS_IMG['SAVEGOBACK'] = 'Save and go back';
$MOD_NEWS_IMG['SETTINGS'] = 'News Settings';
$MOD_NEWS_IMG['TAG'] = 'Tag';
$MOD_NEWS_IMG['TAG_COLOR'] = 'Tag color';
$MOD_NEWS_IMG['TAG_EXISTS'] = 'Tag exists';
$MOD_NEWS_IMG['TAGS'] = 'Tags';
$MOD_NEWS_IMG['TAGS_INFO'] = 'To use tags, edit a post and select the desired posts there.';
$MOD_NEWS_IMG['TO'] = 'to';
$MOD_NEWS_IMG['UPLOAD'] = 'Upload new image';
$MOD_NEWS_IMG['USE_SECOND_BLOCK'] = 'Use second block';
$MOD_NEWS_IMG['USE_SECOND_BLOCK_HINT'] = 'Must be supported by the template';
$MOD_NEWS_IMG['VIEW'] = 'Presentation / View';
$MOD_NEWS_IMG['VIEW_INFO'] = 'After changing the setting, hit save; the markups for post loop and post details view will be adjusted automatically.';

// Image settings
$MOD_NEWS_IMG['CROP'] = 'Crop';
$MOD_NEWS_IMG['GALLERY'] = 'Image gallery';
$MOD_NEWS_IMG['GALLERY_INFO'] = 'After changing the gallery setting, hit save; the markup for the image loop will be adjusted automatically.';
$MOD_NEWS_IMG['GALLERY_WARNING'] = 'Are you sure? Note that customized settings for the image loop markup will get lost.';
$MOD_NEWS_IMG['IMAGE_MAX_SIZE'] = 'Max. image size in kilobytes';
$MOD_NEWS_IMG['RESIZE_PREVIEW_IMAGE_TO'] = 'Resize preview image to';
$MOD_NEWS_IMG['RESIZE_GALLERY_IMAGES_TO'] = 'Resize gallery images to';
$MOD_NEWS_IMG['TEXT_CROP'] = 'If the aspect ratio of the original does not match the specified aspect ratio, the overlap of the longer edge will be cut off.';
$MOD_NEWS_IMG['TEXT_DEFAULTS'] = 'Default sizes';
$MOD_NEWS_IMG['TEXT_DEFAULTS_CLICK'] = 'Click to choose from the defaults';
$MOD_NEWS_IMG['THUMB_SIZE'] = 'Thumbnail size';

// Uploader
$MOD_NEWS_IMG['DRAG_N_DROP_HERE'] = 'Drag &amp; drop files here';
$MOD_NEWS_IMG['CLICK_TO_ADD'] = 'Click to add Files';
$MOD_NEWS_IMG['NO_FILES_UPLOADED'] = 'No files uploaded.';
$MOD_NEWS_IMG['COMPLETE_MESSAGE'] = 'Save your changes to show the upload in the gallery';
 
// Variables for the frontend
$MOD_NEWS_IMG['PAGE_NOT_FOUND'] = 'Page not found';
$MOD_NEWS_IMG['TEXT_AT'] = 'at';
$MOD_NEWS_IMG['TEXT_BACK'] = 'Back';
$MOD_NEWS_IMG['TEXT_BY'] = 'By';
$MOD_NEWS_IMG['TEXT_LAST_CHANGED'] = 'Last changed';
$MOD_NEWS_IMG['TEXT_NEXT_POST'] = 'Next post';
$MOD_NEWS_IMG['TEXT_O_CLOCK'] = 'o&#39;clock';
$MOD_NEWS_IMG['TEXT_ON'] = 'on';
$MOD_NEWS_IMG['TEXT_POSTED_BY'] = 'Posted by';
$MOD_NEWS_IMG['TEXT_PREV_POST'] = 'Previous post';
$MOD_NEWS_IMG['TEXT_READ_MORE'] = 'Read More';
$MOD_NEWS_IMG['TEXT_RESET'] = 'Reset';
$MOD_NEWS_IMG['TO'] = "to";
$MOD_NEWS_IMG['IMPORT'] = 'import';
