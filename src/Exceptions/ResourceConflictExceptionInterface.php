<?php
namespace Mirelap\Exceptions;

interface ResourceConflictExceptionInterface
{
    public function getSubmittedResource() : array;

    public function getCurrentResource() : array;

    public function getChangedFields() : array;
}