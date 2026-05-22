<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChessComService;

class AuthController extends Controller
{
    protected $chessService;

    public function __construct(ChessComService $chessService)
    {
        $this->chessService = $chessService;
    }

    public function showLogin()
    {
        if (session()->has('chess_username')) {
            return redirect()->route('dashboard');
        }
        return view('pages.login');
    }

    public function connect(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255'
        ]);

        $username = trim($request->input('username'));
        
        // Verify user exists on Chess.com
        $profile = $this->chessService->getProfile($username);
        
        if (!$profile) {
            return back()->with('error', 'Chess.com profile not found. Please check the username and try again.')->withInput();
        }

        // Store in session
        session(['chess_username' => $username]);
        
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('chess_username');
        return redirect()->route('login');
    }
}
