@php
    if (!function_exists('getSortUrl')) {
        function getSortUrl($column, $currentSortBy, $currentSortOrder) {
            $params = request()->query();
            $params['sort_by'] = $column;
            $params['sort_order'] = ($currentSortBy === $column && $currentSortOrder === 'asc') ? 'desc' : 'asc';
            return route('machines.index', $params);
        }
    }

    $critLabels = [
        'mission_critical' => 'Sangat Kritis',
        'high' => 'Tinggi',
        'medium' => 'Sedang',
        'low' => 'Rendah'
    ];

    $statusLabels = [
        'running' => 'Beroperasi',
        'idle' => 'Tidak Beroperasi',
        'maintenance' => 'Dalam Perawatan',
        'breakdown' => 'Rusak',
        'stopped' => 'Berhenti'
    ];
@endphp

<x-layouts.app 
    title="Daftar Mesin | Sistem MRM"
    topbar-title="Daftar Mesin"
    :subnav="['Aktif' => route('machines.index', ['status_filter' => 'ACTIVE']), 'Nonaktif' => route('machines.index', ['status_filter' => 'INACTIVE']), 'Pensiun' => route('machines.index', ['status_filter' => 'RETIRED']), 'Semua' => route('machines.index', ['status_filter' => 'ALL'])]"
    active-subnav="{{ $statusFilter === 'ACTIVE' ? 'Aktif' : ($statusFilter === 'INACTIVE' ? 'Nonaktif' : ($statusFilter === 'RETIRED' ? 'Pensiun' : 'Semua')) }}"
