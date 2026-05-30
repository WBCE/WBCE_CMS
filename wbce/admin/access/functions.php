<?php


if(!function_exists('renderAccessTabs')){
    /**
     * renderAccessTabs
     *     Build the Tab Navigation used in Admin Backend and check
     *     for permissions.
     *     The items array consists of the area and fa icon
     *  
     * 
     * @param  string  $sSelectedPos
     * @param  array   $aItems
     * @return type
     */
    function renderAddonsTabs($sSelectedPos = 'access', $aItems = [
        // area  => fa icon
        'access' => 'key',
        'users'  => 'user',
        'groups' => 'users'
    ]) {
        $aTabs = [];    // Collect array with translation + FA icon

        foreach ($aItems as $key => $icon) {
            if (!$GLOBALS['admin']->get_permission($key)) {
                continue; // Skip if user has no permission
            }

            $sUri = $key; // the URI (href attribute)
            
            // special handling for addons/config.php
            if ($key == 'addons/config.php') {
                $key = 'settings';
            }

            $isCurrent = ($key == $sSelectedPos); // Check if current position/tab

            $aTabs[$key] = [
                'pos'       => $sUri,
                'a_class'   => ($isCurrent) ? ' sel' : '',
                'li_class'  => ($isCurrent) ? ' class="actionSel"' : '',
                'link_name' => L_("{MENU:" . strtoupper($key) . "}"),
                'icon'      => $icon
            ];
        }

        return $aTabs;
    }    
}
