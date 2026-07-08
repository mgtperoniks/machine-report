<x-layouts.app 
    title="Spareparts Integration | MRM System"
    topbar-title="Sparepart Integration"
>
    <x-breadcrumb :items="['Spareparts' => '']" />
    
    <x-empty-state 
        title="WMS Spareparts Integration"
        description="This module is under development. It will integrate directly with your Warehouse Management System to verify real-time stock availability, track part consumption, and trigger alerts."
        icon="inventory_2"
    />
</x-layouts.app>
