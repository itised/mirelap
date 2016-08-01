<?php namespace Mirelap\Http\Transformers;

use Illuminate\Http\Request;

class TransformerFactory
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function make($transformerClass) : TransformerAbstract
    {
        /** @var TransformerAbstract $transformer */
        $transformer = app($transformerClass);

        $transformer->setRequest($this->request);

        return $transformer;
    }
}