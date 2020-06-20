<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\{Builder,Model,Scope};

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
