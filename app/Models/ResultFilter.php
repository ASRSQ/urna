<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    class ResultFilter extends Model
{
    protected $fillable = [
        'election_id',
        'label',
        'start',
        'end'
    ];
}