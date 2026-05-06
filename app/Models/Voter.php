<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
     protected $fillable = [
        'name',
        'registration'
    ];
    public function voter()
{
    return $this->belongsTo(Voter::class);
}
}
