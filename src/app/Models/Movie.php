<?php

namespace App\Models;

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

    public function images()
    {
        return $this->hasMany(MovieImage::class);
    }

}
