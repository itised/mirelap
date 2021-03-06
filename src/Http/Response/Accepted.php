<?php namespace Mirelap\Http\Response;

class Accepted extends Response
{
    public function __construct($content = null, array $headers = [], int $options = 0)
    {
        parent::__construct($content, static::HTTP_ACCEPTED, $headers, $options);
    }
}