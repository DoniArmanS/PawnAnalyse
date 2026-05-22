<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ChessComService
{
    private $baseUrl = 'https://api.chess.com/pub/player/';

    /**
     * Get player profile
     */
    public function getProfile(string $username)
    {
        return Cache::remember("chess_profile_{$username}", 3600, function () use ($username) {
            $response = Http::get($this->baseUrl . $username);
            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get player stats
     */
    public function getStats(string $username)
    {
        return Cache::remember("chess_stats_{$username}", 3600, function () use ($username) {
            $response = Http::get($this->baseUrl . $username . '/stats');
            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get player game archives (returns list of monthly archive URLs)
     */
    public function getGameArchives(string $username)
    {
        return Cache::remember("chess_archives_{$username}", 86400, function () use ($username) {
            $response = Http::get($this->baseUrl . $username . '/games/archives');
            return $response->successful() ? $response->json('archives') : [];
        });
    }

    /**
     * Get games for a specific month
     */
    public function getGamesForMonth(string $username, int $year, int $month)
    {
        $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
        return Cache::remember("chess_games_{$username}_{$year}_{$monthStr}", 86400, function () use ($username, $year, $monthStr) {
            $response = Http::get($this->baseUrl . $username . "/games/{$year}/{$monthStr}");
            return $response->successful() ? $response->json('games') : [];
        });
    }

    /**
     * Get recent games (from current and previous month if needed)
     */
    public function getRecentGames(string $username, int $limit = 10)
    {
        $archives = $this->getGameArchives($username);
        
        if (empty($archives)) {
            return [];
        }

        $allGames = [];
        // Fetch starting from the most recent archive
        for ($i = count($archives) - 1; $i >= 0; $i--) {
            $archiveUrl = $archives[$i];
            
            // Extract year and month from URL
            // Format: https://api.chess.com/pub/player/{username}/games/{YYYY}/{MM}
            preg_match('/games\/(\d{4})\/(\d{2})$/', $archiveUrl, $matches);
            
            if (count($matches) === 3) {
                $year = (int)$matches[1];
                $month = (int)$matches[2];
                
                $games = $this->getGamesForMonth($username, $year, $month);
                
                // Merge games
                $allGames = array_merge($games, $allGames);
                
                if (count($allGames) >= $limit) {
                    break;
                }
            }
        }
        
        // Return latest $limit games
        usort($allGames, function($a, $b) {
            return $b['end_time'] <=> $a['end_time'];
        });

        return array_slice($allGames, 0, $limit);
    }
}