>
    <!-- Breadcrumbs -->
    <x-breadcrumb :items="['Daftar Mesin' => route('machines.index')]" />

    <x-page-header title="Daftar Mesin" subtitle="Total: {{ $machines->count() }} Unit Peralatan Terdaftar" class="mb-6">
        <x-slot name="right">
            <x-button variant="primary" icon="add" href="{{ route('machines.create') }}">
                Tambah Mesin Baru
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Registry Table -->
        <div class="col-span-12 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            
            <!-- Filters & Search Form -->
            <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
                <form method="GET" action="{{ route('machines.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Keep current sort parameter -->
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}"/>
                    <input type="hidden" name="sort_order" value="{{ $sortOrder }}"/>
                    <input type="hidden" name="status_filter" value="{{ $statusFilter }}"/>

                    <!-- Search bar -->
                    <div class="md:col-span-3">
                        <label class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Cari Kata Kunci</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                            <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md text-sm" placeholder="Kode, Nama, Model..." type="text"/>
                        </div>
                    </div>
                    
                    <!-- Department Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Departemen</label>
                        <select name="department" class="w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md text-sm">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Kategori</label>
                        <select name="category" class="w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Criticality Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Tingkat Prioritas</label>
                        <select name="criticality" class="w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md text-sm">
                            <option value="">Semua Prioritas</option>
                            @foreach($criticalities as $crit)
                                <option value="{{ $crit }}" {{ request('criticality') === $crit ? 'selected' : '' }}>{{ $critLabels[$crit] ?? ucfirst($crit) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Status Mesin</label>
                        <select name="operational_status" class="w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md text-sm">
                            <option value="">Semua Status</option>
                            @foreach($operationalStatuses as $status)
                                <option value="{{ $status }}" {{ request('operational_status') === $status ? 'selected' : '' }}>{{ $statusLabels[$status] ?? ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-1 flex gap-2">
                        <button type="submit" class="flex-1 bg-primary text-on-primary hover:bg-primary/90 p-2 rounded-lg flex items-center justify-center shadow-sm" title="Terapkan Filter">
                            <span class="material-symbols-outlined">filter_list</span>
                        </button>
                        @if(request()->anyFilled(['search', 'department', 'category', 'criticality', 'operational_status']))
                            <a href="{{ route('machines.index') }}" class="flex-1 bg-surface-container border border-outline-variant hover:bg-surface-container-high p-2 rounded-lg flex items-center justify-center" title="Reset Filter">
                                <span class="material-symbols-outlined">filter_list_off</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            
            <!-- Dynamic Machine Table List -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-bright border-b border-outline-variant">
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider w-16">Foto</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <a href="{{ getSortUrl('code', $sortBy, $sortOrder) }}" class="flex items-center gap-1 hover:text-primary transition-colors">
                                    Kode Mesin
                                    @if($sortBy === 'code')
                                        <span class="material-symbols-outlined text-[16px]">{{ $sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Model / Produsen</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <a href="{{ getSortUrl('department', $sortBy, $sortOrder) }}" class="flex items-center gap-1 hover:text-primary transition-colors">
                                    Departemen & Area
                                    @if($sortBy === 'department')
                                        <span class="material-symbols-outlined text-[16px]">{{ $sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <a href="{{ getSortUrl('category', $sortBy, $sortOrder) }}" class="flex items-center gap-1 hover:text-primary transition-colors">
                                    Kategori
                                    @if($sortBy === 'category')
                                        <span class="material-symbols-outlined text-[16px]">{{ $sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <a href="{{ getSortUrl('criticality', $sortBy, $sortOrder) }}" class="flex items-center gap-1 hover:text-primary transition-colors">
                                    Prioritas
                                    @if($sortBy === 'criticality')
                                        <span class="material-symbols-outlined text-[16px]">{{ $sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <a href="{{ getSortUrl('operational_status', $sortBy, $sortOrder) }}" class="flex items-center gap-1 hover:text-primary transition-colors">
                                    Status
                                    @if($sortBy === 'operational_status')
                                        <span class="material-symbols-outlined text-[16px]">{{ $sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <a href="{{ getSortUrl('health', $sortBy, $sortOrder) }}" class="flex items-center gap-1 hover:text-primary transition-colors">
                                    Kondisi
                                    @if($sortBy === 'health')
                                        <span class="material-symbols-outlined text-[16px]">{{ $sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status PM</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Sparepart</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Siklus</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($machines as $machine)
                            <tr class="hover:bg-surface-container-low transition-colors @if(!$machine->is_active || $machine->lifecycle_status !== 'ACTIVE') opacity-60 bg-surface-container-low italic text-on-surface-variant @endif">
                                <!-- Machine Photo -->
                                <td class="px-6 py-4">
                                    @php
                                        $primaryPhoto = $machine->photos->where('type', 'overall')->first();
                                    @endphp
                                    @if($primaryPhoto && $primaryPhoto->file_path)
                                        <img src="{{ asset($primaryPhoto->file_path) }}" alt="{{ $machine->name }}" class="w-10 h-10 object-cover rounded border border-outline-variant shadow-sm"/>
                                    @else
                                        <div class="w-10 h-10 rounded border border-outline-variant bg-surface-container flex items-center justify-center text-on-surface-variant" title="Dokumen Belum Diunggah">
                                            <span class="material-symbols-outlined text-[20px]">image_not_supported</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Code -->
                                <td class="px-6 py-4 mono text-body-sm font-bold text-primary">
                                    {{ $machine->code }}
                                </td>

                                <!-- Name -->
                                <td class="px-6 py-4 font-body-md font-semibold text-on-surface">
                                    {{ $machine->name }}
                                </td>

                                <!-- Model / Manufacturer -->
                                <td class="px-6 py-4 font-body-sm text-on-surface-variant">
                                    {{ $machine->model }} / {{ $machine->manufacturer }}
                                </td>

                                <!-- Department & Area -->
                                <td class="px-6 py-4 font-body-sm">
                                    <span class="font-semibold block text-on-surface">{{ $machine->department }}</span>
                                    <span class="text-xs text-on-surface-variant">{{ $machine->production_area }}</span>
                                </td>

                                <!-- Category -->
                                <td class="px-6 py-4 font-body-sm text-on-surface">
                                    {{ $machine->category }}
                                </td>

                                <!-- Criticality -->
                                <td class="px-6 py-4">
                                    <x-status-badge :type="$machine->criticality" />
                                </td>

                                <!-- Operational Status -->
                                <td class="px-6 py-4">
                                    <x-status-badge :type="$machine->operational_status" />
                                </td>

                                <!-- Health Score (Calculated, not persisted) -->
                                <td class="px-6 py-4">
                                    <x-health-score :score="$machine->health_score" type="bar" />
                                </td>

                                <!-- PM Status (Placeholder) -->
                                <td class="px-6 py-4">
                                    @php
                                        // Mocks based on operational status
                                        $pmType = match($machine->operational_status) {
                                            'breakdown' => 'danger',
                                            'maintenance' => 'warning',
                                            default => 'success'
                                        };
                                        $pmLabel = match($machine->operational_status) {
                                            'breakdown' => 'Terlambat',
                                            'maintenance' => 'Dalam Proses',
                                            default => 'Sesuai Jadwal'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold py-0.5 px-2 rounded @if($pmType === 'danger') text-red-700 bg-red-50 @elseif($pmType === 'warning') text-blue-700 bg-blue-50 @else text-green-700 bg-green-50 @endif">
                                        {{ $pmLabel }}
                                    </span>
                                </td>

                                <!-- Sparepart Readiness (Placeholder) -->
                                <td class="px-6 py-4">
                                    @php
                                        // Mock readiness based on code
                                        $readinessLabel = match($machine->code) {
                                            'CNC-08' => 'Tersedia',
                                            'CNC-04' => 'Peringatan Stok',
                                            default => 'Tersedia'
                                        };
                                        $readinessColor = match($machine->code) {
                                            'CNC-04' => 'text-orange-700 bg-orange-50',
                                            default => 'text-green-700 bg-green-50'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center text-xs font-semibold py-0.5 px-2 rounded {{ $readinessColor }}">
                                        {{ $readinessLabel }}
                                    </span>
                                </td>

                                <!-- Lifecycle status badge -->
                                <td class="px-6 py-4">
                                    <x-status-badge type="{{ $machine->lifecycle_status === 'ACTIVE' ? 'success' : ($machine->lifecycle_status === 'INACTIVE' ? 'warning' : 'low') }}" label="{{ $machine->lifecycle_status === 'ACTIVE' ? 'Aktif' : ($machine->lifecycle_status === 'INACTIVE' ? 'Nonaktif' : 'Pensiun') }}" />
                                </td>

                                <!-- Passport Button -->
                                <td class="px-6 py-4 text-right">
                                    <x-button variant="secondary" icon="chevron_right" href="{{ route('machines.show', $machine->code) }}" class="p-1 px-2 text-[14px]">
                                        Paspor
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-12 text-center">
                                    <x-empty-state 
                                        title="Mesin Tidak Ditemukan"
                                        description="Kami tidak dapat menemukan mesin yang sesuai dengan pencarian atau filter Anda. Silakan ubah kata kunci pencarian atau filter."
                                        icon="precision_manufacturing"
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
