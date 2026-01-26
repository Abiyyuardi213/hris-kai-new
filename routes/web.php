<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
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

    Route::get('/master-data', [App\Http\Controllers\MasterDataController::class, 'index'])->name('master.index');

    Route::resource('role', RoleController::class);
    Route::post('role/{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('role.toggleStatus');

    Route::resource('users', UserController::class);

    Route::resource('cities', App\Http\Controllers\CityController::class);
    Route::post('cities/sync', [App\Http\Controllers\CityController::class, 'sync'])->name('cities.sync');

    Route::resource('offices', App\Http\Controllers\OfficeController::class);

    Route::resource('divisions', App\Http\Controllers\DivisionController::class);
    Route::resource('positions', App\Http\Controllers\PositionController::class);
});
