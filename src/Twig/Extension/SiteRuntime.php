<?php

namespace Bolt\Site\Twig\Extension;

use Doctrine\Common\Cache\CacheProvider;
use GuzzleHttp\Client;
use Twig\Markup;

/**
 * Bolt.cm Twig run-time extension.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class SiteRuntime
{
    /** @var Client */
    private $client;
    /** @var CacheProvider */
    private $cache;

    /**
     * Constructor.
     *
     * @param Client        $client
     * @param CacheProvider $cache
     */
    public function __construct(Client $client, CacheProvider $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function getLatestVersion()
    {
        if ($this->cache->contains('bolt_version')) {
            $version = $this->cache->fetch('bolt_version');
        } else {
            $request = $this->client->get('https://get.bolt.cm/latest');
            if ($request->getStatusCode() >= 200 && $request->getStatusCode() < 300) {
                $version = (string) $request->getBody();
                $this->cache->save('bolt_version', $version, 3600);
            } else {
                $version = '3.3.0';
            }
        }

        return $version;
    }

    public function getChangeLogLink()
    {
        return new Markup(sprintf(
            '<a href="https://github.com/bolt/bolt/blob/v%s/changelog.md" ' .
            'title="The releasenotes on GitHub">View the release notes in the Changelog</a>',
            $this->getLatestVersion()
        ), 'UTF-8');
    }
}
