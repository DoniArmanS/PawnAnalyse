@extends('layouts.base')

@section('content')
<!-- SideNavBar -->
<nav class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 bg-surface-container-low border-r border-outline-variant py-lg gap-md z-40">
    <!-- Brand / Header -->
    <div class="px-lg pb-md mb-md border-b border-outline-variant flex flex-col gap-sm">
        <div class="flex items-center gap-sm">
            <span class="material-symbols-outlined text-primary text-[32px]" style="font-variation-settings: 'FILL' 1;">chess</span>
            <span class="font-headline-md text-headline-md font-bold text-primary tracking-tight">PawnAnalyse</span>
        </div>
    </div>
    
    <!-- User Profile Area -->
    <div class="px-md mb-md">
        <div class="flex items-center gap-md p-sm bg-surface rounded-lg border border-outline-variant">
            <div class="w-10 h-10 rounded-full bg-surface-container-high overflow-hidden border border-outline-variant shrink-0">
                <img alt="User Profile" class="w-full h-full object-cover" src="{{ session('chess_avatar', 'https://www.chess.com/bundles/web/images/user-image.svg') }}"/>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="font-label-caps text-label-caps text-on-surface truncate">{{ session('chess_username') }}</span>
                <span class="font-data-mono text-data-mono text-primary truncate">Connected</span>
            </div>
        </div>
    </div>
    
    <!-- Main Nav Links -->
    <div class="flex-1 overflow-y-auto px-sm flex flex-col gap-xs">
        <a class="bg-primary-container/20 text-primary border-l-4 border-primary px-md py-sm flex items-center gap-md font-label-caps text-label-caps hover:bg-surface-variant/50 transition-all duration-200 translate-x-1" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>
        <a class="text-on-surface-variant hover:text-on-surface px-md py-sm flex items-center gap-md font-label-caps text-label-caps hover:bg-surface-variant/50 transition-all duration-200 border-l-4 border-transparent" href="#">
            <span class="material-symbols-outlined">query_stats</span>
            Analytics
        </a>
        <a class="text-on-surface-variant hover:text-on-surface px-md py-sm flex items-center gap-md font-label-caps text-label-caps hover:bg-surface-variant/50 transition-all duration-200 border-l-4 border-transparent" href="#">
            <span class="material-symbols-outlined">trending_up</span>
            Progression
        </a>
        <a class="text-on-surface-variant hover:text-on-surface px-md py-sm flex items-center gap-md font-label-caps text-label-caps hover:bg-surface-variant/50 transition-all duration-200 border-l-4 border-transparent" href="#">
            <span class="material-symbols-outlined">query_builder</span>
            Analysis
        </a>
    </div>
    
    <!-- CTA -->
    <div class="px-md mb-md">
        <button class="w-full bg-primary text-on-primary py-sm px-md rounded font-label-caps text-label-caps flex items-center justify-center gap-sm hover:bg-primary-fixed-dim transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            New Analysis
        </button>
    </div>
    
    <!-- Footer Links -->
    <div class="mt-auto px-sm flex flex-col gap-xs pt-md border-t border-outline-variant mx-sm">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-on-surface-variant hover:text-error px-md py-sm flex items-center gap-md font-label-caps text-label-caps hover:bg-surface-variant/50 transition-all duration-200 rounded">
                <span class="material-symbols-outlined">logout</span>
                Disconnect
            </button>
        </form>
    </div>
</nav>

<!-- Main Content Wrapper -->
<main class="flex-1 md:ml-64 flex flex-col min-w-0">
    <!-- TopNavBar -->
    <header class="bg-surface-container-lowest flex justify-between items-center w-full px-lg py-sm h-16 border-b border-outline-variant sticky top-0 z-30">
        <!-- Mobile Brand -->
        <div class="flex items-center gap-sm md:hidden">
            <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 1;">chess</span>
            <span class="font-headline-md text-headline-md font-bold tracking-tight text-primary">PawnAnalyse</span>
        </div>
        
        <!-- Desktop Context -->
        <div class="hidden md:flex items-center text-on-surface-variant font-data-mono text-data-mono opacity-60">
            <span class="material-symbols-outlined mr-sm text-[18px]">terminal</span>
            > engine_status: optimal
        </div>
        
        <!-- Trailing Actions -->
        <div class="flex items-center gap-md text-primary">
            <button class="p-sm hover:bg-surface-variant rounded-full transition-colors duration-200 flex items-center justify-center relative active:scale-95">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="p-sm hover:bg-surface-variant rounded-full transition-colors duration-200 flex items-center justify-center md:hidden active:scale-95">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </header>

    <!-- Canvas Area -->
    <div class="p-md md:p-xl flex-1 overflow-y-auto w-full max-w-[1400px] mx-auto flex flex-col gap-xl">
        @yield('page_content')
    </div>
</main>
@endsection
