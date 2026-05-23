@extends('layouts.app')

@section('title', 'Game Analysis')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chessboard-js/1.0.0/chessboard-1.0.0.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Themed board squares */
    .white-1e1d7 { background-color: #4a5568 !important; }
    .black-3c85d { background-color: #1a202c !important; }
    /* Force board to fill wrapper */
    #board .board-b72b1 { width: 100% !important; }
    #board table { width: 100% !important; }

    .move-row:hover { background: rgba(173,198,255,0.06); }
    .move-cell.active { background: rgba(173,198,255,0.15); color: #adc6ff; border-radius: 4px; }
</style>
@endpush

@section('page_content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-lg relative" style="min-height: 600px;">

    {{-- ─── Left Column: Board ─────────────────────────────── --}}
    <div class="lg:col-span-8 flex flex-col gap-md">

        {{-- Black player info --}}
        <div class="flex items-center justify-between bg-surface-container border border-outline-variant px-md py-sm rounded">
            <div class="flex items-center gap-sm">
                <span class="w-4 h-4 rounded-full bg-on-surface border border-outline-variant inline-block"></span>
                <span class="font-body-sm text-body-sm font-semibold">{{ $gameData['black']['username'] ?? 'Black Player' }}</span>
                <span class="font-data-mono text-data-mono text-xs text-on-surface-variant">({{ $gameData['black']['rating'] ?? '?' }})</span>
            </div>
            <div class="font-data-mono text-data-mono bg-surface-container-high px-2 py-1 rounded-sm text-xs border border-outline-variant">Black</div>
        </div>

        {{-- Board + eval bar wrapper --}}
        <div class="bg-surface-container border border-outline-variant rounded flex items-center justify-center p-md relative" style="min-height: 420px;">
            {{-- Eval bar (left) --}}
            <div class="absolute left-2 top-4 bottom-4 w-3 bg-surface-container-high border border-outline-variant rounded-sm overflow-hidden flex flex-col justify-end z-10">
                <div id="eval-bar-fill" class="bg-primary w-full transition-all duration-500" style="height: 50%;">
                    <div id="eval-score" class="absolute -top-4 left-1/2 -translate-x-1/2 font-data-mono text-[9px] text-primary whitespace-nowrap px-1">0.0</div>
                </div>
            </div>

            {{-- Chessboard --}}
            <div id="board-wrap" style="width: min(100%, 460px);">
                <div id="board"></div>
            </div>
        </div>

        {{-- White player info --}}
        <div class="flex items-center justify-between bg-surface-container border border-outline-variant px-md py-sm rounded">
            <div class="flex items-center gap-sm">
                <span class="w-4 h-4 rounded-full bg-surface-bright border border-outline-variant inline-block"></span>
                <span class="font-body-sm text-body-sm font-semibold">{{ $gameData['white']['username'] ?? 'White Player' }}</span>
                <span class="font-data-mono text-data-mono text-xs text-on-surface-variant">({{ $gameData['white']['rating'] ?? '?' }})</span>
            </div>
            <div class="font-data-mono text-data-mono bg-surface-container-high px-2 py-1 rounded-sm text-xs border border-outline-variant">White</div>
        </div>

        {{-- Navigation controls --}}
        <div class="bg-surface-container border border-outline-variant rounded p-sm flex justify-center items-center gap-sm">
            <button id="btn-start"  class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined">first_page</span>
            </button>
            <button id="btn-prev" class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button id="btn-next" class="p-xs rounded bg-primary text-on-primary hover:bg-primary-fixed transition-colors">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
            <button id="btn-end" class="p-xs rounded bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined">last_page</span>
            </button>
        </div>
    </div>

    {{-- ─── Right Column: Engine + Move List ───────────────── --}}
    <div class="lg:col-span-4 flex flex-col gap-md">

        {{-- Engine card --}}
        <div class="bg-surface-container border border-outline-variant rounded p-md flex flex-col gap-sm shrink-0">
            <div class="flex justify-between items-center border-b border-outline-variant pb-xs">
                <span class="font-label-caps text-label-caps text-on-surface-variant flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[16px]">memory</span> ENGINE (Lc0 / WASM)
                </span>
                <div class="flex gap-xs items-center">
                    <span class="w-2 h-2 rounded-full bg-outline-variant animate-pulse" id="engine-indicator"></span>
                    <span class="font-data-mono text-xs text-on-surface-variant" id="engine-text">Loading…</span>
                </div>
            </div>
            <div id="engine-lines" class="flex flex-col gap-xs font-data-mono text-sm">
                <div class="flex justify-between items-center px-2 py-1 text-on-surface-variant">Waiting for engine…</div>
            </div>
        </div>

        {{-- Move List --}}
        <div class="bg-surface-container border border-outline-variant rounded flex-1 flex flex-col overflow-hidden">
            {{-- Header --}}
            <div class="p-sm border-b border-outline-variant bg-surface-container-high flex justify-between items-center shrink-0">
                <span class="font-label-caps text-label-caps">MOVE LIST</span>
                <div class="flex gap-lg text-[10px] font-label-caps text-on-surface-variant">
                    <span>{{ $gameData['white']['username'] ?? 'White' }} (W)</span>
                    <span>{{ $gameData['black']['username'] ?? 'Black' }} (B)</span>
                </div>
            </div>

            {{-- Column headers --}}
            <div class="grid grid-cols-[32px_1fr_1fr] px-sm py-xs bg-surface-container-low border-b border-outline-variant shrink-0 text-[10px] font-label-caps text-on-surface-variant">
                <span>#</span>
                <span>White · time</span>
                <span>Black · time</span>
            </div>

            {{-- Moves --}}
            <div id="move-list" class="flex-1 overflow-y-auto p-xs flex flex-col gap-[2px] font-data-mono text-[13px]">
                @php
                    $pgn = $gameData['pgn'] ?? '';
                    // Parse moves from PGN (extract move-number blocks)
                    // PGN moves format: "1. e4 {[%clk 0:02:59.9]} 1... c5 {[%clk 0:02:59.9]}"
                    $parsedMoves = [];
                    if ($pgn) {
                        // Remove header tags
                        $body = preg_replace('/\[.*?\]\s*/s', '', $pgn);
                        // Match each half-move with optional clock
                        preg_match_all('/(\d+)\.\s+(\S+)\s*(?:\{[^}]*\[%clk\s+([\d:\.]+)[^}]*\})?.*?(?:(\d+)\.\.\.\s+(\S+)\s*(?:\{[^}]*\[%clk\s+([\d:\.]+)[^}]*\})?)?/s', $body, $matches, PREG_SET_ORDER);
                        foreach ($matches as $m) {
                            $parsedMoves[] = [
                                'no'         => $m[1],
                                'white_move' => $m[2] ?? '',
                                'white_clk'  => $m[3] ?? '',
                                'black_move' => $m[5] ?? '',
                                'black_clk'  => $m[6] ?? '',
                            ];
                        }
                    }
                @endphp

                @if(count($parsedMoves) > 0)
                    @foreach($parsedMoves as $i => $mv)
                    <div class="move-row grid grid-cols-[32px_1fr_1fr] items-center px-xs rounded cursor-pointer transition-colors" data-move="{{ $i * 2 }}">
                        <span class="text-outline-variant text-[10px]">{{ $mv['no'] }}</span>
                        <button class="move-cell text-left px-1 py-[3px] text-on-surface hover:text-primary transition-colors" data-move="{{ $i * 2 }}">
                            <span class="font-semibold">{{ $mv['white_move'] }}</span>
                            @if($mv['white_clk'])
                            <span class="text-[9px] text-outline-variant ml-xs block">{{ $mv['white_clk'] }}</span>
                            @endif
                        </button>
                        @if($mv['black_move'])
                        <button class="move-cell text-left px-1 py-[3px] text-on-surface hover:text-primary transition-colors" data-move="{{ $i * 2 + 1 }}">
                            <span class="font-semibold">{{ $mv['black_move'] }}</span>
                            @if($mv['black_clk'])
                            <span class="text-[9px] text-outline-variant ml-xs block">{{ $mv['black_clk'] }}</span>
                            @endif
                        </button>
                        @else
                        <span></span>
                        @endif
                    </div>
                    @endforeach
                @elseif($pgn)
                    {{-- Fallback: show raw PGN if regex didn't match --}}
                    <div class="text-on-surface-variant text-xs p-sm whitespace-pre-wrap break-all">{{ $pgn }}</div>
                @else
                    <div class="text-center p-md text-on-surface-variant opacity-50 italic">No moves available.</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chessboard-js/1.0.0/chessboard-1.0.0.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.3/chess.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── 1. Parse PGN & extract moves ─────────────────────────────
    const rawPgn = {!! isset($gameData['pgn']) ? json_encode($gameData['pgn']) : "''" !!};
    const username = {!! json_encode(session('chess_username')) !!};
    const whiteUser = {!! json_encode($gameData['white']['username'] ?? '') !!};
    const isUserWhite = whiteUser.toLowerCase() === username.toLowerCase();

    const game = new Chess();
    let moveHistory = [];

    if (rawPgn) {
        try {
            game.load_pgn(rawPgn);
            moveHistory = game.history({ verbose: true });
            game.reset();
        } catch(e) {
            console.error('chess.js PGN parse error', e);
        }
    }

    // ── 2. Init Chessboard ───────────────────────────────────────
    const boardEl = document.getElementById('board');
    const boardWrap = document.getElementById('board-wrap');

    // Give the board div an explicit pixel width before init
    boardEl.style.width = boardWrap.offsetWidth + 'px';

    const board = Chessboard('board', {
        pieceTheme: 'https://chessboardjs.com/img/chesspieces/wikipedia/{piece}.png',
        position: 'start',
        draggable: false,
        orientation: isUserWhite ? 'white' : 'black',
    });

    // Resize on window resize
    window.addEventListener('resize', () => {
        boardEl.style.width = boardWrap.offsetWidth + 'px';
        board.resize();
    });
    setTimeout(() => {
        boardEl.style.width = boardWrap.offsetWidth + 'px';
        board.resize();
    }, 150);

    // ── 3. Navigation state ──────────────────────────────────────
    let currentIdx = -1; // -1 = start position

    function gotoMove(idx) {
        if (!moveHistory.length) return;
        idx = Math.max(-1, Math.min(idx, moveHistory.length - 1));
        currentIdx = idx;

        // Replay moves up to idx
        const tmp = new Chess();
        for (let i = 0; i <= idx; i++) {
            tmp.move(moveHistory[i]);
        }
        board.position(tmp.fen(), false);

        // Highlight active move cell
        document.querySelectorAll('.move-cell').forEach(c => c.classList.remove('active'));
        if (idx >= 0) {
            const active = document.querySelector(`.move-cell[data-move="${idx}"]`);
            if (active) {
                active.classList.add('active');
                active.scrollIntoView({ block: 'nearest' });
            }
        }

        // Update engine with current position FEN
        if (window.stockfishReady && window.sf) {
            window.sf.postMessage('stop');
            window.sf.postMessage('position fen ' + tmp.fen());
            window.sf.postMessage('go depth 18');
        }

        updateEvalBar(0); // reset while engine thinks
    }

    document.getElementById('btn-start').onclick = () => gotoMove(-1);
    document.getElementById('btn-prev').onclick  = () => gotoMove(currentIdx - 1);
    document.getElementById('btn-next').onclick  = () => gotoMove(currentIdx + 1);
    document.getElementById('btn-end').onclick   = () => gotoMove(moveHistory.length - 1);

    // Click on move cell
    document.querySelectorAll('.move-cell').forEach(btn => {
        btn.addEventListener('click', function() {
            gotoMove(parseInt(this.dataset.move));
        });
    });

    // Keyboard nav
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft')  gotoMove(currentIdx - 1);
        if (e.key === 'ArrowRight') gotoMove(currentIdx + 1);
    });

    // ── 4. Eval Bar helper ───────────────────────────────────────
    function updateEvalBar(cpScore) {
        const clamped = Math.max(-600, Math.min(600, cpScore));
        const pct = 50 + (clamped / 600) * 50;
        document.getElementById('eval-bar-fill').style.height = pct + '%';
        const fmt = (cpScore / 100).toFixed(2);
        document.getElementById('eval-score').innerText = cpScore > 0 ? '+' + fmt : fmt;
    }

    // ── 5. Engine: Stockfish WASM via blob bypass ────────────────
    const engineIndicator = document.getElementById('engine-indicator');
    const engineText      = document.getElementById('engine-text');
    const engineLines     = document.getElementById('engine-lines');

    window.stockfishReady = false;

    (async () => {
        try {
            engineText.innerText = 'Connecting…';
            // Use Stockfish 11 WASM build — more reliable CDN
            const resp = await fetch('https://cdn.jsdelivr.net/npm/stockfish.wasm@0.10.0/stockfish.js');
            if (!resp.ok) throw new Error('fetch failed');
            const code = await resp.text();
            const blob = new Blob([code], { type: 'application/javascript' });
            const sf = new Worker(URL.createObjectURL(blob));
            window.sf = sf;

            sf.onmessage = function(e) {
                const line = typeof e.data === 'string' ? e.data : (e.data?.data ?? '');

                if (line === 'uciok') {
                    sf.postMessage('isready');
                }
                if (line === 'readyok') {
                    window.stockfishReady = true;
                    engineIndicator.classList.remove('bg-outline-variant');
                    engineIndicator.classList.add('bg-secondary');
                    engineText.innerText = 'Stockfish 11 Online';
                    // Start analysis on current position
                    sf.postMessage('position startpos');
                    sf.postMessage('go depth 18');
                }
                if (line.startsWith('info') && line.includes('score cp') && line.includes(' pv ')) {
                    const cpM = line.match(/score cp (-?\d+)/);
                    const pvM = line.match(/ pv (.+)/);
                    const depM = line.match(/depth (\d+)/);
                    if (cpM && pvM) {
                        const cp    = parseInt(cpM[1]);
                        const moves = pvM[1].trim().split(' ').slice(0, 5).join(' ');
                        const depth = depM ? depM[1] : '?';
                        const fmt   = (cp / 100).toFixed(2);
                        const disp  = cp > 0 ? '+' + fmt : fmt;
                        engineLines.innerHTML = `
                            <div class="flex items-center justify-between bg-surface-container-high px-2 py-1 rounded">
                                <span class="text-primary font-bold text-base">${disp}</span>
                                <span class="text-[10px] text-outline-variant">depth ${depth}</span>
                            </div>
                            <div class="text-on-surface-variant text-[11px] px-2 truncate">${moves}</div>`;
                        updateEvalBar(cp);
                    }
                }
            };

            sf.postMessage('uci');

        } catch(err) {
            console.error('Engine error:', err);
            engineText.innerText = 'Engine unavailable';
            engineIndicator.classList.add('bg-error');
        }
    })();
});
</script>
@endpush
