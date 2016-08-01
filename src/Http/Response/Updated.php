<?php namespace Mirelap\Http\Response;

class Updated extends Response
{
    public function __construct($content = null, array $headers = [], int $options = 0)
    {
        $status = $content === null ? static::HTTP_NO_CONTENT : static::HTTP_OK;

        parent::__construct($content, $status, $headers, $options);
    }
}