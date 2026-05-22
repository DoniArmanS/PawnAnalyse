@extends('layouts.app')

@section('title', 'Game Analysis')

@push('styles')
<style>
    .chessboard-square.dark-square { background-color: #27272A; }
    .chessboard-square.light-square { background-color: #3f3f46; }
    .chessboard-square.highlight { outline: 2px solid #adc6ff; outline-offset: -2px; }
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
        <div class="flex-1 bg-surface-container border border-outline-variant rounded flex items-center justify-center p-md relative aspect-square lg:aspect-auto">
            <!-- Engine Evaluation Bar (Vertical Left) -->
            <div class="absolute left-md top-md bottom-md w-4 bg-surface-container-high border border-outline-variant rounded-sm overflow-hidden flex flex-col justify-end">
                <div id="eval-bar-fill" class="bg-primary-container h-[50%] w-full relative transition-all duration-300">
                    <!-- Score indicator -->
                    <div id="eval-score" class="absolute -top-3 left-1/2 -translate-x-1/2 font-data-mono text-[10px] text-on-primary-container bg-surface px-1 rounded shadow-sm border border-outline-variant">0.0</div>
                </div>
            </div>
            
            <!-- Placeholder for Board -->
            <div class="w-full max-w-[600px] aspect-square bg-surface-container-highest border border-outline-variant relative flex items-center justify-center" id="chessboard-container">
                <span class="material-symbols-outlined text-[120px] opacity-10">grid_4x4</span>
                @if(!$gameData)
                    <div class="absolute text-center">
                        <p class="font-headline-md text-on-surface-variant">No Game Selected</p>
                        <p class="font-body-sm text-on-surface-variant mt-2">Select a game from the dashboard to analyze.</p>
                    </div>
                @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const engineIndicator = document.getElementById('engine-indicator');
        const engineText = document.getElementById('engine-text');
        const engineLines = document.getElementById('engine-lines');
        
        // Basic Stockfish integration
        try {
            // Using Web Worker for Stockfish
            const stockfish = new Worker('https://cdnjs.cloudflare.com/ajax/libs/stockfish.js/10.0.2/stockfish.js');
            
            stockfish.onmessage = function(event) {
                const line = event.data;
                
                if (line === 'uciok') {
                    engineIndicator.classList.remove('bg-outline-variant');
                    engineIndicator.classList.add('bg-secondary', 'animate-pulse');
                    engineText.innerText = 'Ready';
                    
                    // Start basic analysis of starting position
                    stockfish.postMessage('position startpos');
                    stockfish.postMessage('go depth 10');
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
            engineText.innerText = 'Engine Failed';
            engineIndicator.classList.remove('bg-outline-variant');
            engineIndicator.classList.add('bg-error');
        }
    });
</script>
@endpush
