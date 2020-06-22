<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\{Builder,Model,Scope};

/**
 * Scope aimed to avoid duplication of this query
 * Class AvailabilityScope
 * @package App\Scopes
 */
class AvailabilityScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('availability','=',1);
    }
}
