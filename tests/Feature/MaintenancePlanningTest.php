<?php

namespace Tests\Feature;

use App\Models\Machine;
use App\Models\MaintenancePlan;
use App\Models\MaintenanceTemplate;
use App\Services\MaintenanceReadinessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenancePlanningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run seeders which automatically populate machines, templates, checklists, and plans
        $this->seed();
    }

    /**
     * Test the planning board list loads and displays plans and status labels.
     */
    public function test_planning_board_index_loads_successfully_and_lists_plans(): void
    {
        $response = $this->get(route('planning.index'));
        $response->assertStatus(200);

        // Check for machine codes
        $response->assertSee('CNC-08');
        $response->assertSee('ARM-12');
        $response->assertSee('DRL-19');

        // Check for template names (Paket Perawatan)
        $response->assertSee('Servis Bulanan CNC Milling');
        $response->assertSee('Pemeriksaan Mingguan Robot Las');
        $response->assertSee('Perawatan Umum Mesin Bor');

        // Check for readiness statuses translated in Indonesian
        $response->assertSee('Terblokir');
        $response->assertSee('Hampir Siap');
        $response->assertSee('Siap Eksekusi');
    }

    /**
     * Test detailed readiness audit showing blockers and warnings.
     */
    public function test_readiness_audit_details_for_blocked_plan(): void
    {
        // Find CNC-08 Plan (which is blocked by breakdown and missing SEAL-TC-40 sparepart)
        $cnc08 = Machine::where('code', 'CNC-08')->first();
        $plan = MaintenancePlan::where('machine_id', $cnc08->id)->firstOrFail();

        $response = $this->get(route('planning.show', $plan->id));
        $response->assertStatus(200);

        // Assert header details
        $response->assertSee('Servis Bulanan CNC Milling');
        $response->assertSee('CNC-08');

        // Assert overall status banner
        $response->assertSee('TERBLOKIR (BLOCKED)');
        
        // Assert specific blockers from report
        $response->assertSee('Mesin CNC-08 sedang dalam kondisi kerusakan (down)');
        $response->assertSee('Stok WMS kurang untuk SEAL-TC-40 (Seal TC 40): dibutuhkan 1, tersedia 0');

        // Assert checklist items from template are rendered
        $response->assertSee('Kalibrasi Tekanan Cairan Pendingin (Coolant)');
        $response->assertSee('Pengukuran Spindle Runout');
    }

    /**
     * Test detailed readiness audit for a fully ready plan.
     */
    public function test_readiness_audit_details_for_ready_plan(): void
    {
        // Find DRL-19 Plan (which is fully ready)
        $drl19 = Machine::where('code', 'DRL-19')->first();
        $plan = MaintenancePlan::where('machine_id', $drl19->id)->firstOrFail();

        $response = $this->get(route('planning.show', $plan->id));
        $response->assertStatus(200);

        $response->assertSee('SIAP EKSEKUSI');
        $response->assertSee('R. Thompson'); // Technician assigned
        $response->assertSee('Cukup'); // WMS stock sufficient
    }

    /**
     * Test planning board filters (Search, Priority, Readiness Status).
     */
    public function test_planning_board_filters_work_correctly(): void
    {
        // 1. Search Filter
        $responseSearch = $this->get(route('planning.index', ['search' => 'FANUC']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('ARM-12');
        $responseSearch->assertDontSee('CNC-08');

        // 2. Priority Filter
        $responsePriority = $this->get(route('planning.index', ['priority' => 'critical']));
        $responsePriority->assertStatus(200);
        $responsePriority->assertSee('CNC-08');
        $responsePriority->assertDontSee('DRL-19');

        // 3. Readiness Status Filter (dynamic in-memory filter)
        $responseReadiness = $this->get(route('planning.index', ['readiness_status' => 'Ready']));
        $responseReadiness->assertStatus(200);
        $responseReadiness->assertSee('DRL-19');
        $responseReadiness->assertDontSee('CNC-08');
    }
}
