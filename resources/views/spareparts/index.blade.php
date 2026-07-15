<x-layouts.app 
    title="Integrasi Sparepart | Sistem MRM"
    topbar-title="Integrasi Sparepart"
>
    <x-breadcrumb :items="['Sparepart' => '']" />
    
    <x-empty-state 
        title="Integrasi Sparepart WMS"
        description="Modul ini sedang dalam pengembangan. Sistem akan terintegrasi langsung dengan Warehouse Management System (WMS) untuk memverifikasi ketersediaan stok secara real-time, melacak konsumsi suku cadang, dan memicu peringatan otomatis."
        icon="inventory_2"
    />
</x-layouts.app>
