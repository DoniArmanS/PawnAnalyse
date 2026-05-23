@extends('layouts.app')

@section('title', 'Game Analysis')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chessboard-js/1.0.0/chessboard-1.0.0.min.css" integrity="sha512-bV/jTzPM//wE8204BMMZ7G2N22Zptj0fG2W8zEdozFzDk2Z1yKjZzPIfM1f1A7aXfRz/D8v2e3rA9I/BvB7vQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Override chessboard.js default styling for our theme */
    .chessboard-63f37 {
        border-radius: 4px;
        overflow: hidden;
    }
    .white-1e1d7 { background-color: #3f3f46; color: #131315; }
    .black-3c85d { background-color: #27272A; color: #c2c6d6; }
</style>
@endpush

@section('page_content')
<!-- Canvas -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-lg relative h-full min-h-[600px]">
    <!-- Left Column: Board & Controls (Spans 8 columns on large screens) -->
    <div class="lg:col-span-8 flex flex-col gap-md h-full">
        <!-- Player Info Top -->
        <div class="flex items-center justify-between bg-surface-container border border-outline-variant p-sm rounded">
            <div class="flex items-center gap-sm">
                <span class="font-body-sm text-body-sm font-semibold">{{ $gameData['black']['username'] ?? 'Black Player' }}</span>
                <span class="font-data-mono text-data-mono text-xs text-on-surface-variant">({{ $gameData['black']['rating'] ?? '?' }})</span>
            </div>
            <div class="font-data-mono text-data-mono bg-surface-container-high px-2 py-1 rounded-sm text-xs border border-outline-variant">Black</div>
        </div>
        
        <!-- The Board Area -->
        <div class="flex-1 bg-surface-container border border-outline-variant rounded flex items-center justify-center p-md relative" style="min-height: 400px;">
            <!-- Engine Evaluation Bar (Vertical Left) -->
            <div class="absolute left-md top-md bottom-md w-4 bg-surface-container-high border border-outline-variant rounded-sm overflow-hidden flex flex-col justify-end">
                <div id="eval-bar-fill" class="bg-primary-container h-[50%] w-full relative transition-all duration-300">
                    <!-- Score indicator -->
                    <div id="eval-score" class="absolute -top-3 left-1/2 -translate-x-1/2 font-data-mono text-[10px] text-on-primary-container bg-surface px-1 rounded shadow-sm border border-outline-variant">0.0</div>
                </div>
            </div>
            
            <!-- The Board -->
            <div id="board-wrap" class="w-full" style="max-width: 480px; aspect-ratio: 1/1;">
                <div id="board" style="width: 100%;"></div>
            </div>
        </div>
        
        <!-- Player Info Bottom -->
        <div class="flex items-center justify-between bg-surface-container border border-outline-variant p-sm rounded">
            <div class="flex items-center gap-sm">
                <span class="font-body-sm text-body-sm font-semibold">{{ $gameData['white']['username'] ?? 'White Player' }}</span>
                <span class="font-data-mono text-data-mono text-xs text-on-surface-variant">({{ $gameData['white']['rating'] ?? '?' }})</span>
            </div>
            <div class="font-data-mono text-data-mono bg-surface-container-high px-2 py-1 rounded-sm text-xs border border-outline-variant">White</div>
        </div>
        
        <!-- Controls -->
        <div class="bg-surface-container border border-outline-variant rounded p-sm flex flex-col gap-sm">
            <div class="flex justify-center items-center gap-sm">
                <button class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors" onclick="alert('Not implemented fully yet')">
                    <span class="material-symbols-outlined">fast_rewind</span>
                </button>
                <button class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="p-xs rounded bg-primary text-on-primary hover:bg-primary-fixed transition-colors">
                    <span class="material-symbols-outlined">play_arrow</span>
                </button>
                <button class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <button class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors">
                    <span class="material-symbols-outlined">fast_forward</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Right Column: Move List & Engine Data -->
    <div class="lg:col-span-4 flex flex-col gap-md h-full">
        <!-- Engine Evaluation Card -->
        <div class="bg-surface-container border border-outline-variant rounded p-md flex flex-col gap-sm shrink-0">
            <div class="flex justify-between items-center border-b border-outline-variant pb-xs">
                <span class="font-label-caps text-label-caps text-on-surface-variant flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[16px]">memory</span> ENGINE (Stockfish 16)
                </span>
                <div class="flex gap-xs" id="engine-status">
                    <span class="w-2 h-2 rounded-full bg-outline-variant" id="engine-indicator"></span>
                    <span class="font-data-mono text-xs text-on-surface-variant" id="engine-text">Loading...</span>
                </div>
            </div>
            
            <div class="flex flex-col gap-xs font-data-mono text-sm" id="engine-lines">
                <div class="flex justify-between items-center px-2 py-1">
                    <span class="text-on-surface-variant">Waiting for engine...</span>
                </div>
            </div>
        </div>
        
        <!-- Move List Container -->
        <div class="bg-surface-container border border-outline-variant rounded flex-1 flex flex-col overflow-hidden">
            <div class="p-sm border-b border-outline-variant bg-surface-container-high flex justify-between items-center shrink-0">
                <span class="font-label-caps text-label-caps">PGN DATA</span>
            </div>
            
            <!-- PGN Data -->
            <div class="flex-1 overflow-y-auto p-sm flex flex-col font-data-mono text-sm">
                @if($gameData && isset($gameData['pgn']))
                    <div class="bg-surface-dim p-sm rounded text-on-surface-variant whitespace-pre-wrap text-xs break-all">
                        {{ $gameData['pgn'] }}
                    </div>
                @else
                    <div class="text-center p-md text-on-surface-variant opacity-50 italic">
                        No PGN data available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery and Chessboard.js -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chessboard-js/1.0.0/chessboard-1.0.0.min.js" integrity="sha512-xofA/z3yW0S/Z1RfcQG2nE3T7Nylf1x1fG20+HlsrM/P4PqgE/tE7jD/x5aV6Qp901oB1V2/G7w28WqEw1A=" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Chess.js for logic -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.3/chess.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const engineIndicator = document.getElementById('engine-indicator');
        const engineText = document.getElementById('engine-text');
        const engineLines = document.getElementById('engine-lines');
        
        // Initialize Chessboard
        const config = {
            pieceTheme: 'https://chessboardjs.com/img/chesspieces/wikipedia/{piece}.png',
            position: 'start',
            draggable: false
        };
        const board = Chessboard('board', config);
        
        // chessboard.js needs explicit pixel width — resize after DOM is laid out
        setTimeout(() => board.resize(), 100);
        
        // Initialize chess.js logic
        const game = new Chess();
        const pgnData = `{!! isset($gameData['pgn']) ? str_replace(["\r", "\n"], ["\\r", "\\n"], addslashes($gameData['pgn'])) : '' !!}`;
        
        let moveHistory = [];
        let currentMoveIndex = -1;
        
        if (pgnData) {
            try {
                game.load_pgn(pgnData);
                moveHistory = game.history({ verbose: true });
                // Reset to start for analysis replay
                game.reset();
                board.position(game.fen());
            } catch(e) {
                console.error("Error parsing PGN with chess.js", e);
            }
        }
        
        // Stockfish Web Worker CORS Fix
        try {
            // Fetch the stockfish script directly to bypass Worker CORS policy
            engineText.innerText = 'Downloading Engine...';
            const response = await fetch('https://cdnjs.cloudflare.com/ajax/libs/stockfish.js/10.0.2/stockfish.js');
            const code = await response.text();
            
            // Create a local blob from the code
            const blob = new Blob([code], { type: 'application/javascript' });
            const workerUrl = URL.createObjectURL(blob);
            
            const stockfish = new Worker(workerUrl);
            
            stockfish.onmessage = function(event) {
                const line = typeof event.data === 'string' ? event.data : event.data.data;
                
                if (line === 'uciok') {
                    engineIndicator.classList.remove('bg-outline-variant');
                    engineIndicator.classList.add('bg-secondary', 'animate-pulse');
                    engineText.innerText = 'Stockfish 10 Online';
                    
                    // Start basic analysis of starting position
                    stockfish.postMessage('position startpos');
                    stockfish.postMessage('go depth 15');
                }
                
                if (line.includes('info depth')) {
                    // Extremely basic parse of score
                    let scoreMatch = line.match(/score cp (-?\d+)/);
                    let pvMatch = line.match(/pv (.+)/);
                    
                    if (scoreMatch && pvMatch) {
                        let score = (parseInt(scoreMatch[1]) / 100).toFixed(2);
                        if (score > 0) score = '+' + score;
                        
                        engineLines.innerHTML = `
                            <div class="flex justify-between items-center bg-surface-container-high px-2 py-1 rounded">
                                <span class="text-primary font-bold">${score}</span>
                                <span class="text-on-surface truncate max-w-[180px]">${pvMatch[1]}</span>
                            </div>
                        `;
                        
                        // Update eval bar
                        let scoreNum = parseFloat(score);
                        // Scale from -5 to +5 maps to 0% to 100%
                        let percentage = 50 + (scoreNum * 10);
                        if (percentage > 100) percentage = 100;
                        if (percentage < 0) percentage = 0;
                        
                        document.getElementById('eval-bar-fill').style.height = percentage + '%';
                        document.getElementById('eval-score').innerText = score;
                    }
                }
            };
            
            stockfish.postMessage('uci');
            
        } catch (e) {
            console.error('Stockfish error:', e);
            engineText.innerText = 'Engine Failed (CORS or Network)';
            engineIndicator.classList.remove('bg-outline-variant');
            engineIndicator.classList.add('bg-error');
        }
    });
</script>
@endpush
