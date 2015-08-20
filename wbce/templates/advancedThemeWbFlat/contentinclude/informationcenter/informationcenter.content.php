<?php
/**
 * Website Baker Theme
 * Additional content to include to the wb information center of the wb backend
 *
 * Part off: Website Baker theme advanced_theme_wb_flat
 * More information see: info.php in main theme folder
 */

if (!defined('WB_PATH')) {
    // include wb system data/functions
    include '../../../../config.php';
}
// --- check if logged in
$bLoggedIn = (isset($_SESSION['USER_ID']) && is_numeric($_SESSION['USER_ID']));

// go on only forward if logged in
if ($bLoggedIn) {

    // ### realize multilingual support for dashboard
    // ### for multilingual dashboards use language files in diretory: 'themediretcory/languages/...'
    // #################################################################################################

    $sLangPath = '../../languages/';
    if (is_readable($sLangPath . 'EN.php')) {include $sLangPath . 'EN.php';}
    if (is_readable($sLangPath . DEFAULT_LANGUAGE . '.php')) {include $sLangPath . DEFAULT_LANGUAGE . '.php';}
    if (is_readable($sLangPath . LANGUAGE . '.php')) {include $sLangPath . LANGUAGE . '.php';}

    // ### IMPORTANT ########################################################################
    //
    // USAGE:
    // This file contains the content for wb information center
    // You don't need to add the outer tags html, head, body
    // Just add the content
    //
    // Use the classes from the backend theme.
    // Or add your own css to file informationcenter.css (in the same directory)
    //
    // It is possible to use php.
    // The variables/constants of websitebaker are available.
    //
    // Mulitlingual support:
    // For easy mulitlingual management you can use the language files of the theme
    // in diretory: 'themediretcory/languages/...'
    // ... or build your own language switches
    //
    // ###
    // ### ADD YOUT CONTENT BELOW:
    // ######################################################################################
    ?>



	<div class="togglebox-content">

		<h2>WBCE Information Center</h2>

		<div class="dynamicGrid-outer">
			<a id="linkbox_manuals" class="linkbox dynamicGrid_3" href="http://help.wbce.org" title="Manuals for WBCE" target="_blank"><?php echo $TEXT['MANUALS'];?></a>
			<a id="linkbox_community" class="linkbox dynamicGrid_3" href="http://forum.wbce.org" title="WBCE Community Forum" target="_blank"><?php echo $TEXT['COMMUNITY'];?></a>
			<a id="linkbox_addons" class="linkbox dynamicGrid_3" href="http://addons.wbce.org" title="Addons for WBCE" target="_blank"><?php echo $TEXT['ADDONS'];?></a>
		</div>



		<div id="togglebox_scrollbox">
			<div class="wb_newsfeed">

			<?php
/*
    // get newsfeed-url based on language
    $url = "http://websitebaker.org/index.php?rss=131&lang=EN";
    if($_SESSION['LANGUAGE']=="DE") $url = "http://websitebaker.org/index.php?rss=275&lang=DE";
    if($_SESSION['LANGUAGE']=="NL") $url = "http://websitebaker.org/index.php?rss=385&lang=NL";

    // get data to show wb newsfeeed in scrollbox
    if (isset($_SESSION['wb_news_feed'])) {
    $rawFeed = $_SESSION['wb_news_feed'];
    } else {
    $rawFeed = file_get_contents($url);
    $_SESSION['wb_news_feed'] = $rawFeed;
    }
    $news = new SimpleXmlElement($rawFeed);

    // output newsfeed
    foreach ( $news->channel->item as $item ) {
    $item->description = str_replace('{SYSVAR:AppUrl.MediaDir}','http://www.websitebaker.org/media/',$item->description);
    echo '<h4><a href="'.(string)$item->link.'" target="_blank">'.(string)$item->title.'</a><span style="float:right;font-size:12px;">'.(string)$item->pubDate.'</span></h4>';
    echo ''.(string)$item->description.'';
    echo '<a class="wb_button" style="margin:0 0 12px 0;color:#fff;" href="'.(string)$item->link.'" target="_blank">Read more</a>';
    echo '<br/>';
    }
     */
    ?>


			</div><!-- ENDE wb_newsfeed -->
		</div><!-- ENDE scrollbox -->


	</div><!-- End togglebox-content -->





<?php } // endif ($bLoggedIn) ?>
