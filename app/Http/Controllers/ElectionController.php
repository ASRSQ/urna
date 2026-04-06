<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    // 📋 Listar eleições
    public function index()
    {
        $elections = Election::latest()->get();
        return view('elections.index', compact('elections'));
    }

    // 📄 Criar eleição (form)
    public function create()
    {
        return view('elections.create');
    }

    // 💾 Salvar eleição
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Election::create([
            'title' => $request->title,
            'active' => true
        ]);

        return redirect('/elections');
    }

    // 👁️ Ver eleição (chapas + resultado)
    public function show($id)
    {
        $election = Election::with('tickets')->findOrFail($id);
        return view('elections.show', compact('election'));
    }

    // ❌ Deletar
    public function destroy($id)
    {
        Election::findOrFail($id)->delete();
        return back();
    }
}