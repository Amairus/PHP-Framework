<?php

declare(strict_types=1);

namespace Magma\Router;

interface RouterInterface
{

/**
 * Add an route to the Router table
 * @param string route
 * @param array params
 * @return void 
 */
public function add(string $route, array $params) :void 


/**
 * Dispatch route and create controller objects and execute the default methods
 * on that object
 */
public function dispatch(string $url) :void 


}