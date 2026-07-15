<x-layouts.app 
    title="Kerusakan & Downtime | Sistem MRM"
    topbar-title="Kerusakan"
>
    <x-breadcrumb :items="['Kerusakan' => '']" />
    
    <x-empty-state 
        title="Manajemen Kerusakan & Downtime"
        description="Modul ini sedang dalam pengembangan. Segera Anda akan dapat melaporkan gejala kerusakan, melacak mean-time-to-repair (MTTR), dan memantau kegagalan peralatan."
        icon="emergency_home"
    />
</x-layouts.app>
