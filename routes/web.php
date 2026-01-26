<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PeranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/master-data', [App\Http\Controllers\DataMasterController::class, 'index'])->name('master.index');

    Route::resource('role', PeranController::class);
    Route::post('role/{id}/toggle-status', [PeranController::class, 'toggleStatus'])->name('role.toggleStatus');

    Route::resource('users', UserController::class);

    Route::resource('cities', App\Http\Controllers\KotaController::class);
    Route::post('cities/sync', [App\Http\Controllers\KotaController::class, 'sync'])->name('cities.sync');

    Route::resource('offices', App\Http\Controllers\KantorController::class);

    Route::resource('divisions', App\Http\Controllers\DivisiController::class);
    Route::resource('positions', App\Http\Controllers\JabatanController::class);

    Route::resource('assets', App\Http\Controllers\AsetController::class);
    Route::resource('employment-statuses', App\Http\Controllers\StatusPegawaiController::class);
    Route::resource('employees', App\Http\Controllers\PegawaiController::class);

    Route::resource('mutations', App\Http\Controllers\MutasiPegawaiController::class)->except(['edit', 'update', 'destroy']); // Usually mutations are final records
    Route::resource('employee-shifts', App\Http\Controllers\ShiftPegawaiController::class)->only(['index', 'store', 'destroy']);
    Route::resource('holidays', App\Http\Controllers\HariLiburController::class)->only(['index', 'store', 'destroy']);
});
