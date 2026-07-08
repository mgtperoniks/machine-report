@props([
    'title',
    'value',
    'icon' => null,
    'border' => null,
    'badgeText' => null,
    'badgeType' => 'success', // 'success', 'warning', 'danger'
    'valueColor' => 'text-on-surface'
])

@php
    $badgeClasses = match($badgeType) {
        'success' => 'text-green-600 bg-green-50',
        'warning' => 'text-orange-600 bg-orange-50',
        'danger' => 'text-error bg-error-container',
        default => 'text-on-surface bg-surface-container'
    };
@endphp

<div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-lg flex flex-col gap-1 {{ $border }}">
    <span class="text-on-surface-variant font-label-md text-label-md">{{ $title }}</span>
    <div class="flex items-end justify-between">
        <span class="text-headline-md font-headline-md {{ $valueColor }}">{{ $value }}</span>
        
        @if($badgeText)
            <span class="font-label-sm text-label-sm px-1.5 py-0.5 rounded {{ $badgeClasses }}">{{ $badgeText }}</span>
        @elseif($icon)
            <span class="material-symbols-outlined {{ $valueColor === 'text-error' ? 'text-error' : 'text-primary' }}" data-icon="{{ $icon }}">{{ $icon }}</span>
        @endif
    </div>
</div>
