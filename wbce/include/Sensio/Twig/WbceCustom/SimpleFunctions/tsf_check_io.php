<?php

/**
 * Twig function: check_io(value, compare)
 *
 * Outputs ' checked' when value matches compare.
 * Used for radio buttons in settings forms.
 *
 * Usage:
 *   <input type="radio" value="true"{{ check_io(cfg.setting, 1) }}>
 *   <input type="radio" value="false"{{ check_io(cfg.setting, 0) }}>
 *
 * Comparison is loose: 'true'/1/true all match 1, 'false'/0/false/''/null match 0.
 */
$oTwig->addFunction(new \Twig\TwigFunction("check_io",
    function ($value = '', $compare = 1): string {
        // Normalize both sides to boolean-ish
        $valBool = in_array($value, [true, 1, '1', 'true'], true);
        $cmpBool = in_array($compare, [true, 1, '1', 'true'], true);

        return ($valBool === $cmpBool) ? ' checked' : '';
    }
));
