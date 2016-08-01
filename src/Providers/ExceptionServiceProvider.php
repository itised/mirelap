<?php namespace Mirelap\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Mirelap\Exceptions\Handler;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerExceptionHandler();
    }

    protected function registerExceptionHandler()
    {
        $parentHandler = $this->app[ExceptionHandler::class];

        $this->app->bind(ExceptionHandler::class, function ($app) use ($parentHandler) {
            return new Handler($parentHandler, $app['config']['app']['debug']);
        });
    }
}