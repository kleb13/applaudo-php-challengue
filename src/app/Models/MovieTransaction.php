<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieTransaction extends Model
{
    public $timestamps=false;

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
