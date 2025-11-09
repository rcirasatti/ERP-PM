<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\TugasController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Projects
    Route::get('/projects', function () {
        return view('projects.index');
    })->name('projects.index');

    // Projects Management (dari Penawaran yang disetujui)
    Route::resource('proyek', ProyekController::class);
    Route::put('proyek/{proyek}/update-status', [ProyekController::class, 'updateStatus'])->name('proyek.updateStatus');
    Route::get('proyek-search', [ProyekController::class, 'search'])->name('proyek.search');

    // Tasks Management (untuk setiap project)
    Route::resource('proyek.tugas', TugasController::class);
    Route::post('proyek/{proyek}/tugas/{tugas}/status', [TugasController::class, 'updateStatus'])->name('tugas.updateStatus');

    // Penawaran (Quotations)
    Route::resource('penawaran', PenawaranController::class);
    Route::put('penawaran/{penawaran}/update-status', [PenawaranController::class, 'updateStatus'])->name('penawaran.updateStatus');

    // Tasks
    Route::get('/tasks', function () {
        return view('tasks.index');
    })->name('tasks.index');

    // Invoices
    Route::get('/invoices', function () {
        return view('invoices.index');
    })->name('invoices.index');

    // Reports
    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');

    // CRUD Master Data
    Route::resource('client', ClientController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('material', MaterialController::class);
    Route::resource('inventory', InventoryController::class);
    Route::get('inventory-log', [InventoryController::class, 'log'])->name('inventory.log');

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
