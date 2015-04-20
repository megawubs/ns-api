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
        $this->publishes([__DIR__ . '/config/NS.php' => config_path('NS.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->bind(
            'zip',
            function () use ($app) {
                return new NSApi($app['config']->get('zip.key'));
            }
        );
    }

    public function provides()
    {
        return ['Wubs\NS\NSApi'];
    }
}