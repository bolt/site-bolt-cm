<?php

namespace Bolt\Site\Controller;

use Bolt\Controller\Base;
use Bolt\Site\Entity;
use Bolt\Site\Repository;
use Carbon\Carbon;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Statistics collection controller.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class StatCollector extends Base
{
    /**
     * {@inheritdoc}
     */
    protected function addRoutes(ControllerCollection $c)
    {
        $c->post('collect', [$this, 'statCollect'])
            ->bind('statCollect')
        ;

        return $c;
    }

    /**
     * Stat collection route.
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return JsonResponse
     */
    public function statCollect(Application $app, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }
        if (!$request->request->has('origin')) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }
        // Register the terminate callback for after the response is sent to
        // the client, so the database calls are non-blocking.
        $app->finish(function (Request $request) use ($app) {
            $this->statCollectCallback($app, $request);
        });

        return new JsonResponse();
    }

    /**
     * Logging callback for the stats collector.
     *
     * @param Application $app
     * @param Request     $request
     */
    private function statCollectCallback(Application $app, Request $request)
    {
        /** @var Repository\SimpleStat $repo */
        $repo = $app['storage']->getRepository(Entity\SimpleStat::class);
        $entity = new Entity\SimpleStat([
            'ip'           => $request->getClientIp(),
            'timestamp'    => Carbon::now(),
            'browseragent' => $request->headers->get('user-agent'),
            'uri'          => $request->request->get('origin'),
            'query'        => $request->getQueryString(),
            'referrer'     => $request->headers->get('referer'),
        ]);
        $repo->save($entity);
    }
}
