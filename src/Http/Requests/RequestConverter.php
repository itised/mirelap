<?php namespace Mirelap\Http\Requests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mirelap\Resources\Interfaces\MultipleIdentifierInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class RequestConverter
{
    /** @var MultipleIdentifierInterface */
    private $model;

    /** @var string */
    protected $modelName;

    public function __construct(MultipleIdentifierInterface $model)
    {
        $this->model = $model;
    }

    public function convert(Request $request, $id = null)
    {
        $lookupField = $request->get($this->getModelName() . 'LookupField', 'id');

        try {
            return $this->model->findByIdentifier($id, $lookupField);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException(sprintf('No record with "%s" = "%s" could be found', $lookupField, $id));
        } catch (\InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    protected function getModelName() : string
    {
        if (!empty($this->modelName)) {
            return $this->modelName;
        }

        return strtolower(substr(strrchr(static::class, '\\'), 10, -9));
    }
}