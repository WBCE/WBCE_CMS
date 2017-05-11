<?php
/**
 * @category        modules
 * @package         Maintainance Mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */
 
// module description 
$module_description = 'Activating the maintainance mode will replace the frontend view temporarily by a "Under construction" page.';

// Headings and text outputs
$MOD_MAINTAINANCE['HEADER'] =      'Maintainance Mode';
$MOD_MAINTAINANCE['DESCRIPTION'] = 'Activating the maintainance mode will replace the frontend view temporarily by a "Under construction" page. As the logged-in super user you will see the usual frontend view. <br />The maintainance page is situated out of reach of the backend. To alter the contents or design of the under construction page, you will have to edit the file /templates/systemplates/maintainance.tpl.php. You can create in the folder of the default frontend template a systemplates/maintainance.tpl.php to avoid the loss of your customizations during a future CMS update.<br /><hr />Please select';
$MOD_MAINTAINANCE['CHECKBOX'] =    'Activate maintainance mode';
