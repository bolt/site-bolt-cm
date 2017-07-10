<?php

namespace Bolt\Site;

use Bolt\Extension\SimpleExtension;
use Bolt\Site\Twig;
use Bolt\Twig\SecurityPolicy;
use Silex\Application;
use Twig\Environment;
use Twig\Extension\SandboxExtension;

/**
 * Bolt.cm site extension.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class SiteExtension extends SimpleExtension
{
    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $app['twig.runtime.site'] = function ($app) {
            return new Twig\Extension\SiteRuntime($app['guzzle.client'], $app['cache']);
        };

        $app['twig.runtimes'] = $app->extend(
            'twig.runtimes',
            function ($runtimes) {
                return $runtimes + [
                    Twig\Extension\SiteRuntime::class => 'twig.runtime.site',
                ];
            }
        );

        $app['twig'] = $app->share($app->extend(
            'twig',
            function (Environment $twig) {
                $twig->addExtension(new Twig\Extension\SiteExtension());

                /** @var SandboxExtension $sandbox */
                $sandbox = $twig->getExtension(SandboxExtension::class);
                /** @var SecurityPolicy $policy */
                $policy = $sandbox->getSecurityPolicy();
                if ($policy instanceof SecurityPolicy) {
                    $policy->addAllowedFunction('latest_version');
                }

                return $twig;
            }
        ));
    }
}
