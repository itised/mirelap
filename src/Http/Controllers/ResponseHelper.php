<?php namespace Mirelap\Http\Controllers;

use ErrorException;
use Mirelap\Http\Response\ResponseFactory;

trait ResponseHelper
{
    protected function response() : ResponseFactory
    {
        return app(ResponseFactory::class);
    }

    public function __get($key)
    {
        $callable = ['response'];

        if (in_array($key, $callable) && method_exists($this, $key)) {
            return $this->$key();
        }

        throw new ErrorException('Undefined property '.get_class($this).'::'.$key);
    }
}