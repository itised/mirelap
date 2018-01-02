<?php namespace Mirelap\Exception;

use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalServerException extends HttpException
{
    public function __construct(string $message = null, Exception $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct(500, $message ?: 'An unknown error has occurred', $previous, $headers, $code);
    }
}