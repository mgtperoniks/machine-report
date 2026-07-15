@props([
    'type' => 'success', // 'success', 'warning', 'danger', 'running', 'idle', 'maintenance', 'breakdown', 'stopped', 'low', 'medium', 'high', 'mission_critical'
    'label' => null
])

@php
    $type = strtolower($type);

    $translatedLabel = $label ?? match($type) {
        'running' => 'Beroperasi',
        'idle' => 'Tidak Beroperasi',
        'breakdown' => 'Rusak',
        'maintenance' => 'Dalam Perawatan',
        'stopped' => 'Berhenti',
        'mission_critical' => 'Sangat Kritis',
        'high' => 'Tinggi',
        'medium' => 'Sedang',
        'low' => 'Rendah',
        'critical' => 'Kritis',
        'attention', 'warning' => 'Perlu Perhatian',
        'success', 'operational', 'healthy' => 'Baik',
        default => ucfirst($type)
    };

    if ($label) {
        $cleanLabel = strtolower(trim($label));
        $translatedLabel = match($cleanLabel) {
            'running' => 'Beroperasi',
            'idle' => 'Tidak Beroperasi',
            'breakdown' => 'Rusak',
            'critical' => 'Kritis',
            'maintenance' => 'Dalam Perawatan',
            'stopped' => 'Berhenti',
            'mission_critical' => 'Sangat Kritis',
            'high' => 'Tinggi',
            'medium' => 'Sedang',
            'low' => 'Rendah',
            'attention', 'warning' => 'Perlu Perhatian',
            'success', 'operational', 'healthy', 'baik' => 'Baik',
            default => $label
        };
    }

    $classes = match($type) {
        'danger', 'critical', 'breakdown' => 'bg-error-container text-on-error-container',
        'warning', 'attention', 'maintenance' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'idle' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
        'success', 'operational', 'running' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'mission_critical' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-300',
        'high' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
        'low', 'stopped' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-300',
        default => 'bg-surface-container text-on-surface'
    };

    $dotColor = match($type) {
        'danger', 'critical', 'breakdown' => 'bg-error',
        'warning', 'attention', 'maintenance' => 'bg-blue-500',
        'idle' => 'bg-orange-500',
        'success', 'operational', 'running' => 'bg-green-500',
        'mission_critical' => 'bg-purple-500',
        'high' => 'bg-red-500',
        'medium' => 'bg-yellow-500',
        'low', 'stopped' => 'bg-slate-400',
        default => 'bg-outline'
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-label-sm font-bold uppercase {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
    {{ $translatedLabel }}
</span>
