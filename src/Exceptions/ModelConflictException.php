<?php namespace Mirelap\Exceptions;

use Exception;
use Mirelap\Resources\EloquentModel;
use RuntimeException;

class ModelConflictException extends RuntimeException
{
    protected $current;
    protected $update;

    /**
     * ModelConflictException constructor.
     *
     * @param $current
     * @param $update
     */
    public function __construct(EloquentModel $current, EloquentModel $update, string $message = '', int $code = 0, Exception $previous = null)
    {
        $this->current = $current;
        $this->update = $update;

        if (empty($message)) {
            $message = 'There is a conflict with the resource you are attempting to update';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getCurrent() : EloquentModel
    {
        return $this->current;
    }

    public function getUpdate() : EloquentModel
    {
        return $this->update;
    }

}