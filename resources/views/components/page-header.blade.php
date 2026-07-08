@props([
    'title',
    'subtitle' => null,
    'backUrl' => null
])

<div class="flex items-center {{ $attributes->get('class') }}">
    @if($backUrl)
        <a href="{{ $backUrl }}" class="mr-4 p-2 hover:bg-surface-container-high rounded-full transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">arrow_back</span>
        </a>
    @endif
    <div class="flex flex-col">
        <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $title }}</h1>
        @if($subtitle)
            <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider mt-0.5">{{ $subtitle }}</span>
        @endif
    </div>
    
    @if(isset($right))
        <div class="ml-auto">
            {{ $right }}
        </div>
    @endif
</div>
