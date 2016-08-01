<?php namespace Mirelap\Http\Transformers;

use Illuminate\Http\Request;

abstract class TransformerAbstract
{
    /** @var  Request */
    protected $request;

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public abstract function transform($item) : array;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function deepTransform($item)
    {
        $includes = $this->getIncludes();
        $data = $this->transform($item);

        foreach ($includes as $include) {
            $includeFunction = 'include' . ucfirst($include);
            $data[$include] = $this->$includeFunction($item);
        }

        return $data;
    }

    public function getAvailableIncludes(): array
    {
        return $this->availableIncludes;
    }

    public function getDefaultIncludes(): array
    {
        return $this->defaultIncludes;
    }

    protected function getIncludes() : array
    {
        $includes = $this->getRequestParameterArray('include');
        $excludes = $this->getRequestParameterArray('exclude');

        $included = array_merge($this->getDefaultIncludes(), array_intersect($this->getAvailableIncludes(), $includes));

        return array_diff($included, $excludes);
    }

    protected function getRequestParameterArray($name, $delimiter = ',') : array
    {
        return explode($delimiter, $this->request->get($name));
    }

    protected function item($item, TransformerAbstract $transformer)
    {
        $transformer->setRequest($this->request);
        return $transformer->deepTransform($item);
    }

    protected function collection($collection, TransformerAbstract $transformer)
    {
        $transformer->setRequest($this->request);

        $transformed = [];
        foreach ($collection as $item) {
            $transformed[] = $transformer->deepTransform($item);
        }

        return $transformed;
    }
}