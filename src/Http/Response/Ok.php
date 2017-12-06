<?php namespace Mirelap\Http\Response;

class Ok extends Response
{
    public function __construct($content = null, array $headers = [], int $options = 0)
    {
        parent::__construct($content, static::HTTP_OK, $headers, $options);
    }
}