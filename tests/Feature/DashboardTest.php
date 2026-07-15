<?php

namespace Tests\Feature;

use App\Models\Machine;
use App\Models\MaintenancePlan;
use App\Models\MaintenanceExecution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed standard DB data (which we modified to include today's plans & executions)
        $this->seed();
    }

    /**
     * Test the Morning Briefing Dashboard loads successfully and returns 200.
     */
    public function test_dashboard_loads_successfully(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Assert header exists
        $response->assertSee('Morning Briefing Control Room');
    }

    /**
     * Test the agenda status counters for today's work orders.
     */
    public function test_dashboard_agenda_counts_are_accurate(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Verify counts are passed to the view
        $response->assertViewHas('counts');
        $counts = $response->viewData('counts');

        // Based on our updated seed:
        // - Plan 1 (CNC-08): scheduled -> 1 Not Started (Blocked)
        // - Plan 2 (CNC-04): draft -> 1 Not Started (Blocked)
        // - Plan 3 (ARM-12): approved -> 1 Not Started (or Almost Ready)
        // - Plan 4 (DRL-19): approved -> 1 Not Started (or Ready)
        // - Plan 6 (PMP-08): in_progress -> 1 In Progress
        // - Plan 7 (PMP-08): completed + waiting_review execution -> 1 Waiting Review
        // So total not_started = 4, waiting_review = 1, in_progress = 1, completed = 0.
        $this->assertEquals(0, $counts['completed']);
        $this->assertEquals(1, $counts['waiting_review']);
        $this->assertEquals(1, $counts['in_progress']);
        $this->assertEquals(4, $counts['not_started']);
    }

    /**
     * Test blocker detection and listing of active blockers on the dashboard.
     */
    public function test_dashboard_blockers_are_generated_and_actionable(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Assert specific blockers from seed are displayed
        $response->assertSee('Hambatan Hari Ini');

        // Blocker 1: Machine Breakdown on CNC-04
        $response->assertSee('sedang mengalami kerusakan/down');
        $response->assertSee('CNC-04');
        $response->assertSee('Buka Paspor Mesin');

        // Blocker 2: Missing Sparepart for SEAL-TC-40 on CNC-04
        $response->assertSee('Stok WMS kurang untuk SEAL-TC-40');
        $response->assertSee('Lihat Detail PM');

        // Blocker 3: Technician Not Assigned for CNC-04
        $response->assertSee('Teknisi pelaksana belum ditunjuk');
        $response->assertSee('Tunjuk Teknisi');
    }

    /**
     * Test priority machines are sorted and reasons are explicitly stated.
     */
    public function test_dashboard_priority_machines_ranking_and_reasons(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        $response->assertViewHas('priorityMachines');
        $priorityMachines = $response->viewData('priorityMachines');

        // Check if top machines are CNC-08 and CNC-04 (due to low health and critical/high priority blockers)
        $firstMachine = $priorityMachines->first();
        $this->assertContains($firstMachine->code, ['CNC-08', 'CNC-04']);

        // Assert priority reason descriptions appear on the dashboard
        $response->assertSee('Kerusakan Aktif');
        $response->assertSee('PM Terblokir');
    }

    /**
     * Test the chronological sorting of the activity timeline.
     */
    public function test_dashboard_activity_timeline_chronology(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        $response->assertViewHas('timelineEvents');
        $events = $response->viewData('timelineEvents');

        // Ensure we have events
        $this->assertNotEmpty($events);

        // Ensure timeline events are sorted ascending by time
        $lastTime = null;
        foreach ($events as $event) {
            if ($lastTime !== null) {
                $this->assertTrue($event['raw_time'] >= $lastTime, "Events are not sorted chronologically.");
            }
            $lastTime = $event['raw_time'];
        }
    }

    /**
     * Test the action recommendations guiding the administrator's workflow.
     */
    public function test_dashboard_operational_recommendations(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        $response->assertSee('Rekomendasi Tindakan');
        
        // Assert we see recommended actions based on the current state:
        // - Assign technicians (since CNC-04 has no technician)
        $response->assertSee('Tunjuk Teknisi Lapangan');
        // - Order parts (since CNC-04 has insufficient stock)
        $response->assertSee('Pesan Suku Cadang Kurang');
        // - Review inspections (since CNC-08 is waiting review)
        $response->assertSee('Tinjau Laporan Lapangan Selesai');
    }
}
