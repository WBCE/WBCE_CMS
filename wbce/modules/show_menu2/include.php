<?php


// prevent this file from being accessed directly
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

// TEMPLATE CODE STARTS BELOW
?>
<!doctype html>
<html lang="<?php echo strtolower(LANGUAGE);?>">


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php page_description(); ?>" />
	<meta name="keywords" content="<?php page_keywords(); ?>" />
	<?php 
	// automatically include optional WB module files (frontend.css, frontend.js)
	// This is the old wasy of including this 
	if (function_exists('register_frontend_modfiles')) {
		register_frontend_modfiles('css');
		register_frontend_modfiles('js');
	} ?>
	
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/template.css" media="screen,projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/print.css" media="print" />

	<title><?php page_title('', '[WEBSITE_TITLE]'); ?></title>
</head>

<body>
    <table class="bodytab" cellpadding="5" cellspacing="0"   align="center">
        <tr>
            <td colspan="2" class="header">
                <?php page_title('','[WEBSITE_TITLE]'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="subheader">
                <div>
                    <?php 
                        show_menu2(
                            2, 
                            SM2_ROOT, 
                            SM2_START, 
                            SM2_ALL);
                      ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="leftrow">
                <div class="menu">
                
                <?php 
                    echo $TEXT['MENU'] . ':';
                    show_menu2("Main", SM2_ROOT  );
                ?>
                
                </div><!-- class menu -->
                
               
                
                <?php 
                    WbSearchBox::Show();
                    
                    $SB=new WbSearchBox();
                    $SB->sHeadline="Search here:";
                    $SB->Display();
                    
                ?>
                

                
                <div class="login">
                
                <?php if(FRONTEND_LOGIN AND !$wb->is_authenticated() AND VISIBILITY != 'private' ) : ?>
                
                    <form name="login" action="<?php echo WB_URL.$_SERVER['SCRIPT_NAME']; ?>" method="post">
                        <?php echo $TEXT['LOGIN']; ?></b>
                            <?php echo $TEXT['USERNAME']; ?>:
                        <input type="text" name="username" />
                        <?php echo $TEXT['PASSWORD']; ?>:
                        <input type="password" name="password" />
                        <input type="submit" name="submit" value="<?php 
                            echo $TEXT['LOGIN']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
                        <a href="<?php echo FORGOT_URL; ?>"><?php echo $TEXT['FORGOT_DETAILS']; ?></a>
                        <?php if (is_numeric(FRONTEND_SIGNUP)) : ?>
                            <a href="<?php echo SIGNUP_URL; ?>"><?php echo $TEXT['SIGNUP']; ?></a>
                        <?php endif ?>
                    </form>
                    
                    <?php elseif (FRONTEND_LOGIN AND $wb->is_authenticated()) : ?>
                    
                    <form name="logout" action="<?php echo LOGOUT_URL; ?>" method="post">

                        <b><?php echo $TEXT['LOGGED_IN']; ?></b>
                            <?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?>
                        <input type="submit" name="submit" value="<?php 
                            echo $MENU['LOGOUT']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
                        <a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a>
                    </form>
                    
                    <?php endif; ?>
                    
                </div><!-- class login -->
                
                <div class="content_left">
                   <?php page_content("Left"); ?> 
                </div><!-- class powered -->
                
                <div class="powered">
                    <a href="http://www.websitebaker.org" target="_blank">Powered by <br /> Website Baker</a>
                </div><!-- class powered -->
            </td>
            <td class="content">
                <?php page_content("Main"); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="footer">
                <?php page_footer(); ?>
            </td>
        </tr>
    </table>
    <?php 
    // automatically include optional WB module file frontend_body.js)
    // this is old call we need to replace this ..  
    if (function_exists('register_frontend_modfiles_body')) { register_frontend_modfiles_body(); } 
    ?>
</body>
</html>

<pre>

<?php 

// $a=get_defined_constants(true);
// print_r($a['user']);









