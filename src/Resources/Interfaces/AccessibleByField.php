<?php namespace Mirelap\Resources\Interfaces;

interface AccessibleByField
{
    public function findByField(string $field, $value);
}