-----------------------------------------------------------------------------------------
 Code snippet SimplePageHead v0.2 for Website Baker v2.6.x and later
 Licencsed under GNU, written by Chio, with a little help from thorn
 Extended for use with other modules by Christoph Marti
-----------------------------------------------------------------------------------------

What it does:
Displays a nearly complete <head> section, except stylesheet and script links
for all pages but particularly for news, bakery, gallery, topics and gocart module pages.
If possible it replaces the title and meta description by the module item title and item (short-)description. 

Displays a tag to hide the image-toolbar and a link to the favicon in WB_URL

NOTE: The snippet is a replacement for most head tags.
You must comment the old head section using <!-- ... --> */


*****************************************************************************************
 SHORT INSTALLATION GUIDE:
*****************************************************************************************
 o Download the simplepagehead zip file from http://websitebaker.at/wb/module.html
 o Log into the backend of WB and install the module as usual


*****************************************************************************************
 USING THE SNIPPET FUNCTION:
*****************************************************************************************
Once the module is installed, it can be invoked from the index.php file of your template.

From template index.php
...
<head>
<?php simplepagehead(); ?>
<link rel="stylesheet" type="text/css" href=".....
...


*****************************************************************************************
 ADDITIONAL PARAMETERS
*****************************************************************************************
For a more customised output, you can pass over serveral parameters to the function explained below.

<?php simplepagehead(endtag, norobotstag, notoolbartag, favicon, generator); ?>

Optional Parameters:
  endtag...          the closing tag: default: "/" for xhtml, "" for html4
  norobotstag...     default 1 = yes, 0 = no: shows on some pages the noindex,nofollow tag (e.g.: search)
  notoolbartag...    default 1 = yes, 0 = no: shows a tag to supress the IE-ImageToolbar
  favicon...         default 1 = yes, 0 = no: shows a link to the favicon in the root of your site (where it should be)
  generator...		 default 1 = yes, 0 = no: shows: meta name="generator" content="CMS: Website Baker; www.websitebaker.org"
 

Example for customised call for html4 with no additional tags:
  <?php simplepagehead('', 0, 0, 0); ?>

Example for customised call for xhtml with no robots-tags, but favicon and notoolbar-tag
  <?php simplepagehead('/', 0, 1, 1); ?>


*****************************************************************************************
 TROUBLE SHOOTING
*****************************************************************************************
 - Pass over either no argument, or all arguments in expected order
 - Remind the ; at the end of the code line


*****************************************************************************************