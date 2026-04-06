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

// API urna
Route::get('/api/election/{id}', [ElectionApiController::class, 'show']);
Route::get('/urna/{id}', [UrnaController::class, 'index'])->name('urna');