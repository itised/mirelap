<?php namespace Mirelap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Mirelap\Http\Response\Response;
use Illuminate\Support\Arr;

class HttpException extends SymfonyHttpException
{
    public function render($request)
    {
        return $this->response();
    }

    protected function response(array $responseData = []) : Response
    {
        return $this->composeResponse($this->composeResponseData($responseData));
    }

    protected function composeResponseData(array $data = []) : array
    {
        if (empty($data['message'])) {
            $data['message'] = $this->getMessage() ?: Response::$statusTexts[$this->getStatusCode()];
        }

        if (empty($data['code']) && $this->getCode()) {
            $data['code'] = $this->getCode();
        }

        if (config('app.debug')) {
            $data['debug_trace'] = [
                'message' => $this->getMessage(),
                'exception' => get_class($this),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => collect($this->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ];
        }

        return $data;
    }

    protected function composeResponse(array $responseData) : Response
    {
        return new Response($responseData, $this->getStatusCode(), $this->getHeaders());
    }
}