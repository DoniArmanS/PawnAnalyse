@extends('layouts.base')

@section('title', 'Connect Account')

@section('body_class', 'bg-surface-container-lowest text-on-surface font-body-base min-h-screen flex items-center justify-center p-md md:p-xl relative overflow-hidden')

@section('content')
<!-- Atmospheric Background Elements -->
<div class="fixed inset-0 pointer-events-none z-0 flex items-center justify-center opacity-20">
    <div class="absolute w-[800px] h-[800px] border border-surface-container-highest rounded-full border-dashed animate-[spin_120s_linear_infinite]"></div>
    <div class="absolute w-[600px] h-[600px] border border-outline-variant/30 rounded-full animate-[spin_90s_linear_infinite_reverse]"></div>
    <div class="absolute w-[400px] h-[400px] border border-primary/10 rounded-full border-dashed animate-[spin_60s_linear_infinite]"></div>
    <!-- Subtle Grid -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
</div>

<!-- Main Content Container (Bento Grid Style Split) -->
<div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-lg z-10 relative">
    <!-- Left Panel: Value Proposition / Visual -->
    <div class="hidden md:flex flex-col justify-between bg-surface border border-outline-variant/50 rounded-xl p-xl hud-glow relative overflow-hidden group min-w-0">
        <!-- Abstract Data Vis Background -->
        <div class="absolute inset-0 z-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1529699211952-734e80c4d42b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center mix-blend-luminosity"></div>
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-surface/80 to-transparent z-0"></div>
        <div class="relative z-10 flex flex-col h-full">
            <!-- Branding -->
            <div class="flex items-center gap-sm mb-xl">
                <span class="material-symbols-outlined text-primary fill text-3xl">psychology</span>
                <h1 class="font-headline-md text-headline-md font-bold tracking-tight text-primary">PawnAnalyse</h1>
            </div>
            
            <div class="mt-auto">
                <div class="inline-flex items-center gap-sm px-sm py-xs bg-primary-container/20 border border-primary/30 rounded-full mb-md">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="font-data-mono text-data-mono text-primary text-xs">Engine Initialized</span>
                </div>
                <h2 class="font-display-lg text-display-lg text-inverse-surface mb-md">Connect your profile</h2>
                <p class="font-body-base text-body-base text-on-surface-variant" style="max-width:none; white-space: normal;">
                    Unlock deep insights from your Lichess and Chess.com history. Our engine processes your past games to identify opening inaccuracies and early game strategic patterns.
                </p>
                
                <!-- Feature Badges -->
                <div class="flex flex-wrap gap-sm mt-lg">
                    <div class="flex items-center gap-xs px-md py-sm bg-surface-container border border-surface-container-highest rounded-DEFAULT">
                        <span class="material-symbols-outlined text-secondary text-sm">troubleshoot</span>
                        <span class="font-data-mono text-data-mono text-xs text-on-surface">Opening Analysis</span>
                    </div>
                    <div class="flex items-center gap-xs px-md py-sm bg-surface-container border border-surface-container-highest rounded-DEFAULT">
                        <span class="material-symbols-outlined text-tertiary text-sm">query_stats</span>
                        <span class="font-data-mono text-data-mono text-xs text-on-surface">Elo Forecasting</span>
                    </div>
                    <div class="flex items-center gap-xs px-md py-sm bg-surface-container border border-surface-container-highest rounded-DEFAULT">
                        <span class="material-symbols-outlined text-primary text-sm">memory</span>
                        <span class="font-data-mono text-data-mono text-xs text-on-surface">Deep AI Parsing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Panel: Connection Options -->
    <div class="flex flex-col justify-center bg-surface-container-low border border-outline-variant/30 rounded-xl p-lg md:p-xl shadow-2xl backdrop-blur-md">
        <div class="mb-xl md:hidden">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-primary fill text-2xl">psychology</span>
                <h1 class="font-headline-md text-headline-md font-bold tracking-tight text-primary">PawnAnalyse</h1>
            </div>
            <h2 class="font-headline-md text-headline-md text-inverse-surface">Connect Account</h2>
        </div>

        @if(session('error'))
        <div class="mb-md p-sm bg-error-container/20 border border-error text-error rounded flex items-center gap-sm">
            <span class="material-symbols-outlined text-sm">error</span>
            <span class="font-body-sm text-body-sm">{{ session('error') }}</span>
        </div>
        @endif
        
        <!-- Primary Connect Actions -->
        <div class="flex flex-col gap-md mb-xl" id="platform-buttons">
            <button onclick="showChessComForm()" type="button" class="w-full flex items-center justify-between px-md py-lg bg-surface-container-high hover:bg-surface-bright border border-outline-variant/50 hover:border-primary/50 rounded-lg transition-all duration-200 group">
                <div class="flex items-center gap-md">
                    <div class="w-10 h-10 bg-[#769656] rounded flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white">chess</span>
                    </div>
                    <div class="text-left">
                        <span class="block font-headline-md text-body-base font-semibold text-on-surface group-hover:text-primary transition-colors">Chess.com</span>
                        <span class="block font-body-sm text-body-sm text-on-surface-variant">Import standard & blitz games</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all">arrow_forward</span>
            </button>
            <button type="button" class="w-full flex items-center justify-between px-md py-lg bg-surface-container-high hover:bg-surface-bright border border-outline-variant/50 hover:border-primary/50 rounded-lg transition-all duration-200 group opacity-50 cursor-not-allowed" title="Coming soon">
                <div class="flex items-center gap-md">
                    <div class="w-10 h-10 bg-white rounded flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-black">sports_esports</span>
                    </div>
                    <div class="text-left">
                        <span class="block font-headline-md text-body-base font-semibold text-on-surface group-hover:text-primary transition-colors">Lichess.org</span>
                        <span class="block font-body-sm text-body-sm text-on-surface-variant">Import all rated history</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all">arrow_forward</span>
            </button>
        </div>
        
        <div id="chesscom-form-container" style="display: none;">
            <div class="relative flex py-sm items-center mb-xl">
                <div class="flex-grow border-t border-outline-variant/50"></div>
                <span class="flex-shrink-0 mx-md font-label-caps text-label-caps text-on-surface-variant">SYSTEM OVERRIDE / MANUAL ENTRY</span>
                <div class="flex-grow border-t border-outline-variant/50"></div>
            </div>
            
            <!-- Standard Login Form modified for Username -->
            <form class="flex flex-col gap-md" action="/connect" method="POST">
                @csrf
                <div>
                    <label class="block font-label-caps text-label-caps text-on-surface-variant mb-xs" for="username">Chess.com Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-sm">
                            <span class="material-symbols-outlined text-on-surface-variant text-sm">person</span>
                        </span>
                        <input class="w-full bg-surface-dim border border-outline-variant text-on-surface font-body-base rounded focus:ring-2 focus:ring-primary focus:border-primary pl-xl pr-md py-sm transition-all outline-none" id="username" name="username" placeholder="e.g. hikaru" type="text" required value="{{ old('username') }}"/>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-sm">
                    <label class="flex items-center gap-xs cursor-pointer">
                        <input class="form-checkbox bg-surface-dim border-outline-variant text-primary rounded-sm focus:ring-primary focus:ring-offset-surface-container-low" type="checkbox" checked/>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Retain connection</span>
                    </label>
                </div>
                
                <button class="w-full bg-primary hover:bg-primary-fixed text-on-primary font-headline-md text-body-base font-semibold py-sm px-md rounded mt-md transition-colors flex justify-center items-center gap-sm" type="submit">
                    Initialize Session
                    <span class="material-symbols-outlined text-sm">login</span>
                </button>
                <button type="button" onclick="hideChessComForm()" class="w-full mt-sm text-on-surface-variant hover:text-on-surface transition-colors font-body-sm text-sm">Cancel</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple ambient interaction: inputs glow subtly on focus
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.parentElement.classList.add('opacity-100');
        });
        input.addEventListener('blur', () => {
            input.parentElement.parentElement.classList.remove('opacity-100');
        });
    });

    function showChessComForm() {
        document.getElementById('platform-buttons').style.display = 'none';
        document.getElementById('chesscom-form-container').style.display = 'block';
    }

    function hideChessComForm() {
        document.getElementById('platform-buttons').style.display = 'flex';
        document.getElementById('chesscom-form-container').style.display = 'none';
    }
    
    // Show form automatically if there's an error
    @if(session('error'))
        showChessComForm();
    @endif
</script>
@endpush
@endsection
