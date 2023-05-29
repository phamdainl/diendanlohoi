<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Router;

use CODOF\Access\CSRF;

class Router
{
    /**
     * @var Dispatcher
     */
    private static $simpleDispatcher = null;

    /**
     * @var Dispatcher
     */
    private static $cachedDispatcher = null;

    const DISPATCHER_TYPE_SIMPLE = 1;
    const DISPATCHER_TYPE_CACHED = 2;

    public static function getDispatcher($dispatcherType)
    {
        if ($dispatcherType === self::DISPATCHER_TYPE_SIMPLE) {
            if (self::$simpleDispatcher == null) {
                self::$simpleDispatcher = new Dispatcher();
            }
            return self::$simpleDispatcher;
        } else {
            if (self::$cachedDispatcher == null) {
                self::$cachedDispatcher = new Dispatcher();
            }
            return self::$cachedDispatcher;
        }
    }

    private function __construct()
    {
    }

    public static function dispatch()
    {
        $cachedDispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $routeData = self::$cachedDispatcher->getRoutes();
            foreach ($routeData as $httpMethod => $routes) {
                foreach ($routes as $route => $handler) {
                    $r->addRoute($httpMethod, $route, $handler);
                }
            }
        });

        self::_dispatch($cachedDispatcher);
    }

    /**
     * Validates CSRF token
     */
    public static function validateCSRF()
    {
        $token = null;
        if (isset($_REQUEST['token'])) {
            $token = $_REQUEST['token'];
        } else if (isset($_REQUEST['_token'])) {
            $token = $_REQUEST['_token'];
        }

        if (!CSRF::valid($token)) {
            exit("Invalid CSRF"); //TODO: Maybe throw an exception and handle it properly ;)
        }
    }

    private static function _dispatch(\FastRoute\Dispatcher $dispatcher)
    {
// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = self::getRequestURI();

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                \CODOF\Smarty\Layout::not_found();
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                call_user_func_array($handler, array_values($vars));
                // ... call $handler with $vars
                break;
        }

    }


    /**
     * (Inspired from limonade request_uri)
     *
     * @return string
     */
    private static function getRequestURI()
    {
        if (isset($_GET['uri'])) {
            $uri = $_GET['uri'];
        } else if (isset($_GET['u'])) {
            $uri = $_GET['u'];
        } else {
            if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
                $request_uri = rtrim($_SERVER['REQUEST_URI'], '?/') . '/';
                $base_path = $_SERVER['SCRIPT_NAME'];

                if ($request_uri . "index.php" == $base_path) $request_uri .= "index.php";
                $uri = str_replace($base_path, '', $request_uri);
                if (strpos($uri, '?') !== false) {
                    $uri = substr($uri, 0, strpos($uri, '?')) . '/';
                }
            } elseif ($_SERVER['argc'] > 1 && trim($_SERVER['argv'][1], '/') != '') {
                $uri = $_SERVER['argv'][1];
            }
        }
        $uri = rtrim($uri, "/"); # removes ending /
        if (empty($uri)) {
            $uri = '/';
        } else if ($uri[0] != '/') {
            $uri = '/' . $uri; # add a leading slash
        }
        return rawurldecode($uri);
    }
}
