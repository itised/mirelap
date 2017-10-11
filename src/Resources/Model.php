<?php namespace Mirelap\Resources;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Mirelap\Resources\Interfaces\AccessibleByFieldInterface;
use Mirelap\Resources\Interfaces\PreventConflictingUpdates;
use Mirelap\Resources\Interfaces\SearchableInterface;

/**
 * Class Model
 *
 * @method Model findOrFail($id)
 * @method Model search(string $term)
 */
abstract class Model extends EloquentModel implements AccessibleByFieldInterface, SearchableInterface, PreventConflictingUpdates
{
    use SearchableTrait;
    use AccessibleByFieldTrait;
    use PreventConflictingUpdatesTrait;
}