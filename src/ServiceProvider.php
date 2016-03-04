<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:10
 */

namespace REST;

use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register()
    {
        $this->app->singleton('REST.router', function ($app) {
            return new Router($app);
        });
    }
}
