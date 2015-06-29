
Maileinstellungen in Optionen ( nur vom Superadmin einzurichten )
-----------------------------------------------------------------
Standard "VON" Adresse: SERVER_EMAIL
Standard Absender Name: WBMAILER_DEFAULT_SENDERNAME

E-Mail Optionen in Formular Einstellungen
-----------------------------------------
E-Mail Empfänger: wenn leer dann wird automatisch mit Superadmin (user_id==1) E-Mail vorbelegt
Angezeigter Name: Benutzerdefiniert oder WBMAILER_DEFAULT_SENDERNAME
E-Mail Betreff:   Benutzerdefiniert oder wenn leer dann senden mit $MOD_FORM['EMAIL_SUBJECT']

E-Mail Bestätigung in Formular Einstellungen
--------------------------------------------
E-Mail Empfänger: Auswahlliste, wenn Auswahl "keine"" dann keine Bestätigungs E-Mail an Sender
Angezeigter Name: Benutzerdefiniert oder WBMAILER_DEFAULT_SENDERNAME (Eintrag unter Optionen Maileinstellungen)
E-Mail Betreff:   Benutzerdefiniert oder wenn leer dann senden mit $MOD_FORM['SUCCESS_EMAIL_SUBJECT']
E-Mail Text:      Benutzerdefiniert oder wenn leer dann senden mit $MOD_FORM['SUCCESS_EMAIL_TEXT'].$MOD_FORM['SUCCESS_EMAIL_TEXT_GENERATED']

