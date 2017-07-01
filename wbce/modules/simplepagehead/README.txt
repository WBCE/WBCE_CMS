-----------------------------------------------------------------------------------------
 Code snippet SimplePageHead v0.6 for WBCE 1.x and later
 Licencsed under GNU, written by Chio, with a little help from thorn, reworked for WBCE
 by Florian Meerwinck
 Extended for use with other modules by Christoph Marti
-----------------------------------------------------------------------------------------

What it does:
Displays a nearly complete <head> section, except stylesheet and script links
for all pages but particularly for news, bakery, gallery, topics and gocart module pages.
If possible it replaces the title and meta description by the module item title and item (short-)description. 

Displays a tag to hide the image-toolbar and a link to the favicon and common app icons if
they were found in the template directory.

NOTE: The snippet is a replacement for most head tags.
You must either remove or comment out hard-coded head tags.


*****************************************************************************************
 SHORT INSTALLATION GUIDE:
*****************************************************************************************
 SimplePageHead is included in WBCE by default. If it is missing or you want to update it:
 o Download simplepagehead from https://addons.wbce.org/pages/addons.php?do=item&item=29
 o Log into the backend of WBCE and update the module as usual


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
For a more customised output, you can pass over serveral parameters to the function 
explained below.

<?php simplepagehead(endtag, norobotstag, notoolbartag, favicon, generator); ?>

Optional Parameters:
  endtag...          the closing tag: 
					 default: "/" for xhtml, "" for html4
  norobotstag...     default 1 = yes, 0 = no: 
					 shows on some pages the noindex,nofollow tag (e.g.: search)
  notoolbartag...    default 1 = yes, 0 = no: shows a tag to supress the IE-ImageToolbar
  favicon...         default 1 = yes, 0 = no: shows a link to the favicon and app icons
					 See details at the bottom
  generator...		 default 1 = yes, 0 = no:
					 shows: meta name="generator" content="WBCE CMS; https://wbce.org"
 

Example for customised call for html4 with no additional tags:
  <?php simplepagehead('', 0, 0, 0); ?>

Example for customised call for xhtml with no robots-tags, but favicon and notoolbar-tag
  <?php simplepagehead('/', 0, 1, 1); ?>

*****************************************************************************************
 FAVICONS AND APP ICONS
*****************************************************************************************

Since simplepagehead 0.6 the module checks if theicon files exist and generates only 
for existing icons the corresponding lines of code.
The module looks at first for a favicon.ico in the root. If this is not found, it looks
in the directory of the current template for a favicon.ico and takes that one if found.
Then it checks whether the apple-touch-icons exist in the template directory, e.g.
apple-touch-icon.png
apple-touch-icon-57x57.png
apple-touch-icon-72x72.png
apple-touch-icon-76x76.png
apple-touch-icon-120x120.png
apple-touch-icon-144x144.png
apple-touch-icon-152x152.png
and generate the necessary lines of code if the given icon were found.  

Hint: Generate your favicons at http://iconifier.net
  
*****************************************************************************************
 TROUBLE SHOOTING
*****************************************************************************************
 - Pass over either no argument, or all arguments in expected order
 - Remind the ; at the end of the code line
 - Make sure the icons are named exactly as described and are stored in the directory of
   the current template of the page / website

*****************************************************************************************