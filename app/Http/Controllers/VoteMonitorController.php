<?php


namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;

class VoteMonitorController extends Controller
{
    public function index($electionId)
    {
        $election = Election::findOrFail($electionId);

        $votes = Vote::with(['voter', 'ticket'])
            ->where('election_id', $election->id)
            ->latest()
            ->take(20)
            ->get();

        $lastVote = $votes->first();

        return view('votes.monitor', compact(
            'election',
            'votes',
            'lastVote'
        ));
    }
}