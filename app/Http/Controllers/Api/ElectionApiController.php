<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Election;

class ElectionApiController extends Controller
{
    public function show($id)
    {
        $election = Election::with('tickets')->findOrFail($id);

        $etapa = [
            'titulo' => 'CHAPA',
            'numeros' => 2,
            'candidatos' => []
        ];

        foreach ($election->tickets as $ticket) {
            $etapa['candidatos'][$ticket->number] = [
                'id' => $ticket->id, // 🔥 ESSENCIAL
                'nome' => $ticket->leader_name,
                'foto' => $ticket->leader_photo,
                'vice' => $ticket->vice_name ? [
                    'nome' => $ticket->vice_name,
                    'foto' => $ticket->vice_photo
                ] : null
            ];
        }

        return response()->json([$etapa]);
    }
}