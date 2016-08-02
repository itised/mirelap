<?php namespace Mirelap\Providers;

use Illuminate\Support\ServiceProvider;

class MirelapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '../../config/mirelap.php' => config_path('mirelap.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}