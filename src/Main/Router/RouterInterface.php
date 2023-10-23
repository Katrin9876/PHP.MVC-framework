<?php

declare(strict_types=1);

namespace Main\Router;

interface RouterInterface
{

    /**
     * Simple add a route to the routing table
     */
    public function add(string $route, array $params = []) : void;

    /**
     * Dispatch route and create controller objects and execute the default method 
     * on that controller object
     */
    public function dispatch(string $url) : void;

}