<?php namespace Mirelap\Exceptions;

use Exception;

class ResourceConflictException extends HttpException implements ResourceConflictExceptionInterface
{
    /** @var array */
    private $submittedResource;

    /** @var array */
    private $currentResource;

    /**
     * Create a new resource exception instance
     *
     * @param array $submittedResource
     * @param array $currentResource
     * @param null|string $message
     * @param Exception $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct(array $submittedResource, array $currentResource, string $message = null, Exception $previous = null, $headers = [], $code = 0)
    {
        $this->submittedResource = $submittedResource;
        $this->currentResource = $currentResource;

        parent::__construct(409, $message, $previous, $headers, $code);
    }

    public function getSubmittedResource() : array
    {
        return $this->submittedResource;
    }

    public function getCurrentResource() : array
    {
        return $this->currentResource;
    }

    public function getChangedFields() : array
    {
        $changedFields = [];
        foreach ($this->submittedResource as $field => $value) {
            if ($this->currentResource[$field] !== $value) {
                $changedFields[] = $field;
            }
        }

        return $changedFields;
    }

    public function render($request)
    {
        $response = [
            'message' => $this->getMessage(),
            'submitted' => $this->getSubmittedResource(),
            'current' => $this->getCurrentResource(),
            'changed' => $this->getChangedFields(),
        ];

        return $this->response($response);
    }
}