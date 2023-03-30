<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tresorerie extends Model
{

    protected $fillable = ['idTresorerie','idCoiffeur','type','montant','motif'];

}
