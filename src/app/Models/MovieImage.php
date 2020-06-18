<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MovieImage
 * @package App\Models
 * @property $id
 * @property $path
 */
class MovieImage extends Model
{
    //

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
