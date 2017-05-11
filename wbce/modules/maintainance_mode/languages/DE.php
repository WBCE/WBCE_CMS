<?php
/**
 * @category        modules
 * @package         Maintainance Mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */
 
// Module description 
$module_description = 'Im Wartungsmodus wird Besuchern der Seite im Frontend nur eine Baustellenseite angezeigt.';

// Language vars
$MOD_MAINTAINANCE['HEADER'] =      'Wartungsmodus';
$MOD_MAINTAINANCE['DESCRIPTION'] = 'Bei aktiviertem Wartungsmodus wird auf allen Seiten dieser Website eine Hinweisseite statt des eigentlichen Inhalts angezeigt. Als am Backend angemeldeter Superadmin sehen Sie weiterhin die normale Frontend-Ansicht.<br />Die Baustellenseite liegt außerhalb des CMS. Zur Anpassung des Erscheinungsbildes muss die /templates/systemplates/maintainance.tpl.php mit einem geeigneten Editor bearbeitet werden. Um diese Änderungen bei einem Update nicht zu verlieren, können Sie auch im Verzeichnis des verwendeten Standard-Frontend-Templates eine systemplates/maintainance.tpl.php anlegen und diese anpassen.<br /><hr />Bitte wählen Sie aus';
$MOD_MAINTAINANCE['CHECKBOX'] =    'Wartungsmodus aktivieren';
