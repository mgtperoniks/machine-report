<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePlan;
use App\Services\MaintenanceReadinessService;
use Illuminate\Http\Request;

class MaintenancePlanController extends Controller
{
    protected MaintenanceReadinessService $readinessService;

    public function __construct(MaintenanceReadinessService $readinessService)
    {
        $this->readinessService = $readinessService;
    }

    /**
     * Display the planning board list & calendar.
     */
    public function index(Request $request)
    {
        $query = MaintenancePlan::with(['machine.documents', 'maintenanceTemplate.checklists', 'maintenanceTemplate.spareparts'])
            ->whereHas('machine', function($q) {
                $q->where('is_active', true)
                  ->where('lifecycle_status', 'ACTIVE');
            });

        // Non-dynamic database filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('machine', function ($mq) use ($search) {
                    $mq->where('code', 'like', "%{$search}%")
                       ->orWhere('name', 'like', "%{$search}%");
                })->orWhereHas('maintenanceTemplate', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $plans = $query->orderBy('scheduled_date', 'asc')->get();

        // Calculate and attach readiness report for each plan
        $plans->each(function ($plan) {
            $plan->readiness = $this->readinessService->getReadinessReport($plan);
        });

        // Filter by dynamic readiness status in-memory
        if ($request->filled('readiness_status')) {
            $status = $request->input('readiness_status');
            $plans = $plans->filter(function ($plan) use ($status) {
                return $plan->readiness['overall_status'] === $status;
            });
        }

        // Calculate summary counters for the filter buttons
        $allPlans = MaintenancePlan::whereHas('machine', function($q) {
            $q->where('is_active', true)
              ->where('lifecycle_status', 'ACTIVE');
        })->with(['machine', 'maintenanceTemplate.spareparts'])->get();
        $allPlans->each(function ($p) {
            $p->readiness = $this->readinessService->getReadinessReport($p);
        });

        $totalCount = $allPlans->count();
        $blockedCount = $allPlans->filter(fn($p) => $p->readiness['overall_status'] === 'Blocked')->count();
        $almostReadyCount = $allPlans->filter(fn($p) => $p->readiness['overall_status'] === 'Almost Ready')->count();
        $readyCount = $allPlans->filter(fn($p) => $p->readiness['overall_status'] === 'Ready')->count();

        // Group plans scheduled for "Hari Ini" (today) or "Terdekat" (upcoming)
        $todayPlans = $plans->filter(fn($p) => $p->scheduled_date->isToday());
        $upcomingPlans = $plans->filter(fn($p) => !$p->scheduled_date->isToday() && $p->scheduled_date->isFuture());

        return view('planning.index', compact(
            'plans',
            'todayPlans',
            'upcomingPlans',
            'totalCount',
            'blockedCount',
            'almostReadyCount',
            'readyCount'
        ));
    }

    /**
     * Display the detailed readiness audit for a single maintenance plan.
     */
    public function show(MaintenancePlan $plan)
    {
        $plan->load([
            'machine.documents',
            'maintenanceTemplate.checklists',
            'maintenanceTemplate.spareparts',
            'execution.answers.checklistItem',
            'execution.photos'
        ]);
        
        $report = $this->readinessService->getReadinessReport($plan);

        return view('planning.show', compact('plan', 'report'));
    }
}
