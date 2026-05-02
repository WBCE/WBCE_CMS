<?php

namespace Twig\WbceCustom\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StripTagsExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('strip_tags', [$this, 'stripTagsFilter']),
        ];
    }

    public function stripTagsFilter($string)
    {
        return strip_tags($string);
    }
}
