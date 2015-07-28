<?php
/*
 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2010, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Insert an extra row into the database
$header = addslashes('<p>');
$level_header = addslashes('<ul>');
$sitemaploop = addslashes('<li><a href="[LINK]" target="[TARGET]">[PAGE_TITLE]</a></li>');
$level_footer = addslashes('</ul>');
$footer = addslashes('</p>');

$database->query("INSERT INTO ".TABLE_PREFIX."mod_sitemap (page_id,section_id,static,header,sitemaploop,footer,level_header,level_footer,startatroot,depth,show_hidden) VALUES ('$page_id','$section_id','1','$header','$sitemaploop','$footer','$level_header','$level_footer', '1', '0','0')");

?>