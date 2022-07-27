<?php

declare(strict_types=1);

namespace Magma\Router;

use Magma\Router\RouterInterface;

class Router implements RouterInterface
{

    /**
     * returns an array of routes from the routing table 
     * @var array
     */
    protected $route = [];

    /**
     * returns an array of route params
     * @var array
     */
    protected $params = [];

    /**
     * Adds surifx on controller name
     * @var string
     */
    protected $surfix = 'controller';

    /**
     * @inheritDoc
     */
    public function add (string $route, array $params) :void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url) :void
    {

    }

}