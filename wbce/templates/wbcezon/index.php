<?php
// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);
?><!DOCTYPE html>
<html lang="de-DE">
   <head>
      <?php simplepagehead(); ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      
      <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/css/components.css">
      <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/css/icons.css">
      <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/css/responsee.css">
	  <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/css/wbcezon.css">
      <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/owl-carousel/owl.carousel.css">
      <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/owl-carousel/owl.theme.css"> 
     
	 <?php
	 
		// obligatorisch - Modul-Styles und -scripte sowie jQuery laden
		// mandantory - load module CSS styles and scripts and jQuery
		register_frontend_modfiles('css');
		register_frontend_modfiles('jquery');
		register_frontend_modfiles('js');
		
		// Navigationen erzeugen (Hauptmenü, seitliches Menü mit Links zu den Unterseiten der jeweils ausgewählten Seite. Das seitliche Menü erscheint nicht in der Mobilansicht.
		// create navigation menus - main menu and aside menu with child pages of the current page. The aside navigation does not appear on smartphone displays.
		$mainnav = show_menu2(
			$aMenu          = 1,
			$aStart         = SM2_ROOT,
			$aMaxLevel      = SM2_ALL,
			$aOptions       = SM2_ALL|SM2_PRETTY|SM2_BUFFER,
			$aItemOpen      = '<li><a href="[url]" class="[class]" target="[target]">[menu_title]</a>',
			$aItemClose     = '</li>',
			$aMenuOpen      = '<ul>',
			$aMenuClose     = '</ul>',
			$aTopItemOpen   = false,
			$aTopMenuOpen   = '<ul class="chevron">'
		  );	
		  
		  $asidenav = show_menu2(
			$aMenu          = 0,
			$aStart         = SM2_ROOT+1,
			$aMaxLevel      = SM2_CURR+1, 
			$aOptions       = SM2_SIBLING|SM2_BUFFER,
			$aItemOpen      = '<li class="[class]"><a href="[url]" class="[class]" target="[target]">[menu_title]</a>',
			$aItemClose     = '</li>',
			$aMenuOpen      = '<ul>',
			$aMenuClose     = '</ul>',
			$aTopItemOpen   = false,
			$aTopMenuOpen   = '<ul>'
		  );	
		  
		  // Login, Logout, Profil und Seite-bearbeiten-Icons erzeugen (erscheinen im Footer). Seite bearbeiten-Link erscheint nur bei Administratoren (https://forum.wbce.org/viewtopic.php?pid=23417#p23417)
		  // create login, logout, profile and edit page-icons which will appear in the footer. By default, only members of the administrator group (group-ID 1) see the edit link
		  if (FRONTEND_LOGIN) {
			  if (is_numeric($wb->get_session('USER_ID'))) {
				  $loginlink = '<a href="'.LOGOUT_URL.'"><i class="icon-sli-logout"  aria-hidden="true"></i></a><a href="'.PREFERENCES_URL.'"><i class="icon-sli-user"  aria-hidden="true"></i></a>';
			  } else {	  
				$loginlink = '<a href="'.LOGIN_URL.'"><i class="icon-sli-login"  aria-hidden="true"></i></a>';
				if (FRONTEND_SIGNUP) {
				  $loginlink .= ' <a href="'.SIGNUP_URL.'"><i class="icon-sli-user-follow"  aria-hidden="true"></i></a>';
				}
			  }			  
		  } else {
			  $loginlink = '';
		  }
		  
		  if ($wb->ami_group_member('1')) {
			  $loginlink .= '<a href="'.ADMIN_URL.'/pages/modify.php?page_id='.PAGE_ID.'" target="_blank"><i class="icon-sli-note" aria-hidden="true"></i></a>';
		  }
		  
		  // Blöcke laden. Es werden nur Blöcke mit Inhalt angezeigt. Ist kein Inhalt in Block 2 vorhanden, geht Block 1 über die gesamte Breite neben der seitlichen Navigation, sonst Aufteilung 6:3
		  // fetch blocks. No empty blocks will be displayed. If block 2 has no content, block 1 uses the whole width beneath the aside menu, otherwise the blocks are displayed in 6:3 ratio.
		  require_once __DIR__.'/info.php';
			foreach($block as $k=>$v){
			ob_start(); 
			page_content($k); 
			$block[$k] = ob_get_clean();
		}
		
		
		if(defined('MODULES_BLOCK2') AND MODULES_BLOCK2 != '') { 
			$block[2] .= MODULES_BLOCK2;
		}
		
		
		// Logo: entweder (Platzhalter-)Logo aus dem Template-Verzeichnis oder ein vom Nutzer nach /media hochgeladenes Logo namens logo.png
		// Logo: use either the (placeholder) logo from the template directory or a file called logo.png uploaded by the user into the media directory
		
		
		if (file_exists(WB_PATH.'/media/logo.png')) {
			$logo = WB_URL.'/media/logo.png';
		} else {
			$logo = TEMPLATE_DIR.'/img/logo.png';
		}
		
		/* 
		Hier wirds kompliziert: Headerbild bzw. Sliderbilder laden. 
		Bilder werden aus einem Unterverzeichnis gezogen, das entweder die Seiten-ID oder die Seiten-ID der übergeordneten Seite als Namen hat und unterhalb von /media/header-pics/ liegt, also z.B. /media/header-pics/1. Ist keines dieser Verzeichnisse vorhanden oder sind keine Bilder in dem gefundenen Verzeichnis, wird das Bild default-header.jpg aus dem Templateverzeichnis verwendet.
		Werden mehrere Bilder gefunden, werden diese automatisch als Slider angezeigt, ansonsten logischerweise nur das eine gefundene Bild.
		
		Here it gets a bit complicated: Loading the header picture(s).
		The script looks for pictures in a directory which is named either like the page-ID of the current page or the page-ID of the parent page of the current page and is situated in /media/header-pics, for example /media/header-pics/1. If none of these directories exist or the found directory does not contain any pictures, the default image default-header.jpg from the template directory is displayed.
		If there are more than one picture found, they are displayed as a slider, otherwise the found picture shows up as a static picture.		
		*/
		
		$picPre ='<div class="item"><img src="';
		$picPost = '" alt=""></div>';
		
		$pics = array();
		$imgPath = WB_PATH.'/media/header-pics/'.PAGE_ID;
		if (!is_dir($imgPath)) {
			$imgPath = WB_PATH.'/media/header-pics/'.PARENT;
		}
		if (is_dir($imgPath)) {
			$dir = $imgPath . '/';
			$extensions = array('jpg', 'jpeg', 'png', 'gif', 'svg');			
			$directory = new DirectoryIterator($dir);
			foreach ($directory as $fileinfo) {
				if ($fileinfo->isFile()) {
					$extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
					if (in_array($extension, $extensions)) {						
						$pics[] = str_replace(WB_PATH,WB_URL,$dir.$fileinfo->getFilename());
					 }
				 }
			 }						
		} else {
			$pics[] = TEMPLATE_DIR.'/img/default-header.jpg';
		}
		if (count($pics) == 0) {
			$pics[] = TEMPLATE_DIR.'/img/default-header.jpg';
		}
		
		
		
		
	 ?>
     
   </head>
   <body class="size-1140">
      <!-- HEADER -->
      <header>
         <div class="line">           
               <div class="s-12 l-3">
                  <a href="<?php echo WB_URL; ?>"><img class="logo center" src="<?php echo $logo; ?>" alt=""></a>
               </div>
			   <div class="s-12 l-9 headerpic">
			   <?php if (sizeof($pics) > 1) {
					// is there more than 1 picture? Then activate the slider
					echo '<div id="header-carousel" class="owl-carousel owl-theme">';
					    foreach($pics as $pic) {						   
						   echo $picPre.$pic.$picPost;
						} 					  
					echo '</div>';
					} else {
						// only one picture? No slider 
						echo '<img src="'.$pics[0].'" alt="'.PAGE_TITLE.'" />';
					}
					?></div>           
         </div>
         <!-- TOP NAV -->  
         <div class="line">
            <nav class="margin-bottom" id="main-nav">               
			    <?php if (SHOW_SEARCH) { ?>
               <div class="hide-s m-12 l-3">                  
					<form class="wbcesuche" name="search" action="<?php echo WB_URL; ?>/search/index.php" method="get">
						<input type="hidden" name="referrer" value="<?php echo defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID; ?>" />
						<input type="text" name="string" class="search" placeholder="<?php echo $TEXT['SEARCH']; ?>" />
						<input type="submit" name="wb_search" value="&#xe090;" />
					</form>
               </div>
			   <?php } ?>
			   <p class="nav-text">Navigation</p>
               <div class="top-nav s-12 m-12 l-9">
                  <?php echo $mainnav; ?>
               </div>
			  
            </nav>
         </div>
      </header>
      <section>
		
         <!-- ASIDE NAV AND CONTENT -->
         <div class="line">
            <div class="box">
               <div class="margin">
			    <!-- ASIDE NAV -->
                  <div class="hide-s m-12 l-3">                     
                     <div class="aside-nav-noresponsee">
                        <?php echo $asidenav; ?>						
                     </div>
					 <?php if ($block[5]!='') {
						 echo $block[5];
					 } ?>
					 &nbsp;
                  </div>
				  <!-- MAIN CONTENT AND OPTIONAL BLOCKS -->
				  <div class="s-12 l-9 maincontent">                  				  
					<?php 														
					if ($block[3] !='') {											
						echo '<div class="s-12">'.$block[3].'</div>';						
					}
					
					if ($block[2] != '') { ?>
						<div class="margin">
							<article class="s-12 l-9">
								<h1 id="contentstart"><?php echo PAGE_TITLE;?></h1>						
								<?php echo $block[1]; ?>			
							</article>
							<aside class="s-12 l-3">
								<?php echo $block[2]; ?>	
							</aside>
						</div>
					<?php } else { ?>
						<article class="s-12">
							<h1  id="contentstart"><?php echo PAGE_TITLE;?></h1>
							<?php echo $block[1]; ?>			
						</article>						
					<?php } 
					 if ($block[4] != '') {
						 echo '<div class="s-12">'.$block[4].'</div>';						
						}						
					?>
					</div>
               </div>
            </div>
         </div>
      </section>
      <!-- FOOTER -->
      <footer>
         <div class="line">
			<div class="box">
				<?php 
				$footerwidth = '';
				if (WEBSITE_FOOTER!='') { 
					$footerwidth = 'l-6';
					echo '<div class="s-12 l-6">'.WEBSITE_FOOTER.'</div>';
				} ?>
				<div class="s-12 <?php echo $footerwidth; ?> text-right">				
				  <?php echo $loginlink; ?>
				</div>
			</div>
         </div>
      </footer>  
		<?php register_frontend_modfiles_body();?>
      <script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>/js/responsee.js"></script>               
      <script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>/owl-carousel/owl.carousel.js"></script>
      
      <script type="text/javascript">
        jQuery(document).ready(function($) {
          var owl = $('#header-carousel');
          owl.owlCarousel({
            nav: false,
            dots: false,
            items: 1,
            loop: true,
            navText: ["&#xf007","&#xf006"],
            autoplay: true,
            autoplayTimeout: 3000,
			animateIn: 'fadeIn',
			animateOut: 'fadeOut'
          });
          
        });
		
	
		if (window.innerWidth >=960) {
			$(window).load(function(){
			  $("#main-nav").sticky({ topSpacing: 0 });
			});
		}
		$( '.chevron li:has(ul)' ).doubleTapToGo();		
		
      </script>
   </body>
</html>