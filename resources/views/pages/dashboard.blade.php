@extends('layouts.app')

@section('title', 'Dashboard')

@section('page_content')
<!-- Hero / Core Metrics Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-12 gap-md md:gap-lg">
    <!-- Main Elo Card -->
    <div class="col-span-1 md:col-span-8 bg-surface-container-low border border-outline-variant rounded-lg p-lg relative overflow-hidden flex flex-col justify-between min-h-[240px] h-full">
        <!-- Background abstract pattern -->
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(circle at 100% 0%, var(--color-primary) 0%, transparent 50%);"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <div class="flex items-center gap-sm mb-sm">
                    <h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Current Rating</h2>
                    <!-- Game Type Dropdown -->
                    <div class="relative">
                        <button id="gameTypeBtn" onclick="toggleDropdown()" class="flex items-center gap-xs font-label-caps text-label-caps text-primary border border-primary/40 bg-primary/10 hover:bg-primary/20 rounded px-sm py-[2px] transition-colors">
                            <span id="gameTypeLabel">Rapid</span>
                            <span class="material-symbols-outlined text-[14px]">expand_more</span>
                        </button>
                        <div id="gameTypeDropdown" class="hidden absolute top-full left-0 mt-xs z-20 bg-surface-container-high border border-outline-variant rounded shadow-xl min-w-[100px] overflow-hidden">
                            <button onclick="switchType('rapid', 'Rapid')" class="w-full text-left px-md py-sm font-label-caps text-label-caps text-on-surface hover:bg-primary/20 hover:text-primary transition-colors flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[14px]">speed</span> Rapid
                            </button>
                            <button onclick="switchType('blitz', 'Blitz')" class="w-full text-left px-md py-sm font-label-caps text-label-caps text-on-surface hover:bg-primary/20 hover:text-primary transition-colors flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[14px]">bolt</span> Blitz
                            </button>
                            <button onclick="switchType('bullet', 'Bullet')" class="w-full text-left px-md py-sm font-label-caps text-label-caps text-on-surface hover:bg-primary/20 hover:text-primary transition-colors flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[14px]">rocket_launch</span> Bullet
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex items-baseline gap-sm">
                    <span id="eloDisplay" class="font-display-lg text-display-lg text-on-surface">{{ $currentElo ?? 'N/A' }}</span>
                </div>
                <p class="font-body-base text-body-base text-primary mt-xs font-semibold">{{ $profile['title'] ?? 'Player' }}</p>
            </div>
            <div class="w-12 h-12 rounded bg-surface border border-outline-variant flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[24px]">military_tech</span>
            </div>
        </div>
        
        <!-- Chart.js Canvas for Rating Trend -->
        <div class="relative z-10 w-full h-24 mt-lg flex items-end">
            <canvas id="eloChart" class="w-full h-full"></canvas>
        </div>
    </div>
    
    <!-- Quick Actions Stack -->
    <div class="col-span-1 md:col-span-4 flex flex-col gap-md h-full">
        <!-- Action Card 1 -->
        <button class="flex-1 bg-surface-container-high border border-outline-variant hover:border-primary/50 rounded-lg p-md flex items-center justify-between group transition-all duration-300 relative overflow-hidden text-left">
            <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-300"></div>
            <div class="relative z-10">
                <span class="material-symbols-outlined text-primary mb-sm block text-[28px]" style="font-variation-settings: 'FILL' 1;">psychology</span>
                <h3 class="font-headline-md text-[18px] leading-tight font-semibold text-on-surface">Daily Puzzles</h3>
                <p class="font-data-mono text-data-mono text-on-surface-variant opacity-70 mt-xs">Tactics training</p>
            </div>
            <div class="relative z-10 w-8 h-8 rounded-full border border-outline-variant flex items-center justify-center group-hover:bg-primary group-hover:text-on-primary group-hover:border-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </div>
        </button>
        
        <!-- Action Card 2 -->
        <button class="flex-1 bg-surface-container-high border border-outline-variant hover:border-primary/50 rounded-lg p-md flex items-center justify-between group transition-all duration-300 relative overflow-hidden text-left">
            <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-300"></div>
            <div class="relative z-10">
                <span class="material-symbols-outlined text-primary mb-sm block text-[28px]" style="font-variation-settings: 'FILL' 1;">troubleshoot</span>
                <h3 class="font-headline-md text-[18px] leading-tight font-semibold text-on-surface">Deep Analysis</h3>
                <p class="font-data-mono text-data-mono text-on-surface-variant opacity-70 mt-xs">Stockfish 16.1</p>
            </div>
            <div class="relative z-10 w-8 h-8 rounded-full border border-outline-variant flex items-center justify-center group-hover:bg-primary group-hover:text-on-primary group-hover:border-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </div>
        </button>
    </div>
</section>

