<?php namespace Mirelap\Http\Validators;

use Illuminate\Http\Request;

class Domain implements ValidatorInterface
{
    /** @var string */
    private $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function validate(Request $request) : bool
    {
        return $request->getHost() === $this->domain;
    }
}