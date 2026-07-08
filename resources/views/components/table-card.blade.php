@props([
    'title',
    'actionText' => null,
    'actionUrl' => '#'
])

<div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
        <h3 class="font-headline-sm text-headline-sm">{{ $title }}</h3>
        
        @if($actionText)
            <a href="{{ $actionUrl }}" class="text-primary font-label-md text-label-md hover:underline">
                {{ $actionText }}
            </a>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>
