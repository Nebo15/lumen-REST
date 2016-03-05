<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:11
 */

namespace Nebo15\REST;

use Laravel\Lumen\Application;

class Router
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function api($route, $controllerName, array $middleware = [])
    {
        # ToDo: validate controller instance

        $this->app->get("/$route", ["uses" => "$controllerName@readList" , 'middleware' => $middleware]);
        $this->app->post("/$route", ["uses" => "$controllerName@create", 'middleware' => $middleware]);
        $this->app->get("/$route/{id}", ["uses" => "$controllerName@read", 'middleware' => $middleware]);
        $this->app->put("/$route/{id}", ["uses" => "$controllerName@update", 'middleware' => $middleware]);
        $this->app->post("/$route/{id}/copy", ["uses" => "$controllerName@copy", 'middleware' => $middleware]);
        $this->app->delete("/$route/{id}", ["uses" => "$controllerName@delete", 'middleware' => $middleware]);
    }
}
