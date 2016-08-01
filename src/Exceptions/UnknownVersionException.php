<?php namespace Mirelap\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnknownVersionException extends HttpException
{

    /**
     * UnknownVersionException constructor.
     *
     * @param null|string $message
     * @param Exception $previous
     * @param int $code
     */
    public function __construct($message = null, Exception $previous = null, $code = 0)
    {
        parent::__construct(400, $message ?: 'API version is invalid', $previous, [], $code);
    }
}