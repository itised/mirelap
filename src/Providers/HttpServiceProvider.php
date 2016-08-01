<?php namespace Mirelap\Providers;

use Illuminate\Support\ServiceProvider;
use Mirelap\Http\Validators\Accept as AcceptValidator;
use Mirelap\Http\Parsers\Accept as AcceptParser;
use Mirelap\Http\Validators\Domain as DomainValidator;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHttpParsers();
        $this->registerHttpValidation();
    }

    protected function registerHttpParsers()
    {
        $this->app->singleton(AcceptParser::class, function ($app) {
            $config = $app['config']['mirelap'];

            return new AcceptParser(
                $config['standardsTree'],
                $config['subtype'],
                $config['version'],
                'json'
            );
        });
    }

    protected function registerHttpValidation()
    {
        $this->app->singleton(AcceptValidator::class, function ($app) {
            return new AcceptValidator($app[AcceptParser::class]);
        });

        $this->app->singleton(DomainValidator::class, function ($app) {
            return new DomainValidator($app['config']['mirelap']['domain']);
        });
    }
}