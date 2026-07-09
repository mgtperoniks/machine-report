<x-layouts.app 
    title="Machine Passport - CNC-08 | MRM System"
    topbar-title="Machine Reliability Management"
    :subnav="['Overview' => '#', 'Medical History' => route('machines.show', 'CNC-08'), 'Spareparts' => '#', 'Documents' => '#']"
    active-subnav="Medical History"
>
    <!-- Breadcrumbs -->
    <x-breadcrumb :items="['Machine Registry' => route('machines.index'), 'CNC-08' => '']" />

    <!-- Identity & Actions Header -->
    <div class="bg-surface-container-lowest border border-outline-variant p-8 mb-8 flex flex-col md:flex-row gap-8 items-start rounded-xl shadow-sm">
        <div class="w-full md:w-1/3 aspect-video rounded overflow-hidden border border-outline-variant relative group">
            <img class="w-full h-full object-cover" alt="CNC Milling Center CNC-08" src="{{ asset('images/cnc-08.webp') }}"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        
        <div class="flex-1 space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-1">CNC Milling Center - CNC-08</h2>
                    <div class="flex flex-wrap gap-4 text-on-surface-variant font-body-md">
                        <span><strong class="font-semibold text-on-surface">Manufacturer:</strong> Siemens</span>
                        <span>|</span>
                        <span><strong class="font-semibold text-on-surface">Model:</strong> X-500</span>
                        <span>|</span>
                        <span><strong class="font-semibold text-on-surface">Year:</strong> 2019</span>
                        <span>|</span>
                        <span><strong class="font-semibold text-on-surface">Department:</strong> Machining Center</span>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <x-status-badge type="critical" label="Critical Status" />
                    
                    <div class="p-2 bg-white border border-outline-variant rounded shadow-sm">
                        <img class="w-16 h-16" alt="QR Code" src="{{ asset('images/qr-cnc-08.png') }}"/>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 pt-4">
                <x-button variant="primary" icon="medical_services" href="{{ route('maintenances.create') }}">
                    Record Treatment (Maintenance)
                </x-button>
                <x-button variant="secondary" icon="stethoscope" href="{{ route('breakdowns.index') }}">
                    Report Symptom (Breakdown)
                </x-button>
                <x-button variant="secondary" icon="ios_share" href="#">
                    Export History
                </x-button>
            </div>
        </div>
    </div>

    <!-- Health & Diagnostic Dashboard (Bento Grid) -->
    <div class="grid grid-cols-12 gap-6 mb-8">
        <!-- Health Gauge Section -->
        <div class="col-span-12 md:col-span-4 bg-surface-container-lowest border border-outline-variant p-6 rounded-xl flex flex-col items-center justify-center text-center shadow-sm">
            <p class="font-label-md text-label-md text-on-surface-variant uppercase mb-4 tracking-widest">Health Vital Score</p>
            <x-health-score score="38" type="circle" />
            
            <div class="mt-6 w-full space-y-2">
                <div class="flex justify-between text-label-md font-label-md">
                    <span>Optimal Range</span>
                    <span class="text-on-surface font-semibold">85% - 100%</span>
                </div>
                <div class="h-1.5 w-full bg-surface-container rounded-full overflow-hidden">
                    <div class="h-full bg-primary" style="width: 85%"></div>
                </div>
            </div>
        </div>

        <!-- Detailed Diagnostics Tabs Content -->
        <div class="col-span-12 md:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col shadow-sm">
            <div class="border-b border-outline-variant px-6 flex space-x-8 overflow-x-auto">
                <button class="py-4 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors">Overview</button>
                <button class="py-4 font-body-md text-body-md text-primary font-bold border-b-2 border-primary">Medical History</button>
                <button class="py-4 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors">Spareparts</button>
                <button class="py-4 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors">Documents</button>
                <button class="py-4 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors">Health Analysis</button>
            </div>
            
            <div class="p-8 flex-1 overflow-y-auto max-h-[360px] hide-scrollbar">
                <!-- Medical History Timeline -->
                <div class="relative">
                    <!-- Vertical line -->
                    <div class="absolute left-[11px] top-4 bottom-0 w-0.5 bg-outline-variant"></div>
                    
                    <div class="space-y-8">
                        <!-- Entry 1: Breakdown -->
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1.5 w-6 h-6 rounded-full bg-error flex items-center justify-center ring-4 ring-white">
                                <span class="material-symbols-outlined text-[14px] text-white" style="font-variation-settings: 'FILL' 1;">emergency</span>
                            </div>
                            <div class="bg-surface-container-low border border-outline-variant p-4 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase">Oct 25, 2023</p>
                                        <h4 class="font-headline-sm text-headline-sm text-error">Breakdown - Spindle Overheating</h4>
                                    </div>
                                    <x-status-badge type="critical" label="High Severity" />
                                </div>
                                <div class="grid grid-cols-2 gap-4 mt-3 pt-3 border-t border-outline-variant">
                                    <div>
                                        <p class="text-label-sm text-on-surface-variant font-label-sm uppercase">Treatment</p>
                                        <p class="font-body-md text-body-md text-on-surface">Cooling fan replacement</p>
                                    </div>
                                    <div>
                                        <p class="text-label-sm text-on-surface-variant font-label-sm uppercase">Technician</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <div class="w-5 h-5 rounded-full bg-secondary text-on-secondary flex items-center justify-center text-[10px]">M</div>
                                            <p class="font-body-md text-body-md text-on-surface">Miller</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Entry 2: Maintenance -->
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1.5 w-6 h-6 rounded-full bg-primary flex items-center justify-center ring-4 ring-white">
                                <span class="material-symbols-outlined text-[14px] text-white" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                            </div>
                            <div class="bg-surface-container-low border border-outline-variant p-4 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase">Oct 10, 2023</p>
                                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Scheduled Maintenance - Full Lubrication</h4>
                                    </div>
                                    <x-status-badge type="success" label="Routine" />
                                </div>
                                <div class="mt-3 pt-3 border-t border-outline-variant">
                                    <div class="flex items-center gap-2 text-primary">
                                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                        <p class="font-body-md text-body-md font-semibold">Result: Success</p>
                                    </div>
                                    <p class="text-body-sm text-on-surface-variant mt-1">All hydraulic channels cleared. Optimal pressure restored to primary milling arm.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Entry 3: Symptom Reported -->
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1.5 w-6 h-6 rounded-full bg-warning-container flex items-center justify-center ring-4 ring-white" style="background-color: #ffdbce;">
                                <span class="material-symbols-outlined text-[14px] text-tertiary" style="font-variation-settings: 'FILL' 1;">warning</span>
                            </div>
                            <div class="bg-surface-container-low border border-outline-variant p-4 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase">Sep 15, 2023</p>
                                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Symptom Reported - Unusual Vibration</h4>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-outline-variant">
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="text-label-sm text-on-surface-variant font-label-sm uppercase">Noted by</p>
                                            <p class="font-body-md text-body-md text-on-surface">Supervisor John</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-label-sm text-on-surface-variant font-label-sm uppercase">Risk Category</p>
                                            <p class="font-body-md text-body-md text-tertiary font-bold">Structural Fatigue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Visualization Layer -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h5 class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant">Live Telemetry</h5>
                <span class="text-[10px] text-error font-bold flex items-center gap-1 animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-error"></span> LIVE</span>
            </div>
            <div class="h-32 flex items-end gap-1">
                <!-- Simple bar chart mockup -->
                <div class="flex-1 bg-primary/20 h-[60%] rounded-t-sm"></div>
                <div class="flex-1 bg-primary/20 h-[85%] rounded-t-sm"></div>
                <div class="flex-1 bg-primary/20 h-[70%] rounded-t-sm"></div>
                <div class="flex-1 bg-error h-[95%] rounded-t-sm"></div>
                <div class="flex-1 bg-primary/20 h-[50%] rounded-t-sm"></div>
                <div class="flex-1 bg-primary/20 h-[40%] rounded-t-sm"></div>
                <div class="flex-1 bg-primary/20 h-[65%] rounded-t-sm"></div>
            </div>
            <p class="text-label-sm text-on-surface-variant mt-2 text-center">Spindle Temperature Profile (°C)</p>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl shadow-sm">
            <h5 class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant mb-4">Upcoming Prescriptions</h5>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary">event</span>
                    <div>
                        <p class="font-body-md text-body-md font-semibold">Bearing Ultrasound</p>
                        <p class="text-label-sm text-on-surface-variant">Scheduled for Nov 05</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-on-surface-variant opacity-40">event</span>
                    <div class="opacity-40">
                        <p class="font-body-md text-body-md font-semibold">Coolant Flush</p>
                        <p class="text-label-sm text-on-surface-variant">Scheduled for Dec 12</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl flex flex-col items-center justify-center text-center shadow-sm">
            <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-2">clinical_notes</span>
            <h5 class="font-body-md text-body-md font-bold text-on-surface">Physician's Summary</h5>
            <p class="text-body-sm text-on-surface-variant mt-2 px-4 italic">"The CNC-08 exhibits significant thermal instability in the main spindle. Immediate palliative care required before catastrophic failure."</p>
            <p class="text-label-sm font-label-sm text-primary mt-3 font-semibold">— Dr. Eng. R. Thompson</p>
        </div>
    </div>

    <!-- Contextual FAB -->
    <div class="fixed bottom-8 right-8 z-50">
        <a href="{{ route('maintenances.create') }}" class="w-14 h-14 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center hover:scale-105 active:scale-95 transition-transform">
            <span class="material-symbols-outlined text-[28px]" data-icon="add">add</span>
        </a>
    </div>
</x-layouts.app>
