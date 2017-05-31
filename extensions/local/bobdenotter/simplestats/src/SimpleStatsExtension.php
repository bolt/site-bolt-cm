<?php

namespace Local\Simplestats;

use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Controller\Zone;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\SimpleExtension;
use Bolt\Menu\MenuEntry;
use Carbon\Carbon;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SimpleStatsExtension extends SimpleExtension
{
    use DatabaseSchemaTrait;

    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $this->extendDatabaseSchemaServices();
    }


    /**
     * {@inheritdoc}
     */
    protected function registerExtensionTables()
    {
        return [
            'simplestats_log' => Tables\LogTable::class,
        ];
    }

    /**
     * Set up the default configuration.
     *
     * @see https://docs.bolt.cm/extensions/basics/configuration#providing-defaults
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'foo' => 'bar',
            'qux' => 'baz'
        ];
    }

    /**
     * Add a backend menu entry under 'extensions'.
     *
     * @see https://docs.bolt.cm/extensions/intermediate/admin-menus#registering-menu-entries
     *
     * @return array
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
     * Register routes for the Backend
     *
     * @see https://docs.bolt.cm/extensions/intermediate/controllers-routes#route-callback-functions
     *
     * @param ControllerCollection $collection
     */
    protected function registerBackendRoutes(ControllerCollection $collection)
    {
        $collection->get('/extensions/simplestats', [$this, 'backendSimpleStats']);

    }


    /**
     * Register twig functions to be used in templates.
     *
     * @see https://docs.bolt.cm/extensions/basics/twig#registering-twig-functions
     *
     * @return array
     */
    protected function registerTwigFunctions()
    {
        return [
            'foo' => 'fooFunction',
            'bar' => ['barFunction', ['is_safe' => ['html']]]
        ];
    }


    /**
     * Render and return the Twig file templates/foo.twig
     *
     * @return string
     */
    public function fooFunction()
    {
        return $this->renderTemplate('foo.twig');
    }


    /**
     * Controller - Callback function for backend route
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function backendSimpleStats(Application $app, Request $request)
    {
        return $this->renderTemplate('simplestats.twig');
    }

    protected function registerAssets()
    {
        $asset = new Snippet();
        $asset->setCallback([$this, 'callbackSnippet'])
            ->setLocation(Target::END_OF_HEAD)
            ->setPriority(0)
            ->setZone(Zone::FRONTEND)
        ;

        return [
            $asset,
        ];
    }

    public function callbackSnippet()
    {
        $app = $this->getContainer();
        $url = parse_url($app['request']->getUri());
        $carbon = new Carbon();
        $log = [
            'ip' => $app['request']->getClientIp(),
            'timestamp' => $carbon->toRfc3339String(),
            'browseragent' => $app['request']->headers->get('user-agent'),
            'route' => $app['request']->get('_route'),
            'uri' => $url['path'],
            'query' => isset($url['query']) ? $url['query'] : '',
            'referrer' => $app['request']->headers->get('referer')
        ];

        $app['db']->insert($this->getTablename(), $log);

        return '<!-- Bolt SimpleStats++: ' . $url['path'] . ' -->';
    }

    public function getTablename()
    {
        $app = $this->getContainer();

        return sprintf("%s%s", $app['config']->get('general/database/prefix'), 'simplestats_log');
    }
}