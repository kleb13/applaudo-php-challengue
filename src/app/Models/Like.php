<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function movie(){
        return $this->belongsTo(Movie::class);
    }
}
