<?php namespace Mirelap\Http\Middleware;

use Closure;
use Mirelap\Http\Validators\Domain as Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidateDomain
{
    /** @var Validator */
    protected $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function handle($request, Closure $next)
    {
        if ($this->validator->validate($request)) {
            return $next($request);
        }

        throw new BadRequestHttpException('Invalid domain');
    }
}