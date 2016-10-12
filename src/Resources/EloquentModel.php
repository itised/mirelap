<?php namespace Mirelap\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mirelap\Exceptions\ModelConflictException;

/**
 * Class EloquentModel
 *
 * @method EloquentModel findOrFail($id)
 * @method EloquentModel search($term)
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

    protected function floatVal($attribute)
    {
        return $this->attributes[$attribute] === null ? null : floatval($this->attributes[$attribute]);
    }

    protected function intVal($attribute)
    {
        return $this->attributes[$attribute] === null ? null : intval($this->attributes[$attribute]);
    }

    protected function boolVal($attribute)
    {
        return $this->attributes[$attribute] === null ? null : $this->attributes[$attribute] !== '0';
    }
}