<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    public function genres()
    {
    return $this->belongsToMany(Genre::class);
    }

    public function ratings()
{
    return $this->hasMany(Rating::class);
}


    use HasFactory;
}
