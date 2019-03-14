<?php

// Signup_Account_Settings
$INI_EDITOR['Signup_Account_Settings'] = 'Einstellungen für die Registrierung und Accounts';

$INI_EDITOR['signup_double_opt_in'] = 'Double Opt-In verwenden?';
$INI_EDITOR['signup_double_opt_in:hint'] = 'Hier kann festgelegt werden, ob die Registrierung per E-Mail bestätigt werden soll (eingeschaltet) oder keine Überprüfung der E-Mail-Adresse erfolgt (ausgeschaltet).';

$INI_EDITOR['user_activated_on_signup'] = 'Sofortige Aktivierung?';
$INI_EDITOR['user_activated_on_signup:hint'] = 'Hier kann festgelegt werden, ob die Benutzeraccounts durch den AccountsManager freigeschaltet werden müssen (ausgeschaltet) oder ob sie sofort aktiv sein sollen (eingeschaltet).';

$INI_EDITOR['accounts_manager_email'] = 'E-Mail des AccountsManagers';
$INI_EDITOR['accounts_manager_email:hint'] = 'Bitte geben Sie die E-Mail Adresse der Person ein, die für Registrierungen verantwortlich sein soll.
	<br />Wenn dieses Feld leer bleibt, wird der Administrator (User-ID 1) automatisch die Rolle des AccountsManagers übernehmen.';

$INI_EDITOR['use_html_email_templates'] = 'HTML-Templates für E-Mails';
$INI_EDITOR['use_html_email_templates:hint'] = 'Wenn ausgeschaltet, werden keine HTML basierten Templates für den E-Mail Versand verwendet, auch wenn sie vorhanden sind.';

$INI_EDITOR['accounts_manager_superadmin'] = 'SuperAdmin immer auch AccountsManager';
$INI_EDITOR['accounts_manager_superadmin:hint'] = 'Wenn eingeschalet, wird der SuperAdmin immer auch als AccountsManager fungieren, 
auch wenn er nicht unter '.$INI_EDITOR['accounts_manager_email'].' angegeben ist.';

$INI_EDITOR['support_email'] = 'Support E-Mail';
$INI_EDITOR['support_email:hint'] = 'Kann leer bleiben. In dem Fall wird <b>die erste</b> E-Mail aus dem obigen "'.$INI_EDITOR['accounts_manager_email'].'" Feld verwendet.
<br />Sollte die Checkbox "'.$INI_EDITOR['accounts_manager_superadmin'].'" aktiv sein, wird die E-Mail des SuperAdmins verwendet, wenn dieses Feld leer bleibt.';

$INI_EDITOR['preferences_template'] = 'Frontend Template für preferences.php';
$INI_EDITOR['preferences_template:hint'] = 'Hier kann ein abweichendes Template für preferences.php eingetragen werden.<br /> Wenn Feld leer oder angegebenes Template nicht installiert, wird das DEFAULT_TEMPLATE verwendet.';

$INI_EDITOR['signup_template'] = 'Frontend Template für signup.php';
#$INI_EDITOR['signup_template:hint'] = 'Hier kann man ein abweichendes Template für signup.php eintragen.';

$INI_EDITOR['login_template'] = 'Frontend Template für login.php';
#$INI_EDITOR['login_template:hint'] = 'Hier kann man ein abweichendes Template für login.php eintragen.';

$INI_EDITOR['notify_on_user_changes'] = 'Benachrichtigung bei Profiländerung eines Users';
$INI_EDITOR['notify_on_user_changes:hint'] = 'Wenn eingeschaltet, werden bei den Profiländerung eines Users E-Mails an den/die AccountsManager versendet.';

	
	
// SIGNUP CONFIG EXPLANATION
$sDOI = 'Double Opt-In ';
$sIA = ' Sofortige Freischaltung';
$sCheck_0 = ' <i class="fa fa2x fa-square-o"></i>';
$sCheck_1 = ' <i class="fa fa2x fa-check-square-o"></i>';
$INI_EDITOR['SIGNUP_CONFIG_EXPL'] = '
<h2>Explanation</h2>
<p><b>'.$sDOI.$sCheck_0.'  + '.$sIA.$sCheck_1.' </b> (0 + 1) kein Double Opt-In, Account wird vom System sofort freigeschaltet (unsicher!).</p>
<p><b>'.$sDOI.$sCheck_0.'  + '.$sIA.$sCheck_0.' </b> (0 + 0) kein Double Opt-In, AccountsManager muss aber den Account freischalten.</p>
<p><b>'.$sDOI.$sCheck_1.'  + '.$sIA.$sCheck_1.' </b> (1 + 1) Double Opt-In, Account wird vom System sofort freigeschaltet, sobald der User seine E-Mail bestätigt hat.</p>
<p><b>'.$sDOI.$sCheck_1.'  + '.$sIA.$sCheck_0.' </b> (1 + 0) Double Opt-In, AccountsManager muss den Account freischalten <b>(Sicherste Einstellungs-Kombination!)</b>.</p>
';