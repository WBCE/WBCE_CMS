<?php
/**
 * Twig function: getSetting(name, default)
 *
 * Read a CMS setting via Settings::get().
 *
 * Usage:
 *   {{ getSetting('website_title') }}
 *   {{ getSetting('my_module_flag', 'fallback') }}
 *   {% if getSetting('frontend_login') == 'true' %} ... {% endif %}
 */
$oTwig->addFunction(new \Twig\TwigFunction('getSetting',
    function (string $name, mixed $default = null): mixed {
        return Settings::get($name) ?? $default;
    }
));