<?php defined('WB_PATH') or header("Location: ../index.php", true, 301); ?>
<!-- [subject]Your login details for {{LOGIN_WEBSITE_TITLE}}[/subject] -->
Hello {{LOGIN_DISPLAY_NAME}},

you have successfully registered on {{LOGIN_WEBSITE_TITLE}}.

Your login details:

Username: {{LOGIN_NAME}}
Password: {{LOGIN_PASSWORD}}

Click here to log in:
<a href="{{LOGIN_URL}}">{{LOGIN_URL}}</a>

If you have any questions, please contact the support: <a href="mailto:{{SUPPORT_EMAIL}}">{{SUPPORT_EMAIL}}</a>

------------------------------------------------------------------------------------------
This email was generated automatically
------------------------------------------------------------------------------------------