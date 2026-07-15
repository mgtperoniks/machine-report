<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan Perawatan | {{ $plan->machine->code }}</title>
    <!-- Local tailwind.js for offline LAN support -->
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <style>
        /* Touch-friendly styling refinements */
        .rating-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .rating-btn:active {
            transform: scale(0.9);
        }
        /* Sticky progress bar styling */
        .sticky-progress {
            position: sticky;
            top: 0;
            z-index: 50;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200 px-4 py-3 sticky top-0 z-40 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
            <span class="text-sm font-bold tracking-tight text-slate-800">MRM Mobile Check</span>
        </div>
        <div class="text-xs text-slate-500 font-mono">
            {{ $plan->machine->code }}
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-lg w-full mx-auto p-4 flex flex-col justify-center">

        <!-- PRE-EXECUTION STATE: Mulai Pemeriksaan Screen -->
        <div id="start-screen" class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 text-center space-y-6">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125v13.5c0 .621-.504 1.125-1.125 1.125h-15a1.125 1.125 0 0 1-1.125-1.125V5.25Z" />
                </svg>
            </div>
            
            <div>
                <h1 class="text-xl font-black text-slate-800">PEMERIKSAAN PM</h1>
                <p class="text-xs text-slate-400 uppercase tracking-widest mt-1">SOP: {{ $plan->maintenanceTemplate->name }}</p>
                <div class="mt-4 bg-slate-50 p-3 rounded-lg border border-slate-100 text-left space-y-1.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Mesin:</span>
                        <span class="font-bold text-slate-800">{{ $plan->machine->name }} ({{ $plan->machine->code }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Lokasi:</span>
                        <span class="font-semibold text-slate-800">{{ $plan->machine->department }} - Area {{ $plan->machine->production_area }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Rekomendasi Durasi:</span>
                        <span class="font-semibold text-slate-800 font-mono">{{ $plan->maintenanceTemplate->estimated_duration }} Menit</span>
                    </div>
                </div>
            </div>

            <button type="button" id="btn-start-inspection" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base rounded-xl shadow-lg shadow-blue-200 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                <span>Mulai Pemeriksaan</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>

        <!-- FORM EXECUTION STATE (Hidden by default) -->
        <form action="{{ route('planning.store-execute', $plan->id) }}" method="POST" enctype="multipart/form-data" id="execution-form" class="hidden space-y-5">
            @csrf
            
            <!-- Started At Hidden Input -->
            <input type="hidden" name="started_at" id="started_at_input" value="">

            <!-- Floating Progress Tracker -->
            <div class="bg-white/95 backdrop-blur-md rounded-xl border border-slate-200 p-3 sticky top-[53px] z-30 shadow-sm flex items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-1">
                        <span>PROGRESS</span>
                        <span id="progress-text">0 / {{ $plan->maintenanceTemplate->checklists->count() }} Terisi</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 text-xs text-rose-800 space-y-1">
                    <p class="font-bold">Beberapa input tidak valid:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Operator Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <label for="operator_name" class="block text-xs font-bold uppercase text-slate-400">Pilih Nama Teknisi / Operator</label>
                    @if($plan->assigned_technician)
                        <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-extrabold">Ditugaskan: {{ $plan->assigned_technician }}</span>
                    @endif
                </div>
                <div class="relative">
                    <select name="operator_name" id="operator_name" required class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                        <option value="" disabled selected>Pilih nama Anda...</option>
                        @foreach ($operators as $op)
                            <option value="{{ $op }}" {{ old('operator_name') === $op ? 'selected' : '' }}>{{ $op }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Checklist Cards -->
            <div class="space-y-4">
                <span class="block text-xs font-bold uppercase text-slate-400 px-1">Daftar Checklist Tindakan</span>
                
                @foreach ($plan->maintenanceTemplate->checklists as $index => $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4 checklist-card transition-all duration-300" id="card-{{ $item->id }}">
                        
                        <!-- Header & Info -->
                        <div>
                            <div class="flex justify-between items-start gap-2 mb-1.5">
                                <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $item->title }}</h3>
                                @if ($item->is_required)
                                    <span class="px-2 py-0.5 text-[9px] font-extrabold text-rose-700 bg-rose-50 border border-rose-200 rounded-md uppercase">Wajib</span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-bold text-slate-400 bg-slate-50 border border-slate-200 rounded-md uppercase">Opsional</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 leading-normal">{{ $item->description ?? '-' }}</p>
                        </div>

                        <!-- 1-5 Score Buttons -->
                        <div class="space-y-1.5">
                            <span class="block text-[10px] font-bold uppercase text-slate-400">Pilih Nilai Kondisi (1-5)</span>
                            <div class="grid grid-cols-5 gap-2">
                                @for ($score = 1; $score <= 5; $score++)
                                    @php
                                        // Colors mapping for score values
                                        $colorClass = match($score) {
                                            1 => 'peer-checked:bg-rose-500 peer-checked:text-white peer-checked:ring-rose-200',
                                            2 => 'peer-checked:bg-amber-500 peer-checked:text-white peer-checked:ring-amber-200',
                                            3 => 'peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:ring-yellow-200',
                                            4 => 'peer-checked:bg-blue-500 peer-checked:text-white peer-checked:ring-blue-200',
                                            5 => 'peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:ring-emerald-200',
                                        };
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="answers[{{ $item->id }}][score]" 
                                               value="{{ $score }}" 
                                               class="sr-only peer score-radio" 
                                               data-item-id="{{ $item->id }}"
                                               required
                                               {{ old("answers.{$item->id}.score") == $score ? 'checked' : '' }}>
                                        <div class="w-full py-3.5 text-center text-sm font-black text-slate-700 bg-slate-50 border border-slate-200 rounded-xl rating-btn peer-checked:ring-4 peer-checked:border-transparent transition-all {{ $colorClass }}">
                                            {{ $score }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            <div class="flex justify-between text-[9px] text-slate-400 px-1 pt-0.5">
                                <span class="font-bold text-rose-500">1 (Rusak Berat)</span>
                                <span class="font-bold text-emerald-500">5 (Sangat Baik)</span>
                            </div>
                        </div>

                        <!-- Conditional Remarks Input -->
                        <div id="remarks-container-{{ $item->id }}" class="hidden space-y-2">
                            <label for="remarks-{{ $item->id }}" class="block text-xs font-bold uppercase text-rose-700">Catatan Kerusakan / Temuan</label>
                            <textarea name="answers[{{ $item->id }}][remarks]" 
                                      id="remarks-{{ $item->id }}" 
                                      rows="2" 
                                      placeholder="Jelaskan detail kerusakan mesin..." 
                                      class="w-full p-3 bg-rose-50/50 border border-rose-200 rounded-xl text-xs text-slate-800 placeholder-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500">{{ old("answers.{$item->id}.remarks") }}</textarea>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Mandatory Photo & Notes -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-0.5">Foto Bukti Pemeriksaan</h3>
                    <p class="text-xs text-slate-400">Unggah satu foto sebagai dokumentasi fisik pemeliharaan (Maks 10MB).</p>
                </div>
                
                <div class="space-y-2">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all overflow-hidden" id="photo-preview-container">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-400" id="photo-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 mb-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                            <p class="text-xs font-bold uppercase tracking-wider">Ketuk Untuk Ambil Foto</p>
                        </div>
                        <img id="photo-preview" class="hidden w-full h-full object-cover" alt="Preview foto" />
                        <input type="file" name="photo" id="photo" required accept="image/*" class="hidden">
                    </label>
                </div>

                <div class="space-y-1.5">
                    <label for="notes" class="block text-xs font-bold uppercase text-slate-400">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" id="notes" rows="2" placeholder="Tuliskan temuan atau catatan umum jika ada..." class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Submit Button (Sticky at bottom for easier single hand operations) -->
            <div class="pt-4 pb-8">
                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-base rounded-2xl shadow-lg shadow-emerald-100 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Kirim Laporan Perawatan</span>
                </button>
            </div>

        </form>
    </main>

    <!-- JavaScript Interactive logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startScreen = document.getElementById('start-screen');
            const executionForm = document.getElementById('execution-form');
            const btnStart = document.getElementById('btn-start-inspection');
            const startedAtInput = document.getElementById('started_at_input');
            const radios = document.querySelectorAll('.score-radio');
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photo-preview');
            const photoPlaceholder = document.getElementById('photo-placeholder');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            const totalItems = {{ $plan->maintenanceTemplate->checklists->count() }};

            // 1. Handle initiation click
            btnStart.addEventListener('click', function() {
                // Save current time in Y-m-d H:i:s format
                const now = new Date();
                const pad = (num) => String(num).padStart(2, '0');
                const formattedTime = now.getFullYear() + '-' + 
                                      pad(now.getMonth() + 1) + '-' + 
                                      pad(now.getDate()) + ' ' + 
                                      pad(now.getHours()) + ':' + 
                                      pad(now.getMinutes()) + ':' + 
                                      pad(now.getSeconds());

                startedAtInput.value = formattedTime;

                // Toggle views
                startScreen.classList.add('hidden');
                executionForm.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // 2. Handle rating selection and conditional input
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const itemId = this.getAttribute('data-item-id');
                    const score = parseInt(this.value);
                    const card = document.getElementById('card-' + itemId);
                    const remarksContainer = document.getElementById('remarks-container-' + itemId);
                    const remarksTextarea = document.getElementById('remarks-' + itemId);

                    // Handle card coloring & conditional remarks
                    if (score === 1) {
                        // Make card highlight red
                        card.classList.remove('border-slate-200', 'bg-white');
                        card.classList.add('border-rose-300', 'bg-rose-50/20');
                        // Show remarks & make it required
                        remarksContainer.classList.remove('hidden');
                        remarksTextarea.setAttribute('required', 'required');
                        remarksTextarea.focus();
                    } else {
                        // Restore card color
                        card.classList.remove('border-rose-300', 'bg-rose-50/20');
                        card.classList.add('border-slate-200', 'bg-white');
                        // Hide remarks & remove required
                        remarksContainer.classList.add('hidden');
                        remarksTextarea.removeAttribute('required');
                    }

                    // Update progress tracking
                    updateProgress();
                });
            });

            // 3. Update Progress Bar
            function updateProgress() {
                // Find all unique checklist items that have a checked radio
                const checkedRadios = document.querySelectorAll('.score-radio:checked');
                const answeredCount = checkedRadios.length;
                const percentage = Math.round((answeredCount / totalItems) * 100);

                progressBar.style.width = percentage + '%';
                progressText.textContent = answeredCount + ' / ' + totalItems + ' Terisi';
            }

            // 4. Handle Photo upload preview
            photoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreview.classList.remove('hidden');
                        photoPlaceholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    photoPreview.classList.add('hidden');
                    photoPlaceholder.classList.remove('hidden');
                }
            });

            // Check if there was old input to keep conditional fields visible
            document.querySelectorAll('.score-radio:checked').forEach(radio => {
                const itemId = radio.getAttribute('data-item-id');
                const score = parseInt(radio.value);
                if (score === 1) {
                    const card = document.getElementById('card-' + itemId);
                    const remarksContainer = document.getElementById('remarks-container-' + itemId);
                    const remarksTextarea = document.getElementById('remarks-' + itemId);
                    card.classList.remove('border-slate-200', 'bg-white');
                    card.classList.add('border-rose-300', 'bg-rose-50/20');
                    remarksContainer.classList.remove('hidden');
                    remarksTextarea.setAttribute('required', 'required');
                }
            });
            updateProgress();
        });
    </script>

</body>
</html>
