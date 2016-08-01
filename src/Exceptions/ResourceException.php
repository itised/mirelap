<?php namespace Mirelap\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Support\MessageBag as MessageBagInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceException extends HttpException implements MessageBagExceptionInterface
{
    /** @var MessageBag */
    protected $errors;

    /**
     * Create a new resource exception instance
     *
     * @param null|string $message
     * @param null|array|MessageBag $errors
     * @param Exception $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct($message = null, $errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        if (is_null($errors)) {
            $this->errors = new MessageBag();
        } else {
            $this->errors = is_array($errors) ? new MessageBag($errors) : $errors;
        }

        parent::__construct(422, $message, $previous, $headers, $code);
    }

    /**
     * Get the error message bag for the exception
     *
     * @return MessageBagInterface
     */
    public function getErrors() : MessageBagInterface
    {
        return $this->errors;
    }

    /**
     * Determine whether the exception's error message bag contains any errors
     *
     * @return bool
     */
    public function hasErrors() : bool
    {
        return !$this->errors->isEmpty();
    }
}