Webadmin Filemnager
------------------------

Download from: [https://gist.github.com/nic-o/1219610](https://gist.github.com/nic-o/1219610 "Webadmin filemanager") as the original 
page [http://cker.name/webadmin/](http://cker.name/webadmin/ "Webadmin filemanager")had a disfunctional .zip file

I added this opensource filemanager to remove files from the webspace
that have been added by websitebaker whith invalid file and directory
permissions on a Server that uses PHP as Apache module.

On such a server it may happen that if you try to remove such files via 
FTP you may get a "insufficient permissions error". Thats because the 
files are created by the webserver under a user different from FTP user.

Just copy this filemanager into the webroot of your page and call:

www.yourdomain.com/webadmin.php


!!! ATTENTION !!!
Please keep in mind that this admintool also can be abused as a hacking 
tool (indeed its a pretty good one, almost a hackershell) 
So please remove it after use or install it into a password protected 
directory (Its not necessary to install it into webroot). 
Please be really carefull!

