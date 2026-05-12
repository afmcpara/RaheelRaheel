<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Models\Invoice;
use App\Models\User;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/account', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/account/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/packages', [AdminController::class, 'packages'])->name('packages');
    Route::post('/packages', [AdminController::class, 'storePackage'])->name('packages.store');
    Route::get('/packages/{package}', [AdminController::class, 'showPackage'])->name('packages.show');
    Route::post('/packages/{package}/mark', [AdminController::class, 'markPackage'])->name('packages.mark');
    Route::get('/invoices', [AdminController::class, 'invoiceQueue'])->name('invoices');
    Route::post('/invoices/{invoice}/review', [AdminController::class, 'reviewInvoice'])->name('invoices.review');
    Route::get('/ship-requests', [AdminController::class, 'shipRequests'])->name('ship-requests');
    Route::post('/ship-requests/{shipRequest}/process', [AdminController::class, 'processShipRequest'])->name('ship-requests.process');
    Route::get('/clients', [AdminController::class, 'clients'])->name('clients');
    Route::get('/clients/{client}', [AdminController::class, 'showClient'])->name('clients.show');
    Route::get('/invoice-file/{invoice}', fn (Invoice $invoice) => response()->file(storage_path('app/public/'.$invoice->file_path)))->name('invoice.file');
});

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/packages', [ClientController::class, 'packages'])->name('packages');
    Route::get('/packages/{package}', [ClientController::class, 'showPackage'])->name('packages.show');
    Route::post('/packages/{package}/invoice', [ClientController::class, 'uploadInvoice'])->name('packages.invoice');
    Route::post('/ship-requests', [ClientController::class, 'createShipRequest'])->name('ship-requests.store');
    Route::get('/shipments', [ClientController::class, 'shipmentStatus'])->name('shipments');
});
