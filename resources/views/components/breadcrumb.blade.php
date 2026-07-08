@props([
    'items' => []
])

<nav class="flex text-on-surface-variant font-label-md text-label-md mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                <span class="material-symbols-outlined mr-1 text-[16px]">home</span>
                Home
            </a>
        </li>
        @foreach($items as $label => $url)
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-[16px] mx-1 opacity-50">chevron_right</span>
                    @if($loop->last || !$url)
                        <span class="font-semibold text-primary">{{ $label }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-primary transition-colors">{{ $label }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
