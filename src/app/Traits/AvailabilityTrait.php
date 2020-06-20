<?php


namespace App\Traits;


use App\Scopes\AvailabilityScope;
use Illuminate\Database\Eloquent\Builder;

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
