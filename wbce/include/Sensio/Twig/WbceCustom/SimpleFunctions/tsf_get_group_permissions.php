<?php

/**
 * Twig function: get_group_permissions(groupId)
 * @platform WBCE CMS 1.7.0
 * 
 * Returns the permission structure for a group, used in the 
 * groups overview table to show permission icons.
 *
 * Updated for AccessManager refactor — delegates to PermissionManager.
 */
$oTwig->addFunction(new \Twig\TwigFunction("get_group_permissions", 
    function ($groupId = 0) {
        $perms = new PermissionManager($GLOBALS['database']);
        return $perms->getStructure((int) $groupId);
    }
));
