<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GameAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $gameUrl = $request->query('game') ? urldecode($request->query('game')) : null;
        $username = session('chess_username');
        
        $gameData = null;
        if ($gameUrl && $username) {
            $recentGames = Cache::get("recent_games_{$username}", []);
            if (isset($recentGames[$gameUrl])) {
                $gameData = $recentGames[$gameUrl];
            }
        }
        
        // Fallback or empty state if no game is selected
        return view('pages.analysis', compact('gameData'));
    }
}
