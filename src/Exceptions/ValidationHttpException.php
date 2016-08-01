<?php namespace Mirelap\Exceptions;

use Exception;
use Illuminate\Contracts\Support\MessageBag;

class ValidationHttpException extends ResourceException
{

    /**
     * ValidationHttpException constructor.
     *
     * @param null|MessageBag|array $errors
     * @param Exception $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct($errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        parent::__construct(null, $errors, $previous, $headers, $code);
    }
}