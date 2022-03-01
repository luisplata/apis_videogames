<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scores extends Model
{
    //
    public function videogame()
    {
        return $this->belongsTo('App\Videogames','videogames_id');
    }
}
