<x-layouts.app 
    title="Machine Registry | MRM System"
    topbar-title="Machine Registry"
    :subnav="['All Machines' => route('machines.index'), 'Active Failures' => '#', 'Decommissioned' => '#']"
    active-subnav="All Machines"
>
    <!-- Breadcrumbs -->
    <x-breadcrumb :items="['Machine Registry' => route('machines.index')]" />

    <x-page-header title="Machine Registry" subtitle="Total: 124 Registered Equipment Units" class="mb-6">
        <x-slot name="right">
            <x-button variant="primary" icon="add" href="{{ route('maintenances.create') }}">
                Register New Machine
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Registry Table -->
        <div class="col-span-12 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                <div class="relative w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md" placeholder="Search Machine Code, Name, Model..." type="text"/>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-bright border-b border-outline-variant">
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Model / Manufacturer</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Health Score</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <!-- Machine Item 1 (CNC-08) -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">CNC-08</td>
                            <td class="px-6 py-4 font-body-md">CNC Milling Center</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Siemens X-500</td>
                            <td class="px-6 py-4 font-body-sm">Machining Center</td>
                            <td class="px-6 py-4">
                                <x-health-score score="38" type="bar" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge type="critical" label="Critical" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-button variant="secondary" icon="chevron_right" href="{{ route('machines.show', 'CNC-08') }}" class="p-1 px-2 text-[14px]">
                                    Passport
                                </x-button>
                            </td>
                        </tr>
                        <!-- Machine Item 2 (CNC-04) -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">CNC-04</td>
                            <td class="px-6 py-4 font-body-md">Precision Lathe Pro</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Haas VF-2</td>
                            <td class="px-6 py-4 font-body-sm">Machining</td>
                            <td class="px-6 py-4">
                                <x-health-score score="42" type="bar" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge type="critical" label="Critical" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-button variant="secondary" icon="chevron_right" href="{{ route('machines.show', 'CNC-04') }}" class="p-1 px-2 text-[14px]">
                                    Passport
                                </x-button>
                            </td>
                        </tr>
                        <!-- Machine Item 3 (ARM-12) -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">ARM-12</td>
                            <td class="px-6 py-4 font-body-md">Robotic Welder X1</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Fanuc R-2000iC</td>
                            <td class="px-6 py-4 font-body-sm">Assembly Center</td>
                            <td class="px-6 py-4">
                                <x-health-score score="58" type="bar" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge type="attention" label="Attention" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-button variant="secondary" icon="chevron_right" href="{{ route('machines.show', 'ARM-12') }}" class="p-1 px-2 text-[14px]">
                                    Passport
                                </x-button>
                            </td>
                        </tr>
                        <!-- Machine Item 4 (PMP-08) -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">PMP-08</td>
                            <td class="px-6 py-4 font-body-md">Hydraulic Feed Pump</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Rexroth A4VSO</td>
                            <td class="px-6 py-4 font-body-sm">Utilities</td>
                            <td class="px-6 py-4">
                                <x-health-score score="61" type="bar" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge type="attention" label="Attention" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-button variant="secondary" icon="chevron_right" href="{{ route('machines.show', 'PMP-08') }}" class="p-1 px-2 text-[14px]">
                                    Passport
                                </x-button>
                            </td>
                        </tr>
                        <!-- Machine Item 5 (DRL-19) -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">DRL-19</td>
                            <td class="px-6 py-4 font-body-md">Radial Drill Press</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Carlton 3A</td>
                            <td class="px-6 py-4 font-body-sm">Maintenance</td>
                            <td class="px-6 py-4">
                                <x-health-score score="85" type="bar" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge type="operational" label="Operational" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-button variant="secondary" icon="chevron_right" href="{{ route('machines.show', 'DRL-19') }}" class="p-1 px-2 text-[14px]">
                                    Passport
                                </x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
