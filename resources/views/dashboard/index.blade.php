<x-layouts.app 
    title="Dashboard | Sistem MRM"
    topbar-title="Dashboard"
    :subnav="['Ikhtisar' => route('dashboard'), 'Riwayat Medis' => '#', 'Sparepart' => '#', 'Dokumen' => '#']"
    active-subnav="Ikhtisar"
>
    <!-- KPI Cards Grid -->
    <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <x-metric-card title="Total Mesin" value="{{ $totalMachines }}" icon="precision_manufacturing" />
        <x-metric-card title="Mesin Sehat" value="{{ $healthyMachines }}" badge-text="{{ $totalMachines > 0 ? round(($healthyMachines / $totalMachines) * 100) . '%' : '0%' }}" badge-type="success" border="border-l-4 border-l-green-500" />
        <x-metric-card title="Jadwal Perawatan" value="{{ $maintenanceDue }}" icon="event_busy" border="border-l-4 border-l-orange-500" value-color="text-orange-500" />
        <x-metric-card title="Downtime (Bln)" value="14.2j" icon="timer" value-color="text-on-surface-variant opacity-50" />
        <x-metric-card title="Kerusakan" value="{{ $breakdowns }}" icon="error" border="border-l-4 border-l-error" value-color="text-error" />
        <x-metric-card title="Peringatan Part" value="5" icon="inventory" />
    </section>

    <!-- Widgets Bento Grid -->
    <div class="grid grid-cols-12 gap-6 items-start">
        <!-- 5 Mesin Prioritas -->
        <x-table-card title="5 Mesin Prioritas" action-text="Lihat Semua Daftar" :action-url="route('machines.index')">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-bright border-b border-outline-variant">
                        <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Kode Mesin</th>
                        <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Kondisi Mesin</th>
                        <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($sickestMachines as $machine)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-semibold text-primary">{{ $machine->code }}</td>
                            <td class="px-6 py-4 font-body-md">{{ $machine->name }}</td>
                            <td class="px-6 py-4">
                                <x-health-score :score="$machine->health_score" type="bar" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :type="$machine->operational_status" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('machines.show', $machine->code) }}" class="text-primary-container material-symbols-outlined" data-icon="chevron_right">chevron_right</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant italic">Belum ada mesin terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-table-card>

        <!-- Health Distribution Chart -->
        <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
            <h3 class="font-headline-sm text-headline-sm mb-6">Distribusi Kondisi</h3>
            <div class="flex flex-col items-center justify-center py-4 relative">
                <!-- Simple SVG Donut -->
                <svg class="w-48 h-48 -rotate-90" viewbox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e0e3e5" stroke-width="3"></path>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#22c55e" stroke-dasharray="82, 100" stroke-width="3"></path>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f97316" stroke-dasharray="12, 100" stroke-dashoffset="-82" stroke-width="3"></path>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#ba1a1a" stroke-dasharray="6, 100" stroke-dashoffset="-94" stroke-width="3"></path>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center pt-4">
                    <span class="font-headline-md text-headline-md">{{ $totalMachines }}</span>
                    <span class="font-label-md text-label-md opacity-50">Unit</span>
                </div>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex justify-between items-center text-body-md">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Baik</div>
                    <span class="mono font-bold">82%</span>
                </div>
                <div class="flex justify-between items-center text-body-md">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Perlu Perhatian</div>
                    <span class="mono font-bold">12%</span>
                </div>
                <div class="flex justify-between items-center text-body-md">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-error"></span> Kritis</div>
                    <span class="mono font-bold">6%</span>
                </div>
            </div>
        </div>

        <!-- Maintenance Compliance -->
        <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
            <h3 class="font-headline-sm text-headline-sm mb-4">Kepatuhan Perawatan</h3>
            <div class="flex flex-col items-center">
                <div class="relative w-40 h-40">
                    <svg class="w-full h-full -rotate-90" viewbox="0 0 36 36">
                        <circle cx="18" cy="18" fill="none" r="16" stroke="#e0e3e5" stroke-width="2.5"></circle>
                        <circle cx="18" cy="18" fill="none" r="16" stroke="#1E40AF" stroke-dasharray="94, 100" stroke-linecap="round" stroke-width="2.5"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="font-headline-lg text-headline-lg text-primary">94%</span>
                        <span class="text-label-sm text-label-sm uppercase opacity-50 font-bold">Target: 90%</span>
                    </div>
                </div>
                <p class="mt-6 text-center text-body-md text-on-surface-variant">
                    Tingkat kepatuhan tinggi dipertahankan di semua 3 shift bulan ini. 
                </p>
            </div>
        </div>

        <!-- Upcoming Maintenance Timeline -->
        <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
            <h3 class="font-headline-sm text-headline-sm mb-6">Jadwal Perawatan Terdekat</h3>
            <div class="space-y-6 relative before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-outline-variant">
                <div class="relative pl-8">
                    <div class="absolute left-0 top-1.5 w-5 h-5 rounded-full bg-surface-container-lowest border-2 border-primary"></div>
                    <div class="flex flex-col">
                        <span class="font-label-sm text-label-sm text-primary font-bold">HARI INI, 14:00</span>
                        <span class="font-body-md text-body-md font-semibold text-on-surface">CNC-04: Full System Calib</span>
                        <span class="text-body-sm text-on-surface-variant">Teknisi: R. Thompson</span>
                    </div>
                </div>
                <div class="relative pl-8 opacity-60">
                    <div class="absolute left-0 top-1.5 w-5 h-5 rounded-full bg-surface-container-lowest border-2 border-outline"></div>
                    <div class="flex flex-col">
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">24 MEI, 09:00</span>
                        <span class="font-body-md text-body-md font-semibold text-on-surface">DRL-19: Bearing Replacement</span>
                        <span class="text-body-sm text-on-surface-variant">Teknisi: S. Chen</span>
                    </div>
                </div>
                <div class="relative pl-8 opacity-60">
                    <div class="absolute left-0 top-1.5 w-5 h-5 rounded-full bg-surface-container-lowest border-2 border-outline"></div>
                    <div class="flex flex-col">
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">25 MEI, 08:30</span>
                        <span class="font-body-md text-body-md font-semibold text-on-surface">CONV-02: Belt Tensioning</span>
                        <span class="text-body-sm text-on-surface-variant">Pemeriksaan Terjadwal</span>
                    </div>
                </div>
                <div class="relative pl-8 opacity-60">
                    <div class="absolute left-0 top-1.5 w-5 h-5 rounded-full bg-surface-container-lowest border-2 border-outline"></div>
                    <div class="flex flex-col">
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">26 MEI, 11:15</span>
                        <span class="font-body-md text-body-md font-semibold text-on-surface">GEN-01: Filter Service</span>
                        <span class="text-body-sm text-on-surface-variant">Teknisi: J. Doe</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sparepart Consumption -->
        <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
            <h3 class="font-headline-sm text-headline-sm mb-6">Konsumsi Sparepart</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-label-md mb-1">
                        <span>V-Belts (Standard)</span>
                        <span class="mono">42 unit</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-label-md mb-1">
                        <span>Hydraulic Fluid (ISO 46)</span>
                        <span class="mono">28 liter</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full opacity-80" style="width: 65%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-label-md mb-1">
                        <span>Ball Bearings (Type-C)</span>
                        <span class="mono">15 unit</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full opacity-60" style="width: 40%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-label-md mb-1">
                        <span>Control Relays</span>
                        <span class="mono">12 unit</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full opacity-40" style="style: width: 30%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer / QR Section -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 bg-primary-container text-on-primary p-8 rounded-xl items-center">
        <div>
            <h2 class="font-headline-md text-headline-md mb-2">Scan-to-Service</h2>
            <p class="font-body-lg text-body-lg opacity-80 max-w-md">Gunakan aplikasi MRM Mobile untuk memindai QR Code mesin untuk mendapatkan diagnosis kondisi dan riwayat medis secara instan.</p>
            <div class="mt-6 flex gap-4">
                <button class="bg-surface-container-lowest text-primary px-6 py-2 rounded-lg font-bold">Unduh Aplikasi</button>
                <button class="border border-white border-opacity-30 text-white px-6 py-2 rounded-lg font-bold">Panduan Operator</button>
            </div>
        </div>
        <div class="flex justify-center md:justify-end">
            <div class="bg-white p-4 rounded-xl shadow-lg border-4 border-primary text-center">
                <img class="w-32 h-32 mx-auto" alt="A clean, minimalist high-contrast QR code centered on a white background with thin industrial blue border." src="{{ asset('images/qr-system-main-hub.png') }}"/>
                <p class="text-primary-fixed mt-2 mono font-bold text-label-sm">ID: SYSTEM-MAIN-HUB</p>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-8 right-8 z-50">
        <a href="{{ route('maintenances.create') }}" class="w-14 h-14 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center hover:scale-105 active:scale-95 transition-transform">
            <span class="material-symbols-outlined text-[28px]" data-icon="add">add</span>
        </a>
    </div>
</x-layouts.app>
