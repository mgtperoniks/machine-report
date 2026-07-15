<x-layouts.app 
    title="Papan Perencanaan Perawatan | Sistem MRM"
    topbar-title="Perencanaan Perawatan"
>
    <x-breadcrumb :items="['Perencanaan' => '']" />

    @php
        // Dynamic Calendar Calculation
        $month = (int) request('month', now()->month);
        $year = (int) request('year', now()->year);
        $currentDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;
        // ISO-8601 day of week: 1 (Monday) to 7 (Sunday)
        $startDayOfWeek = $startOfMonth->dayOfWeekIso; 

        // Indonesian Month names
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

    <!-- Dashboard Header Info -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-headline-md text-headline-md text-on-surface">Papan Perencanaan PM</h1>
            <p class="text-body-md text-on-surface-variant">Kelola SOP (Paket Perawatan) dan jadwalkan inspeksi preventif berkala untuk menjamin keandalan manufaktur.</p>
        </div>
    </div>

    <!-- Top KPI Dashboard Grid -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">calendar_today</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase font-semibold">Total Rencana</p>
                <h3 class="text-headline-md font-headline-md">{{ $totalCount }}</h3>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-green-500">
            <div class="w-12 h-12 rounded-lg bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">check_circle</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase font-semibold">Siap Eksekusi</p>
                <h3 class="text-headline-md font-headline-md text-green-600">{{ $readyCount }}</h3>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-orange-500">
            <div class="w-12 h-12 rounded-lg bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">pending</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase font-semibold">Hampir Siap</p>
                <h3 class="text-headline-md font-headline-md text-orange-600">{{ $almostReadyCount }}</h3>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-error">
            <div class="w-12 h-12 rounded-lg bg-error-container text-on-error-container flex items-center justify-center animate-pulse">
                <span class="material-symbols-outlined text-[28px]">block</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase font-semibold">Terblokir</p>
                <h3 class="text-headline-md font-headline-md text-error">{{ $blockedCount }}</h3>
            </div>
        </div>
    </section>

    <!-- Priority Alerts Area -->
    @php
        $todayBlocked = $todayPlans->filter(fn($p) => $p->readiness['overall_status'] === 'Blocked');
    @endphp
    @if($todayBlocked->count() > 0)
        <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-xl border border-error flex items-start gap-4 shadow-sm">
            <span class="material-symbols-outlined text-[28px] mt-0.5" style="font-variation-settings: 'FILL' 1;">emergency</span>
            <div class="flex-1">
                <h4 class="font-headline-sm text-headline-sm text-error font-bold mb-1">Perhatian: Ada Rencana Terjadwal Hari Ini yang Terblokir!</h4>
                <p class="text-body-md mb-2">Sebanyak {{ $todayBlocked->count() }} rencana pemeliharaan preventif yang dijadwalkan hari ini terhambat oleh masalah ketersediaan suku cadang atau kondisi mesin.</p>
                <div class="space-y-1.5 mt-2 bg-white/20 p-3 rounded-lg">
                    @foreach($todayBlocked as $plan)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-body-sm">
                            <span class="font-semibold">{{ $plan->machine->code }} — {{ $plan->maintenanceTemplate->name }}</span>
                            <span class="text-xs bg-error text-white font-bold px-2 py-0.5 rounded uppercase mt-1 sm:mt-0">
                                @if(in_array($plan->machine->operational_status, ['breakdown', 'maintenance']))
                                    Mesin Down
                                @else
                                    Stok WMS Kosong
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Filtering Panel & Board Toggles -->
    <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl shadow-sm mb-8">
        <form method="GET" action="{{ route('planning.index') }}" class="flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Search Input -->
                <div class="md:col-span-4 relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant">search</span>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari mesin, paket perawatan, catatan..." 
                        class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg pl-10 pr-4 py-2 text-body-md focus:outline-none focus:border-primary"
                    />
                </div>

                <!-- Priority Filter -->
                <div class="md:col-span-2">
                    <select name="priority" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-body-md focus:outline-none focus:border-primary">
                        <option value="">Semua Prioritas</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>

                <!-- Readiness Status Filter -->
                <div class="md:col-span-3">
                    <select name="readiness_status" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-body-md focus:outline-none focus:border-primary">
                        <option value="">Semua Audit Kesiapan</option>
                        <option value="Ready" {{ request('readiness_status') === 'Ready' ? 'selected' : '' }}>Siap Eksekusi</option>
                        <option value="Almost Ready" {{ request('readiness_status') === 'Almost Ready' ? 'selected' : '' }}>Hampir Siap</option>
                        <option value="Blocked" {{ request('readiness_status') === 'Blocked' ? 'selected' : '' }}>Terblokir</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-2">
                    <select name="status" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-body-md focus:outline-none focus:border-primary">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
                        <option value="waiting_approval" {{ request('status') === 'waiting_approval' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                    </select>
                </div>

                <!-- Submit / Reset Actions -->
                <div class="md:col-span-1 flex gap-2">
                    <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-lg text-body-md font-bold flex-1 flex justify-center items-center">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'priority', 'readiness_status', 'status']))
                        <a href="{{ route('planning.index') }}" class="bg-surface-container border border-outline-variant hover:bg-surface-container-high px-3 py-2 rounded-lg flex items-center justify-center" title="Reset Filter">
                            <span class="material-symbols-outlined">restart_alt</span>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Tabs Toggle: Timeline Board vs Calendar Board -->
    <div class="tabs-container bg-surface-container-low border border-outline-variant p-1 rounded-xl flex gap-1 mb-6 max-w-md">
        <button id="tab-btn-timeline" class="flex-1 py-2 text-center text-label-md font-semibold rounded-lg bg-surface-container-lowest text-primary shadow-sm transition-all focus:outline-none" onclick="switchView('timeline')">
            <span class="flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">view_timeline</span>
                Tampilan Daftar
            </span>
        </button>
        <button id="tab-btn-calendar" class="flex-1 py-2 text-center text-label-md font-semibold rounded-lg text-on-surface-variant hover:bg-surface-container-lowest/50 transition-all focus:outline-none" onclick="switchView('calendar')">
            <span class="flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                Tampilan Kalender
            </span>
        </button>
    </div>

    <!-- VIEW 1: TIMELINE / CARD LIST VIEW -->
    <div id="view-timeline" class="space-y-6">
        @if($plans->count() > 0)
            <div class="relative pl-6 sm:pl-8 before:absolute before:left-3 sm:before:left-4 before:top-2 before:bottom-2 before:w-0.5 before:bg-outline-variant space-y-6">
                @foreach($plans as $plan)
                    @php
                        $rd = $plan->readiness;
                        $statusText = $rd['overall_status'];
                        
                        $statusBadgeClass = match($statusText) {
                            'Completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200',
                            'Waiting Review' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200',
                            'Ready' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200',
                            'Almost Ready' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 border border-orange-200',
                            'Blocked' => 'bg-error-container text-on-error-container border border-error/20',
                        };

                        $statusLabel = match($statusText) {
                            'Completed' => 'Selesai',
                            'Waiting Review' => 'Menunggu Review',
                            'Ready' => 'Siap Eksekusi',
                            'Almost Ready' => 'Hampir Siap',
                            'Blocked' => 'Terblokir',
                        };

                        $priorityBadgeClass = match($plan->priority) {
                            'low' => 'bg-surface-container text-on-surface-variant',
                            'medium' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300',
                            'high' => 'bg-orange-50 text-orange-700 dark:bg-orange-900/20 dark:text-orange-300',
                            'critical' => 'bg-error-container text-on-error-container font-bold',
                        };

                        $priorityLabel = match($plan->priority) {
                            'low' => 'Rendah',
                            'medium' => 'Sedang',
                            'high' => 'Tinggi',
                            'critical' => 'Kritis',
                        };
                    @endphp

                    <!-- Timeline Event Block -->
                    <div class="relative">
                        <!-- Left Node Dot -->
                        <div class="absolute -left-[30px] sm:-left-[38px] top-4 w-4 h-4 rounded-full border-4 border-surface-container-lowest ring-4 ring-white shadow-sm
                            {{ $statusText === 'Completed' || $statusText === 'Ready' ? 'bg-green-500' : ($statusText === 'Waiting Review' ? 'bg-blue-500' : ($statusText === 'Almost Ready' ? 'bg-orange-500' : 'bg-error')) }}">
                        </div>

                        <!-- Card Body -->
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-primary/30 transition-all">
                            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <span class="mono text-body-sm font-semibold text-primary px-2 py-0.5 bg-primary/10 rounded">{{ $plan->machine->code }}</span>
                                        <span class="text-body-sm text-on-surface-variant font-medium">{{ $plan->machine->name }}</span>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-label-sm font-bold uppercase {{ $priorityBadgeClass }}">
                                            {{ $priorityLabel }}
                                        </span>
                                    </div>
                                    <h3 class="font-headline-sm text-headline-sm text-on-surface">
                                        Paket Perawatan: {{ $plan->maintenanceTemplate->name }}
                                    </h3>
                                    <p class="text-body-sm text-on-surface-variant mt-1">Jadwal: <span class="font-semibold">{{ $plan->scheduled_date->format('d M Y') }}</span></p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-label-sm font-bold uppercase {{ $statusBadgeClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusText === 'Completed' || $statusText === 'Ready' ? 'bg-green-500' : ($statusText === 'Waiting Review' ? 'bg-blue-500' : ($statusText === 'Almost Ready' ? 'bg-orange-500' : 'bg-error')) }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                    <a href="{{ route('planning.show', $plan->id) }}" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-lg text-body-sm font-bold inline-flex items-center gap-1.5 transition-colors">
                                        Audit Kesiapan
                                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                    </a>
                                </div>
                            </div>

                            <hr class="border-outline-variant my-3" />

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-body-sm text-on-surface-variant">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">timer</span>
                                    <span>Durasi Est: <strong>{{ $plan->maintenanceTemplate->estimated_duration }} menit</strong></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">engineering</span>
                                    <span>Teknisi: <strong>{{ $plan->assigned_technician ?? 'Belum Ditugaskan' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">smart_toy</span>
                                    <span>Sistem Pembuat: <strong>{{ $plan->generation_source }}</strong></span>
                                </div>
                            </div>

                            @if($plan->notes)
                                <div class="mt-4 p-3 bg-surface-container-low rounded-lg text-body-sm text-on-surface-variant italic">
                                    "{{ $plan->notes }}"
                                </div>
                            @endif

                            <!-- Display Blockers Summary -->
                            @if($statusText === 'Blocked' && count($rd['blockers']) > 0)
                                <div class="mt-4 flex flex-col gap-1 border-t border-error/15 pt-3">
                                    <span class="text-label-sm text-error font-bold uppercase flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span> Hambatan Utama (Blockers)
                                    </span>
                                    <ul class="list-disc pl-5 text-xs text-error space-y-0.5">
                                        @foreach($rd['blockers'] as $blocker)
                                            <li>{{ $blocker }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif($statusText === 'Almost Ready' && count($rd['warnings']) > 0)
                                <div class="mt-4 flex flex-col gap-1 border-t border-orange-300/30 pt-3">
                                    <span class="text-label-sm text-orange-600 font-bold uppercase flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">warning</span> Persiapan Kurang (Warnings)
                                    </span>
                                    <ul class="list-disc pl-5 text-xs text-orange-700 space-y-0.5">
                                        @foreach($rd['warnings'] as $warning)
                                            <li>{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state 
                title="Tidak Ada Rencana Perawatan" 
                description="Tidak ada data rencana perawatan preventif yang cocok dengan kriteria filter saat ini." 
                icon="search_off"
            />
        @endif
    </div>

    <!-- VIEW 2: CALENDAR VIEW GRID -->
    <div id="view-calendar" class="hidden">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <!-- Calendar Navigation Header -->
            <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">
                    {{ $namaBulan[$month] }} {{ $year }}
                </h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('planning.index', array_merge(request()->all(), ['month' => $prevMonth->month, 'year' => $prevMonth->year])) }}" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                    <a href="{{ route('planning.index', array_merge(request()->all(), ['month' => now()->month, 'year' => now()->year])) }}" class="px-3 py-1 rounded border border-outline-variant text-label-md hover:bg-surface-container transition-colors">
                        Hari Ini
                    </a>
                    <a href="{{ route('planning.index', array_merge(request()->all(), ['month' => $nextMonth->month, 'year' => $nextMonth->year])) }}" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Calendar Grid Layout -->
            <div class="grid grid-cols-7 border-b border-outline-variant bg-surface-container-low text-center text-label-sm font-semibold text-on-surface-variant py-2">
                <div>Sen</div>
                <div>Sel</div>
                <div>Rab</div>
                <div>Kam</div>
                <div>Jum</div>
                <div>Sab</div>
                <div>Min</div>
            </div>

            <div class="grid grid-cols-7 bg-surface-bright divide-x divide-y divide-outline-variant border-collapse min-h-[350px]">
                <!-- Blank days offset -->
                @for($i = 1; $i < $startDayOfWeek; $i++)
                    <div class="bg-surface-container-low/30 min-h-[100px] p-2"></div>
                @endfor

                <!-- Month days -->
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $dayPlans = $plans->filter(fn($p) => $p->scheduled_date->format('Y-m-d') === $dateString);
                        $isToday = now()->format('Y-m-d') === $dateString;
                    @endphp
                    <div class="min-h-[100px] p-2 bg-surface-container-lowest relative group flex flex-col justify-between">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-label-md font-bold px-1.5 py-0.5 rounded-full {{ $isToday ? 'bg-primary text-on-primary' : 'text-on-surface-variant' }}">
                                {{ $day }}
                            </span>
                        </div>
                        <div class="flex-1 flex flex-col justify-end gap-1 overflow-hidden mt-1">
                            @foreach($dayPlans as $p)
                                @php
                                    $rdClass = match($p->readiness['overall_status']) {
                                        'Completed' => 'bg-green-600 hover:bg-green-700',
                                        'Waiting Review' => 'bg-blue-500 hover:bg-blue-600',
                                        'Ready' => 'bg-green-500 hover:bg-green-600',
                                        'Almost Ready' => 'bg-orange-500 hover:bg-orange-600',
                                        'Blocked' => 'bg-error hover:bg-error/95',
                                    };
                                @endphp
                                <a 
                                    href="{{ route('planning.show', $p->id) }}" 
                                    class="text-[10px] text-white font-semibold truncate rounded px-1.5 py-0.5 flex items-center justify-between gap-1 shadow-sm transition-all hover:scale-[1.02] {{ $rdClass }}"
                                    title="{{ $p->machine->code }} - {{ $p->maintenanceTemplate->name }} ({{ $p->readiness['overall_status'] }})"
                                >
                                    <span>{{ $p->machine->code }}</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endfor

                <!-- Blank days at end of week -->
                @php
                    $lastDayOfWeek = \Carbon\Carbon::createFromDate($year, $month, $daysInMonth)->dayOfWeekIso;
                    $endOffset = 7 - $lastDayOfWeek;
                @endphp
                @for($i = 0; $i < $endOffset; $i++)
                    <div class="bg-surface-container-low/30 min-h-[100px] p-2"></div>
                @endfor
            </div>
        </div>

        <!-- Legend Card -->
        <div class="mt-4 p-4 border border-outline-variant rounded-xl bg-surface-container-lowest shadow-sm flex flex-wrap gap-6 text-label-sm font-semibold text-on-surface-variant">
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-green-600"></span> Selesai</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-blue-500"></span> Menunggu Review</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-green-500"></span> Siap Eksekusi</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-orange-500"></span> Hampir Siap</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-error"></span> Terblokir (Masalah Ketersediaan / Mesin Rusak)</span>
        </div>
    </div>

    <!-- UI State Navigation Script -->
    <script>
        function switchView(view) {
            const btnTimeline = document.getElementById('tab-btn-timeline');
            const btnCalendar = document.getElementById('tab-btn-calendar');
            const viewTimeline = document.getElementById('view-timeline');
            const viewCalendar = document.getElementById('view-calendar');

            if (view === 'timeline') {
                btnTimeline.classList.add('bg-surface-container-lowest', 'text-primary', 'shadow-sm');
                btnTimeline.classList.remove('text-on-surface-variant', 'hover:bg-surface-container-lowest/50');
                
                btnCalendar.classList.remove('bg-surface-container-lowest', 'text-primary', 'shadow-sm');
                btnCalendar.classList.add('text-on-surface-variant', 'hover:bg-surface-container-lowest/50');
                
                viewTimeline.classList.remove('hidden');
                viewCalendar.classList.add('hidden');
                localStorage.setItem('mrm_planning_view', 'timeline');
            } else {
                btnCalendar.classList.add('bg-surface-container-lowest', 'text-primary', 'shadow-sm');
                btnCalendar.classList.remove('text-on-surface-variant', 'hover:bg-surface-container-lowest/50');
                
                btnTimeline.classList.remove('bg-surface-container-lowest', 'text-primary', 'shadow-sm');
                btnTimeline.classList.add('text-on-surface-variant', 'hover:bg-surface-container-lowest/50');
                
                viewCalendar.classList.remove('hidden');
                viewTimeline.classList.add('hidden');
                localStorage.setItem('mrm_planning_view', 'calendar');
            }
        }

        // Restore view preferences from session storage
        document.addEventListener('DOMContentLoaded', () => {
            const savedView = localStorage.getItem('mrm_planning_view');
            if (savedView === 'calendar' || {{ request()->has('month') ? 'true' : 'false' }}) {
                switchView('calendar');
            } else {
                switchView('timeline');
            }
        });
    </script>
</x-layouts.app>
