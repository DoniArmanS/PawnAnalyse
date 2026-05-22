@extends('layouts.app')

@section('title', 'Dashboard')

@section('page_content')
<!-- Hero / Core Metrics Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-12 gap-md md:gap-lg">
    <!-- Main Elo Card -->
    <div class="col-span-1 md:col-span-8 bg-surface-container-low border border-outline-variant rounded-lg p-lg relative overflow-hidden flex flex-col justify-between min-h-[240px]">
        <!-- Background abstract pattern -->
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(circle at 100% 0%, var(--color-primary) 0%, transparent 50%);"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <h2 class="font-label-caps text-label-caps text-on-surface-variant mb-sm uppercase tracking-widest">Current Rating (Rapid)</h2>
                <div class="flex items-baseline gap-sm">
                    <span class="font-display-lg text-display-lg text-on-surface">{{ $currentElo ?? 'N/A' }}</span>
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
    <div class="col-span-1 md:col-span-4 flex flex-col gap-md">
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
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('eloChart').getContext('2d');
        
        // Mock data for the chart, you can inject real history data from backend here later
        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Elo Rating',
                data: [{{ $currentElo - 50 }}, {{ $currentElo - 30 }}, {{ $currentElo - 10 }}, {{ $currentElo + 15 }}, {{ $currentElo - 5 }}, {{ $currentElo }}],
                borderColor: '#adc6ff', // primary color
                backgroundColor: 'rgba(173, 198, 255, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#131315',
                pointBorderColor: '#adc6ff',
                pointRadius: 3,
                fill: true,
                tension: 0.4
            }]
        };

        const config = {
            type: 'line',
            data: data,
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
                    x: {
                        display: false
                    },
                    y: {
                        display: false,
                        min: {{ $currentElo - 100 }},
                        max: {{ $currentElo + 50 }}
                    }
                },
                layout: {
                    padding: 0
                }
            }
        };

        new Chart(ctx, config);
    });
</script>
@endpush
