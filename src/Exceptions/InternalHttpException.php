<?php namespace Mirelap\Exception;

use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalHttpException extends HttpException
{
    /** @var Response */
    protected $response;

    /**
     * InternalHttpException constructor.
     *
     * @param Response $response
     * @param null|string $message
     * @param Exception $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct(Response $response, $message = null, Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->response = $response;

        parent::__construct($response->getStatusCode(), $message, $previous, $headers, $code);
    }

    public function getResponse() : Response
    {
        return $this->response;
    }
}