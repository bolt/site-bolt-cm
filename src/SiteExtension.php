<?php

namespace Bolt\Site;

use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\StorageTrait;
use Bolt\Menu\MenuEntry;
use Bolt\Site\Schema\Table;
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
    use DatabaseSchemaTrait;
    use StorageTrait;

    /**
     * {@inheritdoc}
     */
    protected function registerFrontendControllers()
    {
        return [
            '/' => new Controller\StatCollector(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        return [
            '/' => new Controller\SiteAdmin(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerMenuEntries()
    {
        $menu = new MenuEntry('SimpleStats-menu', 'simplestats');
        $menu->setLabel('SimpleStats')
            ->setIcon('fa:bar-chart')
            ->setPermission('dashboard')
        ;

        return [
            $menu,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return [
            'templates' => ['namespace' => 'site'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $this->extendDatabaseSchemaServices();
        $this->extendRepositoryMapping();

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

    /**
     * {@inheritdoc}
     */
    protected function registerExtensionTables()
    {
        return [
            'simplestats_log' => Table\SimpleStat::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerRepositoryMappings()
    {
        return [
            'simplestats_log' => [Entity\SimpleStat::class => Repository\SimpleStat::class],
        ];
    }
}
