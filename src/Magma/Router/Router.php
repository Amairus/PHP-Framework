<?php

declare(strict_types=1);

namespace Magma\Router;

use Exception;
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
            $controllerString = $this->params['controller'];
            $controllerString = $this->transformUpperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString);
         
            if(class_exists($controllerString)){
                $controllerObject = new $controllerString();
                $action = $this->params['action'];
                $action = $this->transformCamelCase($action);

                if(is_callable([$controllerObject, $action])){
                    $controllerObject->$action();
                }else{
                    throw new Exception();
                }
            } else {
                throw new Exception();
            }
        } else {
            throw new Exception();
        }
    }


    public function transformUpperCamelCase(string $string): string
    {
        return str_replace(' ','',ucwords(str_replace('-', ' ', $string)));
    }

    public function transformCamelCase(string $string) : string
    {
        return lcfirst($this->transformUpperCamelCase($string));
    }

    /**
     * Match the route to the routes in the route table, and set the $this->params property 
     * if route is found
     * 
     * @param string $url
     * @return boolean
     */
    private function match(string $url) : bool
    {
        foreach($this->routes as $route => $params){
            if(preg_match($route, $url, $matches)){
                foreach($matches as $key => $param){
                    if(is_string($key)){
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the namespace for the controller class . the namespace defined in the route param only 
     * if exists
     *
     * @param string $string
     * @return string
     */
    public function getNamespace(string $string) : string
    {
        $namespace = 'App\\Controller';
        if(array_key_exists('namespace', $this->params))
        {
            $namespace .= $this->params['namespace'] .'\\';
        }
        return $namespace;
    }

}