<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
    'leader_name',
    'leader_photo',
    'vice_name',
    'vice_photo',
    'number',
    'election_id'
];

    // 🔗 Relacionamentos
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // 📊 Total de votos da chapa
    public function totalVotes()
    {
        return $this->votes()->count();
    }

    // 📊 Percentual de votos
    public function percentage()
    {
        $total = $this->election->totalVotes();

        if ($total === 0) return 0;

        return round(($this->totalVotes() / $total) * 100, 2);
    }
}