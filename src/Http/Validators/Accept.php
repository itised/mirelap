<?php namespace Mirelap\Http\Validators;

use Illuminate\Http\Request;
use Mirelap\Http\Parsers\Accept as Parser;

class Accept implements ValidatorInterface
{
    /** @var Parser */
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function validate(Request $request) : bool
    {
        if ($request->getMethod() === 'OPTIONS') {
            return true;
        }

        //BadHttpRequestException will be thrown if there is an error.
        $this->parser->parse($request);

        return true;
    }
}