<?php

use Illuminate\Support\Facades\Route;

// Executive Dashboard
Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');

// Machine Registry
Route::get('/machines', function () {
    return view('machines.index');
})->name('machines.index');

Route::get('/machines/{machine}', function ($machine) {
    return view('machines.show', compact('machine'));
})->name('machines.show');

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

// Planning
Route::get('/planning', function () {
    return view('planning.index');
})->name('planning.index');

// Reports
Route::get('/reports', function () {
    return view('reports.index');
})->name('reports.index');

// Administration
Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.index');
