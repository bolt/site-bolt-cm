<?php

namespace Bolt\Site\Controller;

use Bolt\Controller\Backend\BackendBase;
use Bolt\Response\TemplateResponse;
use Bolt\Response\TemplateView;
use Silex\ControllerCollection;

/**
 * Site admin controller.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class SiteAdmin extends BackendBase
{
    /**
     * {@inheritdoc}
     */
    protected function addRoutes(ControllerCollection $c)
    {
        $c->get('/extensions/simplestats', [$this, 'simpleStats']);

        return $c;
    }

    /**
     * @return TemplateResponse|TemplateView
     */
    public function simpleStats()
    {
        $this->app['twig']->addPath();

        return $this->render('@site/simplestats.twig');
    }
}
