<?php namespace Mirelap\Resources\Interfaces;

interface MultipleIdentifierInterface
{
    public function findByIdentifier($id, string $field = 'id');
}