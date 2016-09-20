<?php namespace Mirelap\Http\Controllers;

use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mirelap\Exceptions\ModelConflictException;
use Mirelap\Exceptions\ResourceConflictException;
use Mirelap\Exceptions\UpdateResourceFailedException;
use Mirelap\Http\Response\ResponseFactory;
use Mirelap\Http\Response\Updated;
use Mirelap\Http\Transformers\TransformerAbstract;
use Mirelap\Http\Transformers\TransformerFactory;
use Mirelap\Resources\EloquentModel;
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

    protected function transform(string $transformerClass, EloquentModel $model)
    {
        $transformer = $this->transformer($transformerClass);
        return $transformer->transform($model);
    }

    protected function findOrFail(EloquentModel $model, $id) : EloquentModel
    {
        try {
            return $model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException("No record with 'id' = '${id}' exists'");
        }
    }

    protected function updateResource(EloquentModel $updated, string $transformerClass, array $headers = []) : Updated
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