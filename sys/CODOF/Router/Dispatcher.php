<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Router;

class Dispatcher
{

    /**
     * All routes with their handlers are stored here
     * @var array
     */
    private $routes = [];

    const ROUTE_GET = 'GET';
    const ROUTE_POST = 'POST';

    public function __construct()
    {
        $this->routes[self::ROUTE_GET] = [];
        $this->routes[self::ROUTE_POST] = [];
    }

    /**
     * Adds GET route with a handler
     * @param $route
     * @param $handler
     */
    public function get($route, $handler)
    {
        $this->routes[self::ROUTE_GET][$route] = $handler;
    }

    /**
     * Adds POST route with a handler
     * @param $route
     * @param $handler
     */
    public function post($route, $handler)
    {
        $this->routes[self::ROUTE_POST][$route] = $handler;
    }

    public function getJSON($route, $handler)
    {
        $this->routes[self::ROUTE_GET][$route] = function () use ($handler){
            Router::validateCSRF();
            echo json_encode(call_user_func_array($handler, func_get_args()));
        };
    }

    public function postJSON($route, $handler)
    {
        $this->routes[self::ROUTE_POST][$route] = function () use ($handler){
            Router::validateCSRF();
            echo json_encode(call_user_func_array($handler, func_get_args()));
        };
    }

    /**
     * Gets all routes
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
