<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coiffure extends Model
{
    protected $fillable = ['nom','temps','prix','photo','points'];

}
