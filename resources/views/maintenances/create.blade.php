<x-layouts.app 
    title="Entri Perawatan - CNC-08 | Sistem MRM"
    :sidebar="false"
    :topbar="false"
>
    <!-- Custom Entry Header -->
    <header class="fixed top-0 right-0 left-0 h-16 bg-surface-bright border-b border-outline-variant z-50 flex items-center px-4 md:px-margin-desktop shadow-sm">
        <a href="{{ route('maintenances.index') }}" class="mr-4 p-2 hover:bg-surface-container-high rounded-full transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">arrow_back</span>
        </a>
        <div class="flex flex-col">
            <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">Catatan Perawatan: CNC-08</h1>
            <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider">ID Aset: 08-PROD-LATHE</span>
        </div>
        <div class="ml-auto flex items-center gap-3">
            <div class="hidden sm:block text-right">
                <p class="font-label-md text-label-md font-bold">TEKNISI: R. MILLER</p>
                <p class="font-label-sm text-label-sm text-on-surface-variant">09:12 AM LOKAL</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="mt-16 pb-24 max-w-2xl mx-auto px-4 pt-6">
        <!-- Verification Banner -->
        <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">verified</span>
            <div class="text-body-sm">
                <strong>Pemindaian QR Terverifikasi.</strong> Lokasi: Machining Wing B, Bay 4. Koneksi terhubung dengan Telemetry Cloud.
            </div>
        </div>

        <form action="{{ route('maintenances.index') }}" method="GET" class="space-y-8">
            <!-- 1. Treatment Category -->
            <section class="space-y-3">
                <h3 class="font-headline-sm text-[16px] font-bold text-on-surface flex items-center gap-2">
                    <span class="text-primary font-bold text-[18px]">1.</span> Pilih Kategori Perawatan
                </h3>
                <div class="grid grid-cols-3 gap-3">
                    <!-- Scheduled -->
                    <label class="relative flex flex-col p-4 bg-surface-container-lowest border border-outline-variant rounded-xl cursor-pointer hover:bg-surface-container-low transition-all">
                        <input type="radio" name="treatment_type" value="scheduled" class="absolute top-4 right-4 text-primary focus:ring-primary" checked />
                        <span class="material-symbols-outlined text-primary mb-2">calendar_today</span>
                        <span class="font-body-md font-semibold text-on-surface">Terjadwal</span>
                        <span class="text-[11px] text-on-surface-variant mt-1">Pemeriksaan & perawatan rutin</span>
                    </label>
                    <!-- Corrective -->
                    <label class="relative flex flex-col p-4 bg-surface-container-lowest border border-outline-variant rounded-xl cursor-pointer hover:bg-surface-container-low transition-all">
                        <input type="radio" name="treatment_type" value="corrective" class="absolute top-4 right-4 text-primary focus:ring-primary" />
                        <span class="material-symbols-outlined text-error mb-2">build</span>
                        <span class="font-body-md font-semibold text-on-surface">Korektif</span>
                        <span class="text-[11px] text-on-surface-variant mt-1">Perbaikan kerusakan aktif</span>
                    </label>
                    <!-- Predictive -->
                    <label class="relative flex flex-col p-4 bg-surface-container-lowest border border-outline-variant rounded-xl cursor-pointer hover:bg-surface-container-low transition-all">
                        <input type="radio" name="treatment_type" value="predictive" class="absolute top-4 right-4 text-primary focus:ring-primary" />
                        <span class="material-symbols-outlined text-orange-500 mb-2">online_prediction</span>
                        <span class="font-body-md font-semibold text-on-surface">Prediktif</span>
                        <span class="text-[11px] text-on-surface-variant mt-1">Berdasarkan telemetri</span>
                    </label>
                </div>
            </section>

            <!-- 2. Clinical Checklist -->
            <section class="space-y-3">
                <h3 class="font-headline-sm text-[16px] font-bold text-on-surface flex items-center gap-2">
                    <span class="text-primary font-bold text-[18px]">2.</span> Checklist Tindakan
                </h3>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 divide-y divide-outline-variant">
                    <label class="flex items-center justify-between py-3 cursor-pointer first:pt-0 last:pb-0">
                        <span class="font-body-md text-on-surface">Kalibrasi Tekanan Cairan Pendingin (Coolant)</span>
                        <input type="checkbox" class="rounded text-primary focus:ring-primary h-5 w-5" checked />
                    </label>
                    <label class="flex items-center justify-between py-3 cursor-pointer first:pt-0 last:pb-0">
                        <span class="font-body-md text-on-surface">Pemeriksaan Titik Lubrikasi Poros (Axis)</span>
                        <input type="checkbox" class="rounded text-primary focus:ring-primary h-5 w-5" checked />
                    </label>
                    <label class="flex items-center justify-between py-3 cursor-pointer first:pt-0 last:pb-0">
                        <span class="font-body-md text-on-surface">Pembersihan Serpihan Chip Conveyor</span>
                        <input type="checkbox" class="rounded text-primary focus:ring-primary h-5 w-5" />
                    </label>
                    <label class="flex items-center justify-between py-3 cursor-pointer first:pt-0 last:pb-0">
                        <span class="font-body-md text-on-surface">Pengukuran Spindle Runout</span>
                        <input type="checkbox" class="rounded text-primary focus:ring-primary h-5 w-5" />
                    </label>
                </div>
            </section>

            <!-- 3. Spareparts Integration -->
            <section class="space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="font-headline-sm text-[16px] font-bold text-on-surface flex items-center gap-2">
                        <span class="text-primary font-bold text-[18px]">3.</span> Suku Cadang yang Diganti
                    </h3>
                    <span class="font-label-sm text-label-sm text-green-600 bg-green-50 px-2 py-0.5 rounded">Sinkronisasi Langsung WMS</span>
                </div>
                
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 space-y-4">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                        <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md" placeholder="Cari katalog suku cadang WMS (mis. bearing, belt)..." type="text" />
                    </div>

                    <!-- Selected Parts List -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-surface-container-low border border-outline-variant rounded-lg">
                            <div class="flex flex-col">
                                <span class="font-body-md font-semibold text-on-surface">HS-Bearing-99</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">High-Speed Ceramic Bearing</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-outline-variant rounded bg-white">
                                    <button type="button" class="px-2 py-1 hover:bg-surface-container-low">-</button>
                                    <span class="px-3 py-1 font-body-md mono">02</span>
                                    <button type="button" class="px-2 py-1 hover:bg-surface-container-low">+</button>
                                </div>
                                <button type="button" class="text-error hover:text-red-700">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="w-full py-3 border border-dashed border-primary text-primary hover:bg-primary-container/5 rounded-xl font-body-md flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Tambah Suku Cadang Lain
                    </button>
                </div>
            </section>

            <!-- 4. Clinical Observations -->
            <section class="space-y-3">
                <h3 class="font-headline-sm text-[16px] font-bold text-on-surface flex items-center gap-2">
                    <span class="text-primary font-bold text-[18px]">4.</span> Observasi Tindakan
                </h3>
                <textarea rows="4" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl p-4 focus:ring-2 focus:ring-primary font-body-md text-on-surface" placeholder="Detail anomali, getaran mikro (micro-vibration), pola kebocoran termal, atau rekomendasi perawatan berikutnya..."></textarea>
            </section>

            <!-- 5. Evidence Captures -->
            <section class="space-y-3">
                <h3 class="font-headline-sm text-[16px] font-bold text-on-surface flex items-center gap-2">
                    <span class="text-primary font-bold text-[18px]">5.</span> Foto Bukti
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border border-dashed border-outline-variant p-6 rounded-xl flex flex-col items-center justify-center text-center cursor-pointer hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[36px] text-on-surface-variant mb-2">add_a_photo</span>
                        <span class="font-body-md text-body-md font-semibold text-on-surface">Sebelum Tindakan</span>
                        <span class="text-[11px] text-on-surface-variant mt-1">Unggah foto gejala</span>
                    </div>
                    <div class="border border-dashed border-outline-variant p-6 rounded-xl flex flex-col items-center justify-center text-center cursor-pointer hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[36px] text-on-surface-variant mb-2">add_a_photo</span>
                        <span class="font-body-md text-body-md font-semibold text-on-surface">Setelah Tindakan</span>
                        <span class="text-[11px] text-on-surface-variant mt-1">Unggah foto setelah perbaikan</span>
                    </div>
                </div>
            </section>

            <!-- 6. Digital Signature -->
            <section class="space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="font-headline-sm text-[16px] font-bold text-on-surface flex items-center gap-2">
                        <span class="text-primary font-bold text-[18px]">6.</span> Tanda Tangan Teknisi
                    </h3>
                    <button type="button" id="clear-signature" class="text-primary font-label-md text-label-md hover:underline">Bersihkan</button>
                </div>
                <div class="relative border border-outline-variant bg-surface-container-lowest rounded-xl overflow-hidden shadow-inner">
                    <canvas id="signature-pad" class="w-full h-40 bg-surface-container-lowest block cursor-crosshair"></canvas>
                    <div id="signature-placeholder" class="absolute inset-0 flex items-center justify-center pointer-events-none text-on-surface-variant opacity-30 select-none">
                        <p class="font-label-md text-label-md tracking-widest uppercase">TANDA TANGAN DI AREA INI</p>
                    </div>
                </div>
            </section>

            <!-- Fixed Footer Actions -->
            <div class="fixed bottom-0 left-0 right-0 h-20 bg-surface-bright border-t border-outline-variant z-50 px-4 md:px-margin-desktop flex items-center justify-between max-w-2xl mx-auto shadow-md">
                <x-button type="a" variant="secondary" href="{{ route('maintenances.index') }}">
                    Simpan Draf
                </x-button>
                <x-button type="submit" variant="primary">
                    Selesaikan Tindakan
                </x-button>
            </div>
        </form>
    </main>

    @push('scripts')
        <script>
            // Signature Pad drawing logic
            const canvas = document.getElementById('signature-pad');
            const clearBtn = document.getElementById('clear-signature');
            const placeholder = document.getElementById('signature-placeholder');
            const ctx = canvas.getContext('2d');
            
            let isDrawing = false;
            
            // Adjust canvas sizing for high DPI screens
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                ctx.scale(ratio, ratio);
                ctx.strokeStyle = '#00288e'; // Primary color stroke
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
            }
            
            window.addEventListener('resize', resizeCanvas);
            window.addEventListener('DOMContentLoaded', resizeCanvas);
            resizeCanvas();
            
            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }
            
            function startDrawing(e) {
                isDrawing = true;
                placeholder.style.display = 'none';
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                e.preventDefault();
            }
            
            function draw(e) {
                if (!isDrawing) return;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                e.preventDefault();
            }
            
            function stopDrawing() {
                isDrawing = false;
            }
            
            // Mouse events
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseleave', stopDrawing);
            
            // Touch events
            canvas.addEventListener('touchstart', startDrawing);
            canvas.addEventListener('touchmove', draw);
            canvas.addEventListener('touchend', stopDrawing);
            
            // Clear button
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                placeholder.style.display = 'flex';
            });
        </script>
    @endpush
</x-layouts.app>
