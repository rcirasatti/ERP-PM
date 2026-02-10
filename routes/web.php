<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\PenawaranDocumentController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Profile (semua role)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard (semua role)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ADMIN ONLY ROUTES
    Route::middleware('check.role:admin')->group(function () {
        // User Management (Admin only)
        Route::resource('user', UserController::class);

        // Master Data
        Route::resource('client', ClientController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('material', MaterialController::class);
        Route::get('material/import/template', [MaterialController::class, 'exportTemplate'])->name('material.export-template');
        Route::post('material/import/preview', [MaterialController::class, 'previewImport'])->name('material.import-preview');
        Route::post('material/import', [MaterialController::class, 'import'])->name('material.import');

        // Inventory (Admin only)
        Route::resource('inventory', InventoryController::class);
        Route::get('inventory-log', [InventoryController::class, 'log'])->name('inventory.log');

        // Penawaran (Admin only)
        Route::resource('penawaran', PenawaranController::class);
        Route::put('penawaran/{penawaran}/update-status', [PenawaranController::class, 'updateStatus'])->name('penawaran.updateStatus');
        
        // Penawaran Documents
        Route::prefix('penawaran/{penawaran}/documents')->group(function () {
            Route::get('invoice-gsb', [PenawaranDocumentController::class, 'invoiceGsb'])->name('penawaran.document.invoice-gsb');
            Route::get('invoice-ritel', [PenawaranDocumentController::class, 'invoiceRitel'])->name('penawaran.document.invoice-ritel');
            Route::get('invoice-corporate', [PenawaranDocumentController::class, 'invoiceCorporate'])->name('penawaran.document.invoice-corporate');
            Route::get('surat-jalan', [PenawaranDocumentController::class, 'suratJalan'])->name('penawaran.document.surat-jalan');
            Route::get('bas', [PenawaranDocumentController::class, 'bas'])->name('penawaran.document.bas');
            Route::get('bast', [PenawaranDocumentController::class, 'bast'])->name('penawaran.document.bast');
        });
    });

    // ADMIN & MANAGER ROUTES
    Route::middleware('check.any.role:admin,manager')->group(function () {
        // Projects Management
        Route::resource('proyek', ProyekController::class);
        Route::put('proyek/{proyek}/update-status', [ProyekController::class, 'updateStatus'])->name('proyek.updateStatus');
        Route::get('proyek/{proyek}/status-info', [ProyekController::class, 'getStatusInfo'])->name('proyek.status-info');
        Route::get('proyek-search', [ProyekController::class, 'search'])->name('proyek.search');

        // Tasks Management
        Route::resource('proyek.tugas', TugasController::class);
        Route::post('proyek/{proyek}/tugas/{tugas}/status', [TugasController::class, 'updateStatus'])->name('tugas.updateStatus');

        // Pengeluaran Management
        Route::resource('pengeluaran', PengeluaranController::class);
        Route::get('pengeluaran/{pengeluaran}/download-bukti', [PengeluaranController::class, 'downloadBukti'])->name('pengeluaran.download-bukti');
        Route::get('pengeluaran/{pengeluaran}/preview-bukti', [PengeluaranController::class, 'previewBukti'])->name('pengeluaran.preview-bukti');

        // Finance/Budget
        Route::get('finance/budget', [FinanceController::class, 'budget'])->name('finance.budget');
        Route::get('finance/budget/{budget}', [FinanceController::class, 'showBudget'])->name('finance.budget.show');
    });

    // Fallback views
    Route::get('/projects', function () {
        return view('projects.index');
    })->name('projects.index');

    Route::get('/tasks', function () {
        return view('tasks.index');
    })->name('tasks.index');

    Route::get('/invoices', function () {
        return view('invoices.index');
    })->name('invoices.index');

    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
