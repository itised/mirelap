<?php namespace Mirelap\Http\Response;

class Created extends Response
{
    public function __construct($location, $content = null, array $headers = [], int $options = 0)
    {
        $headers['Location'] = $location;

        parent::__construct($content, static::HTTP_CREATED, $headers, $options);
    }
}