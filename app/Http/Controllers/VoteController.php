<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\ResultFilter;
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
// 📊 Resultado da eleição

public function results(Request $request, $id)
{
    // 🟢 RESULTADO GERAL (SEM FILTRO)
    $baseQuery = Vote::where('election_id', $id);

    $data = [
        'validos' => (clone $baseQuery)->valid()->count(),
        'brancos' => (clone $baseQuery)->blank()->count(),
        'nulos' => (clone $baseQuery)->nullVotes()->count(),

        'chapas' => (clone $baseQuery)
            ->valid()
            ->selectRaw('ticket_id, count(*) as total')
            ->groupBy('ticket_id')
            ->with('ticket')
            ->orderByDesc('total') // 🔥 ranking geral
            ->get()
    ];

    // 🔵 SALVAR FILTRO (SEM DUPLICAR)
    if ($request->start && $request->end && $request->label) {

        ResultFilter::firstOrCreate([
            'election_id' => $id,
            'label' => $request->label,
            'start' => $request->start,
            'end' => $request->end
        ]);
    }

    // 🔥 SUB-RESULTADOS
    $filters = ResultFilter::where('election_id', $id)
        ->orderBy('start')
        ->get();

    $subResultados = [];

    foreach ($filters as $filtro) {

        $q = Vote::where('election_id', $id)
            ->whereBetween('created_at', [$filtro->start, $filtro->end]);

        // 🏆 RANKING POR CHAPA NO FILTRO
        $chapas = (clone $q)
            ->valid()
            ->selectRaw('ticket_id, count(*) as total')
            ->groupBy('ticket_id')
            ->with('ticket')
            ->orderByDesc('total')
            ->get();

        $subResultados[] = [
            'id' => $filtro->id, // 🔥 importante pro delete
            'label' => $filtro->label,
            'start' => $filtro->start,
            'end' => $filtro->end,

            'validos' => (clone $q)->valid()->count(),
            'brancos' => (clone $q)->blank()->count(),
            'nulos' => (clone $q)->nullVotes()->count(),

            'chapas' => $chapas // 🔥 ranking incluído
        ];
    }

    return view('votes.results', compact('data', 'subResultados'));
}
public function destroy_filtro($id)
    {
        ResultFilter::findOrFail($id)->delete();

        return back()->with('success', 'Filtro removido!');
    }
}