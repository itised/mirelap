<?php namespace Mirelap\Http\Transformers;

use Illuminate\Http\Request;

abstract class TransformerAbstract
{
    /** @var  Request */
    protected $request;

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    /** @var string */
    private $depth;

    public function __construct(string $depth = null)
    {
        $this->depth = $depth;
    }

    public abstract function transform($item) : array;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function deepTransform($item)
    {
        if (is_null($item)) {
            return $item;
        }

        $includes = $this->getIncludes();
        $data = $this->transform($item);

        foreach ($includes as $include) {
            $depth = ($this->depth === null ? '' : $this->depth . '.') . $include;
            $includeFunction = 'include' . ucfirst($include);
            $data[$include] = $this->$includeFunction($item, $depth);
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

        if ($this->depth !== null) {
            $includes = $this->parseIncludes($includes);
            $excludes = $this->parseIncludes($excludes);
        }

        $included = array_merge($this->getDefaultIncludes(), array_intersect($this->getAvailableIncludes(), $includes));

        return array_diff($included, $excludes);
    }

    protected function parseIncludes(array $includes) : array
    {
        $depth = $this->depth .= '.';

        return collect($includes)->filter(function($include) use ($depth) {
            return starts_with($include, $depth);
        })->map(function($include) use ($depth) {
            return substr($include, strlen($depth));
        })->toArray();
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