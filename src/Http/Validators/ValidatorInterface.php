<?php namespace Mirelap\Http\Validators;

use Illuminate\Http\Request;

interface ValidatorInterface
{
    public function validate(Request $request) : bool;
}