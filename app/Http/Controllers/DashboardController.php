<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChessComService;

class DashboardController extends Controller
{
    protected $chessService;

    public function __construct(ChessComService $chessService)
    {
        $this->chessService = $chessService;
    }

    public function index()
    {
        $username = session('chess_username');
        
        $profile = $this->chessService->getProfile($username);
        $stats = $this->chessService->getStats($username);
        $recentGames = $this->chessService->getRecentGames($username, 10);
        
        // Extract main Elo (rapid)
        $currentElo = $stats['chess_rapid']['last']['rating'] ?? ($stats['chess_blitz']['last']['rating'] ?? 0);
        
        return view('pages.dashboard', compact('profile', 'stats', 'recentGames', 'currentElo', 'username'));
    }
}
