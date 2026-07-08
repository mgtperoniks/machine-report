<x-layouts.app 
    title="Maintenance Registry | MRM System"
    topbar-title="Maintenance Management"
    :subnav="['All Records' => route('maintenances.index'), 'Scheduled' => '#', 'Corrective' => '#']"
    active-subnav="All Records"
>
    <!-- Breadcrumbs -->
    <x-breadcrumb :items="['Maintenance' => route('maintenances.index')]" />

    <x-page-header title="Maintenance Records" subtitle="Clinical Treatment logs for manufacturing equipment" class="mb-6">
        <x-slot name="right">
            <x-button variant="primary" icon="medical_services" href="{{ route('maintenances.create') }}">
                Record New Maintenance
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                <div class="relative w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-body-md" placeholder="Search by Machine, Tech, Type..." type="text"/>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-bright border-b border-outline-variant">
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Asset</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Performed By</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Cost</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <!-- Row 1 -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">CNC-08</td>
                            <td class="px-6 py-4 font-body-md font-semibold">Scheduled</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Cooling fan replacement and calibration</td>
                            <td class="px-6 py-4 font-body-sm">R. Miller</td>
                            <td class="px-6 py-4 font-body-sm font-bold">$150.00</td>
                            <td class="px-6 py-4 font-body-sm">Oct 10, 2023</td>
                            <td class="px-6 py-4">
                                <x-status-badge type="success" label="Completed" />
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">CNC-04</td>
                            <td class="px-6 py-4 font-body-md font-semibold text-error">Corrective</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Unplanned repair: main spindle overheating</td>
                            <td class="px-6 py-4 font-body-sm">R. Thompson</td>
                            <td class="px-6 py-4 font-body-sm font-bold">$420.00</td>
                            <td class="px-6 py-4 font-body-sm">Oct 02, 2023</td>
                            <td class="px-6 py-4">
                                <x-status-badge type="success" label="Completed" />
                            </td>
                        </tr>
                        <!-- Row 3 -->
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 mono text-body-sm font-bold text-primary">ARM-12</td>
                            <td class="px-6 py-4 font-body-md font-semibold text-tertiary">Predictive</td>
                            <td class="px-6 py-4 font-body-sm text-on-surface-variant">Scheduled belt alignment and checking</td>
                            <td class="px-6 py-4 font-body-sm">S. Chen</td>
                            <td class="px-6 py-4 font-body-sm font-bold">$75.00</td>
                            <td class="px-6 py-4 font-body-sm">Nov 24, 2023</td>
                            <td class="px-6 py-4">
                                <x-status-badge type="warning" label="Scheduled" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
