<?php namespace Mirelap\Resources;

use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = strtoupper($term);

        return $query->whereRaw('UPPER('.$this->table.'.'.$this->displayField.') LIKE ?', ['%'.$term.'%']);
    }
}