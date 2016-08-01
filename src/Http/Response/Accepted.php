<?php namespace Mirelap\Http\Response;

class Accepted extends Response
{
    public function __construct(string $location = null, $content = null, array $headers = [], int $options = 0)
    {
        $status = $content === null ? static::HTTP_NO_CONTENT : static::HTTP_OK;

        if ($location !== null) {
            $headers['Location'] = $location;
        }

        parent::__construct($content, $status, $headers, $options);
    }
}