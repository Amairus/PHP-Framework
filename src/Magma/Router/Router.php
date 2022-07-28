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
        if($this->match($url)){
            $controllerSettings = $this->params['controller'];
            $controllerSettings = $this->transformUpperCamelCase($controllerSettings);
            $controllerSettings = $this->getNamespace($controllerSettings);
        }
    }

    public function transformUpperCamelCase($controllerSettings)
    {
        return str_replace(' ', '', ucwords(str_replace('-', '', $controllerSettings)));
    }


    /**
     * Match the route to the route in the routing table, setting the $this->params property 
     * if a route is found
     * 
     * @param string $url
     * @return bool 
     */
    public function match(string $url) : bool 
    {
        
        foreach($this->routes as $route){
            if(preg_match($route, $url, $matches)){
                foreach($matches as $key => $param){
                    if(is_string($key))
                        $params[$key] = $param;
                }
                $this->params = $param;
                return true;
            }
        }
        return false;
    }


    public function getNamespace(string $string) : string
    {

    }

}