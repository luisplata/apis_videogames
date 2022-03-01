<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videogames extends Model
{
    //
    public function scores()
    {
        return $this->hasMany('App\Scores');
    }
}
