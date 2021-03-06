<?php namespace Mirelap\Http\Controllers;

use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mirelap\Exceptions\ModelConflictException;
use Mirelap\Exceptions\ResourceConflictException;
use Mirelap\Exceptions\UpdateResourceFailedException;
use Mirelap\Http\Response\ResponseFactory;
use Mirelap\Http\Response\Updated;
use Mirelap\Http\Transformers\TransformerAbstract;
use Mirelap\Http\Transformers\TransformerFactory;
use Mirelap\Resources\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ResponseHelper
{
    protected function response() : ResponseFactory
    {
        return app(ResponseFactory::class);
    }

    protected function transformer(string $transformerClass) : TransformerAbstract
    {
        $factory = app(TransformerFactory::class);
        return $factory->make($transformerClass);
    }

    protected function transform(string $transformerClass, $model)
    {
        $transformer = $this->transformer($transformerClass);
        return $transformer->transform($model);
    }

    protected function findOrFail($model, $id)
    {
        try {
            return $model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException("No record with 'id' = '${id}' exists'");
        }
    }

    protected function addQueryTerm(Request $request, Builder $query)
    {
        if ($request->has('query')) {
            return $query->search($request->get('query'));
        }

        return $query;
    }

    protected function getCollection(Request $request, Builder $query)
    {
        if ($request->has('pageSize')) {
            return $query->paginate($request->get('pageSize'), ['*'], 'page', $request->get('pageNumber', 1));
        } else {
            return $query->get();
        }
    }

    protected function getFilteredCollection(Request $request, Builder $query)
    {
        return $this->getCollection($request, $this->addQueryTerm($request, $query));
    }

    protected function updateResource($updated, string $transformerClass, array $headers = []) : Updated
    {
        try {
            if (!$updated->save()) {
                // TODO: maybe throw a different exception?
                throw new UpdateResourceFailedException('The resource could not be saved');
            }
        } catch (ModelConflictException $exception) {
            throw new ResourceConflictException(
                $this->transform($transformerClass, $exception->getUpdate()),
                $this->transform($transformerClass, $exception->getCurrent()),
                $exception->getMessage()
            );
        }

        return $this->response()->updated($updated, $transformerClass, $headers);
    }

    public function __get($key)
    {
        $callable = ['response'];

        if (in_array($key, $callable) && method_exists($this, $key)) {
            return $this->$key();
        }

        throw new ErrorException('Undefined property '.get_class($this).'::'.$key);
    }
}