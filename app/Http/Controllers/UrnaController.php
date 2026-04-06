<?php

namespace App\Http\Controllers;

use App\Models\Election;

class UrnaController extends Controller
{
    public function index($id)
    {
        // 🔥 carregar eleição + tickets (chapas)
        $election = Election::with('tickets')->findOrFail($id);

        return view('urna.index', compact('election'));
    }
}