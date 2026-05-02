<?php

namespace Twig\WbceCustom\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UnserializeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('unserialize', [$this, 'unserializeFilter']),
        ];
    }

    public function unserializeFilter($string)
    {
        if (is_string($string)) {
            return unserialize($string);
        }
        
        return $string;
    }
}