<?php namespace Mirelap\Resources\Interfaces;

interface PreventConflictingUpdates
{
    public function hasConflict(string $timestamp): bool;
}