@props([
    'title' => 'Segera Hadir',
    'description' => 'Modul ini saat ini sedang dalam pengembangan aktif.',
    'icon' => 'hourglass_empty'
])

<div class="flex flex-col items-center justify-center text-center py-16 bg-surface-container-lowest border border-outline-variant rounded-xl p-8 shadow-sm">
    <span class="material-symbols-outlined text-[64px] text-primary opacity-40 mb-4">{{ $icon }}</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">{{ $title }}</h3>
    <p class="font-body-md text-body-md text-on-surface-variant max-w-md mb-6">{{ $description }}</p>
    <a href="{{ route('dashboard') }}" class="bg-primary hover:bg-primary-container text-on-primary px-6 py-2 rounded-lg font-body-md inline-flex items-center gap-2 transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali ke Dashboard
    </a>
</div>
