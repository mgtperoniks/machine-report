@props([
    'type' => 'success', // 'success', 'warning', 'danger'
    'label'
])

@php
    $classes = match($type) {
        'danger', 'critical' => 'bg-error-container text-on-error-container',
        'warning', 'attention' => 'bg-orange-100 text-orange-800',
        'success', 'operational' => 'bg-green-100 text-green-800',
        default => 'bg-surface-container text-on-surface'
    };

    $dotColor = match($type) {
        'danger', 'critical' => 'bg-error',
        'warning', 'attention' => 'bg-orange-500',
        'success', 'operational' => 'bg-green-500',
        default => 'bg-outline'
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-label-sm font-bold uppercase {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
    {{ $label }}
</span>
