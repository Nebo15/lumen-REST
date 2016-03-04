<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:11
 */

namespace REST;

use Laravel\Lumen\Application;

class Router
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function api($route, $controllerName)
    {
        # ToDo: validate controller instance

        $this->app->get("/$route", ["uses" => "$controllerName@readList"]);
        $this->app->post("/$route", ["uses" => "$controllerName@create"]);
        $this->app->get("/$route/{id}", ["uses" => "$controllerName@read"]);
        $this->app->put("/$route/{id}", ["uses" => "$controllerName@update"]);
        $this->app->post("/$route/{id}/clone", ["uses" => "$controllerName@copy"]);
        $this->app->delete("/$route/{id}", ["uses" => "$controllerName@delete"]);
    }
}
