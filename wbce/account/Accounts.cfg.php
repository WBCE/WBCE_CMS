; <?php defined('WB_PATH') or die(); ?>

; DE: Einstellungen für die Benutzerregistrierung, Kommentarzeilen beginnen mit einem Semikolon
; EN: Account Registration Configuration Settings, comment lines start with a semicolon

[Signup_Account_Settings]

; DE: Hier kann festgelegt werden, ob die Registrierung per E-Mail bestätigt werden soll (1) oder keine Überprüfung der E-Mail-Adresse erfolgt (0)
; EN: decide whether Double opt-in should be used (1) or not (0) 

signup_double_opt_in = 1;


; DE: Hier kann festgelegt werden, ob die Benutzeraccounts durch den Administrator (User-ID 1) freigeschaltet werden müssen (0) oder der Benutzeraccount sofort aktiv ist (1)
; EN: decide whether the user account should be activated right after signup (1) or has to be activated by the administrator (User-ID 1) first (0) 

user_activated_on_signup = 0;

; Examples
; 0 + 1 no double opt in, account is activated immediately (insecure!) 
; 0 + 0 no double opt-in, administrator has to activate the user account 
; 1 + 1 double opt-in, administrator has to activate the user account  
; 1 + 0 double opt-in, account is activated immediately  