<?php namespace Mirelap\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mirelap\Exceptions\ModelConflictException;

/**
 * Class EloquentModel
 * 
 * @method EloquentModel findOrFail($id)
 */
abstract class EloquentModel extends Model
{

    abstract public function scopeSearch(Builder $query, $term);

    public function findByField($field, $value)
    {
        return $this->where($field, $value)->firstOrFail();
    }

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