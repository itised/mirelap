<?php namespace Mirelap\Resources;

use Illuminate\Database\Eloquent\Model;
use Mirelap\Exceptions\ModelConflictException;

abstract class EloquentModel extends Model
{
    public function hasConflict(string $timestamp)
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