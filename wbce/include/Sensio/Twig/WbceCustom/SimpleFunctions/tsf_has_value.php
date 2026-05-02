<?php
/**
 * Checks if a value is set and not empty.
 *
 * This function is useful in templates where you want to conditionally display
 * data based on whether a variable has a meaningful value. It returns true if
 * the provided value is set and is not an empty string or an empty array, and 
 * false otherwise.
 *
 * @param mixed $value The value to check. Can be a string or an array.
 *
 * @return bool True if the value is set and not an empty string or an empty array, 
 *              false otherwise.
 */
$oTwig->addFunction(new \Twig\TwigFunction("has_value", 
    function ($value) {
        if (is_array($value)) {
            return !empty($value);
        }
        return isset($value) && $value !== '';
    }
));
