<?php

namespace Max13\Barcode;

use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/barcode.php', 'barcode',
        );

        $this->app->singleton('barcode', function ($app) {
            return new Manager($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/barcode.php' => config_path('barcode.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../../public' => public_path('vendor/barcode'),
        ], 'public');
    }
}
