<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

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

// Help file in English
?>
<p>English Help</p>
<p style="color:red;"><b>(Sorry, the english help is not completed yet)</b></p>
<p>Topics ist based on the News-Module, but made for full pages, like articles,
  stories, tutorials. It has search engine optimization, better commenting and
  support of pictures.</p>
<p>There are no more groups; the section is the group.</p>
<p>You can set &quot;see also&quot; links from one topic to another.</p>
<h2>Usage of a second block</h2>
<p>In view.php  TOPIC_BLOCK2 is defined:<br>
  Usage in the template (example):<br>
  if(defined('TOPIC_BLOCK2') AND TOPIC_BLOCK2 != '')
  { echo TOPIC_BLOCK2; }
else { page_content(2); }</p>
<h2>module_settings.php</h2>
<p>  Use this file for special/basic settings. Be careful. Note: if you upgrade
  Topics, this file is NOT changed/reset.</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td width="14%">Placeholder</td>
    <td width="86%">Output</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>In all fields:</h4></td>
  </tr>
  <tr>
    <td>[TOPIC_ID]</td>
    <td>Unique number</td>
  </tr>
  <tr>
    <td>[TITLE]</td>
    <td>Title</td>
  </tr>
  <tr>
    <td>[LINK]</td>
    <td>The URL of this topic-page</td>
  </tr>
  <tr>
    <td>[SHORT_DESCRIPTION]</td>
    <td>A short description, different to [TOPIC_SHORT] or
      [META_DESCRIPTION]</td>
  </tr>
  <tr>
    <td>[PICTURE_DIR]</td>
    <td>Can be set for each single section. Note: If you move topics, the picture-link
      breaks, if there are different directories.<br>    &quot;http:/meinedomain.de/media/meinetopicsbilder&quot;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Overview-Page: Header/Footer:</h4></td>
  </tr>
  <tr>
    <td>[SECTION_TITLE]</td>
    <td>group title</td>
  </tr>
  <tr>
    <td>[SECTION_DESCRIPTION]</td>
    <td>group description. May also contain html</td>
  </tr>
  <tr>
    <td>{PREV_NEXT_PAGES}</td>
    <td>the full footer links. empty, when there are no links</td>
  </tr>
  <tr>
    <td>[PREVIOUS_LINK]</td>
    <td>See the news-Module</td>
  </tr>
  <tr>
    <td>[NEXT_LINK]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[TOTALNUM]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>{JUMP_LINKS_LIST}</td>
    <td>List of jump Links href=&quot;#jumptid'.$t_id.'&quot;&gt;</td>
  </tr>
  <tr>
    <td>{JUMP_LINKS_LIST_PLUS}</td>
    <td>As above but including the Short_description</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Overview-Page AND  Topic Page</h4></td>
  </tr>
  <tr>
    <td>[TOPIC_SHORT]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[PICTURE]</td>
    <td>the filename of the picture; &quot;thepic.jpg&quot;</td>
  </tr>
  <tr>
    <td>{PICTURE}</td>
    <td>the full image tag</td>
  </tr>
  <tr>
    <td>[THUMB]</td>
    <td>the <strong>full</strong> url of the picture; &quot;&lt;img src=&quot;http://www......
      /media/topics-pictures/thumbs/thepic.jpg&quot; class=&quot;&quot;&gt;</td>
  </tr>
  <tr>
    <td>{THUMB}</td>
    <td>the <strong>full</strong> url + link: &lt;a href=&quot;...&quot;&gt;&lt;img src=&quot;...&quot; /&gt;&lt;/a&gt;</td>
  </tr>
  <tr>
    <td>[ADDITIONAL_PICTURES]</td>
    <td>A small gallery</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[ACTIVE]</td>
    <td>usually &gt; 3, if the topic is visible, max: 6</td>
  </tr>
  <tr>
    <td>[MODI_DATE]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[MODI_TIME]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[PUBL_DATE]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[PUBL_TIME]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[USER_ID]</td>
    <td>ID of the author</td>
  </tr>
  <tr>
    <td><s><font color="#808080">[USERNAME]</font></s><br>
      <font color="#008000">[USER_NAME]</font></td>
    <td>Username</td>
  </tr>
  <tr>
    <td><s><font color="#808080">[DISPLAY_NAME]<br>
    </font></s><font color="#008000">[USER_DISPLAY_NAME]</font></td>
    <td>full name</td>
  </tr>
  <tr>
    <td><s><font color="#808080">[EMAIL]<br>
    </font></s><font color="#008000">[USER_EMAIL]</font></td>
    <td>eMail of the author</td>
  </tr>
  <tr>
    <td><font color="#008000">[USER_MODIFIEDINFO]</font></td>
    <td>Last modified by User on Day at Time. Output only, if modified by a different
      user and later than published.</td>
  </tr>
  <tr>
    <td>[XTRA1]<br>
    [XTRA2]<br>
    [XTRA3]</td>
    <td>3 extra fields, see module_settings.php</td>
  </tr>
  <tr>
    <td>[EVENT_START_DATE]<br>
[EVENT_STOP_DATE]<br>
[EVENT_START_DAY]<br>
[EVENT_START_MONTH]<br>
[EVENT_START_YEAR]<br>
[EVENT_START_DAYNAME]<br>
[EVENT_START_MONTHNAME]<br>
[EVENT_START_TIME]<br>
[EVENT_STOP_TIME]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Overview-Page only:</h4></td>
  </tr>
  <tr>
    <td>{TITLE}</td>
    <td>[TITLE] + [LINK]. contains a link, if [LONG] has content</td>
  </tr>
  <tr>
    <td>[READ_MORE]</td>
    <td>&lt;div class=&quot;topics-readmore&quot;&gt;... &quot;Read-Link&quot;.
      Complete tag. Empty, if [LONG] has no content.</td>
  </tr>
  <tr>
    <td>[COMMENTSCOUNT]</td>
    <td>Integer, Number of comments</td>
  </tr>
  <tr>
    <td>[COMMENTSCLASS]</td>
    <td>0 - 4; Amount of number of comments. (none, one, more, much) For use
      with css-classes or icons</td>
  </tr>
  <tr>
    <td>[COUNTER]</td>
    <td>1, 2, 3, 4 ,5 ..</td>
  </tr>
  <tr>
    <td>[COUNTER2]</td>
    <td>0,1,0,1 alternating</td>
  </tr>
  <tr>
    <td>[COUNTER3]</td>
    <td>0,1,2,0,1,2 </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Single Topic Page only</h4></td>
  </tr>
  <tr>
    <td>[SECTION_TITLE]</td>
    <td>group title</td>
  </tr>
  <tr>
    <td>[SECTION_DESCRIPTION]</td>
    <td>group description.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[TOPIC_SHORT]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[TOPIC_EXTRA]</td>
    <td>see module_settings.php</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[EDITLINK]</td>
    <td>full link, only visible for admins (group 1)</td>
  </tr>
  <tr>
    <td>[BACK]</td>
    <td>the link only</td>
  </tr>
  <tr>
    <td>[META_DESCRIPTION]</td>
    <td>Use simplepagehead in your template, to have title, meta-description
      and keywords for each topic page</td>
  </tr>
  <tr>
    <td>[META_KEYWORDS]</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><font color="#008000">[ALLCOMMENTSLIST]</font></td>
    <td>All comments. If there is no placeholder, comments are handled like before</td>
  </tr>
  <tr>
    <td><font color="#008000">[COMMENFRAME]</font></td>
    <td>The iFrame with the comment-box. If there is no placeholder, it is displayed
      like before</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
