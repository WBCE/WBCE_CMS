/*~ readme.txt
.---------------------------------------------------------------------------.
|  Software: QuickSkin                                                      |
|   Version: 5.0                                                            |
|   Contact: andy.prevost@worxteam.com                                      |
|      Info: http://sourceforge.net/projects/quickskin                      |
|   Support: http://sourceforge.net/projects/quickskin                      |
| ------------------------------------------------------------------------- |
|    Author: Andy Prevost andy.prevost@worxteam.com (admin)                 |
|    Author: Manuel 'EndelWar' Dalla Lana endelwar@aregar.it (former admin) |
|    Author: Philipp v. Criegern philipp@criegern.com (original founder)    |
| Copyright (c) 2002-2009, Andy Prevost. All Rights Reserved.               |
|    * NOTE: QuickSkin is the SmartTemplate project renamed. SmartTemplate  |
|            information and downloads can still be accessed at the         |
|            smarttemplate.sourceforge.net site                             |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| We offer a number of paid services:                                       |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
Last modified: January 01 2009 ~*/

For documentation, please see the 'DOCS' folder.

QuickSkin v5.0
http://sourceforge.net/projects/quickskin 

Copyright (c) 2002-2009, Andy Prevost. All Rights Reserved.

QuickSkin is a Template Engine that was designed to support web based 
applications of any size. It is particularly suited to large scale applications
like Content Management Systems. The project was originally started by 
Philipp v. Criegern and named "SmartTemplate". The project was then taken over
by Manuel 'EndelWar' Dalla Lana and HonestQiao. The name of the project back
then led to confusion with Smarty Templating. Despite its advantages over other
templating systems (speed, small footprint, ease of use, power), SmartTemplate
has not been updated and had many outstanding bugs. Codeworx Technologies has
been using SmartTemplate on a variety of projects for several years. Over that
time we have become very familiar with the code. We have now taken over the
project. Our first task was to rename the project to make it less confusing,
fix the bugs, and extend its capabilities -- while maintaining its core 
simplicity and ease of use.

What's so special about it?

Common template engines work like the following: Your PHP script specifies an 
HTML template and assigns some dynamic content to display. The template parser
replaces all placeholders within the template with the assigned content and
displays it to the user. This means a lot of string processing and regular
expression work each time you want to display some content.

QuickSkin works like a 'template compiler' that converts templates into executable
PHP code and stores it for later reuse. The first time a new template is processed,
all placeholders in the template are replaced by small PHP code elements that
print the assigned content. The HTML template fragment <H3>{TITLE}</H3>, for
example, is converted into something like <H3><?php echo $TITLE; ?></H3>. If you
have assigned your content to the right variables, there is no need for any
template parsing anymore. The only thing that has to be done is to include and
execute the compiled template. This usually increases the execution time of the
template engine dramatically.

QuickSkin supports:

- Simple Scalar Substitution (Strings, etc.)
- Block Iterations (nested Arrays / LOOP..ENDLOOP)
- Basic Control Structures (IF..ELSEIF..ELSE)
- Custom Extension (Output filters, uppercase, sprintf, etc.)
- Template Compilation (HTML templates are converted to executable PHP Code)
- Output Caching (Accelerates your applications by reusing page output) 
- Tested and works with PHP4 and PHP5 (5.2.4)

If you successfully deploy QuickSkin with your applications, please add text
link or link the QuickSkin button back to
http://sourceforge.net/projects/quickskin/.

Please let us know about your usage, so we can keep track of the sites using
QuickSkin.
     
Andy Prevost.
