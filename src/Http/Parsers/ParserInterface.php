<?php namespace Mirelap\Http\Parsers;

use Illuminate\Http\Request;

interface ParserInterface
{
    public function parse(Request $request) : array;
}