@props([
    'score',
    'type' => 'bar', // 'bar' or 'circle'
    'size' => 'max-w-[100px]' // For bar width or custom sizing
])

@php
    $score = (int) $score;
    
    // Determine color boundaries
    $colorClass = 'bg-green-500';
    $textClass = 'text-green-600';
    $strokeColor = 'text-green-500';
    $statusText = 'OPTIMAL';

    if ($score < 50) {
        $colorClass = 'bg-error';
        $textClass = 'text-error';
        $strokeColor = 'text-error';
        $statusText = 'CRITICAL';
    } elseif ($score < 80) {
        $colorClass = 'bg-orange-500';
        $textClass = 'text-orange-600';
        $strokeColor = 'text-orange-500';
        $statusText = 'ATTENTION';
    }

    // Circular SVG math: 2 * pi * r (r=88) = 552.9
    $circumference = 552.9;
    $dashOffset = $circumference * (1 - $score / 100);
@endphp

@if($type === 'bar')
    <div class="w-full bg-surface-container rounded-full h-2 {{ $size }}">
        <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $score }}%"></div>
    </div>
    <span class="{{ $textClass }} font-bold text-label-md mt-1 block">{{ $score }}%</span>
@elseif($type === 'circle')
    <div class="relative w-48 h-48 flex items-center justify-center">
        <svg class="w-full h-full transform -rotate-90">
            <circle class="text-surface-container" cx="96" cy="96" fill="transparent" r="88" stroke="currentColor" stroke-width="12"></circle>
            <circle class="{{ $strokeColor }} transition-all duration-1000" cx="96" cy="96" fill="transparent" r="88" stroke="currentColor" stroke-dasharray="552.9" stroke-dashoffset="{{ $dashOffset }}" stroke-width="12"></circle>
        </svg>
        <div class="absolute flex flex-col items-center">
            <span class="font-headline-lg text-[48px] leading-none {{ $textClass }}">{{ $score }}%</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant mt-1">{{ $statusText }}</span>
        </div>
    </div>
@endif
