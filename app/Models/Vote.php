<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'election_id',
        'ticket_id',
        'blank',
        'voter_id'
    ];

    protected $casts = [
        'blank' => 'boolean'
    ];

    // 🔗 Relacionamentos
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // 🧠 Scope: votos válidos
    public function scopeValid($query)
    {
        return $query->where('blank', false)
                     ->whereNotNull('ticket_id');
    }

    // 🧠 Scope: votos em branco
    public function scopeBlank($query)
    {
        return $query->where('blank', true);
    }

    // 🧠 Scope: votos nulos
    public function scopeNullVotes($query)
    {
        return $query->whereNull('ticket_id')
                     ->where('blank', false);
    }
}