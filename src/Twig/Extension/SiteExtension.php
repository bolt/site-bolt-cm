<?php

namespace Bolt\Site\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Bolt.cm Twig extension.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class SiteExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('latest_version', [SiteRuntime::class, 'getLatestVersion']),
        ];
    }
}
