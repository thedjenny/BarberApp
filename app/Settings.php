<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['day','H_debut','H_pause','H_retour','H_fin'];
}
