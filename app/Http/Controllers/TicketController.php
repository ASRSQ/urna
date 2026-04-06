<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // 💾 Criar chapa
    public function store(Request $request, $election_id)
    {
        $request->validate([
            'leader_name' => 'required',
            'number' => 'required|digits:2',
            'leader_photo' => 'nullable|image',
            'vice_photo' => 'nullable|image'
        ]);

        // 🚫 evitar número repetido na mesma eleição
        if (Ticket::where('election_id', $election_id)
            ->where('number', $request->number)
            ->exists()) {
            return back()->with('error', 'Número já usado!');
        }

        // 📸 Upload das imagens
        $leaderPhoto = null;
        $vicePhoto = null;

        if ($request->hasFile('leader_photo')) {
            $leaderPhoto = $request->file('leader_photo')->store('candidatos', 'public');
        }

        if ($request->hasFile('vice_photo')) {
            $vicePhoto = $request->file('vice_photo')->store('candidatos', 'public');
        }

        // 💾 Criar chapa
        Ticket::create([
            'leader_name' => $request->leader_name,
            'leader_photo' => $leaderPhoto,
            'vice_name' => $request->vice_name,
            'vice_photo' => $vicePhoto,
            'number' => $request->number,
            'election_id' => $election_id
        ]);

        return back()->with('success', 'Chapa cadastrada!');
    }

    // ❌ Deletar chapa
    public function destroy($id)
    {
        Ticket::findOrFail($id)->delete();

        return back()->with('success', 'Chapa removida!');
    }
}