<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = [
        'title',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    // 🔗 Relacionamentos
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // 📊 Total de votos
    public function totalVotes()
    {
        return $this->votes()->count();
    }

    // 🗳️ Votos em branco
    public function blankVotes()
    {
        return $this->votes()->where('blank', true)->count();
    }

    // 📊 Resultado geral
    public function results()
    {
        return $this->tickets()
            ->withCount('votes')
            ->get()
            ->map(function ($ticket) {
                return [
                    'name' => $ticket->name,
                    'number' => $ticket->number,
                    'votes' => $ticket->votes_count
                ];
            });
    }
}