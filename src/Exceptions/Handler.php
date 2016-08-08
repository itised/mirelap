<?php namespace Mirelap\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler implements ExceptionHandler
{
    /** @var ExceptionHandler */
    protected $parentHandler;

    /** @var */
    protected $debug;

    public function __construct(ExceptionHandler $parentHandler, $debug)
    {
        $this->parentHandler = $parentHandler;
        $this->debug = $debug;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        $this->parentHandler->report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        return $this->handle($e);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception $e
     *
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        $this->parentHandler->renderForConsole($output, $e);
    }

    protected function handle(Exception $e) : Response
    {
        $statusCode = $this->getStatusCode($e);

        if (! $message = $e->getMessage()) {
            $message = sprintf('%d %s', $statusCode, Response::$statusTexts[$statusCode]);
        }

        $response = [
            'status_code' => $statusCode,
            'message' => $message
        ];

        if ($e->getCode()) {
            $response['code'] = $e->getCode();
        }

        if ($e instanceof MessageBagExceptionInterface && $e->hasErrors()) {
            $response['errors'] = $e->getErrors();
        }

        if ($e instanceof ResourceConflictExceptionInterface) {
            $response['submitted'] = $e->getSubmittedResource();
            $response['current'] = $e->getCurrentResource();
        }

        if ($code = $e->getCode()) {
            $response['code'] = $code;
        }

        if ($this->debug) {
            $response['debug'] = [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'class' => get_class($e),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        }

        return new Response($response, $statusCode, $this->getHeaders($e));
    }

    protected function getStatusCode(Exception $exception)
    {
        return $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
    }

    protected function getHeaders(Exception $exception)
    {
        return $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
    }
}