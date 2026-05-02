<?php

/**
 * Twig function: get_area_states(groupId)
 * 
 * Returns tri-state ('none', 'partial', 'full') per permission area,
 * considering both system permission children AND addon permissions.
 * Used in the groups overview table for icon coloring.
 */
$oTwig->addFunction(new \Twig\TwigFunction("get_area_states", 
    function ($groupId = 0) {
        $perms = new PermissionManager($GLOBALS['database']);
        return $perms->getAreaStates((int) $groupId);
    }
));
