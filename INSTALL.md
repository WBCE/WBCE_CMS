# WBCE Installation Instructions

 1. Preparing your webspace
    - Login to your webspace account and create a MySQL database (or figure out the login credentials, e.g. server (usually localhost), database name, database user and database password)

 2. Download WBCE
    - Download [WBCE](http://wbce.org)
    - Unzip the package to your local machine

 3. Uploading
   - Upload all files inside subfolder `wbce` into the root folder of your webspace using your FTP tool of choice

 5. Installation
   - Point your web browser to the domain (URL) where WBCE will be installed (for example yourdomain.com)
   - The installation wizard starts automatically, checks the system requirements and will ask for database and login credentials and some more information
   - Follow the instructions of the installation wizard

 6. Wrap up
   - Check if the root folder of your webspace contains an `/install` folder and/or a `upgrade-script.php` file
   - Delete if necessary the folder and the file from your webspace using your FTP tool of choice (usually these files are deleted when loggin in the first time)
   - By default, the login to the WBCE backend can be found at yourdomain.com/admin
   - Have fun
