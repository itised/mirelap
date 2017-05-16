<?php namespace Mirelap\Resources;

trait AccessibleByFieldTrait
{
    public function findByField(string $field, $value)
    {
        return $this->where($field, $value)->firstOrFail();
    }
}