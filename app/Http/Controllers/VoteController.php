<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
   public function store(Request $request)
{
    Log::info('📥 Requisição recebida', $request->all());

    $request->validate([
        'election_id' => 'required',
        'voter_id' => ['required', 'regex:/^[0-9]{7}$/'],
        'ticket_id' => 'nullable',
        'blank' => 'required|boolean'
    ]);

    Log::info('✅ Dados validados', $request->all());

    // 🚫 verificar duplicado
    $jaVotou = \App\Models\Vote::where('election_id', $request->election_id)
        ->where('voter_id', $request->voter_id)
        ->exists();

    if ($jaVotou) {
        Log::warning('🚫 Tentativa de voto duplicado', [
            'voter_id' => $request->voter_id
        ]);

        return response()->json([
            'error' => 'Este código já votou!'
        ], 403);
    }

    $vote = \App\Models\Vote::create([
        'election_id' => $request->election_id,
        'ticket_id' => $request->ticket_id,
        'blank' => $request->blank,
        'voter_id' => $request->voter_id
    ]);

    Log::info('🗳️ Voto salvo', $vote->toArray());

    return response()->json([
        'success' => true
    ]);
}

    // 📊 Resultado da eleição
    public function results($election_id)
    {
        $tickets = \App\Models\Ticket::where('election_id', $election_id)
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get();

        $blankVotes = Vote::where('election_id', $election_id)
            ->where('blank', true)
            ->count();

        $nullVotes = Vote::where('election_id', $election_id)
            ->whereNull('ticket_id')
            ->where('blank', false)
            ->count();

        return view('results.index', compact('tickets', 'blankVotes', 'nullVotes'));
    }
}