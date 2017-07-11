<?php

namespace Bolt\Site\Entity;

use Bolt\Storage\Entity\Entity;
use DateTime;

/**
 * SimpleStat entity.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class SimpleStat extends Entity
{
    /** @var DateTime */
    protected $timestamp;
    /** @var string */
    protected $ip;
    /** @var string */
    protected $browseragent;
    /** @var string */
    protected $route;
    /** @var string */
    protected $uri;
    /** @var string */
    protected $query;
    /** @var string */
    protected $referrer;
    /** @var int */
    protected $aggregated;

    /**
     * @return DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getBrowseragent()
    {
        return $this->browseragent;
    }

    /**
     * @param string $browserAgent
     */
    public function setBrowseragent($browserAgent)
    {
        $this->browseragent = $browserAgent;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @param string $referrer
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    /**
     * @return int
     */
    public function getAggregated()
    {
        return $this->aggregated;
    }

    /**
     * @param int $aggregated
     */
    public function setAggregated($aggregated)
    {
        $this->aggregated = $aggregated;
    }
}
