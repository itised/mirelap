<?php namespace Mirelap\Resources;

trait AccessibleByFieldTrait
{
    public function findByField(string $field, $value)
    {
        return $this->where($field, $value)->firstOrFail();
    }

    public function findByCaseInsensitiveField(string $field, $value)
    {
        return $this->where(sprintf('UPPER(%s)', $field), strtoupper($value))->firstOrFail();
    }
}