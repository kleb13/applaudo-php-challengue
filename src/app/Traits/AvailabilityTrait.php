<?php


namespace App\Traits;


use App\Scopes\AvailabilityScope;

trait AvailabilityTrait
{

    public static function bootAvailabilityTrait()
    {
        static::addGlobalScope(new AvailabilityScope());
    }
}
