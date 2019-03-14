<?php

// Signup_Account_Settings
$INI_EDITOR['Signup_Account_Settings'] = 'Signup and Account Settings</small>';

$INI_EDITOR['signup_double_opt_in'] = 'Use Double Opt-In?';
$INI_EDITOR['signup_double_opt_in:hint'] = 'Decide whether Double opt-in should be used (checked) or not (unchecked).';

$INI_EDITOR['user_activated_on_signup'] = 'Immediate activation?';
$INI_EDITOR['user_activated_on_signup:hint'] = 'Decide whether the user account should be activated right after signup (checked) or has to be activated by the Accounts-Manager first (unchecked).';

$INI_EDITOR['accounts_manager_email'] = 'E-Mail of the AccountsManager';
$INI_EDITOR['accounts_manager_email:hint'] = 'Enter the E-Mail address of the person who will be responsible for the registrations.
	<br />If this field is empty the Administrator (User-ID 1) will inherit the role of the AccountsManager automatically.';
	
$INI_EDITOR['use_csv_export'] = 'Use CSV export';
$INI_EDITOR['use_csv_export:hint'] = 'Allow the export of user data into the CSV format.';

$INI_EDITOR['use_html_email_templates'] = 'HTML-Templates for E-Mails';
$INI_EDITOR['use_html_email_templates:hint'] = 'If checked, no HTML based templates will be used for E-Mail delivery, regardless whether they\'re present or not.';

$INI_EDITOR['accounts_manager_superadmin'] = 'SuperAdmin is always AccountsManager';
$INI_EDITOR['accounts_manager_superadmin:hint'] = 'If checked, the SuperAdmin will always be a AccountsManager too, 
regardless whether or not his E-Mail is listed in '.$INI_EDITOR['accounts_manager_email'].'.';

$INI_EDITOR['support_email'] = 'Support E-Mail';
$INI_EDITOR['support_email:hint'] = 'This field may stay empty. If empty <b>the first</b> E-Mail address from the above setting "'.$INI_EDITOR['accounts_manager_email'].'" will be used OR, in case the checkbox
<br />"'.$INI_EDITOR['accounts_manager_superadmin'].'" is checked, SuperAdmin\'s E-Mail will be used.';

$INI_EDITOR['preferences_template'] = 'Frontend Template for preferences.php';
$INI_EDITOR['preferences_template:hint'] = 'You can define a different frontend template to be used on the preferences.php page. 
<br />If this field stays empty or the entered template is not installed, the DEFAULT_TEMPLATE will be used.';

$INI_EDITOR['signup_template'] = 'Frontend Template for signup.php';
#$INI_EDITOR['signup_template:hint'] = 'You can define a different frontend template to be used on the signup.php page.';

$INI_EDITOR['login_template'] = 'Frontend Template for login.php';
#$INI_EDITOR['login_template:hint'] = 'You can define a different frontend template to be used on the login.php page.';	

$INI_EDITOR['notify_on_user_changes'] = 'Notify on profile changes';
$INI_EDITOR['notify_on_user_changes:hint'] = 'If checked, the AccountsManager(s) will be informed via mail when a user makes changes to his profile.
<br /><b>This is only relevant when the AdminTool is used for advanced profiles.</b>';
	
// SIGNUP CONFIG EXPLANATION
$sDOI = 'Double Opt-In ';
$sIA = ' Immediate activation';
$sCheck_0 = ' <i class="fa fa2x fa-square-o"></i>';
$sCheck_1 = ' <i class="fa fa2x fa-check-square-o"></i>';
$INI_EDITOR['SIGNUP_CONFIG_EXPL'] = '
<h2>Explanation</h2>
<p><b>'.$sDOI.$sCheck_0.'  + '.$sIA.$sCheck_1.' </b> (0 + 1) no double opt in, account is activated immediately (insecure!).</p>
<p><b>'.$sDOI.$sCheck_0.'  + '.$sIA.$sCheck_0.' </b> (0 + 0) no double opt-in, AccountsManager has to activate the user account.</p>
<p><b>'.$sDOI.$sCheck_1.'  + '.$sIA.$sCheck_1.' </b> (1 + 1) double opt-in, account is activated as soon the new user verifies his E-Mail address.</p>
<p><b>'.$sDOI.$sCheck_1.'  + '.$sIA.$sCheck_0.' </b> (1 + 0) double opt-in, AccountsManager has to activate the user account <b>(safest option!)</b>.</p>
';
	
	
