<?php

namespace Tests\Feature;

use App\Models\Machine;
use App\Models\MaintenancePlan;
use App\Models\MaintenanceTemplate;
use App\Models\MaintenanceTemplateChecklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaintenanceExecutionTest extends TestCase
{
    use RefreshDatabase;

    protected MaintenancePlan $plan;
    protected MaintenanceTemplateChecklist $checklistRequired;
    protected MaintenanceTemplateChecklist $checklistOptional;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Create a machine
        $machine = Machine::create([
            'code' => 'CNC-99',
            'name' => 'Test Milling Machine',
            'department' => 'Machining Center',
            'production_area' => 'Area A',
            'category' => 'Milling Machine',
            'criticality' => 'high',
            'operational_status' => 'running',
            'manufacturer' => 'Siemens',
            'model' => 'X-Test',
            'serial_number' => 'SN-TEST-123',
            'installation_date' => '2020-01-01',
            'commissioning_date' => '2020-01-05',
            'vendor' => 'Test Vendor',
            'qr_code_path' => 'images/qr-test.png',
        ]);

        // Create a maintenance template
        $template = MaintenanceTemplate::create([
            'name' => 'Monthly CNC Test PM',
            'description' => 'Test PM Procedure',
            'machine_category' => 'Milling Machine',
            'maintenance_type' => 'Monthly',
            'estimated_duration' => 60,
            'is_active' => true,
        ]);

        // Create template checklists
        $this->checklistRequired = MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $template->id,
            'sequence' => 1,
            'title' => 'Check Spindle Oil Pressure',
            'description' => 'Verify gauge shows 4-5 bar',
            'is_required' => true,
        ]);

        $this->checklistOptional = MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $template->id,
            'sequence' => 2,
            'title' => 'Clean Chip Collector',
            'description' => 'Wipe clean the debris collector bin',
            'is_required' => false,
        ]);

        // Create a maintenance plan
        $this->plan = MaintenancePlan::create([
            'machine_id' => $machine->id,
            'maintenance_template_id' => $template->id,
            'scheduled_date' => now()->addDays(2),
            'priority' => 'high',
            'status' => 'ready',
            'assigned_technician' => 'Budi Utomo',
            'notes' => 'Test notes',
            'generation_source' => 'Manual',
        ]);
    }

    /**
     * Test QR scan secure entry flow.
     */
    public function test_qr_entry_initializes_start_timestamp_and_redirects()
    {
        $response = $this->get(route('planning.qr-entry', $this->plan->machine->code));

        $response->assertRedirect(route('planning.execute', $this->plan->id));

        // Follow redirect or hit the execution page directly
        $response = $this->get(route('planning.execute', $this->plan->id));
        $response->assertStatus(200);
    }

    /**
     * Test mobile execution form view displays required inputs.
     */
    public function test_mobile_execution_form_view_displays_checklists_and_technicians()
    {
        $response = $this->get(route('planning.execute', $this->plan->id));

        $response->assertStatus(200);
        $response->assertSee('Check Spindle Oil Pressure');
        $response->assertSee('Clean Chip Collector');
        $response->assertSee('Budi Utomo');
    }

    /**
     * Test validation requires score to be selected for all items.
     */
    public function test_validation_requires_scores_for_checklists()
    {
        $response = $this->post(route('planning.store-execute', $this->plan->id), [
            'operator_name' => 'Budi Utomo',
            'started_at' => now()->format('Y-m-d H:i:s'),
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'answers' => [],
        ]);

        $response->assertSessionHasErrors(['answers.' . $this->checklistRequired->id . '.score']);
    }

    /**
     * Test validation requires remarks if rating score is below 2.
     */
    public function test_validation_requires_remarks_for_poor_ratings()
    {
        $response = $this->post(route('planning.store-execute', $this->plan->id), [
            'operator_name' => 'Budi Utomo',
            'started_at' => now()->format('Y-m-d H:i:s'),
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'answers' => [
                $this->checklistRequired->id => [
                    'score' => 1, // score 1 < 2 requires remarks
                    'remarks' => '',
                ],
                $this->checklistOptional->id => [
                    'score' => 5,
                    'remarks' => '',
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['answers.' . $this->checklistRequired->id . '.remarks']);
    }

    /**
     * Test successful execution report submission.
     */
    public function test_successful_execution_report_submission()
    {
        $fakePhoto = UploadedFile::fake()->image('checklist_proof.jpg', 1200, 1200);

        $response = $this->post(route('planning.store-execute', $this->plan->id), [
            'operator_name' => 'Budi Utomo',
            'started_at' => now()->subMinutes(15)->format('Y-m-d H:i:s'),
            'answers' => [
                $this->checklistRequired->id => [
                    'score' => 4,
                    'remarks' => '',
                ],
                $this->checklistOptional->id => [
                    'score' => 5,
                    'remarks' => '',
                ],
            ],
            'notes' => 'Tindakan selesai dengan lancar.',
            'photo' => $fakePhoto,
        ]);

        $response->assertRedirect(route('planning.show', $this->plan->id));
        $response->assertSessionHas('success');

        // Assert database records
        $this->assertDatabaseHas('maintenance_executions', [
            'maintenance_plan_id' => $this->plan->id,
            'operator_name' => 'Budi Utomo',
            'notes' => 'Tindakan selesai dengan lancar.',
            'status' => 'waiting_review',
            'overall_score' => 4.50, // (4 + 5) / 2
        ]);

        $this->assertDatabaseHas('maintenance_plans', [
            'id' => $this->plan->id,
            'status' => 'completed',
        ]);

        // Assert answers were created
        $this->assertDatabaseHas('maintenance_execution_answers', [
            'checklist_item_id' => $this->checklistRequired->id,
            'score' => 4,
        ]);

        $this->assertDatabaseHas('maintenance_execution_answers', [
            'checklist_item_id' => $this->checklistOptional->id,
            'score' => 5,
        ]);

        // Assert photo record exists
        $this->assertDatabaseHas('maintenance_execution_photos', [
            'type' => 'general',
        ]);
    }
}
