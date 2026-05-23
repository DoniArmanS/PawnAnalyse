@extends('layouts.app')

@section('title', 'Performance Analytics')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('page_content')

{{-- ── Header ──────────────────────────────────────────────────── --}}
<header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md border-b border-outline-variant pb-md -mt-sm">
    <div>
        <h1 class="font-display-lg text-[36px] leading-[44px] font-bold text-primary tracking-tight">Performance Analytics</h1>
        <p class="font-body-base text-body-base text-on-surface-variant mt-xs">Deep evaluation of your last {{ $total }} rated games.</p>
    </div>
    <div class="flex gap-sm items-center shrink-0">
        <div class="bg-surface-container border border-outline-variant rounded px-sm py-xs flex items-center gap-xs">
            <span class="material-symbols-outlined text-on-surface-variant text-sm">calendar_today</span>
            <span class="font-data-mono text-data-mono text-xs text-on-surface">Recent Data</span>
        </div>
    </div>
</header>

{{-- ── Bento Grid ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-md">

    {{-- ── Overall Win Rate Donut (col-span-4) ─────────────────── --}}
    <section class="lg:col-span-4 bg-surface-container border border-outline-variant rounded-lg p-md flex flex-col relative overflow-hidden group">
        <div class="flex justify-between items-center mb-lg z-10">
            <h2 class="font-label-caps text-label-caps text-on-surface-variant">Overall Outcome</h2>
            <span class="material-symbols-outlined text-outline text-[20px]">pie_chart</span>
        </div>

        {{-- Donut via conic-gradient --}}
        @php
            $wPct = $total > 0 ? $totalWins   / $total * 100 : 33;
            $lPct = $total > 0 ? $totalLosses / $total * 100 : 33;
            $dPct = 100 - $wPct - $lPct;
        @endphp
        <div class="relative flex-1 flex items-center justify-center min-h-[220px] z-10">
            <div class="w-44 h-44 rounded-full relative flex items-center justify-center"
                 style="background: conic-gradient(
                     #adc6ff 0% {{ $wPct }}%,
                     #ffb4ab {{ $wPct }}% {{ $wPct + $lPct }}%,
                     #424754 {{ $wPct + $lPct }}% 100%
                 );">
                {{-- Hole --}}
                <div class="absolute inset-[18px] rounded-full bg-surface-container flex flex-col items-center justify-center">
                    <span class="font-display-lg text-[32px] font-bold text-primary leading-none">{{ $winRate }}%</span>
                    <span class="font-label-caps text-label-caps text-on-surface-variant mt-xs">Win Rate</span>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="flex justify-center gap-md mt-auto pt-md border-t border-outline-variant z-10">
            <div class="flex items-center gap-xs">
                <div class="w-2 h-2 rounded-full bg-primary"></div>
                <span class="font-data-mono text-data-mono text-xs text-on-surface">Win ({{ $totalWins }})</span>
            </div>
            <div class="flex items-center gap-xs">
                <div class="w-2 h-2 rounded-full bg-error"></div>
                <span class="font-data-mono text-data-mono text-xs text-on-surface">Loss ({{ $totalLosses }})</span>
            </div>
            <div class="flex items-center gap-xs">
                <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                <span class="font-data-mono text-data-mono text-xs text-on-surface">Draw ({{ $totalDraws }})</span>
            </div>
        </div>

        {{-- Glow --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-primary/5 blur-[50px] rounded-full pointer-events-none group-hover:bg-primary/10 transition-colors duration-500"></div>
    </section>

    {{-- ── Performance by Color Bar Chart (col-span-8) ─────────── --}}
    <section class="lg:col-span-8 bg-surface-container border border-outline-variant rounded-lg p-md flex flex-col">
        <div class="flex justify-between items-center mb-xl">
            <h2 class="font-label-caps text-label-caps text-on-surface-variant">Performance by Color</h2>
            <div class="flex gap-sm flex-wrap">
                @php
                    $wGames = $white['wins'] + $white['losses'] + $white['draws'];
                    $bGames = $black['wins'] + $black['losses'] + $black['draws'];
                @endphp
                <span class="px-2 py-1 rounded bg-surface border border-outline-variant font-data-mono text-data-mono text-xs text-on-surface">
                    White: {{ $wGames }} games
                </span>
                <span class="px-2 py-1 rounded bg-surface border border-outline-variant font-data-mono text-data-mono text-xs text-on-surface">
                    Black: {{ $bGames }} games
                </span>
            </div>
        </div>

        <div class="flex-1 min-h-[220px] relative">
            <canvas id="colorChart"></canvas>
        </div>
    </section>

    {{-- ── Top Openings Table (col-span-12) ────────────────────── --}}
    <section class="lg:col-span-12 bg-surface-container border border-outline-variant rounded-lg p-md">
        <div class="flex justify-between items-center mb-md pb-sm border-b border-outline-variant">
            <h2 class="font-label-caps text-label-caps text-on-surface-variant">Top Openings Repertoire</h2>
            <span class="font-data-mono text-[10px] text-outline-variant">Based on recent {{ $total }} games</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="font-label-caps text-label-caps text-outline border-b border-surface-variant">
                        <th class="p-2 font-normal">Opening Name</th>
                        <th class="p-2 font-normal">Color</th>
                        <th class="p-2 font-normal">Games</th>
                        <th class="p-2 font-normal">Win %</th>
                    </tr>
                </thead>
                <tbody class="font-data-mono text-data-mono text-sm">
                    @forelse($topOpenings as $name => $data)
                    @php
                        $wp = $data['games'] > 0 ? round($data['wins'] / $data['games'] * 100) : 0;
                        $isGood = $wp >= 50;
                    @endphp
                    <tr class="border-b border-surface-variant/50 hover:bg-surface-variant/30 transition-colors group">
                        <td class="p-2">
                            <div class="flex items-center gap-sm">
                                <span class="w-1 h-5 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></span>
                                <span class="font-body-base text-body-sm text-on-surface">{{ $name }}</span>
                            </div>
                        </td>
                        <td class="p-2">
                            @if($data['color'] === 'white')
                                <div class="w-3 h-3 rounded-full border border-outline-variant bg-surface-container-lowest inline-block align-middle" title="White"></div>
                            @else
                                <div class="w-3 h-3 rounded-full border border-outline-variant bg-on-surface inline-block align-middle" title="Black"></div>
                            @endif
                        </td>
                        <td class="p-2 text-on-surface-variant">{{ $data['games'] }}</td>
                        <td class="p-2">
                            <div class="flex items-center gap-xs">
                                <span class="{{ $isGood ? 'text-primary' : 'text-error' }}">{{ $wp }}%</span>
                                <div class="w-16 h-1 bg-surface-dim rounded-full overflow-hidden">
                                    <div class="h-full {{ $isGood ? 'bg-primary' : 'bg-error' }}" style="width: {{ $wp }}%"></div>
                                </div>
                                <span class="material-symbols-outlined text-[14px] {{ $isGood ? 'text-secondary' : 'text-error' }}">
                                    {{ $isGood ? 'arrow_upward' : 'arrow_downward' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-md text-center text-on-surface-variant opacity-50 italic">
                            No opening data yet — play more rated games.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ── Stats cards row (col-span-12) ──────────────────────── --}}
    <div class="lg:col-span-12 grid grid-cols-2 sm:grid-cols-4 gap-md">
        @php
            $rapid  = $stats['chess_rapid']['last']['rating']  ?? null;
            $blitz  = $stats['chess_blitz']['last']['rating']  ?? null;
            $bullet = $stats['chess_bullet']['last']['rating'] ?? null;
            $cards  = [
                ['label'=>'Rapid Rating',   'value'=> $rapid  ?? '–', 'icon'=>'speed',          'color'=>'text-primary'],
                ['label'=>'Blitz Rating',   'value'=> $blitz  ?? '–', 'icon'=>'bolt',           'color'=>'text-secondary'],
                ['label'=>'Bullet Rating',  'value'=> $bullet ?? '–', 'icon'=>'rocket_launch',  'color'=>'text-tertiary'],
                ['label'=>'Total Analysed', 'value'=> $total,         'icon'=>'analytics',      'color'=>'text-primary'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-surface-container-low border border-outline-variant rounded-lg p-md flex flex-col gap-sm relative overflow-hidden group hover:border-primary/40 transition-colors">
            <div class="absolute -right-3 -bottom-3 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-[72px] {{ $card['color'] }}">{{ $card['icon'] }}</span>
            </div>
            <span class="font-label-caps text-label-caps text-on-surface-variant">{{ $card['label'] }}</span>
            <span class="font-display-lg text-[36px] font-bold {{ $card['color'] }} leading-none">{{ $card['value'] }}</span>
        </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('colorChart').getContext('2d');

    const wW = {{ $white['wins'] }};
    const wL = {{ $white['losses'] }};
    const wD = {{ $white['draws'] }};
    const bW = {{ $black['wins'] }};
    const bL = {{ $black['losses'] }};
    const bD = {{ $black['draws'] }};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['White – Win', 'White – Loss', 'White – Draw', 'Black – Win', 'Black – Loss', 'Black – Draw'],
            datasets: [{
                data: [wW, wL, wD, bW, bL, bD],
                backgroundColor: ['#adc6ff','#ffb4ab','#424754','#adc6ff','#ffb4ab','#424754'],
                borderRadius: 4,
                borderSkipped: false,
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
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    ticks: { color: '#c2c6d6', font: { family: 'JetBrains Mono', size: 11 } },
                    grid: { color: 'rgba(66,71,84,0.3)' },
                },
                y: {
                    ticks: { color: '#c2c6d6', stepSize: 1 },
                    grid: { color: 'rgba(66,71,84,0.3)' },
                    beginAtZero: true,
                }
            }
        }
    });
});
</script>
@endpush

@endsection
