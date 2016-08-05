<?php namespace Mirelap\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as IlluminateFormRequest;
use Mirelap\Exceptions\ResourceException;

abstract class FormRequest extends IlluminateFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ResourceException('The request data could not be validated', $validator->getMessageBag());
    }
}