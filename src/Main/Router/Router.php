<?php

declare(strict_types=1);

namespace Main\Router;

use Main\Router\RouterInterface;

class Router implements RouterInterface
{

    /**
     * returns an array of route from our routing table
     */
    protected array $routes = [];

    /**
     * returns an array of route parameters
     */
    protected array $params = [];

    /**
     * Adds a suffix onto the controller name
     */
    protected string $controllerSuffix = 'controller';

    /**
     * @inheritDoc
     */
    public function add(string $route, array $params = []) : void
    {   
        // // Convert the route to a regular expression: escape forward slashes
        // $route = preg_replace('/\//', '\\/', $route);

        // // Convert variables e.g. {controller}
        // $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // // Convert variables with custom regular expressions e.g. {id:\d+}
        // $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // // Add start and end delimiters, and case insensitive flag
        // $route = '/^' . $route . '$/i';
        
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url) : void
    {   
        // $url = $this->formatQueryString($url);
        if ($this->match($url)) {
            $controllerString = $this->params['controller'] . $this->controllerSuffix;
            $controllerString = $this->transformUpperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString) . $controllerString;

            if (class_exists($controllerString)) {
                $controllerObject = new $controllerString($this->params);
                $action = $this->params['action'];
                $action = $this->transformCamelCase($action);

                if (\is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new Exeption;
                    //throw new BaseBadMethodCallException('Invalid method');
                }
            } else {
                throw new Exeption;
                //throw new BaseBadFunctionCallException('Controller class does not exist');
            }
        } else {
            throw new Exeption;
            //throw new BaseInvalidArgumentException('404 ERROR no page found');
        }
        
    }

    public function transformUpperCamelCase(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    public function transformCamelCase(string $string) : string
    {
        return \lcfirst($this->transformUpperCamelCase($string));
    }

    /**
     * Match the route to the routes in the routing table, setting the $this->params property
     * if a route is found
     */
    private function match(string $url) : bool
    {
        //$this->add('http://localhost/user/index',['controller'=>'User', 'action'=>'index']);
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
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
     * Get the namespace for the controller class. the namespace difined within the route parameters 
     * only if it was added.
   
     */
    public function getNamespace(string $string) : string
    {
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }

    /**
     */
    // protected function formatQueryString($url)
    // {
    //     if ($url != '') {
    //         $parts = explode('&', $url, 2);

    //         if (strpos($parts[0], '=') === false) {
    //             $url = $parts[0];
    //         } else {
    //             $url = '';
    //         }
    //     }

    //     return rtrim($url, '/');
    // }

    // public function getRoutes()
    // {
    //     return $this->routes;
    // }


}