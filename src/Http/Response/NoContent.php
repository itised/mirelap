<?php namespace Mirelap\Http\Response;

class NoContent extends Response
{
    public function __construct(array $headers = [])
    {
        parent::__construct(null, static::HTTP_NO_CONTENT, $headers);
    }
}