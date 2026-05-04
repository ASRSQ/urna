<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Api\ElectionApiController;
use App\Http\Controllers\UrnaController;

// eleições
Route::resource('elections', ElectionController::class);

// chapas
Route::post('/election/{id}/tickets', [TicketController::class, 'store']);
Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);

// votação
Route::post('/vote', [VoteController::class, 'store']);
Route::get('/results/{id}', [VoteController::class, 'results']);
Route::delete('/filters/{id}', [VoteController::class, 'destroy_filtro'])->name('filters.destroy');

// API urna
Route::get('/api/election/{id}', [ElectionApiController::class, 'show']);
Route::get('/urna/{id}', [UrnaController::class, 'index'])->name('urna');

Route::post('/api/check-voter', function (\Illuminate\Http\Request $request) {

    $voter = \App\Models\Voter::where('registration', $request->registration)->first();

    if (!$voter) {
        return response()->json([
            'success' => false,
            'message' => 'Matrícula não encontrada'
        ]);
    }

    $used = \App\Models\Vote::where('voter_id', $voter->registration)
        ->where('election_id', $request->election_id)
        ->exists();

    return response()->json([
        'success' => true,
        'used' => $used,
        'name' => $voter->name,
        'id' => $voter->id,                 // ID interno (recomendado)
        'registration' => $voter->registration // 👈 matrícula
    ]);
});
