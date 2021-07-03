<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weekday extends Model
{
    protected $fillable = ['date','day','H_debut','H_fin'];
}
