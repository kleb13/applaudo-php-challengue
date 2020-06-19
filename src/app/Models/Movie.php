<?php

namespace App\Models;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

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

    protected $with = ['images'];

    protected $withCount = ['likes'];
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
}
