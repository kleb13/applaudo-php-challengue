<?php


namespace App\Traits;


use App\Scopes\AvailabilityScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * This trait is aimed to create scope for filtering
 * Trait AvailabilityTrait
 * @package App\Traits
 */
trait AvailabilityTrait
{

    public static function bootAvailabilityTrait()
    {
        static::addGlobalScope(new AvailabilityScope());
    }

    public function scopeUnavailable(Builder $query)
    {
        return $query->withoutGlobalScope(AvailabilityScope::class)
            ->where("availability","=",0);
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->withoutGlobalScope(AvailabilityScope::class)
            ->where("availability","=",1);
    }

    public function scopeFilter(Builder $query,$filter)
    {
        if(!in_array($filter,['available','unavailable'])){
            return $query->withoutGlobalScope(AvailabilityScope::class);
        }
        return $this->{'scope'.ucfirst($filter)}($query);
    }
}
