<?php namespace Mirelap\Exceptions;

use Exception;
use RuntimeException;

class ModelConflictException extends RuntimeException
{
    protected $current;
    protected $update;

    /**
     * ModelConflictException constructor.
     *
     * @param mixed $current the original model
     * @param mixed $update the updated model
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($current, $update, string $message = '', int $code = 0, Exception $previous = null)
    {
        $this->current = $current;
        $this->update = $update;

        if (empty($message)) {
            $message = 'There is a conflict with the resource you are attempting to update';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function getUpdate()
    {
        return $this->update;
    }

}