<?php
/**
 *
 * @category        modules
 * @package         news
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: submit_comment.php 1634 2012-03-09 02:20:16Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/modules/news/submit_comment.php $
 * @lastmodified    $Date: 2012-03-09 03:20:16 +0100 (Fr, 09. Mrz 2012) $
 *
 */

// Include config file
require('../../config.php');

/*
overwrite php.ini on Apache servers for valid SESSION ID Separator
if(function_exists('ini_set')) {
	ini_set('arg_separator.output', '&amp;');
}
*/

require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb;

/*
$post_id = (int)$_GET['post_id'];
$section_id = (int)$_GET['section_id'];
if (!$wb->checkFTAN())
{
	$wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL."/modules/news/comment.php?post_id=".$post_id."&section_id=".$section_id);
}
 */
// Get page id
	$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
	$page_id = intval(isset(${$requestMethod}['page_id'])) ? ${$requestMethod}['page_id'] : (isset($page_id) ? intval($page_id) : 0);
// Get post_id
	$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
	$post_id = (intval(isset(${$requestMethod}['post_id'])) ? ${$requestMethod}['post_id'] : (isset($post_id) ? intval($post_id) : 0));
// Get section id if there is one
	$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
	$section_id = intval(isset(${$requestMethod}['section_id'])) ? ${$requestMethod}['section_id'] : (isset($section_id) ? intval($section_id) : 0);

// Check if we should show the form or add a comment
if(isset($_GET['page_id']) AND is_numeric($_GET['page_id'])
    AND isset($_GET['section_id']) AND is_numeric($_GET['section_id'])
        AND isset($_GET['post_id']) AND is_numeric($_GET['post_id'])
            AND ( ( ENABLED_ASP AND isset($_POST['comment_'.date('W')]) AND $_POST['comment_'.date('W')] != '')
            OR ( !ENABLED_ASP AND isset($_POST['comment']) AND $_POST['comment'] != '' ) ) )
{

	if(ENABLED_ASP){
        $comment = $_POST['comment_'.date('W')];
	}
	else
    {
        $comment = $_POST['comment'];
	}

	$comment = $wb->add_slashes(strip_tags($comment));
	$title = $wb->add_slashes(strip_tags($_POST['title']));
	// do not allow droplets in user input!
	$title = str_replace(array("[[", "]]"), array("&#91;&#91;", "&#93;&#93;"), $title);
	$comment = str_replace(array("[[", "]]"), array("&#91;&#91;", "&#93;&#93;"), $comment);

	$page_id = (int)$_GET['page_id'];
	$section_id = (int)$_GET['section_id'];
	$post_id = (int)$_GET['post_id'];

	// Check captcha
	$query_settings = $database->query("SELECT use_captcha FROM ".TABLE_PREFIX."mod_news_settings WHERE section_id = '$section_id'");
	if( !$query_settings->numRows())
    {
		header("Location: ".WB_URL.PAGES_DIRECTORY."");
	    exit( 0 );
	}
    else
    {
		$settings = $query_settings->fetchRow();
		$t=time();

        // Advanced Spam Protection
	    if(ENABLED_ASP AND ( ($_SESSION['session_started']+ASP_SESSION_MIN_AGE > $t)  // session too young
            OR (!isset($_SESSION['comes_from_view']))// user doesn't come from view.php
            OR (!isset($_SESSION['comes_from_view_time']) OR $_SESSION['comes_from_view_time'] > $t-ASP_VIEW_MIN_AGE) // user is too fast
            OR (!isset($_SESSION['submitted_when']) OR !isset($_POST['submitted_when'])) // faked form
            OR ($_SESSION['submitted_when'] != $_POST['submitted_when']) // faked form
            OR ($_SESSION['submitted_when'] > $t-ASP_INPUT_MIN_AGE && !isset($_SESSION['captcha_retry_news'])) // user too fast
            OR ($_SESSION['submitted_when'] < $t-43200) // form older than 12h
            OR ($_POST['email'] OR $_POST['url'] OR $_POST['homepage'] OR $_POST['comment']) /* honeypot-fields */ ) )
        {
            header("Location: ".WB_URL.PAGES_DIRECTORY."");
	        exit( 0 );
		}

		if(ENABLED_ASP)
        {
			if(isset($_SESSION['captcha_retry_news']))
            {
              unset($_SESSION['captcha_retry_news']);
            }
		}

		if($settings['use_captcha'])
        {
			$search = array('{SERVER_EMAIL}');
			$replace = array( SERVER_EMAIL,);
			$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = str_replace($search,$replace,$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA']);
			if(isset($_POST['captcha']) AND $_POST['captcha'] != '')
            {
				// Check for a mismatch
				if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha'])
                {
					$_SESSION['captcha_error'] = $MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'];
					$_SESSION['comment_title'] = $title;
					$_SESSION['comment_body'] = $comment;
					header("Location: ".WB_URL."/modules/news/comment.php?post_id=".$post_id."&section_id=".$section_id."" );
	                exit( 0 );
				}
			}
            else
            {
				$_SESSION['captcha_error'] = $MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'];
				$_SESSION['comment_title'] = $title;
				$_SESSION['comment_body'] = $comment;
				header("Location: ".WB_URL."/modules/news/comment.php?post_id=".$post_id."&section_id=".$section_id."" );
	            exit( 0 );
			}
		}
	}

	if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }

	if(ENABLED_ASP)
    {
		unset($_SESSION['comes_from_view']);
		unset($_SESSION['comes_from_view_time']);
		unset($_SESSION['submitted_when']);
	}

	// Insert the comment into db
	$commented_when = time();
	if($wb->is_authenticated() == true)
    {
		$commented_by = $wb->get_user_id();
	}
    else
    {
		$commented_by = '';
	}

	$query = $database->query("INSERT INTO ".TABLE_PREFIX."mod_news_comments (section_id,page_id,post_id,title,comment,commented_when,commented_by) VALUES ('$section_id','$page_id','$post_id','$title','$comment','$commented_when','$commented_by')");
	// Get page link
	$query_page = $database->query("SELECT link FROM ".TABLE_PREFIX."mod_news_posts WHERE post_id = '$post_id'");
	$page = $query_page->fetchRow();
	header('Location: '.$wb->page_link($page['link']).'?post_id='.$post_id.'' );
	exit( 0 );
}
else
{
	if( isset($_GET['post_id']) AND is_numeric($_GET['post_id'])
        AND isset($_GET['section_id']) AND is_numeric($_GET['section_id']) )
    {
 		header("Location: ".WB_URL."/modules/news/comment.php?post_id=".(int)$_GET['post_id']."&section_id=".(int)$_GET['section_id']."" ) ;
	    exit( 0 );
    }
	else
    {
		header("Location: ".WB_URL.PAGES_DIRECTORY."");
	    exit( 0 );
    }
}
