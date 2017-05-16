<?php namespace Mirelap\Resources\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface Searchable
{
    public function scopeSearch(Builder $query, string $term): Builder;
}