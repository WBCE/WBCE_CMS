<?php
/**
 * CastExtension
 *
 * Adds PHP type-cast filters to Twig:
 *   |string   (string) cast
 *   |int      (int) cast
 *   |float    (float) cast
 *   |bool     (bool) cast
 *
 * Registration in TwigLoader.php:
 *   $oTwig->addExtension(new \Twig\WbceCustom\Extension\CastExtension());
 *
 * Namespace: Twig\WbceCustom\Extension  (matches TwigLoader autoloader)
 */

namespace Twig\WbceCustom\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CastExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('string', fn(mixed $v): string => (string)$v),
            new TwigFilter('int',    fn(mixed $v): int    => (int)$v),
            new TwigFilter('float',  fn(mixed $v): float  => (float)$v),
            new TwigFilter('bool',   fn(mixed $v): bool   => (bool)$v),
        ];
    }
}