<!-- Bottom Section: Recent Games & Performance -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
    <!-- Recent Games List -->
    <div class="lg:col-span-2 flex flex-col gap-md">
        <div class="flex items-center justify-between pb-xs border-b border-outline-variant">
            <h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Recent Games</h3>
            <a class="font-data-mono text-data-mono text-primary hover:underline flex items-center gap-xs" href="#">View All <span class="material-symbols-outlined text-[14px]">arrow_outward</span></a>
        </div>
        
        <div class="flex flex-col border border-outline-variant rounded-lg overflow-hidden bg-surface-container-lowest">
            @forelse($recentGames as $game)
                @php
                    $isWhite = strtolower($game['white']['username']) == strtolower($username);
                    $myResult = $isWhite ? $game['white']['result'] : $game['black']['result'];
                    $opponent = $isWhite ? $game['black'] : $game['white'];
                    
                    if ($myResult == 'win') {
                        $outcomeStr = 'W';
                        $colorClass = 'bg-secondary';
                        $textClass = 'text-secondary';
                        $score = '1-0';
                    } elseif (in_array($myResult, ['agreed', 'repetition', 'stalemate', '50move', 'insufficient', 'timevsinsufficient'])) {
                        $outcomeStr = 'D';
                        $colorClass = 'bg-outline-variant';
                        $textClass = 'text-outline-variant';
                        $score = '½-½';
                    } else {
                        $outcomeStr = 'L';
                        $colorClass = 'bg-error';
                        $textClass = 'text-error';
                        $score = '0-1';
                    }
                @endphp
                <div class="flex items-center p-sm md:p-md border-b border-outline-variant bg-surface hover:bg-surface-variant/30 transition-colors group">
                    <div class="w-1 {{ $colorClass }} h-10 mr-sm rounded-full"></div>
                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-sm">
                        <div class="flex items-center gap-md">
                            <div class="flex flex-col items-center justify-center w-12 h-12 bg-surface-container-high rounded border border-outline-variant">
                                <span class="font-label-caps text-label-caps text-on-surface-variant">{{ $outcomeStr }}</span>
                                <span class="font-data-mono text-data-mono {{ $textClass }}">{{ $score }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-sm">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>
                                    <span class="font-body-base text-body-base font-semibold">{{ $opponent['username'] }}</span>
                                    <span class="font-data-mono text-data-mono text-on-surface-variant opacity-60">({{ $opponent['rating'] }})</span>
                                </div>
                                <div class="font-data-mono text-[12px] text-on-surface-variant mt-xs flex items-center gap-sm">
                                    <span>{{ $game['time_class'] }}</span>
                                    <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                                    <a href="{{ route('analysis', ['game' => urlencode($game['url'])]) }}" class="hover:text-primary hover:underline text-primary flex items-center gap-1">
                                        Analyze Game <span class="material-symbols-outlined text-[14px]">query_stats</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-md text-on-surface-variant font-data-mono text-center">No recent games found.</div>
            @endforelse
        </div>
    </div>
    
    <!-- Performance Breakdown -->
    <div class="lg:col-span-1 flex flex-col gap-md">
        <div class="flex items-center justify-between pb-xs border-b border-outline-variant">
            <h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Status</h3>
        </div>
        
        <!-- HUD Element -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-[120px] text-primary">memory</span>
            </div>
            <h4 class="font-label-caps text-label-caps text-on-surface-variant mb-sm">Connection Status</h4>
            <div class="flex items-center gap-sm font-data-mono text-data-mono text-secondary">
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                Chess.com API Online
            </div>
            <div class="mt-sm font-data-mono text-[10px] text-on-surface-variant opacity-60">
                Data Synced
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Rating data per game type from backend
    const ratingData = {
        rapid:  {{ $stats['chess_rapid']['last']['rating']  ?? 0 }},
        blitz:  {{ $stats['chess_blitz']['last']['rating']  ?? 0 }},
        bullet: {{ $stats['chess_bullet']['last']['rating'] ?? 0 }},
    };

    let eloChart;
    let currentType = 'rapid';

    function buildChartData(elo) {
        if (!elo || elo === 0) elo = null;
        return elo
            ? [elo - 50, elo - 30, elo - 10, elo + 15, elo - 5, elo]
            : [0, 0, 0, 0, 0, 0];
    }

    function toggleDropdown() {
        document.getElementById('gameTypeDropdown').classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('gameTypeBtn');
        const dd  = document.getElementById('gameTypeDropdown');
        if (!btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });

    function switchType(type, label) {
        currentType = type;
        const elo = ratingData[type];
        document.getElementById('gameTypeLabel').textContent = label;
        document.getElementById('eloDisplay').textContent = elo > 0 ? elo : 'N/A';
        document.getElementById('gameTypeDropdown').classList.add('hidden');

        // Update chart
        const chartData = buildChartData(elo);
        eloChart.data.datasets[0].data = chartData;
        eloChart.options.scales.y.min = elo > 0 ? elo - 100 : 0;
        eloChart.options.scales.y.max = elo > 0 ? elo + 50  : 10;
        eloChart.update();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('eloChart').getContext('2d');
        const initElo = ratingData['rapid'];

        const config = {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Elo Rating',
                    data: buildChartData(initElo),
                    borderColor: '#adc6ff',
                    backgroundColor: 'rgba(173, 198, 255, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#131315',
                    pointBorderColor: '#adc6ff',
                    pointRadius: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#2a2a2c',
                        titleColor: '#e5e1e4',
                        bodyColor: '#c2c6d6',
                        borderColor: '#424754',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: { display: false },
                    y: {
                        display: false,
                        min: initElo > 0 ? initElo - 100 : 0,
                        max: initElo > 0 ? initElo + 50  : 10
                    }
                },
                layout: { padding: 0 }
            }
        };

        eloChart = new Chart(ctx, config);
    });
</script>
@endpush
