<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChessComService;

class AnalyticsController extends Controller
{
    protected $chessService;

    public function __construct(ChessComService $chessService)
    {
        $this->chessService = $chessService;
    }

    public function index()
    {
        $username   = session('chess_username');
        $stats      = $this->chessService->getStats($username);
        $recentGames = \Illuminate\Support\Facades\Cache::get("recent_games_{$username}", []);

        // Count outcomes per color
        $white = ['wins' => 0, 'losses' => 0, 'draws' => 0];
        $black = ['wins' => 0, 'losses' => 0, 'draws' => 0];
        $openings = [];

        $drawResults = ['agreed','repetition','stalemate','50move','insufficient','timevsinsufficient'];

        foreach ($recentGames as $game) {
            $isWhite  = strtolower($game['white']['username'] ?? '') === strtolower($username);
            $myResult = $isWhite ? ($game['white']['result'] ?? '') : ($game['black']['result'] ?? '');
            $key      = $isWhite ? 'white' : 'black';

            if ($myResult === 'win')                       $$key['wins']++;
            elseif (in_array($myResult, $drawResults))    $$key['draws']++;
            else                                          $$key['losses']++;

            // Parse opening from PGN
            if (!empty($game['pgn'])) {
                preg_match('/\[ECOUrl "[^"]*\/([^"\/]+)"\]/', $game['pgn'], $m);
                $opening = isset($m[1]) ? str_replace('-', ' ', $m[1]) : 'Unknown';
                $opening = ucwords($opening);
                if (!isset($openings[$opening])) $openings[$opening] = ['games'=>0,'wins'=>0,'color'=>($isWhite?'white':'black')];
                $openings[$opening]['games']++;
                if ($myResult === 'win') $openings[$opening]['wins']++;
            }
        }

        // Sort openings by games played
        uasort($openings, fn($a,$b) => $b['games'] - $a['games']);
        $topOpenings = array_slice($openings, 0, 8, true);

        $total = count($recentGames);
        $totalWins   = $white['wins']   + $black['wins'];
        $totalLosses = $white['losses'] + $black['losses'];
        $totalDraws  = $white['draws']  + $black['draws'];
        $winRate     = $total > 0 ? round($totalWins / $total * 100) : 0;

        return view('pages.analytics', compact(
            'stats','white','black','topOpenings',
            'total','totalWins','totalLosses','totalDraws','winRate'
        ));
    }
}
