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

    public function api(
        $route,
        $controllerName,
        array $middleware = [],
        $api_prefix = 'api/v1/admin',
        $namespace = 'App\Http\Controllers'
    ) {
        # ToDo: validate controller instance

        $app = $this->app;
        $app->group(
            [
                'prefix' => $api_prefix,
                'namespace' => $namespace,
                'middleware' => $middleware
            ],
            function () use ($app, $route, $controllerName) {
                $app->get("/$route", ["uses" => "$controllerName@readList"]);
                $app->post("/$route", ["uses" => "$controllerName@create"]);
                $app->get("/$route/{id}", ["uses" => "$controllerName@read"]);
                $app->put("/$route/{id}", ["uses" => "$controllerName@update"]);
                $app->post("/$route/{id}/copy", ["uses" => "$controllerName@copy"]);
                $app->delete("/$route/{id}", ["uses" => "$controllerName@delete"]);
            }
        );
    }
}
