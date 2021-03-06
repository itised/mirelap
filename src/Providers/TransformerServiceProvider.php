<?php namespace Mirelap\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Mirelap\Http\Transformers\TransformerFactory;

class TransformerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTransformers();
    }

    protected function registerTransformers()
    {
        $this->app->singleton(TransformerFactory::class, function ($app) {
            return new TransformerFactory($app[Request::class]);
        });
    }
}