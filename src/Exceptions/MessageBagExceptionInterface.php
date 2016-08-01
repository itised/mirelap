<?php namespace Mirelap\Exceptions;

use Illuminate\Contracts\Support\MessageBag;

interface MessageBagExceptionInterface
{
    /**
     * Get the error message bag for the exception
     *
     * @return MessageBag
     */
    public function getErrors() : MessageBag;

    /**
     * Determine whether the exception's error message bag contains any errors
     *
     * @return bool
     */
    public function hasErrors() : bool;
}