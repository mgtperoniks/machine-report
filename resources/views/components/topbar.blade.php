@props([
    'title' => 'MRM System',
    'subnav' => [],
    'activeSubnav' => null
])

<header class="fixed top-0 left-0 right-0 lg:left-52 h-16 bg-surface-bright dark:bg-surface-dim border-b border-outline-variant dark:border-outline flex justify-between items-center px-4 lg:px-margin-desktop z-40 shadow-sm dark:shadow-none">
    <div class="flex items-center gap-3 lg:gap-8 min-w-0">
        <!-- Hamburger (mobile only) -->
        <button id="hamburger-btn" onclick="openMobileDrawer()" class="lg:hidden shrink-0 w-10 h-10 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors" aria-label="Buka Menu">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface truncate">{{ $title }}</h2>

        @if(!empty($subnav))
            <nav class="hidden md:flex gap-6 shrink-0">
                @foreach($subnav as $label => $url)
                    @php $isActive = ($activeSubnav === $label); @endphp
                    <a class="{{ $isActive ? 'text-primary border-b-2 border-primary pb-1 font-semibold' : 'text-on-surface-variant hover:text-primary' }} transition-all font-body-md text-body-md whitespace-nowrap"
                       href="{{ $url }}">{{ $label }}</a>
                @endforeach
            </nav>
        @endif
    </div>

    <div class="flex items-center gap-2 lg:gap-4 shrink-0">
        <!-- Emergency Report Button: icon-only on mobile, full on desktop -->
        <a href="{{ route('breakdowns.index') }}" class="bg-primary hover:bg-primary-container text-on-primary h-10 px-3 lg:px-4 rounded-lg font-body-md flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-[20px]">warning</span>
            <span class="hidden sm:inline">Lapor Darurat</span>
        </a>

        <div class="hidden lg:block h-8 w-px bg-outline-variant"></div>

        <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center text-on-primary cursor-pointer hover:brightness-110 transition-all">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">account_circle</span>
        </div>
    </div>
</header>
