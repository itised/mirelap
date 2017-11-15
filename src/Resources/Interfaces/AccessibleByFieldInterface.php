<?php namespace Mirelap\Resources\Interfaces;

interface AccessibleByFieldInterface
{
    public function findByField(string $field, $value);

    public function findByCaseInsensitiveField(string $field, $value);
}