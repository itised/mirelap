<?php namespace Mirelap\Resources;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Mirelap\Resources\Interfaces\AccessibleByField;
use Mirelap\Resources\Interfaces\PreventConflictingUpdates;
use Mirelap\Resources\Interfaces\Searchable;

/**
 * Class Model
 *
 * @method Model findOrFail($id)
 * @method Model search(string $term)
 */
abstract class Model extends EloquentModel implements AccessibleByField, Searchable, PreventConflictingUpdates
{
    use SearchableTrait;
    use AccessibleByFieldTrait;
    use PreventConflictingUpdatesTrait;
}