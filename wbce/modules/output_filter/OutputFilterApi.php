<?php
/**
 *  Copyright (C) 2013 Werner v.d. Decken <wkl@isteam.de>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * OutputFilterApi.php
 *
 * @category     Addons
 * @package      Addons_OutputFilter
 * @copyright    Manuela v.d.Decken <manuela@isteam.de>
 * @author       Manuela v.d.Decken <manuela@isteam.de>
 * @license      http://www.gnu.org/licenses/gpl.html   GPL License
 * @version      0.0.1
 * @lastmodified $Date: 2014-01-03 02:21:42 +0100 (Fr, 03 Jan 2014) $
 * @since        File available since 25.12.2013
 * @description  can apply one ore more filters to $content
 *      Example: $sContent = OutputFilterApi('WbLink', $sContent);
 *      or..     $sContent = OutputFilterApi('WbLink|Relurl', $sContent);
 *      or..     $sContent = OutputFilterApi(array('WbLink', 'RelUrl'), $sContent);
 */
/**
 * OutputFilterApi
 * @param   string|array $mFilters  list of one or more filters
 * @param   string $sContent  content to apply filters
 * @return  string
 */
function OutputFilterApi($mFilters, $sContent)
{
    if (!is_array($mFilters)) {
        $mFilters = preg_split('/\s*?[,;| +]\s*?/', $mFilters, -1, PREG_SPLIT_NO_EMPTY);
    }
    foreach ($mFilters as $sFilterName) {
        if (!preg_match('/^[a-z][a-z0-9\-]*$/si', $sFilterName)) { continue; }
        $sFilterFile = __DIR__.'/filters/'.'filter'.$sFilterName.'.php';
        if (is_readable($sFilterFile)) {
            require_once($sFilterFile);
            $sFilterFunc = 'doFilter'.$sFilterName;
            if (function_exists($sFilterFunc)) {
                $sContent = $sFilterFunc($sContent);
            }
        }
    }
    return $sContent;
}

