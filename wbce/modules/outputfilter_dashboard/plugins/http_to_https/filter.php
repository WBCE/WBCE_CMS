<?php

/*
plugin_install.php

Copyright (C) 2019 Martin Hecht (mrbaseman)

This file is part of opf http to https, a plugin-filter for OutputFilter Dashboard.

opf automatic links is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

opf automatic links is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with opf automatic links. If not, see <http://www.gnu.org/licenses/>.

*/

function opff_http_to_https(&$content, $page_id, $section_id, $module, $wb) {
  // find out what we want to replace
  $http = str_replace ('https://', 'http://', WB_URL );
  // find out by what it shall be replaced
  $https = str_replace ('http://', 'https://', WB_URL );
  // finally do the replacement
  $content = str_replace ($http, $https, $content );
  return(TRUE);
}
?>
