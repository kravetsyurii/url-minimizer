<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    public $timestamps = false;

    public function stats()
    {
        return $this->hasMany('App\Statistic');
    }
}
