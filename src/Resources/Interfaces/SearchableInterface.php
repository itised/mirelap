<?php namespace Mirelap\Resources\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface SearchableInterface
{
    public function scopeSearch(Builder $query, string $term): Builder;
}