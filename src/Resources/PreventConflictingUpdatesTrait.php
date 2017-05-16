<?php namespace Mirelap\Resources;

use Mirelap\Exceptions\ModelConflictException;

trait PreventConflictingUpdatesTrait
{
    public function hasConflict(string $timestamp): bool
    {
        return $this->{static::UPDATED_AT}->toDateTimeString() !== $timestamp;
    }

    public function save(array $options = [])
    {
        if ($this->exists && $this->isDirty(static::UPDATED_AT)) {
            throw new ModelConflictException($this->original(), $this);
        }

        return parent::save($options);
    }

    public function original()
    {
        return self::find($this->id);
    }
}