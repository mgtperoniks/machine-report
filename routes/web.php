<?php

use Illuminate\Support\Facades\Route;

use App\Models\Machine;

use App\Http\Controllers\DashboardController;

// Morning Briefing Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


use App\Http\Controllers\MachineController;

// Machine Registry
Route::get('/machines', [MachineController::class, 'index'])->name('machines.index');
Route::get('/machines/create', [MachineController::class, 'create'])->name('machines.create');
Route::post('/machines', [MachineController::class, 'store'])->name('machines.store');
Route::get('/machines/{machine}', [MachineController::class, 'show'])->name('machines.show');
Route::get('/machines/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
Route::put('/machines/{machine}', [MachineController::class, 'update'])->name('machines.update');
Route::delete('/machines/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');

// Maintenance Management
Route::get('/maintenances', function () {
    return view('maintenances.index');
})->name('maintenances.index');

Route::get('/maintenances/create', function () {
    return view('maintenances.create');
})->name('maintenances.create');

// Breakdowns & Downtime
Route::get('/breakdowns', function () {
    return view('breakdowns.index');
})->name('breakdowns.index');

// Spareparts Integration
Route::get('/spareparts', function () {
    return view('spareparts.index');
})->name('spareparts.index');

use App\Http\Controllers\MaintenancePlanController;
use App\Http\Controllers\MaintenanceExecutionController;

// Planning
Route::get('/planning', [MaintenancePlanController::class, 'index'])->name('planning.index');
Route::get('/planning/{plan}', [MaintenancePlanController::class, 'show'])->name('planning.show');

// Mobile/QR Checklist Execution
Route::get('/machines/qr/{machineCode}/execute', [MaintenanceExecutionController::class, 'qrEntry'])->name('planning.qr-entry');
Route::get('/planning/{plan}/execute', [MaintenanceExecutionController::class, 'create'])->name('planning.execute');
Route::post('/planning/{plan}/execute', [MaintenanceExecutionController::class, 'store'])->name('planning.store-execute');
Route::get('/planning/{plan}/print', [MaintenanceExecutionController::class, 'print'])->name('planning.print');

// Reports
Route::get('/reports', function () {
    return view('reports.index');
})->name('reports.index');

// Administration
Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.index');
