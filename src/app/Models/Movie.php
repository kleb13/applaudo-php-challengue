<?php

namespace App\Models;

use Doctrine\DBAL\Query\QueryBuilder;
use http\QueryString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Movie
 * @package App\Models
 * @property integer id
 * @property integer stock
 * @property string title
 * @property string description
 * @property boolean availability
 * @property float sale_price
 * @property float rental_price
 */
class Movie extends Model
{

    protected $guarded = [];

    protected $with = ['images','likeByCurrentUser'];

    protected $withCount = ['likes'];

    protected $appends = ["liked"];

    public function images()
    {
        return $this->hasMany(MovieImage::class);
    }

    public function logs(){
        return $this->hasMany(MovieLog::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hasBeenLikedByUser($userId)
    {
        return $this->likesByUser($userId)->exists();
    }

    public function likesByUser(int $userId){
        return $this->likes()->where('user_id', $userId);
    }

    public function likeByCurrentUser()
    {
        /**
         * @todo fix this
         */
        return $this->hasOne(Like::class)->where('user_id', auth()->user()->id??null);
    }

    public function getLikedAttribute()
    {
        return !empty($this->likeByCurrentUser);
    }

    public function scopeSearchByTitle(Builder $query,string $term)
    {
        if (empty($term)) {
            return $query;
        }

        $query->where('title', 'like', "%$term%");
    }

    public function scopeOrderByPopularity(Builder $query,string $direction ="desc")
    {
        $query->orderBy("likes_count",$direction);
    }

    public function scopeOrderByTitle(Builder $query, string $direction="asc")
    {
        $query->orderBy("title",$direction);
    }

    public function scopeOrder(Builder $query, string $sortBy)
    {
        $sort = str_replace('-','',$sortBy);
        $direction = str_starts_with($sortBy,'-')?"desc":"asc";
        switch ($sort){
            case 'popularity':
                return $this->scopeOrderByPopularity($query,$direction);
            case 'title':
                return $this->scopeOrderByTitle($query,$direction);
            default:
                return $this->scopeOrderByTitle($query);
        }

    }
}
