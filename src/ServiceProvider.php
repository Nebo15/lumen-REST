<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:10
 */

namespace Nebo15\REST;

use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register()
    {
        $this->app->singleton('Nebo15\REST\Router', function ($app) {
            return new Router($app);
        });

        $this->commands([
            'Nebo15\REST\Console\Command\CreateCRUD',
        ]);
    }
}
