<?php
/**
 * $Id: index.php 865 2008-10-25 08:29:52Z doc $
 * Website Baker template: blank
 * This template is one of four basis templates distributed with Website Baker.
 * Use this template for pages where you do not want anything wrapping the 
 * content e.g. a page for a global block.
 *
 * This file contains the Website Baker template function to output the content.
 *
 * LICENSE: GNU General Public License
 * 
 * @author     Ryan Djurovich, C. Sommer
 * @copyright  GNU General Public License
 * @license    http://www.gnu.org/licenses/gpl.html
 * @version    2.70
 * @platform   Website Baker 2.7
 *
 * Website Baker is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Website Baker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

// TEMPLATE CODE STARTS BELOW

// output only the page content, nothing else
page_content();

?>