<?php
/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 20-04-15
 * Time: 10:14
 */

namespace Wubs\NS;

use Illuminate\Support\ServiceProvider;

class NSServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/config/ns.php' => config_path('ns.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->singleton(
            '\Wubs\NS\NSApi',
            function ($app) {
                $email = $app['config']->get('ns.email');
                $key = $app['config']->get('ns.key');
                return new NSApi($email, $key);
            }
        );

        $app->bind(
            'ns',
            function () use ($app) {
                return $app->make('\Wubs\NS\NSApi');
            }
        );
    }

    public function provides()
    {
        return ['Wubs\NS\NSApi'];
    }
}