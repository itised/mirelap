<?php namespace Mirelap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Mirelap\Http\Response\Response;
use Illuminate\Support\Arr;

class HttpException extends SymfonyHttpException
{
    protected $data = [];

    public function __construct($statusCode = 400, $message = null, \Exception $previous = null, array $headers = [], int $code = 0)
    {
        if (!empty($message)) {
            $this->data['message'] = $message;
        }

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
    
    public function render($request)
    {
        if (config('app.debug')) {
            $this->data['debug_trace'] = $this->getStackTrace();
        }

        return new Response($this->data, $this->getStatusCode(), $this->getHeaders());
    }

    protected function getStackTrace() : array
    {
        return [
            'message' => $this->getMessage(),
            'exception' => get_class($this),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => collect($this->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }
}