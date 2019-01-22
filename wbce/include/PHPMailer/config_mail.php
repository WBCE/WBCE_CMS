<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 * 
 * 
 * @package      PHPMailer 
 * @copyright    Ryan Djurovich (2004-2009)
 * @copyright    WebsiteBaker Org. e.V. (2009-2015)
 * @copyright    WBCE Project (2015-)
 * @license      GNU GPL2 (or any later version)
 * 
 * @description  This array can be used for setting up the Mailer Settings.
 *               If set,  this array will  override  the settings in the database.
 *               It  may  be  convenient  sometimes  to  use  this files settings 
 *               rather than the  database  settings,  for example if you work on 
 *               localhost to set up the site before you move it to a live server.
 */

/** /
// *********************************************************************************
//  Localhost? Override Mailer Settings from the database with those of this array
// *********************************************************************************
if ( $_SERVER["SERVER_ADDR"] == '::1' ) { // set "localhost" or "127.0.0.1" or "::1" according to environment
    return array(
            'routine'            => 'smtp',                             // required (smtp or mail)
            'server_email'       => 'example_email@example_host.com',   // required (Default From Mail)
            'default_sendername' => 'My Sender Name',                   // required

            'smtp_host'          => 'smtp.example_host.com',            // required if SMPT
            'smtp_secure'        => 'tls',                              // required if SMPT
            'smtp_port'          => 587,                                // required if SMPT

            // some Hosts require SMTP authentication
            'smtp_auth'          => true,
            'smtp_username'      => 'login_name',                       // (SMTP Loginname)
            'smtp_password'      => '~lMq?#_19h4'                       // (SMTP Password)
    );
}
/ **/