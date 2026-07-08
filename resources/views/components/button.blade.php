@props([
    'type' => 'button', // 'button', 'submit', 'a'
    'variant' => 'primary', // 'primary', 'secondary', 'danger'
    'icon' => null,
    'href' => null
])

@php
    $isAnchor = ($type === 'a' || $href !== null);
    $baseClasses = 'px-4 py-2 rounded-lg font-body-md flex items-center justify-center gap-2 transition-all active:scale-[0.98]';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-primary hover:bg-primary-container text-on-primary shadow-sm shadow-primary/10',
        'secondary' => 'bg-surface-container-lowest border border-outline text-on-surface hover:bg-surface-container',
        'danger' => 'bg-error text-on-error hover:opacity-90',
        default => 'bg-primary hover:bg-primary-container text-on-primary'
    };
@endphp

@if($isAnchor)
    <a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
        @if($icon)
            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
        @if($icon)
            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
