<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perintah Kerja Pemeliharaan (Work Order) | {{ $plan->machine->code }}</title>
    <!-- Use local tailwind.js for offline LAN deployment compatibility -->
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <style>
        @media print {
            body {
                background-color: #fff;
                color: #000;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-900 antialiased p-4 sm:p-8">

    <!-- Print Control Banner (Hidden during print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 bg-white rounded-lg shadow border border-gray-200 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-gray-800">Dokumen Perintah Kerja</h1>
            <p class="text-xs text-gray-500">Gunakan pintasan browser <kbd class="px-1.5 py-0.5 bg-gray-200 rounded border font-mono">Ctrl + P</kbd> untuk mencetak instruksi kerja ini untuk teknisi.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('planning.show', $plan->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Kembali ke Audit
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-650 hover:bg-blue-700 text-sm font-medium rounded-md text-white transition-colors shadow">
                Cetak Perintah Kerja
            </button>
        </div>
    </div>

    <!-- Main Work Order Document Sheet -->
    <div class="max-w-4xl mx-auto bg-white border border-gray-300 rounded-lg shadow-sm p-6 sm:p-8 print:border-0 print:shadow-none print:p-0">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start border-b-2 border-gray-250 pb-6 mb-6 gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-0.5 text-xs font-semibold tracking-wider text-blue-800 bg-blue-100 rounded uppercase">Preventive Maintenance</span>
                    <span class="px-2 py-0.5 text-xs font-semibold tracking-wider text-orange-800 bg-orange-100 rounded uppercase">
                        {{ $plan->priority === 'critical' ? 'Kritis' : ($plan->priority === 'high' ? 'Tinggi' : ($plan->priority === 'medium' ? 'Sedang' : 'Rendah')) }}
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 uppercase">PERINTAH KERJA (WORK ORDER)</h1>
                <p class="text-sm text-gray-500 mt-1">ID Rencana: <span class="font-mono font-medium text-gray-700">#PM-{{ str_pad($plan->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-sm text-gray-500">Tanggal Terjadwal: <span class="font-medium text-gray-700">{{ $plan->scheduled_date->format('d M Y') }}</span></p>
                <p class="text-sm text-gray-500">Teknisi Ditugaskan: <span class="font-medium text-gray-700">{{ $plan->assigned_technician ?? 'Belum Ditugaskan' }}</span></p>
            </div>
            
            <!-- QR Code Section -->
            <div class="flex flex-col items-center sm:items-end gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                <div class="w-32 h-32 flex items-center justify-center bg-white border border-gray-300 p-1">
                    <img src="{{ $qrCodeImage }}" alt="QR Code Link" class="w-full h-full" />
                </div>
                <span class="text-[10px] font-medium tracking-tight text-gray-500 text-center uppercase">Pindai QR Untuk Eksekusi</span>
            </div>
        </div>

        <!-- Machine Information Section -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4 uppercase tracking-wide">1. Identitas Mesin</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Machine Details Table -->
                <div class="md:col-span-2 grid grid-cols-2 gap-y-3 gap-x-4 text-sm">
                    <div>
                        <span class="text-xs text-gray-400 block uppercase">Kode Mesin</span>
                        <span class="font-mono font-bold text-base text-gray-900">{{ $plan->machine->code }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase">Nama Mesin</span>
                        <span class="font-semibold text-gray-800">{{ $plan->machine->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase">Lokasi / Departemen</span>
                        <span class="text-gray-700">{{ $plan->machine->department }} - Area {{ $plan->machine->production_area }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase">Kategori</span>
                        <span class="text-gray-700">{{ $plan->machine->category }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase">Pabrikan / Model</span>
                        <span class="text-gray-700">{{ $plan->machine->manufacturer }} - {{ $plan->machine->model }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase">Nomor Seri</span>
                        <span class="font-mono text-gray-700">{{ $plan->machine->serial_number ?? '-' }}</span>
                    </div>
                </div>

                <!-- Machine & Nameplate Photos -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-3">
                    @php
                        $nameplatePhoto = $plan->machine->photos->firstWhere('type', 'name_plate');
                    @endphp
                    
                    <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col items-center justify-center min-h-[110px]">
                        @if ($plan->machine->primary_photo_url)
                            <img src="{{ $plan->machine->primary_photo_url }}" alt="Foto Mesin" class="max-h-24 object-contain rounded" />
                        @else
                            <div class="text-center text-gray-400 py-3">
                                <span class="text-xs">Foto Mesin Tidak Tersedia</span>
                            </div>
                        @endif
                        <span class="text-[9px] text-gray-400 uppercase mt-1">Foto Mesin</span>
                    </div>

                    <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col items-center justify-center min-h-[110px]">
                        @if ($nameplatePhoto && Storage::disk('public')->exists($nameplatePhoto->file_path))
                            <img src="{{ asset('storage/' . $nameplatePhoto->file_path) }}" alt="Papan Nama" class="max-h-24 object-contain rounded" />
                        @else
                            <div class="text-center text-gray-400 py-3">
                                <span class="text-xs">Papan Nama Tidak Tersedia</span>
                            </div>
                        @endif
                        <span class="text-[9px] text-gray-400 uppercase mt-1">Papan Nama (Nameplate)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Objective & Target duration -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="md:col-span-2">
                <span class="text-xs text-gray-400 block uppercase font-semibold">Tujuan Pemeliharaan</span>
                <p class="text-gray-700 mt-1 font-medium">{{ $plan->notes ?? 'Pemeriksaan pemeliharaan berkala standar.' }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 block uppercase font-semibold">Estimasi Durasi</span>
                <p class="text-gray-700 mt-1 font-mono text-base font-bold">{{ $plan->maintenanceTemplate->estimated_duration ?? '120' }} Menit</p>
            </div>
        </div>

        <!-- Previous Maintenance Notes Briefing -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-3 uppercase tracking-wide">2. Catatan Pemeliharaan Sebelumnya</h2>
            <div class="bg-yellow-50 border border-yellow-250 text-gray-800 p-3 rounded text-xs">
                @if ($previousExecution && !empty($previousExecution->notes))
                    <div class="flex justify-between items-center mb-1 text-[10px] text-gray-450 uppercase font-semibold">
                        <span>Operator: {{ $previousExecution->operator_name }}</span>
                        <span>Selesai: {{ $previousExecution->completed_at->format('d M Y H:i') }}</span>
                    </div>
                    <p class="font-medium text-gray-700 italic">"{{ $previousExecution->notes }}"</p>
                @else
                    <p class="text-gray-500 italic">Tidak ada catatan pemeliharaan historis untuk mesin ini.</p>
                @endif
            </div>
        </div>

        <!-- Required Spareparts Section -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-3 uppercase tracking-wide">3. Kebutuhan Suku Cadang & Bahan</h2>
            
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 text-xs rounded overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wider">Kode Barang</th>
                        <th scope="col" class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wider">Nama Suku Cadang</th>
                        <th scope="col" class="px-4 py-2.5 class text-center font-bold text-gray-500 uppercase tracking-wider w-24">Jumlah Dibutuhkan</th>
                        <th scope="col" class="px-4 py-2.5 text-center font-bold text-gray-500 uppercase tracking-wider w-24">Verifikasi Pengambilan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($plan->maintenanceTemplate->spareparts as $reqPart)
                        @php
                            // Fetch stock name from WMS
                            $wms = app(\App\Repositories\WarehouseRepository::class)->getItemDetails($reqPart->warehouse_item_code);
                        @endphp
                        <tr>
                            <td class="px-4 py-2.5 whitespace-nowrap font-mono text-gray-900">{{ $reqPart->warehouse_item_code }}</td>
                            <td class="px-4 py-2.5 text-gray-700 font-medium">{{ $wms['name'] ?? 'Suku Cadang' }}</td>
                            <td class="px-4 py-2.5 text-center font-bold text-gray-800">{{ $reqPart->quantity }} unit</td>
                            <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                <div class="w-5 h-5 mx-auto border-2 border-gray-400 rounded"></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-450 italic">
                                Tidak membutuhkan suku cadang khusus untuk rencana perawatan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Inspection Tasks/Checklist Section -->
        <div class="mb-6 page-break">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-3 uppercase tracking-wide">4. Daftar Tugas & Checklist Tindakan</h2>
            
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 text-xs rounded overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-center font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th scope="col" class="px-4 py-2 text-left font-bold text-gray-500 uppercase tracking-wider">Tindakan Pemeriksaan</th>
                        <th scope="col" class="px-4 py-2 text-left font-bold text-gray-500 uppercase tracking-wider w-1/3">Keterangan / SOP</th>
                        <th scope="col" class="px-4 py-2 text-center font-bold text-gray-500 uppercase tracking-wider w-20">Wajib</th>
                        <th scope="col" class="px-4 py-2 text-center font-bold text-gray-500 uppercase tracking-wider w-28">Skor Inspeksi (1-5)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($plan->maintenanceTemplate->checklists as $index => $item)
                        <tr>
                            <td class="px-3 py-3 text-center whitespace-nowrap text-gray-500 font-mono">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-gray-800 font-semibold text-xs leading-tight">{{ $item->title }}</td>
                            <td class="px-4 py-3 text-gray-600 leading-normal">{{ $item->description ?? '-' }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($item->is_required)
                                    <span class="text-red-700 font-bold uppercase text-[9px] bg-red-50 border border-red-200 px-1 py-0.5 rounded">Wajib</span>
                                @else
                                    <span class="text-gray-400 uppercase text-[9px]">Opsional</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5 text-gray-400 font-bold">
                                    <span>[1]</span>
                                    <span>[2]</span>
                                    <span>[3]</span>
                                    <span>[4]</span>
                                    <span>[5]</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-450 italic">
                                Belum ada daftar tugas pemeriksaan untuk rencana pemeliharaan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signatures & Operator Notes Section -->
        <div class="mt-8 border-t border-gray-200 pt-6">
            <div class="grid grid-cols-2 gap-6">
                <!-- Operator Notes Box (Handwritten) -->
                <div class="border border-gray-300 rounded p-4 h-32 flex flex-col justify-between">
                    <span class="text-xs font-bold text-gray-500 uppercase">Catatan Operator Lapangan (Tulisan Tangan)</span>
                    <div class="border-b border-dashed border-gray-300 w-full mb-1"></div>
                    <div class="border-b border-dashed border-gray-300 w-full mb-1"></div>
                    <div class="border-b border-dashed border-gray-300 w-full"></div>
                </div>

                <!-- Signatures Grid -->
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="flex flex-col justify-between h-32 border border-gray-250 p-3 bg-gray-50 rounded">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Pelaksana PM (Teknisi)</span>
                        <div class="mt-4 border-b border-gray-300 mx-auto w-3/4"></div>
                        <span class="text-[10px] font-medium text-gray-700 mt-1">{{ $plan->assigned_technician ?? 'Tanda Tangan' }}</span>
                    </div>
                    <div class="flex flex-col justify-between h-32 border border-gray-250 p-3 bg-gray-50 rounded">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Pengawas PM (Admin)</span>
                        <div class="mt-4 border-b border-gray-300 mx-auto w-3/4"></div>
                        <span class="text-[10px] font-medium text-gray-500 mt-1">Tanda Tangan & Nama Terang</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
